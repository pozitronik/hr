<?php
declare(strict_types=1);
/**
 * Хелпер для работ с датами
 * @author Moiseenko-EA
 * @date 12.09.2017
 * @time 10:39
 */

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
	public static function lcDate(): string {
		return date('Y-m-d H:i:s');
	}

	/**
	 * Получение интервала квартала
	 * @param mixed $d Микротайм mktime(0,0,0,4,1,2016)
	 * @return array
	 */
	public static function intervalQuarter($d): array {
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
	public static function currentQuarter(): int {
		return (int)((date('n') + 2) / 3);
	}

	/**
	 * Получить номер следующего квартала от текущего
	 * @return int
	 */
	public static function nextQuarter(): int {
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
	public static function zeroAddMoth($month): string {
		return 1 === strlen($month)?'0'.$month:(string)$month;
	}

	/**
	 * Проверяет попадает ли выбранная дата в интервал дат
	 * @param string $date Проверяемая дата (Y-m-d)
	 * @param array $interval Массив интервала дат ['start' => 'Y-m-d', 'end' => 'Y-m-d']
	 * @return bool
	 */
	public static function isBetweenDate(string $date, array $interval): bool {
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
	public static function getWeekEnd($currentDate): ?DateTime {
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
	public static function diff($dateStart, $dateEnd, $format): string {
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
	public static function isValidDate($date, $format = 'Y-m-d H:i:s'): bool {
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
}
