<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\dynamic_attribute;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\components\pozitronik\widgets\CachedWidget;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use Throwable;
use yii\base\InvalidConfigException;
use yii\web\ServerErrorHttpException;

/**
 * Виджет рисует панель атрибута со всеми его свойствами, динамически рассчитывая размеры для полей свойств
 * Атрибут может быть задан либо через $user_id + $attribute_id, либо напрямую через переданный атрибут в $attribute
 * @property int|null $user_id
 * @property int $attribute_id
 * @property DynamicAttributes $attribute
 * @property bool $show_category
 * @property bool $read_only
 * @property null|int[] $property_id -- если указан, то id свойств, которые должны быть показаны (остальные скипаются)
 */
class DynamicAttributeWidget extends CachedWidget {
	public $user_id;
	public $attribute_id;
	public $attribute;
	public $show_category = false;
	public $read_only = true;
	public $property_id;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		DynamicAttributeWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return null|string
	 * @throws Throwable
	 */
	public function run():?string {
		if (null === $this->attribute) {
			if (null === $this->user_id || null === $this->attribute_id) {
				throw new InvalidConfigException("Either 'attribute', or 'user_id' and 'attribute_id' properties must be specified.");
			}
			if (null === $this->attribute = DynamicAttributes::findModel($this->attribute_id, new ServerErrorHttpException("Dynamic attribute {$this->attribute_id} not found"))) return null;
			if (empty($this->attribute->structure)) return "Атрибут не имеет свойств";
			$propertiesCollection = $this->attribute->getUserProperties($this->user_id);
		} else {
			if (empty($this->attribute->structure)) return "Атрибут не имеет свойств";
			$propertiesCollection = $this->attribute->getVirtualProperties();
		}

		if (null !== $this->property_id) {
			$propertiesCollection = array_filter($propertiesCollection, function(DynamicAttributeProperty $property) {
				return in_array($property->id, $this->property_id);
			});
		}

		$fieldsCount = count($propertiesCollection);//В зависимости от количества СВОЙСТВ в атрибуте высчитываем подходящее количество колонок
		if (1 === $fieldsCount) {
			$mdClass = "col-md-12";
		} elseif (2 === $fieldsCount) {
			$mdClass = "col-md-6";
		} else $mdClass = "col-md-4";

		return null === $this->user_id?$this->render('virtual_attribute', [
			'dynamicAttribute' => $this->attribute,
			'propertiesCollection' => $propertiesCollection,
			'mdClass' => $mdClass
		]):$this->render('attribute', [
			'dynamicAttribute' => $this->attribute,
			'propertiesCollection' => $propertiesCollection,
			'mdClass' => $mdClass,
			'user_id' => $this->user_id,
			'read_only' => $this->read_only
		]);
	}
}
