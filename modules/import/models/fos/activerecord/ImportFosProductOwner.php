<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos\activerecord;

use app\models\core\traits\ARExtended;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_product_owner".
 * Это больше справочник: продуктовнеры матчатся по имени (TH овнера не укзан в файле).
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
			[['user_id'], 'unique'],
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
		return $this->hasOne(ImportFosUsers::class, ['id' => 'user_id']);
	}
}
