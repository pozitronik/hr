<?php
declare(strict_types = 1);

namespace app\models\dynamic_attributes\types;

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
	public function attributeLabels():array {
		return [
			'selfScoreValue' => 'Оценка сотрудника (СО)',
			'tlScoreValue' => 'Оценка тимлида (TL)',
			'alScoreValue' => 'Оценка ареалида (AL)',
			'selfScoreComment' => 'Комментарий к самооценке',
			'tlScoreComment' => 'Комментарий к оценке тимлида',
			'alScoreComment' => 'Комментарий к оценке ареалида',
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
	 * @return int
	 */
	public function getSelfScoreValue():?int {
		return $this->_self_score_value;
	}

	/**
	 * @param int $self_score_value
	 */
	public function setSelfScoreValue(?int $self_score_value):void {
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
	 * @return int
	 */
	public function getTlScoreValue():?int {
		return $this->_tl_score_value;
	}

	/**
	 * @param int $tl_score_value
	 */
	public function setTlScoreValue(?int $tl_score_value):void {
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
	 * @return int
	 */
	public function getAlScoreValue():?int {
		return $this->_l_score_value;
	}

	/**
	 * @param int $al_score_value
	 */
	public function setAlScoreValue(?int $al_score_value):void {
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

}