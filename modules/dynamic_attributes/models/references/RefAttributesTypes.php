<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\references;

use pozitronik\references\models\CustomisableReference;
use app\models\relations\RelUsersAttributesTypes;

/**
 * This is the model class for table "ref_attributes_types".
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $deleted
 * @property-read integer $usedCount Количество объектов, использующих это значение справочника
 */
class RefAttributesTypes extends CustomisableReference {
	public $menuCaption = 'Типы отношений атрибутов';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_attributes_types';
	}


	/**
	 * @return int
	 */
	public function getUsedCount():int {
		return (int)RelUsersAttributesTypes::find()->where(['type' => $this->id])->count();
	}

}
