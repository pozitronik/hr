<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use Throwable;
use yii\base\Model;

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
		self::ATTRIBUTE_CREATED => 'Добавлено',
		self::ATTRIBUTE_CHANGED => 'Изменено',
		self::ATTRIBUTE_DELETED => 'Удалено'
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
			'type' => 'Изменение',
			'typeName' => 'Событие',
			'attributeName' => 'Атрибут',
			'attributeOldValue' => 'Было',
			'attributeNewValue' => 'Стало'
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