<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\helpers\Utils;
use Yii;
use yii\bootstrap\Alert;
use yii\base\Model;

/**
 * Прототип модели для передачи алертов
 * Class AlertPrototype
 * @package app\models\prototypes
 * @property string $body
 * @property string $type
 */
class AlertPrototype extends Model {
	public const MODE_PAGE = 0;
	public const MODE_PANEL = 1;
	public const MODE_FLOATING = 2;

	public const TYPE_PRIMARY = 'primary';
	public const TYPE_SUCCESS = 'success';
	public const TYPE_DANGER = 'danger';

	private $body;
	private $type;

	/**
	 * @return string
	 */
	public function getBody():string {
		return $this->body;
	}

	/**
	 * @param string $body
	 */
	public function setBody(string $body):void {
		$this->body = $body;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type):void {
		$this->type = $type;
	}

	/**
	 *
	 */
	public static function SuccessNotify():void {
		Yii::$app->session->setFlash(self::TYPE_SUCCESS, "Успешно!");
	}

	/**
	 * Форматирует стандартный массив ошибок модели в читаемую строку, чисто для удобства заказчика
	 * @param array $errors
	 * @return string
	 */
	private static function ArrayErrors2String(array $errors) {
		$array_values = [];
		array_walk_recursive($errors, function($v, $k) use (&$array_values) {
			if (!empty($v)) {
				$array_values[] = $v;
			}
		});
		return implode(',</br>', $array_values);
	}

	/**
	 * @param array $errors
	 */
	public static function ErrorsNotify(array $errors):void {
		Yii::$app->session->setFlash(self::TYPE_DANGER, self::ArrayErrors2String($errors));
	}

}


