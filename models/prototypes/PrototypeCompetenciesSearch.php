<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use yii\base\Model;

/**
 * Class PrototypeCompetenciesSearch
 * Прототип модели поиска пользователей по компетенциям
 * @package app\models\prototypes
 *
 * @property boolean $logic Режим поиска: true - И (все правила), false - ИЛИ (хотя бы одно правило)
 * @property Competencies $competency Искомая компетенция
 * @property CompetencyField $field Поле компетенции
 * @property PrototypeCompetencySearchCondition $condition
 * @property mixed $value Искомое значение
 */
class PrototypeCompetenciesSearch extends Model {
	public $logic = true;
	public $competency;
	public $field;
	public $condition;
	public $value;

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['logic'], 'boolean'],
			[['competency', 'field', 'condition', 'value'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'logic' => 'Объединение',
			'competency' => 'Компетенция',
			'field' => 'Поле',
			'condition' => 'Условие',
			'value' => 'Значение'
		];
	}

}