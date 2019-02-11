<?php
declare(strict_types = 1);

namespace app\models\core;

/**
 * Интерфейс виджетов-выбиралок (несущественный, просто вынос констант. Вохможно, будет дополнен методами геттерами-сеттерами атрибутов
 * Interface SelectionWidget
 * @package app\models\core
 */
interface SelectionWidgetInterface {
	public const MODE_FIELD = 0;
	public const MODE_FORM = 1;
	public const MODE_AJAX = 2;
}