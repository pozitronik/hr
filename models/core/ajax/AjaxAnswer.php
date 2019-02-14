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
 * @property null|array $items Массив результатов (если не установлен, то не используется
 *
 * @property-read array $answer Массив с ответом
 */
class AjaxAnswer extends Model {
	public const RESULT_OK = 0;/*Отработано*/
	public const RESULT_ERROR = 1;/*Ошибка*/
	public const RESULT_POSTPONED = 2;/*На будущее*/

	private $_resultCode = self::RESULT_OK;
	private $_count;
	private $_items;

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
	public function getItems():?array {
		return $this->_items;
	}

	/**
	 * @param array $items
	 */
	public function setItems(?array $items):void {
		$this->_items = $items;
	}

	/**
	 * Добавляет ошибку и возвращает ответ (для случая, когда ajax-контроллер должен ответить при обнаружении ошибки)
	 * @param string $attribute
	 * @param string $error
	 * @return array
	 */
	public function addError($attribute, $error = ''):array {
		parent::addError($attribute, $error);
		$this->resultCode = self::RESULT_ERROR;
		return $this->answer;
	}

	/**
	 * Добавляет массив ошибок и возвращает ответ (для случая, когда ajax-контроллер должен ответить при обнаружении ошибки)
	 * @param array $items
	 * @return array
	 */
	public function addErrors(array $items):array {
		parent::addErrors($items);
		$this->resultCode = self::RESULT_ERROR;
		return $this->answer;
	}

	/**
	 * Возврат ответа
	 * @return array
	 */
	public function getAnswer():array {
		$result = [
			'result' => $this->resultCode,
			'errors' => ([] === $this->errors)?null:$this->errors,
			'count' => $this->count,
			'items' => $this->items
		];
		return $result;

	}

}