<?php
declare(strict_types = 1);

namespace app\models\imports;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_decomposed".
 *
 * @property int $id
 * @property string $num № п/п
 * @property int $position_id
 * @property int $user_id
 * @property int $functional_block
 * @property int $division_level_1
 * @property int $division_level_2
 * @property int $division_level_3
 * @property int $division_level_4
 * @property int $division_level_5
 * @property int $functional_block_tribe
 * @property int $tribe_id
 * @property int $cluster_product_id
 * @property int $command_id
 * @property int $command_position_id
 * @property int $chapter_id
 * @property int $domain Служеная метка очереди импорта
 */
class ImportFosDecomposed extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_fos_decomposed';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['position_id', 'user_id', 'functional_block', 'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4', 'division_level_5', 'functional_block_tribe', 'tribe_id', 'cluster_product_id', 'command_id', 'command_position_id', 'chapter_id', 'domain'], 'integer'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'position_id' => 'Position ID',
			'user_id' => 'User ID',
			'functional_block' => 'Functional Block',
			'division_level_1' => 'Division Level 1',
			'division_level_2' => 'Division Level 2',
			'division_level_3' => 'Division Level 3',
			'division_level_4' => 'Division Level 4',
			'division_level_5' => 'Division Level 5',
			'functional_block_tribe' => 'Functional Block Tribe',
			'tribe_id' => 'Tribe ID',
			'cluster_product_id' => 'Cluster Product ID',
			'command_id' => 'Command ID',
			'command_position_id' => 'Command Position ID',
			'chapter_id' => 'Chapter ID',
			'domain' => 'Служебная метка очереди импорта',
		];
	}
}
