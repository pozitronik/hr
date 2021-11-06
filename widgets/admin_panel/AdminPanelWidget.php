<?php
declare(strict_types = 1);

namespace app\widgets\admin_panel;

use app\models\core\controllers\CoreController;
use app\components\pozitronik\cachedwidget\CachedWidget;
use app\components\pozitronik\helpers\ArrayHelper;
use app\models\core\controllers\WigetableController;
use Throwable;
use yii\base\InvalidConfigException;

/**
 * Class AdminPanelWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *AdminPanel* на нужное нам имя, и работаем
 * @package app\components\admin_panel
 *
 * @property integer $mode
 * @property string[]|string $controllers_directory
 */
class AdminPanelWidget extends CachedWidget {
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
	 * @throws Throwable
	 * @throws InvalidConfigException
	 *
	 * todo: сюда будут попадать только WigetableControllers. Имеет смысл переделать фильтр так, чтобы включались контроллеры с нужным флагом, либо искуственно добавлялись нужные
	 * "простые" контроллеры с пустыми/дефолтными параметрами
	 */
	public function run():string {
		if (is_array($this->controllers_directory)) {
			$controllersList = [[]];
			/** @noinspection ForeachSourceInspection */
			foreach ($this->controllers_directory as $directory) {
				$controllersList[] = CoreController::GetControllersList($directory, null, [WigetableController::class]);
			}
			$controllers = array_merge(...$controllersList);

		} else {
			$controllers = CoreController::GetControllersList($this->controllers_directory, null, [WigetableController::class]);
		}

		ArrayHelper::multisort($controllers, ['orderWeight']);
		return $this->render('admin_panel', [
			'controllers' => $controllers,
			'mode' => $this->mode
		]);
	}
}
