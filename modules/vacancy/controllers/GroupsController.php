<?php
declare(strict_types = 1);

namespace app\modules\vacancy\controllers;

use app\models\core\WigetableController;
use app\modules\groups\models\Groups;
use app\modules\vacancy\models\Vacancy;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class GroupsController
 * @package app\modules\vacancy\controllers
 */
class GroupsController extends WigetableController {
	public $menuDisabled = true;
	public $defaultRoute = 'vacancy/vacancy';

	/**
	 * @param int $id groupId
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionIndex(int $id):?string {
		if (null === $group = Groups::findModel($id, new NotFoundHttpException())) return null;
		$vacancies = Vacancy::find()->active()->where(['group' => $id]);


		return $this->render('index',[
			'group' => $group,
			'provider' => new ActiveDataProvider([
				'query' => $vacancies
			])
		]);
	}
}