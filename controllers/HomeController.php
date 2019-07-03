<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\UsersSearch;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\Response;
use yii\web\Controller;

/**
 * Class HomeController
 * @package app\controllers
 */
class HomeController extends Controller {

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionIndex() {
		if (null === CurrentUser::User()) return $this->redirect(['site/login']);
		$stack = [];
		/** @var Groups $leadingGroup */
		foreach ((array)CurrentUser::User()->relLeadingGroups as $leadingGroup) {
			$leadingGroup->buildHierarchyTree($stack);
		}

		$commonGroupsIds = ArrayHelper::getColumn(CurrentUser::User()->relGroups, 'id');
		$stack = array_unique(array_merge($stack, $commonGroupsIds));

		$params = Yii::$app->request->queryParams;
		$searchModel = new GroupsSearch();
		return $this->render(ArrayHelper::getValue($params, 't', false)?'boss-table':'boss', [
			'dataProvider' => $searchModel->search($params, $stack),
			'searchModel' => $searchModel,
			'groupsScope' => $stack
		]);
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionUsers():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new UsersSearch();
		$allowedGroups = [];
		//Проверяем доступы к списку юзеров

		return $this->render(ArrayHelper::getValue($params, 't', false)?'table':'users', [
			'dataProvider' => $searchModel->search($params, $allowedGroups),
			'searchModel' => $searchModel,
			'groupName' => Groups::findModel($searchModel->groupId)->name,
			'positionTypeName' => empty($searchModel->positionType)?'Все сотрудники':RefUserPositionTypes::findModel($searchModel->positionType)->name//применимо только для дашборда
		]);

	}

}
