<?php
declare(strict_types = 1);

namespace app\controllers\admin\groups;

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\models\groups\GroupsSearch;
use Throwable;
use Yii;
use app\models\core\WigetableController;
use yii\filters\ContentNegotiator;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class WorkgroupsController
 */
class GroupsController extends WigetableController {
	public $menuCaption = "Команды";
	public $menuIcon = "/img/admin/groups.png";

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
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

	/**
	 * @return string
	 */
	public function actionTree(int $id){
		return $this->render('tree', [
			'id' => $id
		]);
	}

	/**
	 * @param int $id
	 * @return array
	 * @throws Throwable
	 */
	public function actionGraph(int $id) {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$group = Groups::findModel($id, new NotFoundHttpException());
		$graph = [];
		$group->getGraph(true, $graph);
		return $graph;
	}
}
