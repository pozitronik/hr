<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use yii\base\Model;

/**
 * Class HistoryEventAction
 *
 * @property int $type тип события
 * @property string $attributeName изменившийся атрибут
 * @property mixed $attributeOldValue значение атрибута до изменения
 * @property mixed $attributeNewValue значение атрибута после изменения
 *
 */
class HistoryEventAction extends Model {
	public const ATTRIBUTE_CREATED = 0;
	public const ATTRIBUTE_CHANGED = 1;
	public const ATTRIBUTE_DELETED = 2;

	public $type;
	public $attributeName;
	public $attributeOldValue;
	public $attributeNewValue;

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'type' => 'Изменение',
			'attributeName' => 'Атрибут',
			'attributeOldValue' => 'Было',
			'attributeNewValue' => 'Стало'
		];
	}
}