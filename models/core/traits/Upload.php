<?php
declare(strict_types = 1);

namespace app\models\core\traits;

use Yii;
use app\helpers\Path;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Trait Upload
 * @package app\models\core\traits
 * @property UploadedFile $uploadFileInstance
 */
trait Upload {
	public $uploadFileInstance;

	/**
	 * Загружает файл в соответствующий модели каталог, возвращает полный путь или null в случае ошибки
	 * @return string|null
	 * @throws InvalidConfigException
	 */
	public function uploadFile():?string {
		/** @var Model $this */
		$saveDir = Yii::getAlias("@app/web/uploads/{$this->formName()}");
		/** @var Model $this */
		if ((null !== $uploadFileInstance = UploadedFile::getInstance($this, 'uploadFileInstance')) && Path::CreateDirIfNotExisted($saveDir)) {
			$fileName = "$saveDir/{$uploadFileInstance->name}";
			$uploadFileInstance->saveAs($fileName);
			return $fileName;
		}
		return null;
	}

}