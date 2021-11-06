<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos\activerecord;

use app\components\pozitronik\core\traits\ARExtended;
use app\modules\import\models\fos\ImportFosDecomposed;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_functional_block".
 *
 * @property int $id
 * @property string $name
 * @property int $domain
 * @property null|int $hr_group_id
 *
 * @property-read ImportFosDecomposed[] $relDecomposed
 * @property-read ImportFosTribe[] $relTribe
 */
class ImportFosFunctionalBlock extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_functional_block';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'string', 'max' => 255],
			[['name'], 'unique'],
			[['name'], 'required'],
			['domain', 'integer'], ['domain', 'required'],
			['hr_group_id', 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDecomposed():ActiveQuery {
		return $this->hasMany(ImportFosDecomposed::class, ['functional_block_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTribe():ActiveQuery {
		return $this->hasMany(ImportFosTribe::class, ['id' => 'tribe_id'])->via('relDecomposed');
	}
}
