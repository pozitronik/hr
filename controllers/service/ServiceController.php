<?php
declare(strict_types = 1);

namespace app\controllers\service;

use app\helpers\Utils;
use app\models\core\Service;
use app\models\core\WigetableController;
use app\modules\groups\models\Groups;
use app\modules\users\models\Users;
use yii\base\Response;

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
	 * Необратимо маскирует
	 * @return string
	 */
	public function actionMaskAndShit($step = 0):string {
		switch ($step) {
			case 0:
				$users = Users::find()->all();
				foreach ($users as $user) {
					$user->username = Utils::MaskString($user->username);
					$user->email = Utils::MaskString($user->email);
					$user->save();
				}
				$this->redirect(['service/mask-and-shit', 'step' => $step + 1]);
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
				$this->redirect(['service/mask-and-shit', 'step' => $step + 1]);
			break;
			case 2:
				$groups = Groups::find()->all();
				foreach ($groups as $group) {
					$group->name = Utils::MaskString($group->name);
					$group->save();
				}
				$this->redirect(['service/mask-and-shit', 'step' => $step + 1]);
			break;
		}

	}
}
