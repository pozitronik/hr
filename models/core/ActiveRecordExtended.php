<?php
declare(strict_types = 1);

namespace app\models\core;

use app\models\core\traits\ARExtended;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/** @noinspection UndetectableTableInspection */

/**
 * Class ActiveRecordExtended
 * @package app\models\core
 */
class ActiveRecordExtended extends ActiveRecord {
	use ARExtended;
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
		if (!$insert) ActiveRecordLogger::logChanges($this);//do not log inserts here => log into afterSave
		return parent::beforeSave($insert);
	}

	/**
	 * {@inheritDoc}
	 */
	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);
		if ($insert) ActiveRecordLogger::logModel($this);
	}

}