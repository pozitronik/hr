<?php

namespace app\models\core;

use app\helpers\Utils;
use app\models\LCQuery\LCQuery;
use app\models\traits\ARExtended;
use Yii;
use Throwable;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "sys_exceptions".
 *
 * @property int $id
 * @property string $timestamp
 * @property int $user_id
 * @property int $code
 * @property string $file
 * @property int $line
 * @property string $message
 * @property string $trace
 * @property string $get
 * @property string $post
 * @property bool $known
 */
class SysExceptions extends ActiveRecord {
	use ARExtended;

	/**
	 * @return LCQuery
	 */
	public static function find() {
		return new LCQuery(static::class);
	}


	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'sys_exceptions';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['timestamp', 'get', 'post'], 'safe'],
			[['user_id', 'code', 'line'], 'integer'],
			[['message', 'trace'], 'string'],
			[['file'], 'string', 'max' => 255],
			['known', 'boolean']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'timestamp' => 'Время',
			'user_id' => 'Пользователь',
			'code' => 'Код',
			'file' => 'Файл',
			'line' => 'Строка',
			'message' => 'Сообщение',
			'trace' => 'Trace',
			'get' => '$_GET',
			'post' => '$_POST',
			'known' => 'Известная ошибка'
		];
	}

	/**
	 * В случае, если надо поставить отлов и логирование исключения
	 * @param Throwable $t
	 * @param bool|Throwable $throw - Если передано исключение, оно выбросится в случае ненахождения модели
	 * @param bool $known_error - Пометить исключение, как известное. Сделано для пометки исключений, с которыми мы ничего сделать не можем (ошибка сторонних сервисов, например).
	 * @throws Throwable
	 */
	public static function log($t, $throw = false, $known_error = false) {
		$logger = new self;
		try {
			$logger->setAttributes([
				'user_id' => Yii::$app->request->isConsoleRequest?0:Yii::$app->user->id,
				'code' => $t->getCode(),
				'file' => $t->getFile(),
				'line' => $t->getLine(),
				'message' => $t->getMessage(),
				'trace' => $t->getTraceAsString(),
				'get' => json_encode($_GET),
				'post' => json_encode($_POST),
				'known' => $known_error
			]);
			if (!$logger->save()) Utils::fileLog($logger->attributes, 'exception catch', 'exception.log');
		} catch (Throwable $t){
			Utils::fileLog($logger->attributes, '!!!exception catch', 'exception.log');
		} finally {
			if (false !== $throw) throw $throw;
		}

	}

	/**
	 * Acknowledge record
	 * @param integer $id
	 * @throws Throwable
	 */
	public static function Acknowledge($id) {
		self::findModel($id, new NotFoundHttpException())->updateAttributes(['known' => true]);
	}


	/**
	 * Помечаем все записи, как известные
	 */
	public static function AcknowledgeAll() {
		self::updateAll(['known' => true], ['known' => false]);
	}

	/**
	 * @return int
	 */
	public static function UnknownCount(){
		return self::find()->unknown()->count();
	}
}
