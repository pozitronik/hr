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
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends Widget {

	/**
	 * {@inheritdoc}
	 */
	public function run() {
		$session = Yii::$app->session;
		$flashes = $session->getAllFlashes();

		foreach ($flashes as $type => $flash) {

			foreach ((array)$flash as $i => $message) {
				echo Growl::widget([
					'type' => $type,
					'title' => 'Well done!',
					'icon' => 'glyphicon glyphicon-ok-sign',
					'body' => $message,
					'showSeparator' => true,
					'delay' => 0,
					'pluginOptions' => [
						'showProgressbar' => true,
						'placement' => [
							'from' => 'top',
							'align' => 'right',
						]
					]
				]);
			}

			$session->removeFlash($type);
		}
	}
}
