<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline;

use Throwable;
use yii\web\NotFoundHttpException;

/**
 * Class ImportBeelineDecomposed
 */
class ImportBeelineDecomposed extends active_record\ImportBeelineDecomposed {

	public const STEP_GROUPS = 0;
	public const STEP_USERS = 1;
	public const STEP_LINKING_USERS = 2;
	public const STEP_LINKING_GROUPS = 3;
	public const LAST_STEP = self::STEP_LINKING_GROUPS + 1;
	public const step_labels = [
		self::STEP_GROUPS => 'Импорт декомпозированных групп',
		self::STEP_USERS => 'Импорт декомпозированных пользователей',
		self::STEP_LINKING_USERS => 'Добавление пользователей в группы',
		self::STEP_LINKING_GROUPS => 'Построение иерархии групп',
		self::LAST_STEP => 'Готово!'
	];

	/**
	 * Разбираем декомпозированные данные и вносим в боевую таблицу
	 * @param int $step
	 * @param array $errors -- прокидывание ошибок
	 * @return bool true - шаг выполнен, false - нужно повторить запрос (шаг разбит на подшаги)
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public static function Import(int $step = self::STEP_GROUPS, array &$errors = []):bool {
		/*Идём по таблицам декомпозиции, добавляя данные из них в соответствующие таблицы структуры*/
		switch ($step) {
			case self::STEP_GROUPS:/*Группы. Добавляем группу и её тип*/
				return self::DoStepGroups();
			case self::STEP_USERS:
				return self::DoStepUsers($errors);
			case self::STEP_LINKING_USERS:
				return self::DoStepLinkingUsers();
			case self::STEP_LINKING_GROUPS:
				return self::DoStepLinkingGroups();
		}
		throw new NotFoundHttpException('Step not found');

	}

	private static function DoStepGroups():bool {
	}

	private static function DoStepUsers(array &$errors):bool {
	}

	private static function DoStepLinkingUsers():bool {
	}

	private static function DoStepLinkingGroups():bool {
	}

}