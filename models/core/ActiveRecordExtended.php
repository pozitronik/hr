<?php
declare(strict_types = 1);

namespace app\models\core;

use app\models\core\traits\ARExtended;
use app\modules\privileges\models\AccessMethods;
use app\modules\privileges\models\UserAccess;
use app\widgets\alert\AlertModel;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

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
	 * Описание связи между историей изменения моделей
	 *    'RelUsersGroups' => [
	 *        'model' => RelUsersGroups::class,//Имя связанной модели в таблице
	 *        'link' => ['id' => 'user_id'],//Схема связи между таблицами
	 *        'substitutions' => [//таблица является связующей, задаём к чему и как она связует.
	 *            [
	 *                'model' => Groups::class,//Имя связуемой таблицы
	 *                'link' => ['id' => 'group_id'],//правило связывания (входящий атрибут => исходящий атрибут, как в hasOne)
	 *                'substitute' => ['group_id' => 'name']//какой атрибут каким заменяем
	 *            ],
	 *            [
	 *                'model' => Users::class,
	 *                'link' => ['id' => 'user_id'],
	 *                'substitute' => ['user_id' => 'username']
	 *            ]
	 *        ]
	 *    ]
	 * @return array
	 */
	public function historyRelations():array {
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
	 * Поскольку базовый deleteAll не триггерит beforeDelete, перекрываем и триггерим сами
	 * Важно: изменение через deleteAll не может быть логировано корректно, потому будем логировать некорректно
	 * {@inheritDoc}
	 */
	public static function deleteAll($condition = null, $params = []):?int {
		$self_class_name = static::class;
		$self_class = new $self_class_name();

		if (UserAccess::canAccess($self_class, AccessMethods::delete)) {
			ActiveRecordLogger::logDeleteAll($self_class, $condition);
			return parent::deleteAll($condition, $params);
		}

		$self_class->addError('id', 'Вам не разрешено производить данное действие.');
		AlertModel::AccessNotify();
		return null;

	}

}