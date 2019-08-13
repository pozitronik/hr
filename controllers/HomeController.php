<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
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
		return $this->render(ArrayHelper::getValue($params, 't', false)?'boss-table':'dashboard', [
			'dataProvider' => $searchModel->search($params, $stack),
			'searchModel' => $searchModel
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

		/** @noinspection RequireParameterInspection */
		return $this->render('table', [
			'dataProvider' => $searchModel->search($params, $allowedGroups),
			'searchModel' => $searchModel,
			'group' => Groups::findModel($searchModel->groupId)
		]);

	}

}
