<?php
declare(strict_types = 1);

namespace app\widgets\admin_panel;

use app\models\core\WigetableController;
use yii\base\Widget;

/**
 * Class AdminPanelWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *AdminPanel* на нужное нам имя, и работаем
 * @package app\components\admin_panel
 *
 * @property integer $mode
 * @property string $controllers_directory
 */
class AdminPanelWidget extends Widget {
	public const MODE_PANEL = 0;//Представление в виде панельки с иконками
	public const MODE_MENU = 1;//Представление в виде дропдаун-меню
	public const MODE_LIST = 1;//Представление в виде списка без иконок

	public $mode;
	public $controllers_directory = '@app/controllers/admin/';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AdminPanelWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('admin_panel',[
			'controllers' => WigetableController::GetControllersList($this->controllers_directory)
		]);
	}
}
