<?php
declare(strict_types = 1);

namespace app\components\pozitronik\arlogger\models;

use Throwable;
use yii\base\Model;
use app\components\pozitronik\helpers\ArrayHelper;

/**
 * Class HistoryEventAction
 *
 * @property int $type тип события
 * @property null|string $attributeName изменившийся атрибут
 * @property mixed $attributeOldValue значение атрибута до изменения
 * @property mixed $attributeNewValue значение атрибута после изменения
 *
 * @property string $typeName
 *
 */
class HistoryEventAction extends Model {
	public const ATTRIBUTE_CREATED = 0;
	public const ATTRIBUTE_CHANGED = 1;
	public const ATTRIBUTE_DELETED = 2;

	public const ATTRIBUTE_TYPE_NAMES = [
		self::ATTRIBUTE_CREATED => 'Added',
		self::ATTRIBUTE_CHANGED => 'Changed',
		self::ATTRIBUTE_DELETED => 'Deleted'
	];

	public $type;
	public $attributeName;
	public $attributeOldValue;
	public $attributeNewValue;

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'type' => 'Change',
			'typeName' => 'Action',
			'attributeName' => 'Attribute',
			'attributeOldValue' => 'Old value',
			'attributeNewValue' => 'New value'
		];
	}

	/**
	 * @return null|string
	 * @throws Throwable
	 */
	public function getTypeName():?string {
		return ArrayHelper::getValue(self::ATTRIBUTE_TYPE_NAMES, $this->type);
	}
}