<?php
declare(strict_types = 1);

use yii\web\View;

\app\assets\FontAwesomeProAsset::register($this);
/* @var View $this */

echo 'icon: '."<i class='far fa-user' title='Пользователи'></i>";