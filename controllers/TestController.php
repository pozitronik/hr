<?php
/** @noinspection ALL */
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\core_module\PluginsSupport;
use app\modules\export\models\attributes\ExportAttributes;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\SalaryModule;
use app\modules\users\UsersModule;
use app\widgets\admin_panel\AdminPanelWidget;
use Yii;
use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\core\core_module\CoreModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\users\models\references\RefUserRoles;
use app\models\relations\RelUsersGroups;
use app\modules\users\models\Users;
use app\modules\users\models\UsersOptions;
use app\widgets\alert\Alert;
use app\widgets\alert\AlertModel;
use kartik\growl\Growl;
use yii\base\Response;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * Class TestController
 * @package app\controllers
 */
class TestController extends Controller {

	/**
	 * @return string|Response
	 */
	public function actionIndex() {
		return Yii::$app->cache->getOrSet('index', function() {
			return $this->render('index');
		});
	}

	public function actionTest() {
		return SalaryModule::to(['salary/index','id' => 10]);
	}

	public function actionList() {
		Utils::log(PluginsSupport::GetAllReferences());
	}

	public function actionSpeed() {
		$userIds = [335, 341, 365];//, 398, 402, 411, 413, 414, 419, 421, 501, 508, 521, 524, 546, 549, 573, 577, 613, 628, 635, 640, 646, 653, 656, 671, 672, 680, 705, 711, 740, 750, 769, 774, 789, 793, 878];
		$startTime = microtime(true);
		foreach ($userIds as $id) {
			if (null !== $user = Users::findModel($id)) {
				$relAttributes = $user->relUsersAttributes;
				foreach ($relAttributes as $relAttribute) {
					$attribute = $relAttribute->relDynamicAttribute;
					$attributeTypeNames = [];
					/** @var RefAttributesTypes $refAttributeType */
					foreach ($relAttribute->refAttributesTypes as $refAttributeType) {
						$attributeTypeNames[] = $refAttributeType->name;
					}
					$properties = $attribute->properties;

					foreach ($properties as $property) {
						$property->userId = $id;
						$value = $property->getValue();
					}
				}
			}
		}
		$execTime = $startTime - microtime(true);
		echo "Property calculation time: $execTime seconds\n";
		$startTime = microtime(true);
		ExportAttributes::UsersExport($userIds);
		$execTime = $startTime - microtime(true);
		echo "Export time: $execTime seconds";

		//	return $this->render('index');
	}

}
