<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\controllers;

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use pozitronik\helpers\ArrayHelper;
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
		$attribute_id = ArrayHelper::getValue(Yii::$app->request->post('depdrop_parents'), 0);
		if (null === $attribute = DynamicAttributes::findModel($attribute_id)) {
			return [
				'output' => []
			];
		}

		$out = $attribute->structure;
		return ['output' => $out, 'selected' => ArrayHelper::getValue($out, '0.id')];
	}

	/**
	 * Возвращает набор условий для этого типа свойства
	 * @return array
	 * @throws Throwable
	 */
	public function actionAttributeGetPropertyCondition():array {
		$attribute_id = (int)ArrayHelper::getValue(Yii::$app->request->post('depdrop_params'), 0);
		$property_id = (int)ArrayHelper::getValue(Yii::$app->request->post('depdrop_parents'), 0);

		if (null === $attribute = DynamicAttributes::findModel($attribute_id)) {
			return [
				'output' => []
			];
		}

		if (null === $property = $attribute->getPropertyById($property_id)) {
			return [
				'output' => []
			];
		}

		if (null === $className = DynamicAttributeProperty::getTypeClass($property->type)) {
			return [
				'output' => []
			];
		}

		$out = ArrayHelper::mapEx($className::conditionConfig(), ['id' => 'key', 'name' => 0]);

		return ['output' => $out, 'selected' => ArrayHelper::getValue($out, '0.id')];

	}

	/**
	 * Возвращает набор агрегаторов, поддерживаемых этим свойством
	 * @return array
	 */
	public function actionAttributeGetPropertyAggregations():array {
		$attribute_id = (int)ArrayHelper::getValue(Yii::$app->request->post('depdrop_params'), 0);
		$property_id = (int)ArrayHelper::getValue(Yii::$app->request->post('depdrop_parents'), 0);
		/*Выбран атрибут, не выбрано|не существует свойство => нужны все агрегаторы всех атрибутов свойства*/
		if (null !== $attribute = DynamicAttributes::findModel($attribute_id)) {
			if ((null === $property = $attribute->getPropertyById($property_id)) || (null === $className = DynamicAttributeProperty::getTypeClass($property->type))) {
				$outputLabels = array_intersect_key(DynamicAttributePropertyAggregation::AGGREGATION_LABELS, array_flip($attribute->possibleAggregations));
			} else {//ничего не выбрано => ничего не возвращаем. Теоретически, нужно вернуть все агрегаторы всех типов скоупа, но здесь мы о скопе ничего не знаем
				return [
					'output' => []
				];
			}
		} else {
			/** @var string $className -- всегда будет заполнено, но стоит переписать условие*/
			$outputLabels = array_intersect_key(DynamicAttributePropertyAggregation::AGGREGATION_LABELS, array_flip($className::aggregationConfig()));
		}

		$out = ArrayHelper::mapEx($outputLabels, ['id' => 'key', 'name' => 'value']);

		return ['output' => $out, 'selected' => ArrayHelper::getValue($out, '0.id')];

	}

	/**
	 * Поиск пользователя в Select2
	 *
	 * @param string|null $term Строка поиска
	 * @param int $page Номер страницы (не поддерживается, задел на быдущее)
	 * @param int|null $user Пользователь, атрибуты которого ИСКЛЮЧАЮТСЯ из поиска
	 * @return array
	 */
	public function actionAttributeSearch(?string $term = null, ?int $page = 0, ?int $user = null):array {
		$out = ['results' => ['id' => '', 'text' => '']];
		if (null !== $term) {
			$data = DynamicAttributes::find()->distinct()->select(['sys_attributes.id', 'sys_attributes.name as text'])
				->where(['like', 'sys_attributes.name', $term])->andWhere(['not', ['sys_attributes.id' => RelUsersAttributes::find()->select('attribute_id')->where(['user_id' => $user])]])->offset(20 * $page)->limit(20)->asArray()->all();
			$out['results'] = array_values($data);
		}
		return $out;
	}

}
