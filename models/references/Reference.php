<?php
declare(strict_types = 1);

namespace app\models\references;

use app\models\core\LCQuery;
use app\models\core\Magic;
use app\models\core\traits\ARExtended;
use app\widgets\alert\AlertModel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\ServerErrorHttpException;
use RuntimeException;

/** @noinspection UndetectableTableInspection */

/**
 * Class Reference
 * Базовая реализация справочника
 * Справочник - стандартная шаблонная модель. Табличка обязательно имеет три поля int(id), (string)name, (bool)deleted
 * Правила и подписи стандартным полям заданы по умолчанию, при необходимссти перекрываются при наследовании.
 * В таблице могут быть отдельные поля, тогда rules() и attributeLabels) также перекрываются при наследовании.
 * Для того, чтобы имя справочника везде корректно отображалось, нужно перекрыть геттер getRef_name().
 * Для того, чтобы задать в index/view свой набор полей, можно перекрыть геттеры getColumns()/getView_columns().
 * Если у справочника своя форма редактирования (например, с дополнительными полями), возвращаем путь к этой вьюхе в getForm().
 * Если форма лежит в @app/views/admin/references/{formName()}/_form.php, то она подтянется автоматически, так что это рекомендуемое расположение вьюх.
 *
 * Получение данных из справочника для выбиралок делаем через mapData() (метод можно перекрывать по необходимости, см. Mcc)
 *
 * @package app\models\references
 *
 * @property int $usedCount Количество объектов, использующих это значение справочника
 */
class Reference extends ActiveRecord implements ReferenceInterface {
	use ARExtended;

	public $menuCaption = "Справочник";
	public $menuIcon = "/img/admin/references.png";

	/**
	 * @return string
	 * @throws RuntimeException
	 */
	public static function tableName():string {
		throw new RuntimeException('Забыли определить имя таблицы, вот олухи');
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['id', 'deleted', 'usedCount'], 'integer'],
			[['name'], 'string', 'max' => 256],
			[['value'], 'string', 'max' => 512]
		];
	}

	/**
	 * @param $path
	 * @return array
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetReferencesList($path):array {
		$result = [];

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Yii::getAlias($path)), RecursiveIteratorIterator::SELF_FIRST);
		/** @var RecursiveDirectoryIterator $file */
		foreach ($files as $file) {
			if ($file->isFile() && 'php' === $file->getExtension() && false !== $model = Magic::GetReferenceModel($file->getRealPath())) {
				$result[] = $model;
			}
		}
		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Удалёно',
			'usedCount' => 'Использований'
		];
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * @param string $class_name
	 * @return Reference
	 * @throws ServerErrorHttpException
	 * @throws Throwable
	 */
	public static function getReferenceClass($class_name):Reference {
		$class = 'app\models\references\refs\\'.$class_name;

		if (!class_exists($class)) {
			throw new ServerErrorHttpException("Отсутствует класс $class");
		}

		return new $class;
	}

	/**
	 * Набор колонок для отображения на главной
	 * @return array
	 */
	public function getColumns():array {
		return [
			[
				'attribute' => 'id',
				'options' => [
					'style' => 'width:36px;'
				]
			],
			[
				'attribute' => 'name',
				'value' => function($model) {
					/** @var self $model */
					return $model->deleted?Html::tag('span', "Удалено:", [
							'class' => 'label label-danger'
						]).$model->name:Html::a($model->name, ['update', 'class' => $model->formName(), 'id' => $model->id]);
				},
				'format' => 'raw'
			],
			'usedCount'
		];
	}

	/**
	 * Набор колонок для отображения на странице просмотра
	 * @return array
	 */
	public function getView_columns():array {
		return $this->columns;
	}

	/**
	 * Если в справочнике требуется редактировать поля, кроме обязательных, то функция возвращает путь к встраиваемой вьюхе, иначе к дефолтной
	 * @return string
	 * @throws InvalidConfigException
	 */
	public function getForm():string {
		$file_path = mb_strtolower($this->formName()).'/_form.php';
		return file_exists(Yii::getAlias("@app/views/admin/references/{$file_path}"))?$file_path:'_form';
	}

	/**
	 * Поиск по модели справочника
	 * @param array $params
	 * @return ActiveQuery
	 */
	public function search($params):ActiveQuery {
		/** @var ActiveQuery $query */
		$query = self::find();
		$this->load($params);
		$query->andFilterWhere(['LIKE', 'name', $this->name]);

		return $query;
	}

	/**
	 * @inheritdoc
	 */
	public static function mapData($sort = true):array {
		return Yii::$app->cache->getOrSet(static::class."MapData".$sort, function() use ($sort) {
			$data = ArrayHelper::map(self::find()->active()->all(), 'id', 'name');
			if ($sort) {
				asort($data);
			}
			return $data;
		});
	}

	/**
	 * @param array $paramsArray
	 * @return boolean
	 */
	public function createRecord($paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if ($this->save()) {
				AlertModel::SuccessNotify();
				self::flushCache();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
		}
		return false;
	}

	/**
	 * @param array $paramsArray
	 * @return bool
	 */
	public function updateRecord($paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if ($this->save()) {
				AlertModel::SuccessNotify();
				self::flushCache();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
		}
		return false;
	}

	/**
	 * Объединяет две записи справочника (все ссылки на fromId ведут на toId, fromId удаляется)
	 * @param int $fromId
	 * @param int $toId
	 * @throws ErrorException
	 */
	public static function merge(int $fromId, int $toId):void {
		throw new ErrorException('Метод merge не имеет реализации по умолчанию');
	}

	/**
	 * @inheritdoc
	 */
	public static function flushCache():void {
		$class = static::class;
		$cacheNames = [
			"{$class}MapData",
			"{$class}MapData1",
			"{$class}DataOptions"
		];
		foreach ($cacheNames as $className) {
			Yii::$app->cache->delete($className);
		}
	}

	/**
	 * Количество объектов, использующих это значение справочника
	 * @return int
	 */
	public function getUsedCount():int {
		return 0;
	}

	/**
	 * @return array|false
	 */
	public function getSearchSort():?array {
		$sortAttributes = [[]];
		foreach ($this->rules() as $rule) {//Сортировать по всему, что вписано в рулесы
			$sortAttributes[] = is_array($rule[0])?$rule[0]:[$rule[0]];
		}
		$sortAttributes = array_unique(array_merge(...$sortAttributes));
		return [
			'defaultOrder' => [
				'id' => SORT_ASC
			],
			'attributes' => $sortAttributes
		];
	}
}
