<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_product_owner".
 *
 * @property int $id
 * @property int $user_id key to user id
 * @property int $domain
 *
 * @property-read ImportFosUsers $relUsers
 */
class ImportFosProductOwner extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_product_owner';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id'], 'required'],
			[['user_id'], 'integer'],
			['domain', 'integer'], ['domain', 'required']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'key to user id'
		];
	}

	/**
	 * @return ImportFosUsers|ActiveQuery
	 */
	public function getRelUsers() {
		return $this->hasOne(ImportFosUsers::class, ['pkey' => 'user_id']);
	}
}
