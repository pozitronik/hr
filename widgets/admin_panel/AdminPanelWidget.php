<?php
declare(strict_types = 1);

namespace app\widgets\admin_panel;

use app\helpers\ArrayHelper;
use app\models\core\WigetableController;
use ReflectionException;
use yii\base\UnknownClassException;
use yii\base\Widget;

/**
 * Class AdminPanelWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *AdminPanel* на нужное нам имя, и работаем
 * @package app\components\admin_panel
 *
 * @property integer $mode
 * @property string[]|string $controllers_directory
 */
class AdminPanelWidget extends Widget {
	public const MODE_PANEL = 0;//Представление в виде панельки с иконками
	public const MODE_MENU = 1;//Представление в виде дропдаун-меню
	public const MODE_LIST = 2;//Представление в виде списка без иконок

	public const DEFAULT_DIRECTORY = '@app/controllers/admin/';

	public $mode;
	public $controllers_directory = self::DEFAULT_DIRECTORY;

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
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public function run():string {
		if (is_array($this->controllers_directory)) {
			$controllersList = [[]];
			/** @noinspection ForeachSourceInspection */
			foreach ($this->controllers_directory as $directory) {
				$controllersList[] = WigetableController::GetControllersList($directory);
			}
			$controllers = array_merge(...$controllersList);

		} else {
			$controllers = WigetableController::GetControllersList($this->controllers_directory);
		}
		ArrayHelper::multisort($controllers, ['orderWeight']);
		return $this->render('admin_panel', [
			'controllers' => $controllers,
			'mode' => $this->mode
		]);
	}
}
