<?php
declare(strict_types = 1);

namespace app\models\references;

use yii\db\ActiveQuery;

/**
 * Интерфейс справочника
 * @package app\models\references
 * @property integer id
 * @property string name
 * @property boolean deleted
 *
 * @property-read string $ref_name
 * @property-read array $columns
 * @property-read array $view_columns
 * @property-read string|false $form
 * @property-read string $title
 */
interface ReferenceInterface {
	/**
	 * Справочникам всегда нужно возвращать массив значений для выбиралок, вот эта функция у них универсальная
	 * @param boolean $sort Сортировка выдачи
	 * @return array
	 */
	public static function mapData($sort = false);

	/**
	 * Строковое название справочника
	 * @return string
	 */
	public function getRef_name();

	/**
	 * Набор колонок для отображения на главной
	 * @return array
	 */
	public function getColumns();
	
	/**
	 * Набор колонок для отображения на странице просмотра
	 * @return array
	 */
	public function getView_columns();

	/**
	 * Если в справочнике требуется редактировать поля, кроме обязательных, то функция возвращает путь к встраиваемой вьюхе, иначе к дефолтной
	 * @return string|false
	 */
	public function getForm();
	
	/**
	 * Заголовок формы. По умолчанию принимает значение поля "name"
	 * @return string
	 */
	public function getTitle();

	/**
	 * Поиск по справочнику
	 * @param array $params
	 * @return ActiveQuery
	 */
	public function search($params);
}
