<?php /** @noinspection UndetectableTableInspection */
declare(strict_types = 1);

namespace app\modules\history\models;

use Throwable;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordHistory
 * @package app\modules\history\models
 * @todo: переписать на behaviors
 * @deprecated
 */
class ActiveRecordHistory extends ActiveRecord {
	public $loggingEnabled = true;

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
	 *     'relations' => [//Конфигурация подключения и отображения связанных моделей. Модели, указанные в конфигурации, будут отданы в историю запрашиваемого объекта, при условии обнаружения их в логе по указанным правилам
	 *        RelUsersGroups::class => ['id' => 'user_id'],//КлассМодели => [входящий ключ => исходящий ключ]. Включает историю КлассаМодели, у которой значение входящего ключа совпадает с со значением исходящего ключа (аналогично hasOne)
	 *        RelUsersGroupsRoles::class => function(ActiveQuery $condition, ActiveRecord $model):ActiveQuery {},//КлассМодели => Замыкание, в которое первым параметром передаётся текущий запрос в таблицу лога, вторым - инициализированный класс модели. Замыкание может произвольно модифицировать запрос и вернуть его для дальнейшего поиска
	 *     ],
	 *     'events' => [//Конфигурация переопределения типов событий, основанная на отслеживании изменений атрибутов (если не определено, тип события будет определён по изменению атрибутов)
	 *        HistoryEventInterface::EVENT_DELETED => [//Тип определяемого события
	 *            'deleted' => [//проверяемый атрибут
	 *                'from' => false,//прежнее значение (если не задано или null - то любое)
	 *                'to' => true//новое значение (если не задано или null - то любое)
	 *          ],
	 *            'deleted' => true//второй вариант определения, проверяется не изменение атрибута, а его состояние после изменения
	 *        ]
	 *
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
		ActiveRecordLogger::logDelete($this);
		return parent::beforeDelete();
	}

	/**
	 * Отличия от базового deleteAll(): работаем в цикле для корректного логирования (через декомпозицию)
	 * @param mixed|null $condition
	 * @return int|null
	 * @throws Throwable
	 */
	public static function deleteAllEx(mixed $condition = null):?int {
		/** @noinspection PhpDeprecationInspection */
		$self_class_name = static::class;
		/** @var static $self_class */
		$self_class = new $self_class_name();
		$deletedModels = $self_class::findAll($condition);
		$dc = 0;
		/** @var static[] $deletedModels */
		foreach ($deletedModels as $deletedModel) {
			$dc += (int)$deletedModel->delete();
		}
		return $dc;
	}

}