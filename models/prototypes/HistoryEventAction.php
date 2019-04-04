<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class HistoryEventAction
 * @package app\models\prototypes
 *
 * @property int $type тип события
 * @property string $attributeName изменившийся атрибут
 * @property mixed $attributeOldValue значение атрибута до изменения
 * @property mixed $attributeNewValue значение атрибута после изменения
 *
 */
class HistoryEventAction extends Model {
	const ATTRIBUTE_CREATED = 0;
	const ATTRIBUTE_CHANGED = 1;
	const ATTRIBUTE_DELETED = 2;

	public $type;
	public $attributeName;
	public $attributeOldValue;
	public $attributeNewValue;

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels() {
		return [
			'type' => 'Изменение',
			'attributeName' => 'Атрибут',
			'attributeOldValue' => 'Было',
			'attributeNewValue' => 'Стало'
		];
	}
}