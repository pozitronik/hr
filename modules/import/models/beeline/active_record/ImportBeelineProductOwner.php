<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_beeline_product_owner".
 *
 * @property int $id
 * @property int $user_id
 * @property int $domain
 * @property null|ImportBeelineUsers $relUsers
 */
class ImportBeelineProductOwner extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_beeline_product_owner';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'domain'], 'integer'],
			[['domain'], 'required'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'domain' => 'Domain',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelUsers():ActiveQuery {
		return $this->hasOne(ImportBeelineUsers::class, ['id' => 'user_id']);
	}
}
