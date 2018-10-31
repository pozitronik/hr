<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\groups\Groups;
use app\models\groups\GroupsSearch;
use app\models\user\CurrentUser;
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
	public function behaviors():array {
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
	public function actions():array {
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
		if ($newGroup->createGroup(Yii::$app->request->post($newGroup->classNameShort))) {
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
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

		if (null !== ($updateArray = Yii::$app->request->post($group->classNameShort))) {
			$group->updateGroup($updateArray);
		}

		if (null !== ($updateArray = Yii::$app->request->post('UserRoles'))) {
			$group->rolesInGroup = $updateArray;
		}

		return $this->render('update', [
			'model' => $group
		]);
	}

	/**
	 * @param int $id
	 * @throws Throwable
	 */
	public function actionDelete(int $id):void {
		Groups::findModel($id, new NotFoundHttpException())->safeDelete();
		$this->redirect('index');
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function actionTree(int $id):string {
		return $this->render('tree', [
			'id' => $id
		]);
	}

	/**
	 * @param int $id
	 * @param int $restorePositions 0: use saved nodes positions, 1 - use original positions, 2 - use originals and reset saved
	 * @return array
	 * @throws Throwable
	 * @todo: move to ajax controller
	 */
	public function actionGraph(int $id, int $restorePositions = 0):array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$group = Groups::findModel($id, new NotFoundHttpException());
		$nodes = [];
		$edges = [];
		$group->getGraph($nodes, $edges);
		$group->roundGraph($nodes);
		switch ($restorePositions) {
			default:
			case 0:
				$group->applyNodesPositions($nodes, ArrayHelper::getValue(CurrentUser::User()->options->nodePositions, $id, []));
			break;
			case 1:
				//do nothing
			break;
			case 2:
				CurrentUser::User()->options->nodePositions[$id] = [];//todo check this
			break;
		}

		return compact('nodes', 'edges');
	}

	/**
	 * @param int $id
	 * @throws Throwable
	 */
	public function actionGraphMap(int $id):void {
		$group = Groups::findModel($id, new NotFoundHttpException());
		$map = [0 => 1];
		$group->getGraphMap($map);
		Utils::log($map);
	}
}
