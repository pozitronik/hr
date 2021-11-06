<?php
declare(strict_types = 1);

namespace app\modules\users\models;

use app\components\pozitronik\helpers\ArrayHelper;
use yii\base\Model;

/**
 * Class Options
 * Set of accessible user options in option<string>=>value<array> format
 * prototype
 * @package app\models\users
 *
 * @property integer $userId
 * @property array $nodePositionsConfig
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
			['nodePositionsConfig', 'safe']
		];
	}

	/**
	 * @return array
	 */
	public function getNodePositionsConfig():array {
		return $this->get('nodePositionsConfig');
	}

	/**
	 * @param array $nodePositionsConfig
	 */
	public function setNodePositionsConfig(array $nodePositionsConfig):void {
		$this->set('nodePositionsConfig', $nodePositionsConfig);
	}

	/**
	 * @param string $option
	 * @return array
	 */
	public function get(string $option):array {
		/** @var UsersOptions $result */
		return (null === $result = UsersOptions::find()->where(['option' => $option, 'user_id' => $this->user_id])->one())?[]:$result->value;
	}

	/**
	 * @param string $option
	 * @param array $value
	 * @return bool
	 */
	public function set(string $option, array $value):bool {
		/** @var UsersOptions $userOptions */
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
		unset($bookmark);
		ArrayHelper::multisort($bookmarks,'order');
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