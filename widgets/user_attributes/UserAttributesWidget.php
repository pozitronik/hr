<?php
declare(strict_types = 1);

namespace app\widgets\user_attributes;

use app\models\dynamic_attributes\DynamicAttributes;
use Throwable;
use yii\base\Widget;
use yii\data\ArrayDataProvider;

/**
 * @property integer $user_id
 * @property integer $attribute_id
 * @property boolean $show_category
 */
class UserAttributesWidget extends Widget {
	public $user_id;
	public $attribute_id;
	public $show_category = false;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserAttributesWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		$attribute = DynamicAttributes::findModel($this->attribute_id);

		if (empty($attribute->structure)) return "Атрибут не имеет свойств";

		$widgetDataProvider = new ArrayDataProvider();

		$widgetDataProvider->allModels = $attribute->getUserProperties($this->user_id);

		return $this->render('attribute', [
			'widgetDataProvider' => $widgetDataProvider,
			'show_category' => $this->show_category
		]);
	}
}
