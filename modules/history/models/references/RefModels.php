<?php
declare(strict_types = 1);

namespace app\modules\history\models\references;

use app\components\pozitronik\core\interfaces\reference\ReferenceInterface;
use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\history\models\ActiveRecordLogger;
use yii\base\Model;
use yii\db\ActiveQuery;

/**
 * Интерфейс к списку моделей истории
 * Class RefModels
 * @package app\modules\history\models\references
 *
 * @property null|string $pluginId
 */
class RefModels extends Model implements ReferenceInterface {
	public $menuCaption = "Объекты истории";
	public $menuIcon = "/img/admin/references.png";
	/*	Массив, перечисляющий имена атрибутов, которые должны отдаваться в dataOptions
		Имя может быть строковое (если название атрибута совпадает с именем data-атрибута, либо массивом
		формата ['имя data-атрибута' => 'атрибут модели']
	*/
	protected $_dataAttributes = [];
	protected $_pluginId;

	/**
	 * Справочникам всегда нужно возвращать массив значений для выбиралок, вот эта функция у них универсальная
	 * @param boolean $sort Сортировка выдачи
	 * @return array
	 */
	public static function mapData(bool $sort = false):array {
		$allModels = ActiveRecordLogger::find()->distinct()->select('model')->asArray()->all();
		$data = ArrayHelper::map($allModels, 'model', 'model');
		if ($sort) {
			asort($data);
		}
		return $data;
	}

	/**
	 * Набор колонок для отображения на главной
	 * @return array
	 */
	public function getColumns():array {
		return [];//Виртуальный справочник
	}

	/**
	 * Набор колонок для отображения на странице просмотра
	 * @return array
	 */
	public function getView_columns():array {
		return [];//Виртуальный справочник
	}

	/**
	 * Если в справочнике требуется редактировать поля, кроме обязательных, то функция возвращает путь к встраиваемой вьюхе, иначе к дефолтной
	 * @return string|false
	 */
	public function getForm():string {
		return '';
	}

	/**
	 * Возвращает id раширения, добавившего справочник (null, если справочник базовый)
	 * @return string|null
	 */
	public function getPluginId():?string {
		return null;//справочник не базовый, но поддержка не нужна
	}

	/**
	 * @param string|null $pluginId
	 */
	public function setPluginId(?string $pluginId):void {
	}

	/**
	 * Поиск по справочнику
	 * @param array $params
	 * @return ActiveQuery
	 */
	public function search(array $params):ActiveQuery {
		return null;
	}

	/**
	 * @return array|false
	 */
	public function getSearchSort():?array {
		return null;
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 */
	public static function merge(int $fromId, int $toId):void {
	}

	/**
	 * Сбрасывает все кеши для этого справочника.
	 * Названия кешей перечислены в дефолтной реализации
	 */
	public static function flushCache():void {
	}

	/**
	 * Количество объектов, использующих это значение справочника
	 * @return int
	 */
	public function getUsedCount():int {
		return 0;//Виртуальный справочник
	}

	/**
	 * Возвращает набор параметров в виде data-опций, которые виджет выбиралки присунет в селект.
	 * Рекомендуемый способ получения опций через аякс не менее геморроен, но ещё и не работает
	 * @return array
	 */
	public static function dataOptions():array {
		return [];//Виртуальный справочник
	}
}