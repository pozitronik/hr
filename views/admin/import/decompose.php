<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var int $step
 * @var int $domain
 * @var $messages array
 */

use app\helpers\Utils;
use yii\helpers\Html;
use yii\web\View;

Utils::log($messages);
?>

<?= (3 !== $step)?Html::a('Следующий шаг', ['decompose', 'step' => $step+1, 'domain' => $domain], ['class' => 'btn btn-default']):Html::a('К результату', ['result', 'domain' => $domain], ['class' => 'btn btn-success']); ?>
