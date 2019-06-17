<?php
declare(strict_types = 1);

namespace app\modules\users\models;

use app\models\relations\RelGroupsGroups;
use app\modules\groups\models\Groups;
use pozitronik\helpers\ArrayHelper;
use Throwable;

/**
 * Временный трейт для графа пользователей. Потом сделаю класс и наследничков
 * Trait Graph
 * @package app\modules\users\models
 */
trait Graph {

	/**
	 * @param int|null $x
	 * @param int|null $y
	 * @return array
	 * @throws Throwable
	 */
	public function asNode(?int $x = 0, ?int $y = 0):array {
		/** @var Users $this */
		$red = random_int(10, 255);
		$green = random_int(10, 255);
		$blue = random_int(10, 255);
		$size = 25 / ($y + 1);
		/** @var Users $this */
		return [
			'id' => "user{$this->id}",
			'label' => (string)$this->username,
			'x' => $x,
			'y' => $y,
			'size' => (string)$size,//придумать характеристику веса группы,
			'color' => ArrayHelper::getValue($this->relUserPositions, 'color', "rgb({$red},{$green},{$blue})"),
			'type' => 'circle',
			'shape' => 'image',
			'image' => $this->avatar,
			'widthConstraint' => true
		];
	}

	/**
	 * @param Groups $to
	 * @return array
	 */
	public function Edge(Groups $from):array {
		return [
			'id' => "Group{$from->id}xUser{$this->id}",
			'from' => "group{$from->id}",
			'to' => "user{$this->id}",
			'label' => false
		];
	}
}