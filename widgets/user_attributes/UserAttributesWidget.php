<?php
declare(strict_types = 1);

namespace app\widgets\user_attributes;

use app\models\dynamic_attributes\DynamicAttributes;
use Throwable;
use yii\base\Widget;
use yii\web\ServerErrorHttpException;

/**
 * Виджет рисует панель атрибута со всеми его свойствами, динамически рассчитывая размеры для полей свойств
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
	 * @return null|string
	 * @throws Throwable
	 */
	public function run():?string {
		if (null === $attribute = DynamicAttributes::findModel($this->attribute_id, new ServerErrorHttpException("Dynamic attribute {$this->attribute_id} not found"))) return null;

		if (empty($attribute->structure)) return "Атрибут не имеет свойств";

		$userProperties = $attribute->getUserProperties($this->user_id);

		$fieldsCount = (count($userProperties));//В зависимости от количества СВОЙСТВ в атрибуте высчитываем подходящее количество колонок
		$mdClass = "col-md-1";
		if (1 === $fieldsCount) {
			$mdClass = "col-md-12";
		} elseif (2 === $fieldsCount) {
			$mdClass = "col-md-6";
		} elseif (3 === $fieldsCount) {
			$mdClass = "col-md-4";
		} elseif (4 === $fieldsCount) {
			$mdClass = "col-md-3";
		} elseif ($fieldsCount < 8) {
			$mdClass = "col-md-2";
		}
		return $this->render('attribute', [
			'dynamicAttribute' => $attribute,
			'userProperties' => $userProperties,
			'mdClass' => $mdClass
		]);
	}
}
