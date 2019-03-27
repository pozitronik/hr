<?php
declare(strict_types = 1);

namespace app\modules\privileges\widgets\access_tree;

use kartik\base\InputWidget;
use yii\base\Widget;
use yii\data\ArrayDataProvider;

/**
 * Class AccessTreeWidget
 * @package app\modules\privileges\widgets\access_tree
 * @property array $tree
 */
class AccessTreeWidget extends InputWidget {
	public $model;
	public $tree; //Массив Модуль->Контроллеры->Экшены

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AccessTreeWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		foreach ($this->tree as $moduleId => $controllerMap) {
			$levelMap = new ArrayDataProvider(['allModels' => $controllerMap]);
		}

		return $this->render('access_tree', [
			'tree' => $this->tree
		]);
	}
}
