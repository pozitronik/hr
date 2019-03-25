<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */
declare(strict_types = 1);

namespace app\models\core\traits;

use app\modules\privileges\models\AccessMethods;
use app\modules\privileges\models\UserAccess;
use app\widgets\alert\AlertModel;
use Throwable;
use yii\base\Model;

/**
 * Код, включающий проверки доступов
 * Пока пишется здесь, потом должно быть перенесено/смонтировано в ActiveRecordExtended
 * Алсо, можно добавить проверки на canGetProperty/canSetProperty
 * Trait MethodsAccess
 * @package app\models\prototypes
 */
trait MethodsAccess {

	/**
	 * @param bool $insert
	 * @return bool
	 * @throws Throwable
	 */
	public function beforeSave($insert):bool {
		/** @var Model $this */
		if (parent::beforeSave($insert) && !UserAccess::canAccess($this, $insert?AccessMethods::create:AccessMethods::update)) {
			AlertModel::AccessNotify();
			return false;
		}
		return true;
	}

	/**
	 * @return bool
	 * @throws Throwable
	 */
	public function beforeDelete():bool {
		/** @var Model $this */
		if (parent::beforeDelete() && !UserAccess::canAccess($this, AccessMethods::delete)) {
			AlertModel::AccessNotify();
			return false;
		}
		return true;
	}

}