<?php
declare(strict_types = 1);

namespace app\helpers;

use Exception;
use Yii;
use Throwable;

/**
 * Class Utils
 * @package app\helpers
 */
class Utils {

	public const AS_IS = 0;
	public const PRINT_R = 1;
	public const VAR_DUMP = 2;

	/**
	 * Возвращает единообразно кодированное имя файла (применяется во всех загрузках)
	 * @param string $filename - имя файла
	 * @return string
	 */
	public static function CypherFileName($filename):string {
		$name = pathinfo($filename, PATHINFO_FILENAME);
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		return (trim(str_replace('#', '-', $name)).'_'.md5($name.microtime()).'.'.$ext);
	}

	/**
	 * @param $some
	 * @param string $title
	 */
	public static function log($some, $title = ''):void {

		print "<pre>$title\n";
		if (is_bool($some)) {
			/** @noinspection ForgottenDebugOutputInspection */
			var_dump($some);
		} else {
			/** @noinspection ForgottenDebugOutputInspection */
			print_r($some);
		}
		print "</pre>";
	}

	/**
	 * @param $data - данные для логирования
	 * @param bool|false|string $title - заголовок логируемых данных
	 * @param string $logName - файл вывода
	 * @param integer $format - формат вывода данных
	 * @return string $string - возвращаем текстом всё, что налогировали
	 */
	public static function fileLog($data, $title = false, $logName = 'debug.log', $format = self::PRINT_R):string {
		$return_contents = '';
		if ($format === self::AS_IS && !is_scalar($data)) $format = self::PRINT_R;
		switch ($format) {
			case self::PRINT_R:
				$data = print_r($data, true);
			break;
			case self::VAR_DUMP:
				ob_start();
				var_dump($data);
				$data = ob_get_contents();
				ob_end_clean();
			break;
		}
		if ($title) {
			$return_contents .= "\n".date('m/d/Y H:i:s')." $title\n";
			file_put_contents(Yii::getAlias("@app")."/runtime/logs/$logName", "\n".date('m/d/Y H:i:s')." $title\n", FILE_APPEND);
		} else {
			$return_contents .= "\n".date('m/d/Y H:i:s').": ";
			file_put_contents(Yii::getAlias("@app")."/runtime/logs/$logName", "\n".date('m/d/Y H:i:s').": ", FILE_APPEND);
		}
		$return_contents .= $data;
		file_put_contents(Yii::getAlias("@app")."/runtime/logs/$logName", $data, FILE_APPEND);
		return $return_contents;
	}

	/**
	 * @param $path
	 * @return string
	 */
	public static function process_path($path):string {
		$pathinfo = pathinfo($path);
		$dir = $pathinfo['dirname'];
		if ('..' === $pathinfo['basename']) {
			$dir = dirname($dir);
		}
		if (empty($dir)) $dir = '.';
		return $dir;
	}

	/** @noinspection PhpDocMissingThrowsInspection */
	/**
	 * RFC-4122 UUID
	 * @param integer|bool|false $length if defined, return only first $length symbols
	 * @return bool|string
	 */
	public static function gen_uuid($length = false) {
		$UUID = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', // 32 bits for "time_low"
			random_int(0, 0xffff), random_int(0, 0xffff),

			// 16 bits for "time_mid"
			random_int(0, 0xffff),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			random_int(0, 0x0fff) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			random_int(0, 0x3fff) | 0x8000,

			// 48 bits for "node"
			random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff));
		return $length?substr($UUID, 0, $length):$UUID;
	}

	/**
	 * Генерирует псевдослучайную строку заданной длины на заданном алфавите
	 * @param $length
	 * @param string $keyspace
	 * @return string
	 * @throws Exception
	 */
	public static function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'):string {
		$pieces = [];
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$pieces [] = $keyspace[random_int(0, $max)];
		}
		return implode('', $pieces);
	}

	/**
	 * @param $data
	 * @return bool
	 */
	public static function is_json($data):bool {
		json_decode($data);
		return (JSON_ERROR_NONE === json_last_error());
	}

	/**
	 * Округление до целого числа в большую сторону
	 * @param int $number
	 * @param int $significance
	 * @return bool|float
	 */
	public static function ceiling($number, $significance = 1000) {
		return (is_numeric($number) && is_numeric($significance))?(ceil($number / $significance) * $significance):false;
	}

	/**
	 * Переводит десятичный индекс в число позиционной системы счисления
	 * @param integer|float $N - десятичный индекс
	 * @param string $alphabet - позиционный алфавит
	 * @return string - строка с числом в указанном алфавите.
	 */
	public static function DecToPos($N, $alphabet):string {
		$q = strlen($alphabet);
		$ret = '';
		while (true) {
			$i = $N % $q;
			$N = floor($N / $q);
			$ret = $alphabet[$i].$ret;
			if ($N < 1) break;
		}
		return $ret;
	}

	/**
	 * Нужно, чтобы andFilterWhere не генерировал условия вида like '%' при LikeContainMode == false
	 * Используем в моделях для поиска
	 * @param string $param
	 * @return string
	 * @throws Throwable
	 */
	public static function MakeLike($param):string {
		if (empty($param)) return '';
		return ArrayHelper::getValue(Yii::$app->params, 'LikeContainMode', false)?"%$param%":"$param%";
	}

	/**
	 * @param string $username
	 * @return string
	 * @throws Exception
	 */
	public static function generateLogin(string $username):string {
		return self::random_str(5);//ololo
	}

}
