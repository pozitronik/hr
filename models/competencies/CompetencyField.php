<?php
declare(strict_types = 1);

namespace app\models\competencies;

use yii\base\Model;

/**
 * Class CompetencyItem
 * Описание структуры поля компетенции
 * @package app\models\competencies
 *
 * @property integer $competencyId
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property boolean $required
 * @property boolean isNewRecord
 */
class CompetencyField extends Model {
	private $competency_id; //Внутреннее поле для связи с компетенцией
	private $id;
	private $name = '';
	private $type = 'integer';
	private $required = false;
	private $isNewRecord = true;

	public const FIELD_TYPES = [
		'integer' => 'Число',
		'boolean' => 'Логический тип',
		'string' => 'Строка',
		'date' => 'Дата',
		'time' => 'Время',
		'range' => 'Интервал',
		'percent' => 'Проценты'
	];

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['id'], 'unique'],
			[['name', 'type'], 'string'],
			[['type'], 'in' , 'range' => array_keys(self::FIELD_TYPES)],//todo check this
			[['required'], 'boolean'],
			[['name', 'type', 'required'], 'required']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'Ключ/порядок',
			'name' => 'Название',
			'type' => 'Тип',
			'required' => 'Обязательное поле'
		];
	}

	/**
	 * @return string
	 */
	public function getName():string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name):void {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getType():string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type):void {
		$this->type = $type;
	}

	/**
	 * @return bool
	 */
	public function getRequired():bool {
		return $this->required;
	}

	/**
	 * @param bool $required
	 */
	public function setRequired(bool $required):void {
		$this->required = $required;
	}

	/**
	 * @return bool
	 */
	public function getIsNewRecord():bool {
		return $this->isNewRecord;//todo: use id
	}

	/**
	 * @return integer
	 */
	public function getId():int {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getCompetencyId():int {
		return $this->competency_id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id):void {
		$this->id = $id;
	}

	/**
	 * @param int $competency_id
	 */
	public function setCompetencyId(int $competency_id):void {
		$this->competency_id = $competency_id;
	}



}