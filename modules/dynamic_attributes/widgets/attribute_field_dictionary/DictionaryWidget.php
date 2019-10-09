<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_field_dictionary;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use yii\widgets\InputWidget;

/**
 * Class DictionaryWidget
 * @property DynamicAttributeProperty $model
 * @property string $attribute
 */
class DictionaryWidget extends InputWidget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		DictionaryWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * Виджет не может возвращать null
	 * @return string
	 */
	public function run():string {

		return $this->render('dictionary', [
			'attribute' => $this->attribute,
			'model' => $this->model
		]);
	}
}
