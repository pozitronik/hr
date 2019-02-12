<?php
declare(strict_types = 1);

namespace app\modules\groups\controllers;

use app\helpers\Utils;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use Throwable;
use Yii;
use app\models\core\WigetableController;
use yii\filters\ContentNegotiator;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class GroupsController
 */
class GroupsController extends WigetableController {
	public $menuCaption = "<i class='fa fa-users'></i>Группы";
	public $menuIcon = "/img/admin/groups.png";
	public $orderWeight = 2;
	public $defaultRoute = 'groups/groups';

	/**
	 * @inheritdoc
	 */
	public function behaviors():array {
		return [
			[
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
					'application/xml' => Response::FORMAT_XML,
					'text/html' => Response::FORMAT_HTML
				]
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions():array {
		return [
			'error' => [
				'class' => ErrorAction::class
			]
		];
	}

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new GroupsSearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);
	}

	/**
	 * Профиль группы
	 * @param integer $id
	 * @return null|string
	 * @throws Throwable
	 */
	public function actionProfile(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;
		if ((null !== ($updateArray = Yii::$app->request->post($group->formName()))) && $group->updateGroup($updateArray)) $group->uploadLogotype();
		return $this->render('profile', [
			'model' => $group
		]);
	}

	/**
	 * Иерархия групп
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionGroups(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;
		if (null !== ($updateArray = Yii::$app->request->post($group->formName()))) $group->updateGroup($updateArray);
		return $this->render('groups', [
			'model' => $group,
		]);
	}

	/**
	 * Пользователи в группе
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionUsers(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;
		if (null !== ($updateArray = Yii::$app->request->post($group->formName()))) $group->updateGroup($updateArray);
		return $this->render('users', [
			'model' => $group,
		]);
	}

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCreate() {
		$newGroup = new Groups();
		if ($newGroup->createGroup(Yii::$app->request->post($newGroup->formName()))) {
			$newGroup->uploadLogotype();
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newGroup->id]);
		}

		return $this->render('create', [
			'model' => $newGroup
		]);
	}

	/**
	 * @param integer $id
	 * @return null|string
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;
		if ((null !== ($updateArray = Yii::$app->request->post($group->formName()))) && $group->updateGroup($updateArray)) $group->uploadLogotype();

		return $this->render('update', [
			'model' => $group
		]);
	}

	/**
	 * @param int $id
	 * @return Response|null
	 * @throws Throwable
	 */
	public function actionDelete(int $id):?Response {
		if (null !== $group = Groups::findModel($id, new NotFoundHttpException())) $group->safeDelete();
		return $this->redirect('index');
	}

	/**
	 * Страница иерархичного отображения пользователей для группы
	 * @param int $id
	 * @param bool $showRolesSelector
	 * @return string
	 * @throws Throwable
	 */
	public function actionUsersHierarchy(int $id, bool $showRolesSelector = false):string {
		$group = Groups::findModel($id, new NotFoundHttpException());
		return $this->render('users/hierarchy', compact('group', 'showRolesSelector'));
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function actionTree(int $id):string {
		return $this->render('tree', [
			'id' => $id
		]);
	}

	/**
	 * @param int $id
	 * @throws Throwable
	 */
	public function actionGraphMap(int $id):void {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return;
		$map = [0 => 1];
		$group->getGraphMap($map);
		Utils::log($map);
	}
}
