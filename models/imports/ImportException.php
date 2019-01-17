<?php
declare(strict_types = 1);

namespace app\models\imports;

use Exception;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * Class ImportException
 * @package app\models\imports\fos
 */
class ImportException extends Exception {
	private $modelName;
	private $errorsText;

	/**
	 * {@inheritDoc}
	 */
	public function __construct(Model $model, array $errors = [], Exception $previous = null) {
		$this->modelName = $model->formName();
		$this->errorsText = VarDumper::dumpAsString($errors);
		parent::__construct("{$this->modelName} import errors: {$this->errorsText}", 0, $previous);
	}

	/**
	 * @inheritdoc
	 */
	public function getName():string {
		return "{$this->modelName} import errors: {$this->errorsText}";
	}
}