<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\structure;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *Structure* на нужное нам имя, и работаем
 * @package app\components\structure
 */
class StructureWidget extends Widget {
	public const MODE_GRAPH = 0;
	public const MODE_TREE = 1;

	public $id;
	public $mode = self::MODE_GRAPH;

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		switch ($this->mode) {
			case self::MODE_GRAPH:
				StructureWidgetAssets::register($this->getView());
				return $this->render('structure', [
					'id' => $this->id
				]);
			break;
			default:
			case self::MODE_TREE:
				VisjsAsset::register($this->getView());
				return $this->render('tree', [
					'id' => $this->id
				]);
			break;
		}

	}
}
