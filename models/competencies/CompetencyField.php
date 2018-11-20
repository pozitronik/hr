<?php
declare(strict_types = 1);

namespace app\models\competencies;

use app\models\competencies\types\CompetencyFieldBoolean;
use app\models\competencies\types\CompetencyFieldDate;
use app\models\competencies\types\CompetencyFieldInteger;
use app\models\competencies\types\CompetencyFieldPercent;
use app\models\competencies\types\CompetencyFieldRange;
use app\models\competencies\types\CompetencyFieldString;
use app\models\competencies\types\CompetencyFieldTime;
use http\Exception\RuntimeException;
use yii\base\InvalidCallException;
use yii\base\Model;
use yii\base\UnknownPropertyException;

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
 *
 * @property integer $userId;
 *
 * @property boolean isNewRecord
 * @property mixed $value
 */
class CompetencyField extends Model {
	private $competency_id; //Внутреннее поле для связи с компетенцией
	private $id;
	private $name = '';
	private $type = 'integer';
	private $required = false;
	private $isNewRecord = true;

	private $user_id;

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
			[['type'], 'in', 'range' => array_keys(self::FIELD_TYPES)],//todo check this
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
	 * @inheritdoc
	 */
	public function __get($name) {
		$getter = 'get'.$name;
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}
		if (method_exists($this, 'set'.$name)) {
			throw new InvalidCallException('Getting write-only property: '.get_class($this).'::'.$name);
		}
		if ((int)$name === $this->id) {/*Хачим геттер метода для совместимости с ActiveForm::field*/
			return $this->value;
		}

		throw new UnknownPropertyException('Getting unknown property: '.get_class($this).'::'.$name);
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

	/**
	 * Вернёт значение поля компетенции для указанного пользователя
	 * @return mixed
	 */
	public function getValue() {
		switch ($this->type) {
			case 'boolean':
				return CompetencyFieldBoolean::getValue($this->competency_id, $this->id, $this->user_id);
			break;
			case 'date':
				return CompetencyFieldDate::getValue($this->competency_id, $this->id, $this->user_id);
			break;
			case 'integer':
				return CompetencyFieldInteger::getValue($this->competency_id, $this->id, $this->user_id);
			break;
			case 'percent':
				return CompetencyFieldPercent::getValue($this->competency_id, $this->id, $this->user_id);
			break;
			case 'range':
				return CompetencyFieldRange::getValue($this->competency_id, $this->id, $this->user_id);
			break;
			case 'string':
				return CompetencyFieldString::getValue($this->competency_id, $this->id, $this->user_id);
			break;
			case 'time':
				return CompetencyFieldTime::getValue($this->competency_id, $this->id, $this->user_id);
			break;
			default:
				throw new RuntimeException("Field type not implemented: {$this->type}");
			break;
		}
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value):void {

	}

	/**
	 * @return int
	 */
	public function getUserId():int {
		return $this->user_id;
	}

	/**
	 * @param int $user_id
	 */
	public function setUserId(int $user_id):void {
		$this->user_id = $user_id;
	}

}