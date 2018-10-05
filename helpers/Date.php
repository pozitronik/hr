<?php
declare(strict_types = 1);

namespace app\helpers;

use app\models\core\SysExceptions;
use DateTime;
use DateInterval;
use Throwable;

/**
 * Class Date
 * @package app\helpers
 */
class Date {

	/*Еврибади гоин крейзи*/
	public const SECONDS_IN_YEAR = 31536000;
	public const SECONDS_IN_MONTH = 2592000;
	public const SECONDS_IN_DAY = 86400;
	public const SECONDS_IN_HOUR = 3600;
	public const SECONDS_IN_MINUTE = 60;

	/**
	 * Мы постоянно используем такую дату, меня задалбывает вспоминать или копипастить, пусть будет алиас
	 * @return string
	 */
	public static function lcDate():string {
		return date('Y-m-d H:i:s');
	}

	/**
	 * Получение интервала квартала
	 * @param mixed $d Микротайм mktime(0,0,0,4,1,2016)
	 * @return array
	 */
	public static function intervalQuarter($d):array {
		$kv = (int)((date('n', $d) - 1) / 3 + 1);
		$year = date('y', $d);

		return [
			'start' => date('Y-m-d', mktime(0, 0, 0, ($kv - 1) * 3 + 1, 1, $year)),
			'end' => date('Y-m-d', mktime(0, 0, 0, $kv * 3 + 1, 0, $year))
		];
	}

	/**
	 * Получить номер текущего квартала
	 * @return int
	 */
	public static function currentQuarter():int {
		return (int)((date('n') + 2) / 3);
	}

	/**
	 * Получить номер следующего квартала от текущего
	 * @return int
	 */
	public static function nextQuarter():int {
		if (self::currentQuarter() + 1 > 4) {
			return 1;
		}

		return self::currentQuarter() + 1;
	}

	/**
	 * Прибавляет заданное кол-во к месяцу
	 * @param null|int $int
	 * @param null|int $month
	 * @return false|string
	 */
	public static function monthPlus($int = null, $month = null) {
		if (null === $int) {
			return date('m');
		}

		if (null !== $month) {
			$month += $int;
		} else {
			$month = date('m') + $int;
		}

		return self::zeroAddMoth($month);
	}

	/**
	 * Отнимает заданное кол-во от месяца
	 * @param null|int $int
	 * @param null|int $month
	 * @return false|null|string
	 */
	public static function monthMinus($int = null, $month = null) {
		if (null === $int) {
			return date('m');
		}

		if (null !== $month) {
			return self::zeroAddMoth($month - $int);
		}

		return self::zeroAddMoth(date('m') - $int);
	}

	/**
	 * Добавляет ноль к месяцу если это необходимо
	 * @param int $month
	 * @return string
	 */
	public static function zeroAddMoth($month):string {
		return 1 === strlen($month)?'0'.$month:(string)$month;
	}

	/**
	 * Проверяет попадает ли выбранная дата в интервал дат
	 * @param string $date Проверяемая дата (Y-m-d)
	 * @param array $interval Массив интервала дат ['start' => 'Y-m-d', 'end' => 'Y-m-d']
	 * @return bool
	 */
	public static function isBetweenDate(string $date, array $interval):bool {
		$d = new DateTime($date);
		$d1 = new DateTime($interval['start']);
		$d2 = new DateTime($interval['end']);

		return ($d2 >= $d && $d1 <= $d) || ($d1 >= $d && $d2 <= $d);
	}

	/**
	 * Возвращает DateTime конца недели, в которой находится день $currentDate
	 * @param DateTime $currentDate - обсчитываемая дата, по ней и вычисляется неделя
	 * @return DateTime
	 * @throws Throwable
	 */
	public static function getWeekEnd($currentDate):?DateTime {
		$currentWeekDay = $currentDate->format('w');
		$t = 7 - $currentWeekDay;
		$td = clone $currentDate;
		try {
			return $td->add(new DateInterval("P{$t}D"));
		} catch (Throwable $t) {
			SysExceptions::log($t, $t);
			return null;
		}
	}

	/**
	 * Простое сравнение двух дат с возможностью задать произвольное форматирование
	 * @param string $dateStart
	 * @param string $dateEnd
	 * @param string $format
	 * @return string
	 */
	public static function diff($dateStart, $dateEnd, $format):string {
		$date1 = new DateTime($dateStart);
		$date2 = new DateTime($dateEnd);
		$diff = $date1->diff($date2);

		return $diff->format($format);
	}

	/**
	 * Проверяет, соответствует ли строка указанному формату даты
	 * @param string $date
	 * @param string $format
	 * @return bool
	 */
	public static function isValidDate($date, $format = 'Y-m-d H:i:s'):bool {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

	/**
	 * Получение полных дней между двумя датами
	 * @param string $dateStart
	 * @param string $dateEnd
	 * @return mixed
	 */
	public static function fullDays($dateStart, $dateEnd) {
		$date1 = new DateTime($dateStart);
		$date2 = new DateTime($dateEnd);

		return $date1->diff($date2)->days;
	}

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
			self::SECONDS_IN_MINUTE,
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
}
