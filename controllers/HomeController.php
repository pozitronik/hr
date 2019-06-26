<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\users\models\UsersSearch;
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
		if (null === $user = CurrentUser::User()) return $this->redirect(['site/login']);
		$leadingGroups = CurrentUser::User()->relLeadingGroups;
		$stack = [];
		foreach ($leadingGroups as $leadingGroup) {
			$leadingGroup->buildHierarchyTree($stack);
		}
		$groups = Groups::findModels($stack);

		return $this->render('boss', [
			'groups' => $groups
		]);
	}

	/**
	 * Пытаемся загенерить матрицу ресурсов.
	 * Пока, конечно, тупо рисуем
	 * @return string
	 * @throws Throwable
	 */
	public function actionMatrix():string {
		return $this->render('matrix');
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionBoss():string {
		$leadingGroups = CurrentUser::User()->relLeadingGroups;
		$stack = [];
		foreach ($leadingGroups as $leadingGroup) {
			$leadingGroup->buildHierarchyTree($stack);
		}
		$groups = Groups::findModels($stack);

		return $this->render('boss', [
			'groups' => $groups
		]);
	}

	/**
	 * @return string
	 */
	public function actionUsers():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new UsersSearch();
		$allowedGroups = [];
		//Проверяем доступы к списку юзеров

		return $this->render('users', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params, $allowedGroups)
		]);

	}

}
