<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\controllers;

use app\models\core\controllers\WigetableController;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\user_attributes\RelUserAttributesSearch;
use Throwable;
use Yii;
use app\modules\users\models\Users;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UserController
 * @package app\modules\dynamic_attributes\controllers
 */
class UserController extends WigetableController {
	public $menuDisabled = true;

	/**
	 * Отдаёт страницу просмотра всех атрибутов пользователя
	 * @param int $user_id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionIndex(int $user_id):?string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new RelUserAttributesSearch(['user_id' => $user_id]);

		return $this->render('index', [
			'user' => Users::findModel($user_id, new NotFoundHttpException()),
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params),
			'updateAttributeAction' => ['add-attribute', 'user_id' => $user_id]
		]);
	}

	/**
	 * @param int $user_id
	 * @return Response
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function actionAddAttribute(int $user_id):Response {
		if ((null !== $user = Users::findModel($user_id)) && null !== ($updateArray = Yii::$app->request->post($user->formName()))) $user->updateModel($updateArray);
		return $this->redirect(['index', 'user_id' => $user_id]);

	}

	/**
	 * Редактор атрибутов пользователя
	 * @param int $user_id
	 * @param int $attribute_id
	 * @return null|string
	 * @throws Throwable
	 */
	public function actionEdit(int $user_id, int $attribute_id):?string {
		if ((null === $user = Users::findModel($user_id, new NotFoundHttpException())) || (null === $attribute = DynamicAttributes::findModel($attribute_id, new NotFoundHttpException()))) return null;

		if (null !== $data = Yii::$app->request->post('DynamicAttributeProperty')) {
			$attribute->setUserProperties($user_id, $data);
		}
		return $this->render('edit', compact('user', 'attribute'));
	}

	/**
	 * Сбросить все свойства атрибута для пользователя
	 * @param int $user_id
	 * @param int $attribute_id
	 * @return null|Response
	 * @throws Throwable
	 */
	public function actionClear(int $user_id, int $attribute_id):?Response {
		if ((null === Users::findModel($user_id, new NotFoundHttpException())) || (null === $attribute = DynamicAttributes::findModel($attribute_id, new NotFoundHttpException()))) return null;
		$attribute->clearUserProperties($user_id);
		return $this->redirect(Yii::$app->request->referrer);
		//return $this->goBack();//лень возиться с setReturnUrl()
	}

	/**
	 * Просмотр графика атрибутов пользователя
	 * @param int $user_id
	 * @param int $attribute_id
	 * @return null|string
	 * @throws Throwable
	 */
	public function actionGraph(int $user_id, int $attribute_id):?string {
		if ((null === $user = Users::findModel($user_id, new NotFoundHttpException())) || (null === $attribute = DynamicAttributes::findModel($attribute_id, new NotFoundHttpException()))) return null;
		return $this->render('graph', compact('user', 'attribute'));
	}

}
