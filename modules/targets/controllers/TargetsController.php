<?php
declare(strict_types = 1);

namespace app\modules\targets\controllers;

use app\models\core\WigetableController;
use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\targets\models\Targets;
use app\modules\targets\models\TargetsPeriods;
use app\modules\targets\models\TargetsSearch;
use app\modules\users\models\Users;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class TargetsController
 * @package app\modules\targets\controllers
 */
class TargetsController extends WigetableController {
	public $menuCaption = "<i class='fa fa-arrow-circle-left'></i>Целеполагание";
	public $menuIcon = "/img/admin/privileges.png";
	public $menuDisabled = false;
	public $orderWeight = 15;
	public $defaultRoute = 'targets/targets';

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new TargetsSearch();
		$dataProvider = $searchModel->search($params);
		return $this->render('index', compact('searchModel', 'dataProvider'));
	}

	/**
	 * @return string|Response
	 * @throws InvalidConfigException
	 * @throws Exception
	 */
	public function actionCreate() {
		$newTarget = new Targets();
		//todo: в таком режиме сохранение происходит потапно в разных транзакциях. Нужно предусмотреть механизм, в котором связанные объекты смогут сохраняться как единое целое. Возможно, это будет составная супермодель, может расширение поведениями, может вообще нечто иное. Текущее решение очевидно плохое и должно быть переделано
		if ($newTarget->createModel(Yii::$app->request->post($newTarget->formName()))) {
			if ($newTarget->isFinal) {//если это финальный тип цели, создадим ему запись с интервалами
				($newTargetInterval = new TargetsPeriods(['target_id' => $newTarget->id]))->createModel(Yii::$app->request->post($newTargetInterval->formName()));
			}
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newTarget->id]);
		}

		return $this->render('create', [
			'model' => $newTarget
		]);
	}

	/**
	 * @param int $id
	 * @return string|null
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):?string {
		if (null === $target = Targets::findModel($id, new NotFoundHttpException())) return null;

		if (null !== ($updateArray = Yii::$app->request->post($target->formName()))) $target->updateModel($updateArray);
		$periodsClassName = (new TargetsPeriods())->formName();
		if (null !== ($updateArray = Yii::$app->request->post($periodsClassName))) {
			TargetsPeriods::addInstance(['target_id' => $target->id], array_merge(['target_id' => $target->id], $updateArray));
		}

		return $this->render('update', [
			'model' => $target
		]);
	}

	/**
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionProfile(int $id):?string {
		if (null === $target = Targets::findModel($id, new NotFoundHttpException())) return null;

		return $this->render('profile', [
			'model' => $target
		]);
	}

	/**
	 * @param int $id
	 * @return Response|null
	 * @throws Throwable
	 */
	public function actionDelete(int $id):?Response {
		if (null !== $target = Targets::findModel($id, new NotFoundHttpException())) $target->safeDelete();
		return $this->redirect('index');
	}

	/**
	 * Временно тут: экшен отображения персональных целей.
	 * @param int|null $id -- id пользователя
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionUser(?int $id = null):?string {
		$id = $id??CurrentUser::Id();
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		$params = Yii::$app->request->queryParams;
		$searchModel = new TargetsSearch();
		$dataProvider = $searchModel->findUserTargets($id, $params);
		return $this->render('user', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'user' => $user,
			'onlyMirrored' => ArrayHelper::getValue($params, 'onlyMirrored', false)
		]);
	}

	/**
	 * Отображение целей группы
	 * @param int $id
	 * @return null|string
	 * @throws Throwable
	 */
	public function actionGroup(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;
		$searchModel = new TargetsSearch();
		$params = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->findGroupTargets($id, $params);
		return $this->render('group', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'group' => $group,
			'onlyMirrored' => ArrayHelper::getValue($params, 'onlyMirrored', false)
		]);
	}

	/**
	 * Отображение зеркальных целей
	 * @param int|null $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionMirror(?int $id = null):?string {
		$id = $id??CurrentUser::Id();
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		$searchModel = new TargetsSearch();
		$dataProvider = $searchModel->findUserMirroredTargets($id, Yii::$app->request->queryParams);
		return $this->render('mirror', compact('searchModel', 'dataProvider', 'user'));
	}

}