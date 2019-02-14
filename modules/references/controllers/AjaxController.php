<?php
declare(strict_types = 1);

namespace app\modules\references\controllers;

use app\models\core\ajax\AjaxAnswer;
use app\models\core\ajax\BaseAjaxController;
use app\models\relations\RelGroupsGroups;
use app\modules\groups\models\Groups;
use Throwable;
use Yii;
use yii\web\Response;

/**
 * Class AjaxController
 * @package app\modules\references\controllers
 */
class AjaxController extends BaseAjaxController {

	/**
	 * Принимает массив ролей пользователя, применяя их
	 * @return array
	 * @throws Throwable
	 */
	public function actionSetUserRolesInGroup():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
		$groupId = Yii::$app->request->post('groupId', false);
		$userId = Yii::$app->request->post('userId', false);
		if (!($groupId && $userId)) {
			return $answer->addError('parameters', 'Not enough parameters');
		}
		/** @var Groups $group */
		if (null === ($group = Groups::findModel($groupId))) {
			return $answer->addError('group', 'Not found');
		}

		$group->setRolesInGroup([$userId => Yii::$app->request->post('roles', [])]);
		return $answer->answer;

	}

	/**
	 * Принимает и применяет тип релейшена между двумя группами
	 * Предполагается, что релейшен уже существует
	 * @return array
	 * @throws Throwable
	 */
	public function actionSetGroupRelationType():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
		$parentGroupId = Yii::$app->request->post('parentGroupId', false);
		$childGroupId = Yii::$app->request->post('childGroupId', false);
		$relation = Yii::$app->request->post('relation', false);
		if (!($parentGroupId && $childGroupId)) {
			return $answer->addError('parameters', 'Not enough parameters');
		}

		/** @var Groups $group */
		if (false === ($groupsRelation = RelGroupsGroups::find()->where(['parent_id' => $parentGroupId, 'child_id' => $childGroupId])->one())) {
			return $answer->addError('groupsRelation', 'Not found');
		}
		$groupsRelation->setAndSaveAttribute('relation', $relation);
		return $answer->answer;

	}

	/**
	 * Принимает и применяет тип группы
	 * @return array
	 * @throws Throwable
	 */
	public function actionSetGroupType():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();
		$groupId = Yii::$app->request->post('groupId', false);
		$type = Yii::$app->request->post('type', false);
		if (!$groupId) {
			return $answer->addError('parameters', 'Not enough parameters');
		}
		/** @var Groups $group */
		if (null === ($group = Groups::findModel($groupId))) {
			return $answer->addError('group', 'Not found');
		}

		$group->setAndSaveAttribute('type', $type);
		return $answer->answer;
	}
}