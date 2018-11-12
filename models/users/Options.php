<?php
declare(strict_types = 1);

namespace app\models\users;

use yii\base\Model;

/** @noinspection MissingPropertyAnnotationsInspection */

/**
 * Class Options
 * Set of accessible user options in option<string>=>value<array> format
 * prototype
 * @package app\models\users
 *
 * @property integer $userId
 * @property array $nodePositions
 * @property Bookmarks[] $bookmarks
 *
 */
class Options extends Model {

	private $user_id;

	/**
	 * @return int
	 */
	public function getUserId():int {
		return $this->user_id;
	}

	/**
	 * @param int $user_id
	 */
	public function setUserId(int $user_id):void {
		$this->user_id = $user_id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			['user_id', 'required'],
			['nodePositions', 'safe']
		];
	}

	/**
	 * @return array
	 */
	public function getNodePositions():array {
		return $this->get('nodePositions');
	}

	/**
	 * @param array $nodePositions
	 */
	public function setNodePositions(array $nodePositions):void {
		$this->set('nodePositions', $nodePositions);
	}

	/**
	 * @param string $option
	 * @return array
	 */
	public function get(string $option):array {
		return (null === $result = UsersOptions::find()->where(['option' => $option, 'user_id' => $this->user_id])->one())?[]:$result->value;
	}

	/**
	 * @param string $option
	 * @param array $value
	 * @return bool
	 */
	public function set(string $option, array $value):bool {
		if (null === $userOptions = UsersOptions::find()->where(['option' => $option, 'user_id' => $this->userId])->one()) {
			$userOptions = new UsersOptions(['user_id' => $this->user_id, 'option' => $option, 'value' => $value]);
		} else {
			$userOptions->value = $value;
		}
		return $userOptions->save();
	}

	/**
	 * @return Bookmarks[]
	 */
	public function getBookmarks():array {
		$bookmarks = $this->get('bookmarks');
		foreach ($bookmarks as &$bookmark) {
			$bookmark = new Bookmarks($bookmark);
		}
		return $bookmarks;
	}

	/**
	 * @param Bookmarks[] $bookmarks
	 */
	public function setBookmarks(array $bookmarks):void {
		$a_bookmarks = [];
		foreach ($bookmarks as $bookmark) {
			$a_bookmarks[] = $bookmark->attributes;
		}
		$this->set('bookmarks', $a_bookmarks);
	}

}