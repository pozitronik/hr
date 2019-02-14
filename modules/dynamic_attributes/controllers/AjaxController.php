<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\controllers;

use app\helpers\ArrayHelper;
use app\models\core\ajax\BaseAjaxController;
use app\models\relations\RelUsersAttributes;
use app\models\relations\RelUsersAttributesTypes;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use Throwable;
use Yii;

/**
 * Class AjaxController
 * Все внутренние аяксовые методы модуля.
 */
class AjaxController extends BaseAjaxController {

	/**
	 * Устанавливает тип связи между пользователем и атрибутом
	 * @return array
	 * @throws Throwable
	 */
	public function actionSetAttributeTypesForUser():array {
		$attributeId = Yii::$app->request->post('attributeId', false);
		$userId = Yii::$app->request->post('userId', false);
		$types = Yii::$app->request->post('types', []);
		if (!($attributeId && $userId)) {
			return $this->answer->addError('parameters', 'Not enough parameters');
		}

		if (null === RelUsersAttributes::find()->where(['attribute_id' => $attributeId, 'user_id' => $userId])->one()) {
			return $this->answer->addError('userAttributeRelation', 'Not found');
		}
		if (!(RelUsersAttributesTypes::clearAllAttributeTypesForUser((int)$userId, (int)$attributeId) && RelUsersAttributesTypes::setAttributeTypeForUser($types, (int)$userId, (int)$attributeId))) {
			return $this->answer->addError('setAttributeTypeForUser', 'Error');
		}
		return $this->answer->answer;
	}

	/**
	 * Возвращает свойства атрибута
	 * @return array
	 * @throws Throwable
	 */
	public function actionAttributeGetProperties():array {
		if (null !== $attribute_id = Yii::$app->request->post('attribute')) {
			if (null !== $attribute = DynamicAttributes::findModel($attribute_id)) {
				$this->answer->items = $attribute->structure;
				return $this->answer->answer;
			}
			return $this->answer->addError('attribute', 'Not found');
		}
		return $this->answer->addError('attribute', 'Empty');
	}

	/**
	 * Возвращает набор условий для этого типа свойства
	 * @return array
	 * @throws Throwable
	 */
	public function actionAttributeGetPropertyCondition():array {
		if (false !== $type = Yii::$app->request->post('type', false)) {
			/** @var string $type */
			if (null !== $className = DynamicAttributeProperty::getTypeClass($type)) {
				$this->answer->items = ArrayHelper::keymap($className::conditionConfig(), 0);
				return $this->answer->answer;
			}
			return $this->answer->addError('type', 'Not found');
		}
		return $this->answer->addError('type', 'Empty');
	}

}
