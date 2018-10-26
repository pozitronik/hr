<?php
declare(strict_types = 1);

namespace app\helpers;

/**
 * Class Csv
 * @package app\helpers
 */
class Csv {
	/**
	 * Преобразование CSV строк в массив
	 * @param string $file Полный путь к файлу
	 * @param string $delimiter Разделитель строк
	 * @return array
	 */
	public static function csvToArray($file, $delimiter = ';'):array {
		$csvArray = [];

		if (false !== ($handle = fopen($file, 'rb'))) {
			while (false !== ($csvData = fgetcsv($handle, 0, $delimiter))) {
				$csvArray[] = $csvData;
			}
			fclose($handle);
		}

		return $csvArray;
	}

	/**
	 * Преобразование массива в CSV
	 * @param array $array Исходный массив
	 * @param string $delimiter разделитель строк
	 * @return string CSV contents
	 */
	public static function arrayToCsv($array, $delimiter = ';'):string {
		$file = fopen('php://temp/maxmemory:'.(5 * 1024 * 1024), 'wb');
		foreach ($array as $value) fputcsv($file, $value, $delimiter);

		rewind($file);

		return stream_get_contents($file);
	}

	/**
	 * @param array $array
	 * @param string|null $fileName Полный путь к файлу, если не задан - создастся временный файл
	 * @param string $delimiter
	 * @return bool|string CSV filename
	 */
	public static function arrayToCsvFile($array, $fileName = null, $delimiter = ';') {
		$_fileName = $fileName??tempnam(sys_get_temp_dir(), 'csv');
		if (!$_fileName) return false;
		$file = fopen($_fileName, 'wb');

		foreach ($array as $value) fputcsv($file, $value, $delimiter);

		fclose($file);

		return $_fileName;
	}
}
