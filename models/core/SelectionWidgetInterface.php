<?php
declare(strict_types = 1);

namespace app\models\core;

/**
 * Интерфейс виджетов-выбиралок (несущественный, просто вынос констант. Вохможно, будет дополнен методами геттерами-сеттерами атрибутов
 * Interface SelectionWidget
 * @package app\models\core
 */
interface SelectionWidgetInterface {
	public const MODE_FIELD = 0;//рендеримся, как поле внешней формы
	public const MODE_FORM = 1;//рендеримся, как самостоятельная форма
	public const MODE_AJAX = 2;//ренедеримся, как есть, с постингом через AJAX


	public const DATA_MODE_LOAD = 1;//данные прогружаются сразу
	public const DATA_MODE_AJAX = 1;//данные прогружаются аяксовым поиском
}