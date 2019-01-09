<?php
declare(strict_types = 1);

namespace app\models\user_rights;

use app\models\core\Magic;
use app\models\user\CurrentUser;
use ReflectionException;
use Throwable;
use yii\base\Model;
use yii\web\Controller;

/**
 * Class UserAccess
 * @package app\models\user_rights
 */
class UserAccess extends Model {

	/**
	 * @param Controller $controller
	 * @param bool $defaultAllow
	 * @return array
	 * @throws ReflectionException
	 * @throws Throwable
	 */
	public static function getUserAccessRules(Controller $controller, bool $defaultAllow = true):array {
		$user = CurrentUser::User();
		$rights = $user->rights;//Все права, присвоенные пользователю
		$rules = [];
		$actions = Magic::GetControllerActions($controller);

		foreach ($actions as $action) {//Пытаемся подобрать правила для всех экшенов в контроллере
			$ruleDefined = false;//Флаг устанавливается, если определение правила найдено
			$action = Magic::GetActionRequestName($action);
			$controllerName = basename(get_class($controller));
			foreach ($rights as $right) {//перебираем все права, пока не найдём право, определяющее доступ (или не переберём все права; в этом случае присвоим доступ по умолчанию)
				//функция не учитывает коллизии прав (одно разрешает, другое запрещает). Буду дорабатывать с тем, чтобы создать метод получающий список определений прав, на основе которого уже будут высчитываться суммарные правила и коллизии

				if (null === $access = $right->getAccess($controllerName, $action)) continue;
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

}