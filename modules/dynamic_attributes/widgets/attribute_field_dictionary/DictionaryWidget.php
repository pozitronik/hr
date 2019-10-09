<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_field_dictionary;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use yii\data\ArrayDataProvider;
use yii\widgets\InputWidget;

/**
 * Class DictionaryWidget
 * @property DynamicAttributeProperty $model
 * @property string $attribute
 */
class DictionaryWidget extends InputWidget {
	public $readOnly = true;//для совместимости вызовов, на деле не используются
	public $showEmpty = true;

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

		$dataProvider = new ArrayDataProvider([
			'allModels' => $this->model->{$this->attribute},
			'sort' => [
				'attributes' => ['id', 'value', 'frequency'],
				'defaultOrder' => ['frequency' => SORT_DESC]
			],
			'pagination' => false
		]);

		return $this->render('dictionary', [
			'provider' => $dataProvider
		]);
	}
}
