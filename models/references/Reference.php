<?php

namespace app\models\references;

use app\models\core\LCQuery;
use app\models\core\Magic;
use app\models\core\traits\ARExtended;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\helpers\ArrayHelper;
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
 * Если форма лежит в @app/views/admin/references/{classNameShort}/_form.php, то она подтянется автоматически, так что это рекомендуемое расположение вьюх.
 *
 * Получение данных из справочника для выбиралок делаем через mapData() (метод можно перекрывать по необходимости, см. Mcc)
 *
 * @package app\models\references
 */
class Reference extends ActiveRecord implements ReferenceInterface {
	use ARExtended;

	public $menuCaption = "Справочник";
	public $menuIcon = "/img/admin/references.png";

	/**
	 * @return string
	 * @throws RuntimeException
	 */
	public static function tableName() {
		throw new RuntimeException('Забыли определить имя таблицы, вот олухи');
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['name'], 'required'],
			[['name'], 'unique'],
			[['name'], 'string', 'max' => 255],
			[['deleted'], 'integer']
		];
	}

	/**
	 * @param $path
	 * @return array
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
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Удалёно'
		];
	}

	/**
	 * @return LCQuery
	 */
	public static function find() {
		return new LCQuery(static::class);
	}

	/**
	 * @param string $class_name
	 * @return Reference
	 * @throws ServerErrorHttpException
	 * @throws Throwable
	 */
	public static function getReferenceClass($class_name) {
		$class = 'app\models\references\refs\\'.$class_name;

		if (!class_exists($class)) {
			throw new ServerErrorHttpException("Отсутствует класс $class");
		}

		return new $class;
	}

	/**
	 * @inheritdoc
	 */
	public function getRef_name() {
		return 'Справочник';
	}

	/**
	 * Набор колонок для отображения на главной
	 * @return array
	 */
	public function getColumns() {
		return [
			'id',
			[
				'attribute' => 'name',
				'value' => function($model) {
					/** @var Reference $model */
					return $model->deleted?"<span class='label label-danger'>Удалено</span> {$model->name}":$model->name;
				},
				'format' => 'raw'
			]
		];
	}

	/**
	 * Набор колонок для отображения на странице просмотра
	 * @return array
	 */
	public function getView_columns() {
		return $this->columns;
	}

	/**
	 * Если в справочнике требуется редактировать поля, кроме обязательных, то функция возвращает путь к встраиваемой вьюхе, иначе к дефолтной
	 * @return string
	 */
	public function getForm() {
		$file_path = mb_strtolower($this->classNameShort).'/_form.php';
		return file_exists(Yii::getAlias("@app/views/{$file_path}"))?$file_path:'_form';
	}

	/**
	 * @inheritdoc
	 */
	public function getTitle() {
		return $this->name;
	}

	/**
	 * Поиск по модели справочника
	 * @param array $params
	 * @return ActiveQuery
	 */
	public function search($params) {
		/** @var ActiveQuery $query */
		$query = self::find();
		$this->load($params);
		$query->andFilterWhere(['LIKE', 'name', $this->name]);

		return $query;
	}

	/**
	 * @inheritdoc
	 */
	public static function mapData($sort = false) {
		$data = ArrayHelper::map(self::find()->active()->all(), 'id', 'name');
		if ($sort) {
			asort($data);
		}

		return $data;
	}
}
