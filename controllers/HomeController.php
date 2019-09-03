<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use app\modules\users\UsersModule;
use kartik\helpers\Html;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class HomeController
 * @package app\controllers
 */
class HomeController extends Controller {

	/**
	 * @return string|Response
	 * @throws Throwable
	 * @var null|int $u -- id пользователя, под которым смотрим
	 */
	public function actionIndex(?int $u = null) {
		if (null === CurrentUser::User()) return $this->redirect(['site/login']);

		/** @var Users $user */
		$user = (null === $u)?CurrentUser::User():Users::findModel($u, new NotFoundHttpException());

		$stack = ArrayHelper::getColumn($user->relLeadingGroups, 'id');//группы, где ползователь лидер
		/** @var Groups $leadingGroup */
//		foreach ((array)CurrentUser::User()->relLeadingGroups as $leadingGroup) {
//			$leadingGroup->buildHierarchyTree($stack);
//		}

		$commonGroupsIds = ArrayHelper::getColumn($user->relGroups, 'id');//группы, где пользователь состоит
		$stack = array_unique(array_merge($stack, $commonGroupsIds));

		$params = Yii::$app->request->queryParams;
		$searchModel = new GroupsSearch();
		return $this->render(ArrayHelper::getValue($params, 't', false)?'boss-table':'dashboard', [
			'dataProvider' => $searchModel->search($params, $stack),
			'searchModel' => $searchModel,
			'title' => (null === $u)?null:"Группы для ".Html::a($user->username, UsersModule::to(['users/profile', 'id' => $user->id]))
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
		return $this->render('group-users', [
			'dataProvider' => $searchModel->search($params, $allowedGroups),
			'searchModel' => $searchModel,
			'group' => Groups::findModel($searchModel->groupId, new NotFoundHttpException())
		]);

	}

}
