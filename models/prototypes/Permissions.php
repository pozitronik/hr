<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\models\groups\Groups;
use app\models\users\Users;
use yii\base\Model;

/**
 * Class Permissions
 * Определение доступов пользователя по отношению к группам пользователей.
 * Право на чтение группы (read): видит всех пользователей (без права изменения). Предполагается, что все видят всех, поэтому есть как есть. Возможно, потом сделаю разделение по владельцам, как в linux
 * (это не будет эффективно с точки зрения SQL, поэтому пока можно без - не делаю).
 * Право на изменение (write): может редактировать пользователей в группе. НЕ РАСПРОСТРАНЯЕТСЯ по дереву
 * Право на полное изменение (full-write): может редактировать пользователей в группе и всех входящих в неё (распространяется по дереву).
 * Право на управление группой (master): может менять положение группы в структуре и её атрибуты.
 *
 * @property-write Users $user (проверяемый пользователь).
 */
class Permissions extends Model {
	private $user;

	/**
	 * @param Groups $group
	 * @return bool
	 */
	public function isGroupWritable(Groups $group):bool {
		return $group->deleted;
	}

	/**
	 * @param Users $user
	 * @return bool
	 */
	public function isUserWritable(Users $user):bool {
		return $user->deleted;
	}

	/**
	 * @param Groups $group
	 * @return bool
	 */
	public function isGroupSubmitted(Groups $group):bool {
		return $group->deleted;
	}

	/**
	 * @param Users $user
	 */
	public function setUser(Users $user):void {
		$this->user = $user;
	}
}