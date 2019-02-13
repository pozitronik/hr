<?php
declare(strict_types = 1);

namespace app\widgets\alert;

use app\helpers\ArrayHelper;
use kartik\growl\Growl;
use Yii;
use yii\bootstrap\Widget;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 */
class Alert extends Widget {

	/**
	 * {@inheritdoc}
	 */
	public function run() {
		$session = Yii::$app->session;
		/*
		 * fixme: имеем тут проблему: getAllFlashes помечает ВСЕ флеши к удалению при следующем обращении к сессии (в том числе - и к AJAX)
		 * Т.о. передача алертов через флеши некорректна, нужно реализовать собственную очередь уведомлений (либо собственный механизм флешек, либо серверная очередь)
		*/
		$flashes = $session->getAllFlashes();

		foreach ($flashes as $type => $flash) {
			if (AlertModel::IDENTIFY_MARKER === ArrayHelper::getValue($flash, 'identify')) {
				$alert = new AlertModel($flash);
				echo Growl::widget([
					'type' => $alert->type,
					'title' => $alert->title,
					'icon' => $alert->icon,//'glyphicon glyphicon-ok-sign',
					'body' => $alert->body,
					'showSeparator' => $alert->showSeparator,
					'delay' => $alert->delay,
					'pluginOptions' => $alert->pluginOptions
				]);
				$session->removeFlash($type);
			}
		}
	}
}
