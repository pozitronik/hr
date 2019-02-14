<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\controllers;

use app\models\core\ajax\AjaxAnswer;
use app\models\core\ajax\BaseAjaxController;
use app\models\relations\RelUsersAttributes;
use app\models\relations\RelUsersAttributesTypes;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\ContentNegotiator;

/**
 * Class AjaxController
 * Все внутренние аяксовые методы.
 * Все экшоны должны отдавать только массивы значений, формат, возможно, определю позже.
 * Каждый метод должен быть прокомментирован: что и откуда к нему лезет.
 *
 * @package app\controllers
 */
class AjaxController extends BaseAjaxController {

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
			],
			'access' => [
				'class' => AccessControl::class,
				'denyCallback' => function() {
					return null;
				},
				'rules' => [
					[
						'allow' => Yii::$app->user->identity,
						'actions' => [
							'set-attribute-types-for-user'
						],
						'roles' => ['@', '?']
					]
				]
			]
		];
	}

	/**
	 * Устанавливает тип связи между пользователем и атрибутом
	 * @return array
	 * @throws Throwable
	 */
	public function actionSetAttributeTypesForUser():array {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$answer = new AjaxAnswer();

		$attributeId = Yii::$app->request->post('attributeId', false);
		$userId = Yii::$app->request->post('userId', false);
		$types = Yii::$app->request->post('types', []);
		if (!($attributeId && $userId)) {
			return $answer->addError('parameters', 'Not enough parameters');
		}

		if (null === RelUsersAttributes::find()->where(['attribute_id' => $attributeId, 'user_id' => $userId])->one()) {
			return $answer->addError('userAttributeRelation', 'Not found');
		}
		if (!(RelUsersAttributesTypes::clearAllAttributeTypesForUser((int)$userId, (int)$attributeId) && RelUsersAttributesTypes::setAttributeTypeForUser($types, (int)$userId, (int)$attributeId))) {
			return $answer->addError('setAttributeTypeForUser', 'Error');
		}
		return $answer->answer;
	}

}
