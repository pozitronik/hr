<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\reference_select;

use app\modules\references\models\ReferenceInterface;
use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;
use yii\web\JsExpression;

/**
 * Виджет-выбиралка для любых справочников. Добавляет к Select2 стандартное для справочников форматирование данных.
 *
 * Class GroupSelectWidget
 * @package app\components\reference_select
 *
 * @property ReferenceInterface $referenceClass
 */
class ReferenceSelectWidget extends Select2 {
	public $referenceClass;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		ReferenceSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws ReflectionException
	 * @throws InvalidConfigException
	 */
	public function run():?string {

		if (null !== $this->referenceClass) {
			$this->pluginOptions['templateResult'] = new JsExpression('function(item) {return formatReferenceItem(item)}');
			$this->pluginOptions['templateSelection'] = new JsExpression('function(item) {return formatSelectedReferenceItem(item)}');
			$this->pluginOptions['escapeMarkup'] = new JsExpression('function (markup) { return markup; }');
			$this->data = $this->referenceClass::mapData();
			$this->options['options'] = $this->referenceClass::dataOptions();
		}
		return parent::run();
	}
}
