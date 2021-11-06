<?php
declare(strict_types = 1);

namespace app\controllers\service;

use app\components\pozitronik\helpers\Utils;
use app\models\core\Service;
use app\models\core\controllers\WigetableController;
use app\modules\groups\models\Groups;
use app\modules\users\models\Users;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\base\Response;
use yii\db\Exception;

/**
 * Class ServiceController
 * @package app\controllers\service
 */
class ServiceController extends WigetableController {

	/**
	 * @return string|Response
	 */
	public function actionIndex() {
		return $this->render('index');
	}

	/**
	 * @return string
	 */
	public function actionReset():string {
//		Yii::$app->user->logout();
		return $this->render('reset', [
			'result' => Service::ResetDB()
		]);

	}

	/**
	 * @return string
	 * @throws InvalidConfigException
	 * @throws NotSupportedException
	 * @throws Exception
	 */
	public function actionResetTargets():string {
//		Yii::$app->user->logout();
		return $this->render('reset', [
			'result' => Service::ResetTargetsTables()
		]);

	}

	/**
	 * Необратимо маскирует
	 * @param int $step
	 * @return Response|string
	 * @throws Throwable
	 */
	public function actionMaskAndShit($step = 0) {
		switch ($step) {
			default:
			case 0:
				$users = Users::find()->all();
				foreach ($users as $user) {
					$user->username = Utils::MaskString($user->username);
					$user->email = Utils::MaskString($user->email);
					$user->save();
				}
				return $this->redirect(['/service/service/mask-and-shit', 'step' => $step + 1]);
			break;
			case 1:
				$users = Users::find()->all();
				foreach ($users as $user) {
					$attributes = $user->relDynamicAttributes;
					foreach ($attributes as $attribute) {
						foreach ($attribute->properties as $property) {
							$property->userId = $user->id;
							if (filter_var($property->getValue(), FILTER_VALIDATE_EMAIL)) {
								$attribute->setUserProperty($property->userId, $property->id, Utils::MaskString($property->getValue()));
							}
						}
					}
				}
				return $this->redirect(['/service/service/mask-and-shit', 'step' => $step + 1]);
			break;
			case 2:
				$groups = Groups::find()->all();
				foreach ($groups as $group) {
					$group->name = Utils::MaskString($group->name);
					$group->save();
				}
				return 'finished';
			break;
		}
	}
}
