<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\models\core\traits\Upload;
use yii\base\Model;

/**
 * Class TargetsImport
 * @package app\modules\targets\models
 */
class ImportTargets extends Model {
	use Upload;
	public const IMPORT_CHUNK_SIZE = 10;
	private $domain;

}