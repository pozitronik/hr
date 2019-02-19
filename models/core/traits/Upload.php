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
	 * @param string|null $saveDirAlias Параметр для переопределения пути загрузки
	 * @param string|null $newFileName Параметр для переименования загруженного файла (без расширения)
	 * @param string|null $newFileExtension Параметр для изменения расширения загруженного файла
	 * @param string $instanceName Параметр для переопределения имени инпута при необходимости
	 * @param int|null $returnPart Возвращаемый элемент имени (как в pathinfo)
	 * @return string|null
	 * @throws InvalidConfigException
	 */
	public function uploadFile(?string $saveDirAlias = null, ?string $newFileName = null, ?string $newFileExtension = null, string $instanceName = 'uploadFileInstance', ?int $returnPart = null):?string {
		/** @var Model $this */
		$saveDir = Yii::getAlias($saveDirAlias??"@app/web/uploads/{$this->formName()}");
		/** @var Model $this */
		if ((null !== $uploadFileInstance = UploadedFile::getInstance($this, $instanceName)) && Path::CreateDirIfNotExisted($saveDir)) {
			$fileName = $uploadFileInstance->name;
			$fileName = (null === $newFileName)?$fileName:Path::ChangeFileName($fileName, $newFileName);
			$fileName = (null === $newFileExtension)?$fileName:Path::ChangeFileExtension($fileName, $newFileExtension);
			$fileName = $saveDir.DIRECTORY_SEPARATOR.$fileName;
			$uploadFileInstance->saveAs($fileName);
			return null === $returnPart?$fileName:pathinfo($fileName, $returnPart);
		}
		return null;
	}

}