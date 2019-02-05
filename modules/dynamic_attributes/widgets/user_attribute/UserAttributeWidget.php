<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\user_attribute;

use app\modules\dynamic_attributes\models\DynamicAttributes;
use Throwable;
use yii\base\Widget;
use yii\web\ServerErrorHttpException;

/**
 * Виджет рисует панель атрибута со всеми его свойствами, динамически рассчитывая размеры для полей свойств
 * @property int $user_id
 * @property int $attribute_id
 * @property bool $show_category
 * @property bool $read_only
 */
class UserAttributeWidget extends Widget {
	public $user_id;
	public $attribute_id;
	public $show_category = false;
	public $read_only = true;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserAttributeWidgetAssets::register($this->getView());
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

		$fieldsCount = count($userProperties);//В зависимости от количества СВОЙСТВ в атрибуте высчитываем подходящее количество колонок
		if (1 === $fieldsCount) {
			$mdClass = "col-md-12";
		} elseif (2 === $fieldsCount) {
			$mdClass = "col-md-6";
		} else $mdClass = "col-md-4";


		return $this->render('attribute', [
			'dynamicAttribute' => $attribute,
			'userProperties' => $userProperties,
			'mdClass' => $mdClass,
			'user_id' => $this->user_id,
			'read_only' => $this->read_only
		]);
	}
}