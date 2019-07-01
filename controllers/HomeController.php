<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\user\CurrentUser;
use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\salary\models\relations\RelRefUserPositionsTypes;
use app\modules\users\models\UsersSearch;
use app\modules\vacancy\models\Vacancy;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\Response;
use yii\data\ActiveDataProvider;
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
	 */
	public function actionIndex() {
		if (null === CurrentUser::User()) return $this->redirect(['site/login']);
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
			'dataProvider' => $searchModel->search($params, $allowedGroups),
			'groupName' => Groups::findModel($searchModel->groupId)->name,
			'positionTypeName' => RefUserPositionTypes::findModel($searchModel->positionType)->name
		]);

	}


}
