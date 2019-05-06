<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\controllers;

use pozitronik\helpers\ArrayHelper;
use app\models\user\CurrentUser;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributesSearch;
use app\modules\dynamic_attributes\models\DynamicAttributesSearchItem;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\models\core\WigetableController;
use app\modules\dynamic_attributes\models\DynamicAttributesSearchCollection;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class AttributesController
 * @package app\controllers\admin
 */
class AttributesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-tags'></i>Атрибуты пользователей";
	public $menuIcon = "/img/admin/attributes.png";
	public $orderWeight = 4;
	public $defaultRoute = 'attributes/attributes';

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new DynamicAttributesSearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);
	}

	/**
	 * @return string|Response
	 * @throws Exception
	 * @throws InvalidConfigException
	 */
	public function actionCreate() {
		$newAttribute = new DynamicAttributes();
		if ($newAttribute->createModel(Yii::$app->request->post($newAttribute->formName()))) {
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newAttribute->id]);
		}
		return $this->render('create', [
			'model' => $newAttribute
		]);
	}

	/**
	 * @param int $id
	 * @return null|string
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):?string {
		if (null === $attribute = DynamicAttributes::findModel($id, new NotFoundHttpException())) return null;

		if (null !== ($updateArray = Yii::$app->request->post($attribute->formName()))) {
			$attribute->updateAttribute($updateArray);
		}

		return $this->render('update', [
			'model' => $attribute
		]);
	}

	/**
	 * @param int $id
	 * @throws Throwable
	 */
	public function actionDelete(int $id):void {
		if (null !== $model = DynamicAttributes::findModel($id, new NotFoundHttpException())) $model->safeDelete();
		$this->redirect('index');
	}

	/**
	 * Сохранение/изменение свойства атрибута
	 * @param int $attribute_id id атрибута
	 * @param null|int $property_id id свойства (null для нового)
	 * @return null|string|Response
	 * @throws Throwable
	 */
	public function actionProperty(int $attribute_id, ?int $property_id = null) {
		if (null === $attribute = DynamicAttributes::findModel($attribute_id, new NotFoundHttpException())) return null;
		$property = new DynamicAttributeProperty([
			'attributeId' => $attribute_id
		]);
		if ($property->load(Yii::$app->request->post())) {
			$attribute->setProperty($property, $property_id);
			if (Yii::$app->request->post('more', false)) return $this->redirect(['property', 'attribute_id' => $attribute_id, 'property_id' => $property_id]);//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $attribute_id]);

		}

		if (null === $property_id) {
			return $this->render('property/create', [
				'attribute' => $attribute,
				'model' => $property
			]);
		}

		$property = $attribute->getPropertyById((int)$property_id, new NotFoundHttpException());
		return $this->render('property/update', [
			'attribute' => $attribute,
			'model' => $property
		]);
	}

	/**
	 * Удаляет свойство из атрибута
	 * Заполненные данные по аттрибуту не удаляются.
	 * @param int $attribute_id
	 * @param int $property_id
	 * @throws Throwable
	 */
	public function actionPropertyDelete(int $attribute_id, int $property_id = null):void {
		if (null === $attribute = DynamicAttributes::findModel($attribute_id, new NotFoundHttpException())) return;
		$attribute->deleteProperty($property_id);
		$this->redirect(['attributes/update', 'id' => $attribute_id]);
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionSearch():string {
		$searchSet = new DynamicAttributesSearchCollection();
		/** @var DynamicAttributes[] $attributes */
		$attributes = DynamicAttributes::find()->active()->all();
		$attribute_data = [];
		foreach ($attributes as $attribute) {
			$attribute_data[$attribute->categoryName][$attribute->id] = $attribute->name;
		}
		$search = Yii::$app->request->post();
		if (null !== $user = CurrentUser::User()) {
			$previousSearchData = $user->options->get('previous_attribute_search');
			$search[$searchSet->formName()] = (ArrayHelper::getValue($search, $searchSet->formName(), []) + $previousSearchData);
			$user->options->set('previous_attribute_search', ArrayHelper::getValue($search, $searchSet->formName(), []));

		}
		$searchSet->load($search);
		if (null !== Yii::$app->request->post('add')) {/*Нажали кнопку "добавить поле", догенерируем набор условий*/
			$searchSet->addItem(new DynamicAttributesSearchItem());
		}
		if (null !== Yii::$app->request->post('remove')) {/*Нажали кнопку "убрать поле", догенерируем набор условий*/
			$searchSet->removeItem();
		}

		if (null !== ArrayHelper::getValue($search, 'search')) {/*Нажали поиск, нужно сгенерировать запрос, поискать, отдать результат*/
			$searchCondition = $searchSet->searchCondition();
			return $this->render('search', [
				'model' => $searchSet,
				'dataProvider' => $searchCondition,
				'attribute_data' => $attribute_data
			]);
		}

		return $this->render('search', [
			'model' => $searchSet,
			'dataProvider' => empty($search[$searchSet->formName()])?null:$searchSet->searchCondition(),
			'attribute_data' => $attribute_data
		]);

	}
}
