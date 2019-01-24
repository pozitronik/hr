<?php
declare(strict_types = 1);

namespace app\widgets\controller;

use app\models\core\Magic;
use app\models\core\WigetableController;
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
	 * @throws \ReflectionException
	 */
	public function run():string {

		$action = ["/{$this->model->route}/{$this->model->defaultAction}"];
		$caption = Magic::hasProperty($this->model, 'menuCaption')?$this->model->menuCaption:$this->model->id;
		switch ($this->mode) {
			case self::MODE_PANEL:
				return $this->render('controller_panel', [
					'style' => Magic::hasProperty($this->model, 'menuIcon')?"style = 'background-image: url({$this->model->menuIcon});'":'',
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
