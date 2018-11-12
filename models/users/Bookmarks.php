<?php
declare(strict_types = 1);

namespace app\models\users;

use yii\base\Model;

/**
 * Class Bookmarks
 * Обращение к закладкам, хранящимся в sys_user_options::bookmarks
 * @package app\models\users
 * @property string $route
 * @property string $name
 * @property integer $type
 * @property integer $order
 *
 * @property-read string $typeSpan
 */
class Bookmarks extends Model {

	public $route;
	public $name;
	public $type;
	public $order;

	public const TYPE_DEFAULT = 0;
	public const TYPE_IMPORTANT = 1;
	public const TYPE_URGENT = 2;
	public const Types = [
		self::TYPE_DEFAULT => 'Обычный',
		self::TYPE_IMPORTANT => 'Важно',
		self::TYPE_URGENT => 'Срочно'
	];

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['route', 'name'], 'string'],
			[['type'], 'integer'],
			[['order'], 'default']
		];
	}

	/**
	 * @return string
	 */
	public function getTypeSpan():string {
		switch ($this->type) {
			default:
			case self::TYPE_DEFAULT:
				return '';
			case self::TYPE_IMPORTANT:
				return '<span class="pull-right label label-danger">Важно</span>';
			case self::TYPE_URGENT:
				return '<span class="pull-right label label-warning">Срочно</span>';
		}
	}

	/**
	 * @return string
	 */
	public function getRoute():string {
		return $this->route;
	}

	/**
	 * @param string $route
	 */
	public function setRoute(string $route):void {
		$this->route = $route;
	}

	/**
	 * @return string
	 */
	public function getName():string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name):void {
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getType():int {
		return $this->type;
	}

	/**
	 * @param int $type
	 */
	public function setType(int $type):void {
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getOrder():int {
		return $this->order;
	}

	/**
	 * @param int $order
	 */
	public function setOrder(int $order):void {
		$this->order = $order;
	}

}