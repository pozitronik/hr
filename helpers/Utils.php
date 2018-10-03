<?php
declare(strict_types = 1);

namespace app\helpers;

use DateTime;
use Exception;
use Yii;
use yii\helpers\Url;
use SimpleXMLElement;
use RuntimeException;
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
	 * Функция возвращает количество секунд между двумя датами
	 * @param string $dateStart - строковое представление даты начала
	 * @param bool|false|string|null $dateEnd - строковое представление даты конца. Если не задано - используется текущее время.
	 * @param bool|true $ignore_holidays - исключать из подсчёта промежутки, выпадающие на выходные
	 * @param array $stats = [
	 *    'result_seconds' => integer подсчитанное количество секунд между двумя датами,
	 *    'days' => integer количество дней, затронутых итерированных подсчётом,
	 *    'ignore_holidays' => bool|true флаг, были ли исключены выходные и праздники из подсчёта,
	 *    'holidays' => [
	 *        'timestamp' => unix_timestamp вычисленного выходного,
	 *        'date' => string дата вычисленного выходного,
	 *        'diff_seconds' => integer дельта времени для этого выходного,
	 *        'diff_time' => string строковая дельта
	 *    ] данные по каждому выходному,
	 *    'holiday_start_diff_seconds' => integer|false если заявка началась в выходной день, то дельта времени до первого рабочего дня, иначе false
	 * ]
	 * @return int
	 */
	public static function SummaryTime($dateStart, $dateEnd = false, $ignore_holidays = true, &$stats = []):int {
		if (false === $dateEnd) $dateEnd = null;

		$date_start = date_timestamp_get(date_create($dateStart));
		$date_end = date_timestamp_get(date_create($dateEnd));

		if (0 === $dateStart) /** @noinspection PhpParamsInspection */
			return date_timestamp_get($date_end);//todo: это не будет работать, date_timestamp_get ждёт DateTime, а тут будет int. Возможно, условие будет работать только при date_end == null

		/*Алгоритм аналогичен lightcab.SUMMARY_TIME*/

		$stats = [
			'ignore_holidays' => $ignore_holidays,
			'holidays' => [],
			'holiday_start_diff_seconds' => false
		];

		if ($ignore_holidays) {

			$period_start = $date_start;
			$period_end = self::getDayEnd($date_end);
			$holiday_diff = 0;

			$holidays_started = false;//флаг, что заявка стартанула в выходной, и следующий день надо тоже считать
			$stats['days'] = 0;
			while ($period_start < $period_end) {
				if (self::isHoliday($period_start)) {
					$diff = self::getDayEnd($period_start) - $period_start;
					$holiday_diff += $diff;
					if ($holidays_started || 0 === $stats['days']) {//заявка стартует в первый день
						$stats['holiday_start_diff_seconds'] += $diff;
						$holidays_started = true;//поставим флаг, если следующий день выходной, то и его дифф включим
					}
					$stats['holidays'][] = [
						'timestamp' => $period_start,
						'date' => date('d-m-Y', $period_start),
						'diff_seconds' => $diff,
						'diff_time' => self::seconds2times($diff)
					];

				} else {
					$holidays_started = false;
				}
				$period_start = (0 === $stats['days'])?self::getDayEnd($period_start):$period_start + Date::SECONDS_IN_DAY;// разве getDayEnd не всегда будет работать?
				$stats['days']++;
			}
			$full_diff = $date_end - $date_start;
			$result = $full_diff - $holiday_diff;

		} else {
			$result = $date_start - $date_end;
			$stats['days'] = floor($result / Date::SECONDS_IN_DAY) + 1;
		}
		$stats['result_seconds'] = $result;

		return $result;
	}

	/**
	 * @param integer $date - timestamp
	 * @return int
	 */
	private static function getDayEnd($date):int {
		return mktime(0, 0, 0, date("m", $date), date("d", $date) + 1, date("y", $date));
	}

	/**
	 * Выдаёт форматированное в заданный формат время
	 * @param bool|int $delay - количество секунд для преобразования
	 * @param bool $short_format
	 * @return string|false
	 */
	public static function seconds2times($delay, $short_format = false) {
		if (null === $delay) $delay = false;
		if (true === $delay) return "Отключено";
		if (false === $delay) return false;//используется для оптимизации статистики SLA
		$str_delay = (string)$delay;
		$sign = '';
		if (0 === strncmp($str_delay, '-', 1)) {
			$sign = '-';
			$str_delay = substr($str_delay, 1);
		}

		$dtF = new DateTime("@0");
		$dtT = new DateTime("@$str_delay");
		$diff = $dtF->diff($dtT);
		$periods = [
			Date::SECONDS_IN_YEAR,
			Date::SECONDS_IN_DAY,
			Date::SECONDS_IN_HOUR,
			Date::SECONDS_IN_MINUTE,
			1
		];// секунд в году|дне|часе|минуте|секунде
		$format_values = $short_format?['%yг ', '%aд ', '%h:', '%I:', '%S']:['%y лет ', '%a д. ', '%h час. ', '%i мин. ', '%s сек.'];
		$format_string = '';
		for ($level = 0; 5 !== $level; $level++) {
			if ($str_delay >= $periods[$level]) $format_string .= $format_values[$level];
		}
		return ('' === $format_string)?'0 сек.':($sign.$diff->format($format_string));
	}

	/**
	 * Аналог SQL-функции UNIX_TIMESTAMP
	 * Возвращает таймстамп даты SQL-формата
	 * @param string $date
	 * @return int|false
	 */
	public static function unix_timestamp($date) {
		if (!$date) return false;
		$dt = DateTime::createFromFormat("Y-m-d H:i:s", $date);
		return $dt->getTimestamp();
	}

	/**
	 * Конвертирует таймстамп в дату
	 * @param $timestamp
	 * @return false|string
	 */
	public static function from_unix_timestamp($timestamp) {
		return date("Y-m-d H:i:s", $timestamp);
	}

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
	 * Превращает сохранённый фильтр в набор GET-параметров
	 * @param array $filter_array
	 * @return string
	 */
	public static function FilterToUrl($filter_array):string {
		$n_filter = ['/'];
		foreach ($filter_array as $key => $value) {
			if ('sort' !== $key) {
				$n_filter["RequestsSearch[$key]"] = $value;
			} else {
				$n_filter['sort'] = $value;
			}
		}

		return Url::toRoute($n_filter);
	}

	/**
	 * Форматирует дату для экспорта АРМФЗ
	 * @param $date
	 * @return string
	 */
	public static function FormatDateToExport($date):string {
		return date_create($date)->format('d.m.Y');
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
	 * @param array $interval
	 * @return int
	 * @deprecated (just to check usage)
	 */
	public function interval2seconds($interval):int {
		$seconds = 0;
		foreach ($interval as $time => $value) {
			switch ($time) {
				case 'd':
					/** @noinspection SummerTimeUnsafeTimeManipulationInspection */
					$seconds += $value * 24 * 60 * 60;
				break;
				case 'h':
					$seconds += $value * 60 * 60;
				break;
				case 'i':
					$seconds += $value * 60;
				break;
				case 's':
					$seconds += $value;
				break;
			}
		}
		return $seconds;
	}

	/**
	 * Append one xml node to other
	 * @param SimpleXMLElement $to
	 * @param SimpleXMLElement $from
	 */
	public static function append_node(SimpleXMLElement $to, SimpleXMLElement $from):void {
		$toDom = dom_import_simplexml($to);
		$fromDom = dom_import_simplexml($from);
		$toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
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

	/**
	 * Метод для получения переданных в GET/POST массивов, например
	 * @param $array
	 * @param $key
	 * @param array $default
	 * @return array
	 * @throws Throwable
	 */
	public static function GetValueAsArray($array, $key, $default = []):array {
		$result = ArrayHelper::getValue($array, $key, $default);
		return is_array($result)?$result:$default;
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
	 * Формирует условие поиска по id
	 * Условие переключается настройкой RequestIdExactSearch, если false - то ищем как like (согласно настройке LikeContainMode), иначе ищем по точному соответствию
	 * @param integer $IdValue Значение Id
	 * @param string $table Опциональное имя таблицы
	 * @return array
	 * @throws Throwable
	 */
	public static function IdSearchCondition($IdValue, $table = ''):array {
		return ArrayHelper::getValue(Yii::$app->params, 'RequestIdExactSearch', false)?["=", "{$table}.id",
			$IdValue]:["like", "{$table}.id", self::MakeLike($IdValue), false];
	}

	/**
	 * Ищет значение в многомерном массиве, если находит его, то возвращает массив со всеми ключами до этого элемента
	 * @param array $array
	 * @param $search
	 * @param array $keys
	 * @return array
	 */
	public static function array_find_deep($array, $search, $keys = []):array {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$sub = self::array_find_deep($value, $search, array_merge($keys, [$key]));
				if (count($sub)) {
					return $sub;
				}
			} elseif ($value === $search) {
				return array_merge($keys, [$key]);
			}
		}

		return [];
	}

}
