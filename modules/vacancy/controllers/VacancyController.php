<?php
declare(strict_types = 1);

namespace app\modules\vacancy\controllers;

use app\models\core\controllers\WigetableController;
use app\modules\users\UsersModule;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\models\VacancySearch;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class VacancyController
 * @package app\modules\vacancy\controllers
 */
class VacancyController extends WigetableController {
	public $menuDisabled = false;
	public $orderWeight = 11;
	public $menuCaption = "<i class='fa fa-pray'></i>Подбор персонала";
	public $defaultRoute = 'vacancy/vacancy';

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new VacancySearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);
	}

	/**
	 * @param int|null $group
	 * @return string|Response
	 * @throws InvalidConfigException
	 * @throws Exception
	 */
	public function actionCreate(?int $group = null) {
		$newVacancy = new Vacancy([
			'group' => $group
		]);
		if ($newVacancy->createModel(Yii::$app->request->post($newVacancy->formName()))) {
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newVacancy->id]);
		}

		return $this->render('create', [
			'model' => $newVacancy
		]);
	}

	/**
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):?string {
		if (null === $vacancy = Vacancy::findModel($id, new NotFoundHttpException())) return null;
		if (null !== ($updateArray = Yii::$app->request->post($vacancy->formName()))) $vacancy->updateModel($updateArray);

		return $this->render('update', [
			'model' => $vacancy
		]);
	}

	/**
	 * Создать пользователя из вакансии
	 * @param int $id
	 * @return string|Response|null
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function actionToUser(int $id) {
		if (null === $vacancy = Vacancy::findModel($id, new NotFoundHttpException())) return null;
		if (Yii::$app->request->post('done') && null !== $newUserId = $vacancy->toUser()) {
			return $this->redirect(UsersModule::to(['users/profile', 'id' => $newUserId]));
		}

		if ($vacancy->opened) {
			return $this->render('to-user', [//Запускаем процесс подбора
				'model' => $vacancy
			]);
		}

		return $this->render('history', [//Показываем историю подбора
			'model' => $vacancy
		]);
	}
}