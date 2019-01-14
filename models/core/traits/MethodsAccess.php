<?php
declare(strict_types = 1);

namespace app\models\core\traits;

use app\models\user_rights\AccessMethods;
use app\models\user_rights\UserAccess;
use app\widgets\alert\AlertModel;
use Throwable;
use yii\base\Model;

/**
 * Код, включающий проверки доступов
 * Алсо, можно добавить проверки на canGetProperty/canSetProperty
 * Trait MethodsAccess
 * @package app\models\prototypes
 */
trait MethodsAccess {

	/**
	 * @param bool $insert
	 * @return bool
	 * @throws Throwable
	 * @todo: parent::beforeSave
	 */
	public function beforeSave($insert) {
		/** @var Model $this */
		if (!UserAccess::canAccess($this, $insert?AccessMethods::create:AccessMethods::update)) {
			AlertModel::AccessNotify();
			return false;
		}
		return true;
	}

	/**
	 * @return bool
	 */
	public function beforeDelete() {
		/** @var Model $this */
		if (!UserAccess::canAccess($this, AccessMethods::delete)) {
			AlertModel::AccessNotify();
			return false;
		}
		return true;
	}

}