<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use yii\base\Model;

/**
 * Class ScoreProperty
 * Модель, описывающая данные с оценкой компетенций
 * @package app\models\dynamic_attributes\types
 *
 * @property null|int $selfScoreValue [int(11)]  Оценка сотрудника (СО)
 * @property null|string $selfScoreComment [varchar(255)]  Комментарий к самооценке
 * @property null|int $tlScoreValue [int(11)]  Оценка тимлида (TL)
 * @property null|string $tlScoreComment [varchar(255)]  Комментарий к оценке тимлида
 * @property null|int $alScoreValue [int(11)]  Оценка ареалида (AL)
 * @property null|string $alScoreComment [varchar(255)]  Комментарий к оценке ареалида
 *
 * @property-read bool $empty
 */
class ScoreProperty extends Model {
	private $_self_score_value;
	private $_tl_score_value;
	private $_al_score_value;
	private $_self_score_comment;
	private $_tl_score_comment;
	private $_al_score_comment;

	/**
	 * {@inheritDoc}
	 */
	public function attributes():array {
		return [
			'selfScoreValue',
			'tlScoreValue',
			'alScoreValue',
			'selfScoreComment',
			'tlScoreComment',
			'alScoreComment'
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function attributeLabels():array {
		return [
			'selfScoreValue' => 'Оценка сотрудника',
			'tlScoreValue' => 'Оценка тимлида',
			'alScoreValue' => 'Оценка ареалида',
			'selfScoreComment' => 'Комментарий к самооценке',
			'tlScoreComment' => 'Комментарий к оценке тимлида',
			'alScoreComment' => 'Комментарий к оценке ареалида'
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['selfScoreValue', 'tlScoreValue', 'alScoreValue'], 'integer', 'min' => 0, 'max' => 5],
			[['selfScoreComment', 'tlScoreComment', 'alScoreComment'], 'string', 'max' => 255]
		];
	}

	/**
	 * @return float|null
	 */
	public function getSelfScoreValue():?float {
		return $this->_self_score_value;
	}

	/**
	 * @param float|null $self_score_value
	 */
	public function setSelfScoreValue(?float $self_score_value):void {
		$this->_self_score_value = $self_score_value;
	}

	/**
	 * @return string
	 */
	public function getSelfScoreComment():?string {
		return $this->_self_score_comment;
	}

	/**
	 * @param string $self_score_comment
	 */
	public function setSelfScoreComment(?string $self_score_comment):void {
		$this->_self_score_comment = $self_score_comment;
	}

	/**
	 * @return float|null
	 */
	public function getTlScoreValue():?float {
		return $this->_tl_score_value;
	}

	/**
	 * @param float|null $tl_score_value
	 */
	public function setTlScoreValue(?float $tl_score_value):void {
		$this->_tl_score_value = $tl_score_value;
	}

	/**
	 * @return string
	 */
	public function getTlScoreComment():?string {
		return $this->_tl_score_comment;
	}

	/**
	 * @param string $tl_score_comment
	 */
	public function setTlScoreComment(?string $tl_score_comment):void {
		$this->_tl_score_comment = $tl_score_comment;
	}

	/**
	 * @return float|null
	 */
	public function getAlScoreValue():?float {
		return $this->_al_score_value;
	}

	/**
	 * @param float|null $al_score_value
	 */
	public function setAlScoreValue(?float $al_score_value):void {
		$this->_al_score_value = $al_score_value;
	}

	/**
	 * @return string
	 */
	public function getAlScoreComment():?string {
		return $this->_al_score_comment;
	}

	/**
	 * @param string $al_score_comment
	 */
	public function setAlScoreComment(?string $al_score_comment):void {
		$this->_al_score_comment = $al_score_comment;
	}

	/**
	 * Проверка на заполненность хотя бы одного атрибута
	 * @return bool
	 */
	public function getEmpty():bool {
		foreach ($this->attributes as $attribute) {
			if (!empty($attribute)) return false;
		}
		return true;
	}

	/**
	 * return string
	 */
	public function __toString():string {
		$data = [];
		foreach ($this->attributes() as $attribute) {
			$label = $this->attributeLabels()[$attribute];
			$data[$label] = $label.': '.(empty ($this->$attribute)?'N/A':$this->$attribute);
		}
		return implode(",\n", $data);
	}

	/**
	 * Сложение моделей (числовых признаков)
	 * @param self[] $items
	 * @return static
	 * @unused
	 * @deprecated
	 */
	public static function add(array $items):self {
		$resultScore = new self([
			'selfScoreValue' => 0,
			'tlScoreValue' => 0,
			'alScoreValue' => 0,
			'selfScoreComment' => null,
			'tlScoreComment' => null,
			'alScoreComment' => null
		]);
		foreach ($items as $item) {
			$resultScore->selfScoreValue += (int)$item->selfScoreValue;
			$resultScore->tlScoreValue += (int)$item->tlScoreValue;
			$resultScore->alScoreValue += (int)$item->alScoreValue;
		}
		return $resultScore;
	}

	/**
	 * Деление числовых показателей
	 * @param float $value
	 * @unused
	 * @deprecated
	 */
	public function div(float $value):void {
		$this->selfScoreValue /= $value;
		$this->tlScoreValue /= $value;
		$this->alScoreValue /= $value;
	}
}