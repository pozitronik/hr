<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class PrototypeCompetenciesSearch
 * Прототип модели поиска пользователей по компетенциям
 * @package app\models\prototypes
 *
 * @property boolean $logic_mode_and Режим поиска: true - И (все правила), false - ИЛИ (хотя бы одно правило)
 */
class PrototypeCompetenciesSearch extends Model {
	public $logic_mode_and = true;


	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['logic_mode_and'], 'boolean']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'logic_mode_and' => 'Учитывать все правила'
		];
	}

}