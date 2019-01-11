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
	 * @param string $path
	 * @param int $mode
	 * @return bool
	 */
	public static function CreateDirIfNotExisted(string $path, int $mode = 0777):bool {
		if (file_exists($path)) {
			if (is_dir($path)) return true;
			throw new RuntimeException(sprintf('Имя "%s" занято', $path));
		}
		if (!mkdir($path, $mode) && !is_dir($path)) {
			throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
		}
		return true;
	}

	/**
	 * @param string $filename
	 * @param string $new_extension
	 * @return string
	 */
	public static function ChangeFileExtension(string $filename, string $new_extension = ''):string {
		return pathinfo($filename, PATHINFO_FILENAME).$new_extension;
	}
}