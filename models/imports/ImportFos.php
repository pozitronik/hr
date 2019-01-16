<?php
declare(strict_types = 1);

namespace app\models\imports;

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\core\traits\Upload;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos".
 *
 * @property int $id
 * @property string $num № п/п
 * @property string $position_id ШД ID
 * @property string $position_name Должность
 * @property string $user_id ТН
 * @property string $user_name Ф.И.О. сотрудника
 * @property string $functional_block Функциональный блок
 * @property string $division_level_1 Подразделение 1 уровня
 * @property string $division_level_2 Подразделение 2 уровня
 * @property string $division_level_3 Подразделение 3 уровня
 * @property string $division_level_4 Подразделение 4 уровня
 * @property string $division_level_5 Подразделение 5 уровня
 * @property string $remote_flag Признак УРМ
 * @property string $town Населенный пункт
 * @property string $functional_block_tribe Функциональный блок трайба
 * @property string $tribe_id Трайб ID
 * @property string $tribe_code Код трайба
 * @property string $tribe_name Трайб
 * @property string $tribe_leader_id Лидер трайба ТН
 * @property string $tribe_leader_name Лидер трайба
 * @property string $tribe_leader_it_id IT-лидер трайба ТН
 * @property string $tribe_leader_it_name IT-лидер трайба
 * @property string $cluster_product_id Кластер/Продукт ID
 * @property string $cluster_product_code Код кластера/продукта
 * @property string $cluster_product_name Кластер/Продукт
 * @property string $cluster_product_leader_id Лидер кластера/продукта ТН
 * @property string $cluster_product_leader_name Лидер кластера/продукта
 * @property string $command_id Команда ID
 * @property string $command_code Код команды
 * @property string $command_name Команда
 * @property string $command_type Тип команды
 * @property string $owner_name Владелец продукта
 * @property string $command_position_id Позиция в команде ID
 * @property string $command_position_code Код позиции в команде
 * @property string $command_position_name Позиция в команде
 * @property string $chapter_id Чаптер ID
 * @property string $chapter_code Код чаптера
 * @property string $chapter_name Чаптер
 * @property string $chapter_leader_id Лидер чаптера ТН
 * @property string $chapter_leader_name Лидер чаптера
 * @property string $chapter_leader_couch_id Agile-коуч ТН
 * @property string $chapter_leader_couch_name Agile-коуч
 * @property string $email_sigma Адрес электронной почты (sigma)
 * @property string $email_alpha Адрес электронной почты (внутренний
 * @property int $domain Служеная метка очереди импорта
 */
class ImportFos extends ActiveRecord {
	use Upload;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_fos';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['domain'], 'integer'],
			[['num', 'position_id', 'position_name', 'user_id', 'user_name', 'functional_block', 'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4', 'division_level_5', 'remote_flag', 'town', 'functional_block_tribe', 'tribe_id', 'tribe_code', 'tribe_name', 'tribe_leader_id', 'tribe_leader_name', 'tribe_leader_it_id', 'tribe_leader_it_name', 'cluster_product_id', 'cluster_product_code', 'cluster_product_name', 'cluster_product_leader_id', 'cluster_product_leader_name', 'command_id', 'command_code', 'command_name', 'command_type', 'owner_name', 'command_position_id', 'command_position_code', 'command_position_name', 'chapter_id', 'chapter_code', 'chapter_name', 'chapter_leader_id', 'chapter_leader_name', 'chapter_leader_couch_id', 'chapter_leader_couch_name', 'email_sigma', 'email_alpha'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'num' => '№ п/п',
			'position_id' => 'ШД ID',
			'position_name' => 'Должность',
			'user_id' => 'ТН',
			'user_name' => 'Ф.И.О. сотрудника',
			'functional_block' => 'Функциональный блок',
			'division_level_1' => 'Подразделение 1 уровня',
			'division_level_2' => 'Подразделение 2 уровня',
			'division_level_3' => 'Подразделение 3 уровня',
			'division_level_4' => 'Подразделение 4 уровня',
			'division_level_5' => 'Подразделение 5 уровня',
			'remote_flag' => 'Признак УРМ',
			'town' => 'Населенный пункт',
			'functional_block_tribe' => 'Функциональный блок трайба',
			'tribe_id' => 'Трайб ID',
			'tribe_code' => 'Код трайба',
			'tribe_name' => 'Трайб',
			'tribe_leader_id' => 'Лидер трайба ТН',
			'tribe_leader_name' => 'Лидер трайба',
			'tribe_leader_it_id' => 'IT-лидер трайба ТН',
			'tribe_leader_it_name' => 'IT-лидер трайба',
			'cluster_product_id' => 'Кластер/Продукт ID',
			'cluster_product_code' => 'Код кластера/продукта',
			'cluster_product_name' => 'Кластер/Продукт',
			'cluster_product_leader_id' => 'Лидер кластера/продукта ТН',
			'cluster_product_leader_name' => 'Лидер кластера/продукта',
			'command_id' => 'Команда ID',
			'command_code' => 'Код команды',
			'command_name' => 'Команда',
			'command_type' => 'Тип команды',
			'owner_name' => 'Владелец продукта',
			'command_position_id' => 'Позиция в команде ID',
			'command_position_code' => 'Код позиции в команде',
			'command_position_name' => 'Позиция в команде',
			'chapter_id' => 'Чаптер ID',
			'chapter_code' => 'Код чаптера',
			'chapter_name' => 'Чаптер',
			'chapter_leader_id' => 'Лидер чаптера ТН',
			'chapter_leader_name' => 'Лидер чаптера',
			'chapter_leader_couch_id' => 'Agile-коуч ТН',
			'chapter_leader_couch_name' => 'Agile-коуч',
			'email_sigma' => 'Адрес электронной почты (sigma)',
			'email_alpha' => 'Адрес электронной почты (внутренний)'
		];
	}

	/**
	 * @param string $filename
	 * @param int|null $domain
	 * @return bool
	 */
	public static function Import(string $filename, ?int $domain = null):bool {
		$reader = new Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($filename);
		$spreadsheet->setActiveSheetIndex(0);
		$dataArray = $spreadsheet->getActiveSheet()->toArray();
		$domain = $domain??time();
		$keys = array_keys((new self())->attributeLabels());
		foreach ($dataArray as $importRow) {
			if (!is_numeric(ArrayHelper::getValue($importRow, "0"))) continue;//В первой ячейке строки должна быть цифра, если нет - это заголовок, его нужно пропустить
			$data = array_combine($keys, $importRow);

			$row = new self($data);
			$row->domain = $domain;
			$row->save(false);//пока сохраняем без строгой валидации
		}
		return true;

	}
}
