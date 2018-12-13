<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\helpers\ArrayHelper;
use app\models\dynamic_attributes\DynamicAttributes;
use app\models\core\WigetableController;
use app\models\groups\Groups;
use app\models\users\UsersMassUpdate;
use Throwable;
use Yii;
use app\models\users\Users;
use yii\data\ArrayDataProvider;
use yii\filters\ContentNegotiator;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Class MassUpdateController
 * @package app\controllers\admin
 */
class BunchController extends WigetableController {
	public $menuCaption = "Групповое изменение пользователей";
	public $menuIcon = "/img/admin/users.png";

	/**
	 * @inheritdoc
	 */
	public function behaviors():array {
		return [
			[
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
					'application/xml' => Response::FORMAT_XML,
					'text/html' => Response::FORMAT_HTML
				]
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => ErrorAction::class
			]
		];
	}

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
