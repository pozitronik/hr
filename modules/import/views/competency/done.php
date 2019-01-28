<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var int $domain
 * @var array $messages
 */

use app\helpers\Utils;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;

?>

Done!

<?php Utils::log($messages) ?>