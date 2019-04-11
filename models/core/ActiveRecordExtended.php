<?php
declare(strict_types = 1);

namespace app\models\core;

use app\models\core\traits\ARExtended;
use app\modules\history\models\ActiveRecordLogger;
use app\modules\privileges\models\AccessMethods;
use app\modules\privileges\models\UserAccess;
use app\widgets\alert\AlertModel;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/** @noinspection UndetectableTableInspection */

/**
 * Class ActiveRecordExtended
 * @package app\models\core
 */
class ActiveRecordExtended extends ActiveRecord {
	use ARExtended;
	public $loggingEnabled = true;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		throw new InvalidConfigException('"'.static::class.'" нельзя вызывать напрямую.');
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * Описание правил отображения истории моделей.
	 * Пример
	 * [
	 *    'eventConfig' => [//конфигурация отображения событий
	 *        'eventLabels' => 'Джигурда',//Переопределение дефолтных заголовков одной строкой as is
	 *        'eventLabels' => [//переопределение дефолтных заголовков событий через массив вида [тип события => текст заголовка]
	 *            HistoryEventInterface::EVENT_CREATED => 'Добавление роли',
	 *            HistoryEventInterface::EVENT_CHANGED => 'Изменение роли',
	 *            HistoryEventInterface::EVENT_DELETED => 'Удаление роли'
	 *        ],
	 *        'eventLabels' => static function(int $eventType, string $default):string,//переопределение через замыкание, первым параметром - тип события, вторым - заголовок по умолчанию
	 *        'actionsFormatter' => static function(array $actions):string {},//переопределение дефолтного форматтера изменений через замыкание, в которое передаётся массив изменений HistoryEventAction[]
	 *        'actionsFormatter' => ['view', ['param'=>'value]],//переопределение дефолтного форматтера вьюхой (первое значение массива) в которую будут переданы указанные вторым значением массива параметры + параметр 'actions', содержащий массив изменений
	 *        'actionsFormatter' => 'Джигурда',//переопределение дефолтного форматтера строкой as is
	 *        'actionsFormatter' => false,//изменения события не будут показаны
	 *     ],
	 *     'attributes' => [//Конфигурация отображения атрибутов модели
	 *        'id' => null,//null (по умолчанию) - будет отображено значение атрибута из истории
	 *        'salt' => '----',//строка - будет отображена as is
	 *        'daddy' => [Users::class => 'username'],//имя атрибута модели => [КлассМодели => имя подставляемого атрибута]. В указанном классе будет найдена модель с primaryKey = значению атрибута из истории, и подставлено ТЕКУЩЕЕ значение подставляемого атрибута
	 *        'position' => static function(string $attributeName, $attributeValue) {},//замыкание, в которое передаются имя атрибута и его значение из истории, можно вернуть в произвольном формате
	 *        'password' => false//значение атрибута не будет показано
	 *     ],
	 *     'relations' => [//Конфигурация подключения и отображения связанных моделей. Модели, указанные в конфигурации, будут отдааны в историю запрашиваемого объекта, при условии обнаружения их в логе по указанным правилам
	 *        RelUsersGroups::class => ['id' => 'user_id'],//КлассМодели => [входящий ключ => исходящий ключ]. Включает историю КлассаМодели, у которой значение входящего ключа совпадает с со значением исходящего ключа (аналогично hasOne)
	 *        RelUsersGroupsRoles::class => function(ActiveQuery $condition, ActiveRecord $model):ActiveQuery {},//КлассМодели => Замыкание, в которое первым параметром передаётся текущий запрос в таблицу лога, вторым - инициализированный класс модели. Замыкание может произвольно модицифировать запрос и вернуть его для дальнейшего поиска
	 *     ]
	 * ]
	 * @return array
	 */
	public function historyRules():array {
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeSave($insert):bool {
		if (!UserAccess::canAccess($this, $insert?AccessMethods::create:AccessMethods::update)) {
			$this->refresh();
			$this->addError('id', 'Вам не разрешено производить данное действие.');
			AlertModel::AccessNotify();
			return false;
		}

		if (!$insert) ActiveRecordLogger::logChanges($this);//do not log inserts here => log into afterSave
		return parent::beforeSave($insert);
	}

	/**
	 * {@inheritDoc}
	 */
	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);
		if ($insert) ActiveRecordLogger::logModel($this);
	}

	/**
	 * @return bool
	 * @throws Throwable
	 */
	public function beforeDelete():bool {
		if (!UserAccess::canAccess($this, AccessMethods::delete)) {
			$this->addError('id', 'Вам не разрешено производить данное действие.');
			AlertModel::AccessNotify();
			return false;
		}
		ActiveRecordLogger::logDelete($this);
		return parent::beforeDelete();
	}

	/**
	 * Удаляет набор моделей по наборк первичных ключей
	 * @param array $primaryKeys
	 * @throws Throwable
	 * @throws StaleObjectException
	 */
	public static function deleteByKeys(array $primaryKeys):void {
		foreach ($primaryKeys as $primaryKey) {
			if (null !== $model = self::findModel($primaryKey)) {
				$model->delete();
			}
		}
	}

	/**
	 * Отличия от базового deleteAll(): работаем в цикле для корректного логирования + проверяем доступы
	 * @param null|mixed $condition
	 * @return int|null
	 * @throws Throwable
	 * todo: При изменении списка связей мы удаляем текущие связи, затем применяем новый список, это захламляет историю. Нужно сделать учёт диффа, т.о. мы совсем откажемся от deleteAllEx.
	 */
	public static function deleteAllEx($condition = null):?int {
		$self_class_name = static::class;
		/** @var static $self_class */
		$self_class = new $self_class_name();
		if (UserAccess::canAccess($self_class, AccessMethods::delete)) {
			$deletedModels = $self_class::findAll($condition);
			$dc = 0;
			/** @var static[] $deletedModels */
			foreach ($deletedModels as $deletedModel) {
				$dc += (int)$deletedModel->delete();
			}
			return $dc;
		}
		$self_class->addError('id', 'Вам не разрешено производить данное действие.');
		AlertModel::AccessNotify();
		return null;
	}

}