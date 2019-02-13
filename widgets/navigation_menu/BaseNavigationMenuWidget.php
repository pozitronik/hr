<?php
declare(strict_types = 1);

namespace app\widgets\navigation_menu;

use ReflectionClass;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Class NavigationMenuWidget
 * @property ActiveRecord $model
 * @property int $mode
 */
class BaseNavigationMenuWidget extends Widget {
	public const MODE_MENU = 0;
	public const MODE_TABS = 1;

	public $model;
	public $mode = self::MODE_TABS;

	protected $_navigationItems = [];

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		BaseNavigationMenuWidgetAssets::register($this->getView());
	}

	/**
	 * {@inheritDoc}
	 * Перекрываем getViewPath, чтобы путь к вьюхам возвращался для ЭТОГО виджета, а не для наследующей модели
	 */
	public function getViewPath() {
		$class = new ReflectionClass(self::class);
		return dirname($class->getFileName()).DIRECTORY_SEPARATOR.'views';
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		if ($this->model->isNewRecord) return '';
		switch ($this->mode) {
			case self::MODE_MENU:
				return $this->render('navigation_menu', [
					'items' => $this->_navigationItems
				]);
			break;
			default:
			case self::MODE_TABS:
				return $this->render('navigation_tabs', [
					'items' => $this->_navigationItems
				]);
			break;
		}

	}
}
