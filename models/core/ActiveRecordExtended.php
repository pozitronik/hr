<?php
declare(strict_types = 1);

namespace app\models\core;

use app\models\SysLog;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/** @noinspection UndetectableTableInspection */

/**
 * Class ActiveRecordExtended
 * @package app\models\core
 */
class ActiveRecordExtended extends ActiveRecord {
	public $loggingEnabled = true;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		throw new InvalidConfigException('"'.static::class.'" нельзя вызывать напрямую.');
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeSave($insert):bool {
		return (parent::beforeSave($insert) && SysLog::logChanges($this));
	}

}