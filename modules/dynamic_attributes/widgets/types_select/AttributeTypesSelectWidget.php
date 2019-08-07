<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\types_select;

use yii\base\Widget;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\models\relations\RelUsersAttributesTypes;

/**
 * Class AttributeTypesSelectWidget
 * @package app\modules\dynamic_attributes\widgets\types_select
 * @property array $data
 * @property array $value
 * @property int $attributeId
 * @property int $userId
 * @property bool $showStatus
 */
class AttributeTypesSelectWidget extends Widget {
	public $data;
	public $value;
	public $attributeId;
	public $userId;
	public $showStatus = true;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AttributeTypesSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('attribute_types_select', [
			'data' => $this->data??RefAttributesTypes::mapData(),
			'value' => $this->value??RelUsersAttributesTypes::getAttributeTypesId($this->userId, $this->attributeId),
			'userId' => $this->userId,
			'attributeId' => $this->attributeId,
			'options' => RefAttributesTypes::dataOptions(),
			'showStatus' => $this->showStatus
		]);
	}
}
