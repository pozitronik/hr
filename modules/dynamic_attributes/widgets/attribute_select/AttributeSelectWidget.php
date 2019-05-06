<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_select;

use pozitronik\helpers\ArrayHelper;
use app\models\core\SelectionWidgetInterface;
use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use kartik\base\InputWidget;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Виддет выбора атрибута (чего-либо, не обязательно пользователя - хотя на данный момент подразумевается, что атрибуты есть только у пользователей)
 * Class AttributeSelectWidget
 * @package app\modules\dynamic_attributes\widgets\attribute_select
 *
 * @property ActiveRecord|null $model Модель, с которой будет ассоциироваться
 * @property string|null $attribute ...и свойством модели
 * @property array $notData Данные, исключаемые из списка
 * @property boolean $multiple
 * @property string|array $formAction Свойство для переопределения экшона формы постинга (при MODE_FORM)
 * @property int $mode
 * @property int $dataMode Режим загрузки данных
 */
class AttributeSelectWidget extends InputWidget implements SelectionWidgetInterface {
	public $mode = self::MODE_FIELD;
	public $dataMode = self::DATA_MODE_LOAD;
	public $notData = [];
	public $multiple = false;
	public $groupByType = true;
	public $formAction = '';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AttributeSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function run():string {

		$data = self::DATA_MODE_AJAX === $this->dataMode?[]:ArrayHelper::map($this->model->isNewRecord?DynamicAttributes::find()->active()->all():DynamicAttributes::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($this->model->{$this->attribute}, 'id')])->all(), 'id', 'name');

		switch ($this->mode) {
			default:
			case self::MODE_FIELD:
				return $this->render('attribute_select_field', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'multiple' => $this->multiple,
					'data_mode' => $this->dataMode,
					'ajax_search_url' => DynamicAttributesModule::to('ajax/attribute-search')
				]);
			break;
			case self::MODE_FORM:
				return $this->render('attribute_select_form', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $data,
					'multiple' => $this->multiple,
					'formAction' => $this->formAction,
					'data_mode' => $this->dataMode,
					'ajax_search_url' => DynamicAttributesModule::to('ajax/attribute-search')
				]);
			break;
		}
	}
}
