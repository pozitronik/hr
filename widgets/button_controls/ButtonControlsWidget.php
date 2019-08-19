<?php
declare(strict_types = 1);

namespace app\widgets\button_controls;

use pozitronik\widgets\CachedWidget;

/**
 * Class ButtonControlsWidget
 * @package app\widgets\button_controls
 * @property bool $radioMode -- если установлено, используем радиокнопки (только одна может быть нажата)
 * @property string $name -- имя блока
 * @property string|array|null $selection -- the selected value(s). String for single or array for multiple selection(s).
 * @property array $options - массив html-опций контейнера
 * @property array $items - массив кнопок, описываемых в формате:
 *    'value' => значение кнопки,
 *    'label' => подпись кнопки,
 *    'options' => массив html-опций для кнопки
 */
class ButtonControlsWidget extends CachedWidget {
	public $radioMode = false;
	public $name;
	public $selection;
	public $options = ['class' => 'button-group'];
	public $items = [];

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		ButtonControlsWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render($this->radioMode?'radio':'checkbox', [
			'name' => $this->name,
			'selection' => $this->selection,
			'options' => $this->options,
			'items' => $this->items
		]);

	}
}
