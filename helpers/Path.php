<?php
declare(strict_types = 1);

namespace app\helpers;

use RuntimeException;

/**
 * Class Path
 * Хелпер для работы с путями и файловыми объектами
 * @package app\helpers
 */
class Path {

	/**
	 * Создаём каталог с нужными проверками
	 * @param $path
	 * @param int $mode
	 * @return bool
	 */
	public static function CreateDirIfNotExisted($path, $mode = 0777):bool {
		if (file_exists($path)) {
			if (is_dir($path)) return true;
			throw new RuntimeException(sprintf('Имя "%s" занято', $path));
		}
		if (!mkdir($path, $mode) && !is_dir($path)) {
			throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
		}
		return true;
	}
}