<?php
declare(strict_types = 1);

namespace app\modules\grades\models\references;

use app\modules\references\models\Reference;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $deleted
 */
class RefGradePremiumGroups extends Reference {
	public $menuCaption = 'Премиальные группы';
	public $menuIcon = false;

	protected $_dataAttributes = ['color'];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_grades_premium_groups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['name'], 'required'],
			[['deleted'], 'integer'],
			[['name', 'color'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'deleted' => 'Deleted',
			'color' => 'Цвет',
			'usedCount' => 'Использований'
		];
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
						]).$model->name:Html::tag('span', Html::a($model->name, ['update', 'class' => $model->formName(), 'id' => $model->id]), [
						'style' => "background: {$model->color}"
					]);
				},
				'format' => 'raw'
			],
			[
				'attribute' => 'usedCount'
			]

		];
	}

	/**
	 * Если в справочнике требуется редактировать поля, кроме обязательных, то функция возвращает путь к встраиваемой вьюхе, иначе к дефолтной
	 * @return string
	 * @throws InvalidConfigException
	 * todo
	 */
//	public function getForm():string {
//		/*Из-за того, что мы находимся в контексте Reference, рендер будет искать файлы вьюх в своём каталоге. Не получается разнести вьюхи по модулям.
//		Как решение, можно выдавать не абсолютный путь, а через алиасы
//		*/
//		$file_path = PluginsSupport::GetPluginById(ReferenceLoader::getReferenceByClassName($this->formName())->pluginId)->viewPath.DIRECTORY_SEPARATOR.'references'.DIRECTORY_SEPARATOR.mb_strtolower($this->formName()).'/_form.php';
//		return file_exists($file_path)?$file_path:'_form';
//	}
}
