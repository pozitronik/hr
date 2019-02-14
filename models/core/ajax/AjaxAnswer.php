<?php
declare(strict_types = 1);

namespace app\models\core\ajax;

use yii\base\Model;

/**
 * Модель ответа ajax-обработчика
 * Class AjaxAnswer
 * @package app\models\core\ajax
 *
 * @property int $resultCode Числовой код результата операции
 * @property null|int $count Количество возвращаемых реззультатов (если не установлено, то не используется)
 * @property array $items Массив результатов
 */
class AjaxAnswer extends Model {
	public const RESULT_OK = 0;/*Отработано*/
	public const RESULT_ERROR = 1;/*Ошибка*/
	public const RESULT_POSTPONED = 2;/*На будущее*/

	private $_resultCode = self::RESULT_OK;
	private $_count;
	private $_items = [];

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['resultCode', 'items'], 'integer'],
			[['items'], 'safe']
		];
	}

	/**
	 * @return int
	 */
	public function getResultCode():int {
		return $this->_resultCode;
	}

	/**
	 * @param int $resultCode
	 */
	public function setResultCode(int $resultCode):void {
		$this->_resultCode = $resultCode;
	}

	/**
	 * @return int
	 */
	public function getCount():?int {
		return $this->_count;
	}

	/**
	 * @param int $count
	 */
	public function setCount(?int $count):void {
		$this->_count = $count;
	}

	/**
	 * @return array
	 */
	public function getItems():array {
		return $this->_items;
	}

	/**
	 * @param array $items
	 */
	public function setItems(array $items):void {
		$this->_items = $items;
	}

}