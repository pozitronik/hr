<?php
declare(strict_types = 1);

namespace app\modules\grades\models\references;

use app\modules\references\models\Reference;

/**
 * Справочник типов должностей. Тип должности -  необязательный, не влияющий ни на что атрибут должности.
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
			[['id', 'deleted'], 'integer'],
			[['name'], 'string', 'max' => 256]
		];
	}
}