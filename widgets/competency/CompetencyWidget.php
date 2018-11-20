<?php
declare(strict_types = 1);

namespace app\widgets\competency;

use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use Throwable;
use yii\base\Widget;
use yii\data\ArrayDataProvider;

/**
 * Class GroupSelectWidget
 * @package app\components\competency
 * @property integer $user_id
 * @property integer $competency_id
 */
class CompetencyWidget extends Widget {
	public $user_id;
	public $competency_id;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		CompetencyWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		$competency = Competencies::findModel($this->competency_id);
		//$user = Users::findModel($this->user_id);

		if (empty($competency->structure)) return "Компетенция не имеет атрибутов";

		$widgetDataProvider = new ArrayDataProvider();

		$result = [];
		foreach ($competency->structure as $field_data) {
			$field = new CompetencyField($field_data);
			$field->competencyId = $this->competency_id;//todo move to initializer
			$result[] = [
				'name' => $field->name,
				'value' => $field->getValue($this->user_id)
			];

		}

		$widgetDataProvider->allModels = $result;

		return $this->render('competency',[
			'widgetDataProvider' => $widgetDataProvider
		]);
	}
}
