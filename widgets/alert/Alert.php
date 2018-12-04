<?php
declare(strict_types = 1);

namespace app\widgets\alert;

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
		$flashes = $session->getAllFlashes();

		foreach ($flashes as $type => $flash) {
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
