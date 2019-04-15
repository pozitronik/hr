<?php
declare(strict_types = 1);

namespace app\widgets\controller;

use app\helpers\ArrayHelper;
use app\models\core\helpers\ReflectionHelper;
use app\models\core\WigetableController;
use ReflectionException;
use yii\base\Widget;

/**
 * Class ControllerWidget
 * Отображение WigetableController в виде виджетов/меню
 * @package app\components\controller
 *
 * @property integer $mode
 * @property WigetableController $model
 */
class ControllerWidget extends Widget {
	public $model;
	public $mode;

	public const MODE_PANEL = 0;//Представление в виде панельки с иконками
	public const MODE_MENU = 1;//Представление в виде дропдаун-меню
	public const MODE_LIST = 2;//Представление в виде списка без иконок

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		ControllerWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws ReflectionException
	 */
	public function run():string {
		$action = ["/{$this->model->defaultRoute}/{$this->model->defaultAction}"];
		$caption = ArrayHelper::getValue($this->model, 'menuCaption', $this->model->id);
		$iconStyle = (null === $icon = ArrayHelper::getValue($this->model, 'menuIcon'))?'':"style = 'background-image: url({$icon});'";
		switch ($this->mode) {
			case self::MODE_PANEL:
				return $this->render('controller_panel', [
					'style' => $iconStyle,
					'action' => $action,
					'caption' => $caption
				]);
			break;
			case self::MODE_MENU:
				//not implemented yet
				return 'mode not implemented yet';
			break;
			default:
			case self::MODE_LIST:
				return $this->render('controller_list', compact('action', 'caption'));
			break;
		}

	}
}
