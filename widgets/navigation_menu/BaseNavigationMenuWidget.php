<?php
declare(strict_types = 1);

namespace app\widgets\navigation_menu;

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use kartik\grid\ActionColumn;
use ReflectionClass;
use yii\base\Model;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class NavigationMenuWidget
 * @property ActiveRecord $model
 * @property int $mode
 */
class BaseNavigationMenuWidget extends Widget {
	public const MODE_MENU = 0;
	public const MODE_TABS = 1;
	public const MODE_BOTH = 2;//Будут отрендерены вкладки, элементы, помеченные, как menu=>true будут отрендерены в меню
	public const MODE_ACTION_COLUMN_MENU = 3;//Меню в колонке GridView

	public $model;
	public $mode = self::MODE_BOTH;

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
	public function getViewPath():string {
		$class = new ReflectionClass(self::class);
		return dirname($class->getFileName()).DIRECTORY_SEPARATOR.'views';
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string|array
	 */
	public function run():string {
		if ($this->model->isNewRecord) return '';
		switch ($this->mode) {
			case self::MODE_MENU:
				return $this->render('navigation_menu', [
					'items' => $this->_navigationItems
				]);
			break;
			case self::MODE_TABS:
				return $this->render('navigation_tabs', [
					'items' => $this->_navigationItems
				]);
			break;
			default:
			case self::MODE_BOTH:
				$menuItems = array_filter($this->_navigationItems, function($element) {
					return true === ArrayHelper::getValue($element, 'menu');
				});
				$tabItems = array_diff_key($this->_navigationItems, $menuItems);

				return (([] === $tabItems)?'':$this->render('navigation_tabs', [
						'items' => $tabItems
					])).(([] === $menuItems)?'':$this->render('navigation_menu', [
						'items' => $menuItems
					]));
			break;
			case self::MODE_ACTION_COLUMN_MENU:
				return $this->render('navigation_column_menu', [
					'items' => $this->_navigationItems
				]);
			break;
		}

	}
}