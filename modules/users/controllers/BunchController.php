<?php
declare(strict_types = 1);

namespace app\modules\users\controllers;

use pozitronik\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\models\core\controllers\WigetableController;
use app\modules\groups\models\Groups;
use app\modules\users\models\UsersMassUpdate;
use Throwable;
use Yii;
use app\modules\users\models\Users;
use yii\data\ArrayDataProvider;
use yii\web\Response;

/**
 * Class BunchController
 * @package app\controllers\admin
 */
class BunchController extends WigetableController {
	public $menuCaption = "<i class='fa fa-user-edit'></i>Редактор пользователей";
//	public $menuIcon = "/img/admin/users.png";
	public $orderWeight = 5;
	public $defaultRoute = 'users/bunch';

	/**
	 * Групповое изменение пользователей
	 * В post['selection'] приходят айдишники выбранных юзеров
	 * @param int|null $group_id - если указано, то выбираются пользователи этой группы
	 * @param bool $hierarchy вместе с group_id прогружает иерархично всех пользователей вниз
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionIndex(int $group_id = null, bool $hierarchy = false) {
		$massUpdate = new UsersMassUpdate();
		$massUpdate->usersId = ArrayHelper::getColumn(Users::find()->active()->all(), 'id');
		if ($massUpdate->load(Yii::$app->request->post())) {
			$statistics = new ArrayDataProvider([
				'allModels' => $massUpdate->apply(),
				'sort' => [
					'attributes' => ['id', 'status', 'error']
				]
			]);
			$massUpdate->loadSelection($massUpdate->usersIdSelected);/*Переподгружаем список айдишников для перегенерации доступных наборов параметров*/
			$massUpdate->usersId = $massUpdate->usersIdSelected;
			return $this->render('index', [
				'massUpdateModel' => $massUpdate,
				'statistics' => $statistics,
				'attributesData' => ArrayHelper::map(DynamicAttributes::find()->active()->all(), 'id', 'name'),
				'group' => Groups::findModel($group_id)
			]);
		}

		if ((null !== $group_id) && false !== $massUpdate->loadGroupSelection($group_id, $hierarchy)) {
			return $this->render('index', [
				'massUpdateModel' => $massUpdate,
				'statistics' => null,
				'attributesData' => ArrayHelper::map(DynamicAttributes::find()->active()->all(), 'id', 'name'),
				'group' => Groups::findModel($group_id)
			]);
		}

		if (false !== $massUpdate->loadSelection(Yii::$app->request->post('selection'))) {
			return $this->render('index', [
				'massUpdateModel' => $massUpdate,
				'statistics' => null,
				'attributesData' => ArrayHelper::map(DynamicAttributes::find()->active()->all(), 'id', 'name'),
				'group' => Groups::findModel($group_id)
			]);
		}
		/*Никаких фильтрационных параметров не передали, редактим всех*/

		return $this->render('index', [
			'massUpdateModel' => $massUpdate,
			'statistics' => null,
			'attributesData' => ArrayHelper::map(DynamicAttributes::find()->active()->all(), 'id', 'name'),
			'group' => false
		]);
	}

}
