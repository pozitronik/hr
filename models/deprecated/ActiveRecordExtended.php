<?php /** @noinspection UndetectableTableInspection */
declare(strict_types = 1);

namespace app\models\core\deprecated;

use app\models\core\traits\ARExtended;
use app\modules\privileges\models\AccessMethods;
use app\modules\privileges\models\UserAccess;
use app\widgets\alert\AlertModel;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordExtended
 * @package app\models\core
 * @deprecated -- в классе оставлены только методы, завязанные на проверку доступов и нотификации. Это нужно переделывать на на behaviors {todo}
 */
class ActiveRecordExtended extends ActiveRecord {
	use ARExtended;

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
	 * {@inheritDoc}
	 */
	public function beforeSave($insert):bool {
		if (!UserAccess::canAccess($this, $insert?AccessMethods::create:AccessMethods::update)) {
			$this->refresh();
			$this->addError('id', 'Вам не разрешено производить данное действие.');
			AlertModel::AccessNotify();
			return false;
		}

		return parent::beforeSave($insert);
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
		return parent::beforeDelete();
	}

	/**
	 * Отличия от базового deleteAll(): проверка доступов и вызов родительского метода, всё.
	 * @param null|mixed $condition
	 * @return int|null
	 * @throws Throwable
	 */
	public static function deleteAllEx($condition = null):?int {
		$self_class_name = static::class;
		/** @var static $self_class */
		$self_class = new $self_class_name();
		if (!UserAccess::canAccess($self_class, AccessMethods::delete)) {
			$self_class->addError('id', 'Вам не разрешено производить данное действие.');
			AlertModel::AccessNotify();
			return null;
		}
		return parent::deleteAllEx($condition);
	}

}