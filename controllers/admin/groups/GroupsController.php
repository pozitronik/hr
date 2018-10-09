<?php
declare(strict_types = 1);

namespace app\controllers\admin\groups;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\models\groups\GroupsSearch;
use Yii;
use app\models\core\WigetableController;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;

/**
 * Class WorkgroupsController
 */
class GroupsController extends WigetableController {
	public $menuCaption = "Команды";
	public $menuIcon = "/img/admin/groups.png";

	/**
	 * @inheritdoc
	 */
	public function actions() {
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
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCreate() {
		$newGroup = new Groups();
		if ($newGroup->createGroup(ArrayHelper::getValue(Yii::$app->request->post(), $newGroup->classNameShort))) {
			return $this->redirect(['update', 'id' => $newGroup->id]);
		}

		return $this->render('create', [
			'model' => $newGroup
		]);
	}

	/**
	 * @param integer $id
	 * @return string
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):string {
		$group = Groups::findModel($id, new NotFoundHttpException());

		if (null !== ($updateArray = ArrayHelper::getValue(Yii::$app->request->post(), $group->classNameShort))) {
			$group->updateGroup($updateArray);
		}
		return $this->render('update', [
			'model' => $group
		]);
	}
}
