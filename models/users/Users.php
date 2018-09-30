<?php

/**
 * Class Users
 */
class Users extends \yii\base\Model {

	/**
	 * @param $id
	 * @return Users
	 */
	public static function findModel($id) {
		return new self();
	}

	/**
	 * @param array $array
	 * @return Users
	 */
	public static function findOne(array $array) {
		return new self;
	}
}