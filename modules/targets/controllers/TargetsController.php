<?php
declare(strict_types = 1);

namespace app\modules\targets\controllers;

use app\models\core\WigetableController;
use app\models\user\CurrentUser;
use app\modules\targets\models\Targets;
use app\modules\targets\models\TargetsIntervals;
use app\modules\targets\models\TargetsSearch;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
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
	 */
	public function actionCreate() {
		$newTarget = new Targets();

		if ($newTarget->createModel(Yii::$app->request->post($newTarget->formName()))) {//todo: в таком режиме сохранение происходит потапно в разных транзакциях. Нужно предусмотреть механизм, в котором связанные объекты смогут сохраняться как единое целое. Возможно, это будет составная супермодель, не знаю.
			$newTargetInterval = new TargetsIntervals(['target' => $newTarget->id]);
			if ($newTargetInterval->createModel(Yii::$app->request->post($newTargetInterval->formName()))) {
				if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
				return $this->redirect(['update', 'id' => $newTarget->id]);
			}
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
	 */
	public function actionHome(?int $id) {
		$id = $id??CurrentUser::Id();
		$searchModel = new TargetsSearch();
		$dataProvider = $searchModel->findUserTargets($id, Yii::$app->request->queryParams);
		return $this->render('home', compact('searchModel', 'dataProvider'));
	}

}