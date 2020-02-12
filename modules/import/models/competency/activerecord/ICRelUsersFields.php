<?php
declare(strict_types = 1);

namespace app\modules\import\models\competency\activerecord;

use pozitronik\core\traits\ARExtended;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_competency_rel_users_fields".
 *
 * @property int $id
 * @property int $user_id Ключ к пользователю
 * @property int $field_id Ключ к полю атрибута
 * @property string $value Значение поля в сыром виде
 * @property int $domain
 *
 * @property ICFields $relField
 * @property ICAttributes $relAttribute
 */
class ICRelUsersFields extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_competency_rel_users_fields';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'field_id'], 'required'],
			[['user_id', 'field_id'], 'integer'],
			[['value'], 'string'],
			['domain', 'integer'], ['domain', 'required']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'Ключ к пользователю',
			'field_id' => 'Ключ к полю атрибута',
			'value' => 'Значение поля в сыром виде'
		];
	}

	/**
	 * @return ICFields|ActiveQuery
	 */
	public function getRelField() {
		return $this->hasOne(ICFields::class, ['id' => 'field_id']);
	}

	/**
	 * @return ICAttributes|ActiveQuery
	 */
	public function getRelAttribute() {
		return $this->hasOne(ICAttributes::class, ['id' => 'attribute_id'])->via('relField');
	}
}
