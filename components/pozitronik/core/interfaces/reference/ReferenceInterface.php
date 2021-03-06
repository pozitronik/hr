<?php
declare(strict_types = 1);

namespace app\components\pozitronik\core\interfaces\reference;

use yii\db\ActiveQuery;

/**
 * Интерфейс справочника
 * @package app\models\references
 * @property integer id
 * @property string name
 * @property boolean deleted
 * @property-read string $ref_name
 * @property-read array $columns
 * @property-read array $view_columns
 * @property-read string $form
 * @property-read string $title
 * @property-read integer $usedCount
 * @property-read array $searchSort
 */
interface ReferenceInterface {//todo: добавить миграцию на индексы color/textcolor всем справочникам

	/**
	 * Справочникам всегда нужно возвращать массив значений для выбиралок, вот эта функция у них универсальная
	 * @param boolean $sort Сортировка выдачи
	 * @return array
	 */
	public static function mapData(bool $sort = false):array;

	/**
	 * Набор колонок для отображения на главной
	 * @return array
	 */
	public function getColumns():array;

	/**
	 * Набор колонок для отображения на странице просмотра
	 * @return array
	 */
	public function getView_columns():array;

	/**
	 * Если в справочнике требуется редактировать поля, кроме обязательных, то функция возвращает путь к встраиваемой вьюхе, иначе к дефолтной
	 * @return string|false
	 */
	public function getForm():string;

	/**
	 * Возвращает id раширения, добавившего справочник (null, если справочник базовый)
	 * @return string|null
	 */
	public function getPluginId():?string;

	/**
	 * @param string|null $pluginId
	 */
	public function setPluginId(?string $pluginId):void;

	/**
	 * Поиск по справочнику
	 * @param array $params
	 * @return ActiveQuery
	 */
	public function search(array $params):ActiveQuery;

	/**
	 * @return array
	 */
	public function getSearchSort():?array;

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 */
	public static function merge(int $fromId, int $toId):void;

	/**
	 * Сбрасывает все кеши для этого справочника.
	 * Названия кешей перечислены в дефолтной реализации
	 */
	public static function flushCache():void;

	/**
	 * Количество объектов, использующих это значение справочника
	 * @return int
	 */
	public function getUsedCount():int;

	/**
	 * Возвращает набор параметров в виде data-опций, которые виджет выбиралки присунет в селект.
	 * Рекомендуемый способ получения опций через аякс не менее геморроен, но ещё и не работает
	 * @return array
	 */
	public static function dataOptions():array;
}
