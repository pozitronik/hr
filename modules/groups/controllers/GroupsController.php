<?php
declare(strict_types = 1);

namespace app\modules\groups\controllers;

use app\helpers\Utils;
use app\modules\dynamic_attributes\models\DynamicAttributesPropertyCollection;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use Throwable;
use Yii;
use app\models\core\WigetableController;
use yii\data\ActiveDataProvider;
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
	 * Изменение группы
	 * @param integer $id
	 * @return null|string
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;
		if ((null !== ($updateArray = Yii::$app->request->post($group->formName()))) && $group->updateModel($updateArray)) $group->uploadLogotype();
		return $this->render('edit', [
			'model' => $group
		]);
	}

	/**
	 * Просмотр профиля
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionProfile(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;

		return $this->render('profile', [
			'model' => $group,
			'dataProvider' => new ActiveDataProvider([
				'query' => $group->getRelUsers()->active()
			])
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
		if (null !== ($updateArray = Yii::$app->request->post($group->formName()))) $group->updateModel($updateArray);
		return $this->render('groups/index', [
			'model' => $group,
			'parentProvider' => new ActiveDataProvider([
				'query' => $group->getRelParentGroups()->orderBy('name')->active()
			]),
			'childProvider' => new ActiveDataProvider([
				'query' => $group->getRelChildGroups()->orderBy('name')->active()
			])
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
		if (null !== ($updateArray = Yii::$app->request->post($group->formName()))) $group->updateModel($updateArray);
		return $this->render('users/index', [
			'model' => $group,
			'provider' => new ActiveDataProvider([
				'query' => $group->getRelUsers()->active()
			])
		]);
	}

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCreate() {
		$newGroup = new Groups();
		if ($newGroup->createModel(Yii::$app->request->post($newGroup->formName()))) {
			$newGroup->uploadLogotype();
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['profile', 'id' => $newGroup->id]);
		}

		return $this->render('edit', [
			'model' => $newGroup
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
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionUsersHierarchy(int $id, bool $showRolesSelector = false):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;
		return $this->render('users/hierarchy', [
			'model' => $group,
			'showRolesSelector' => $showRolesSelector,
			'hierarchy' => [
				[
					'label' => null === $group->type?$group->name:"{$group->relGroupTypes->name}: $group->name",
					'url' => GroupsModule::to(['groups/profile', 'id' => $group->id])
				]
			]
		]);
	}

	/**
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionAttributesStatistics(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;

		$parametersModel = new DynamicAttributesPropertyCollection();

		$parametersModel->load(Yii::$app->request->post());

		if (false !== $attribute = Yii::$app->request->get('attribute', false)) $parametersModel->attributeId = $attribute;
		if (false !== $property = Yii::$app->request->get('property', false)) $parametersModel->propertyId = $property;
		if (false !== $stattype = Yii::$app->request->get('stattype', false)) $parametersModel->aggregation = $stattype;
		$parametersModel->userScope = $group->relUsers;

		return $this->render('attributes-statistics', [
			'model' => $group,
			'aggregatedAttributes' => $parametersModel->applyAggregation(),
			'parametersModel' => $parametersModel
		]);
	}
}
