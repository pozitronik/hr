<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use app\modules\references\models\Reference;

/**
 * Справочник типов должностей. Тип должности -  необязательный, не влияющий ни на что атрибут должности.
 *
 * @property string $color
 */
class RefUserPositionTypes extends Reference {
	public $menuCaption = 'Типы должностей';
	public $menuIcon = false;

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'ref_user_position_types';
	}

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['id'], 'integer'],
			[['deleted'], 'boolean'],
			[['name', 'color'], 'string', 'max' => 256]
		];
	}
}