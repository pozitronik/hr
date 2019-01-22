<?php
declare(strict_types = 1);

namespace app\models\user_rights;

use app\models\core\Magic;
use app\models\groups\Groups;
use app\models\user\CurrentUser;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\web\Controller;

/**
 * Class UserAccess
 * @package app\models\user_rights
 */
class UserAccess extends Model {

	/**
	 * Формирует массив правил доступа к контроллерам и экшенам (с учётом параметров), применимый в правилах AccessControl
	 * @param Controller $controller
	 * @param array|null $actionParameters
	 * @param bool $defaultAllow
	 * @return array
	 * @throws ReflectionException
	 * @throws Throwable
	 */
	public static function getUserAccessRules(Controller $controller, ?array $actionParameters = null, bool $defaultAllow = false):array {
		if (null === $user = CurrentUser::User()) return [];
		$rights = $user->rights;//Все права, присвоенные пользователю
		$rules = [];
		if ($user->is('sysadmin')) $defaultAllow = true;
		$actions = Magic::GetControllerActions($controller);

		foreach ($actions as $action) {//Пытаемся подобрать правила для всех экшенов в контроллере
			$ruleDefined = false;//Флаг устанавливается, если определение правила найдено
			$action = Magic::GetActionRequestName($action);
			foreach ($rights as $right) {//перебираем все права, пока не найдём право, определяющее доступ (или не переберём все права; в этом случае присвоим доступ по умолчанию)
				//функция не учитывает коллизии прав (одно разрешает, другое запрещает). Буду дорабатывать с тем, чтобы создать метод получающий список определений прав, на основе которого уже будут высчитываться суммарные правила и коллизии
				//Пофиг на коллизии, будем определять очерёдность применения прав по порядку, определённому в наборе прав
				if (null === $access = $right->getAccess($controller, $action, $actionParameters??Yii::$app->request->get())) continue;
				$rules[] = [
					'actions' => [$action],
					'allow' => $access,
					'roles' => ['@']
				];
				$ruleDefined = true;
				break 1;

			}
			if (!$ruleDefined) {//Ни одно право не определило правило доступа
				$rules[] = [
					'actions' => [$action],
					'allow' => $defaultAllow,
					'roles' => ['@']
				];
			}
		}
		return $rules;
	}

	/**
	 * Вычисляет, имется ли у текущего пользователя доступ к выполнению метода у модели
	 * @param Model $model
	 * @param null|int $method
	 * @param array|null $actionParameters
	 * @param bool $defaultAllow
	 * @return bool
	 * @throws Throwable
	 */
	public static function canAccess(Model $model, ?int $method = AccessMethods::any, ?array $actionParameters = null, bool $defaultAllow = false):bool {
		if (null === $user = CurrentUser::User()) return false;
		$rights = $user->rights;//Все права, присвоенные пользователю
		if ($user->is('sysadmin')) $defaultAllow = true;
		foreach ($rights as $right) {//перебираем все права, пока не найдём право, определяющее доступ (или не переберём все права; в этом случае присвоим доступ по умолчанию)
			if (null !== $access = $right->canAccess($model, $method, $actionParameters??Yii::$app->request->get())) {
				return $access;
			}

		}
		return $defaultAllow;
	}

	/**
	 * @return ActiveQuery
	 */
	public static function GetGroupsScope():ActiveQuery {
//		$query =;
//		Example::SetGroupsScope($query);
		return  Groups::find();
	}

	/**
	 * @param int $flag
	 * @param bool $defaultAllow
	 * @return bool
	 * @throws Throwable
	 */
	public static function GetFlag(int $flag, bool $defaultAllow = false):bool {
		if (null === $user = CurrentUser::User()) return false;
		$rights = $user->rights;//Все права, присвоенные пользователю
		if ($user->is('sysadmin')) $defaultAllow = true;
		foreach ($rights as $right) {//перебираем все права, пока не найдём право, определяющее доступ (или не переберём все права; в этом случае присвоим доступ по умолчанию)
			if (null !== $access = $right->getFlag($flag)) {
				return $access;
			}

		}
		return $defaultAllow;
	}

}