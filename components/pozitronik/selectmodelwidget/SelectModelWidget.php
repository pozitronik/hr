<?php
declare(strict_types = 1);

namespace app\components\pozitronik\selectmodelwidget;

use app\components\pozitronik\core\models\lcquery\LCQuery;
use app\components\pozitronik\core\traits\ARExtended;
use Exception;
use kartik\base\InputWidget;
use kartik\select2\Select2;
use app\components\pozitronik\core\interfaces\widgets\SelectionWidgetInterface;
use app\components\pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * Class SelectModelWidget
 *
 * @property ActiveRecordInterface $selectModel Класс модели, по которой будем выбирать данные
 * @property array $exclude Записи, исключаемые из выборки. Массив id, либо массив элементов, либо ActiveQuery-условие
 * @property string $mapAttribute Названия атрибута, который будет отображаться на выбиралку
 * @property string|null $pkName Имя ключевого атрибута модели, если не указано -- подберётся автоматически
 * @property string $postUrl Путь к экшену постинга формы/ajax-постинга.
 * @property int $ajaxMinimumInputLength Количество симоволов для старта поиска при аксовом режиме
 * @property string $ajaxSearchUrl Путь к экшену ajax-поиска.
 * @property int $loadingMode self::DATA_MODE_AJAX -- фоновая загрузка, DATA_MODE_LOAD -- вычисляемые данные
 * @property int $renderingMode see SelectionWidgetInterface modes constants
 * @property boolean $multiple true by default
 *
 * @property string $jsPrefix костыль для призыва нужных JS-функций в ассетах потомков
 *
 * @todo: в случае, если виджет используется для редактирования в режиме DATA_MODE_AJAX, то имеющиеся связи будут отображены, как айдишники. Это нужно поправить.
 * @todo: добавить перечисление допустимых режимов потомка, чтобы виджеты, не поддурживающие аяксовый поиск сообщали об этом
 */
class SelectModelWidget extends InputWidget implements SelectionWidgetInterface {
	//private $data = [];//calculated/evaluated/received data array
	private $ajaxPluginOptions = [];//calculated select2 ajax parameters
	/** @var ARExtended|ActiveRecordInterface $loadedClass */
	protected $loadedClass;

	public $pkName;//primary key name for selectModel
	public $selectModel;
	public $exclude = [];
	public $mapAttribute = 'name';
	public $postUrl = '';
	public $ajaxMinimumInputLength = 1;
	public $ajaxSearchUrl;

	public $loadingMode = self::DATA_MODE_LOAD;
	public $renderingMode = self::MODE_FIELD;
	public $multiple = true;
	public $jsPrefix = '';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		SelectModelWidgetAssets::register($this->getView());
		if (null === $this->selectModel) {
			throw new InvalidConfigException('$selectModel parameter is required');
		}

		$this->loadedClass = Yii::createObject($this->selectModel);
		if (!($this->loadedClass instanceof ActiveRecordInterface)) {
			throw new InvalidConfigException("{$this->selectModel} must be a instance of ActiveRecordExtended");
		}
		$this->pkName = $this->pkName??$this->loadedClass::pkName();
		if (null === $this->pkName) {
			throw new InvalidConfigException("{$this->selectModel} must have primary key and it should not be composite");
		}

		$this->options['id'] = isset($this->options['id'])?$this->options['id'].$this->model->primaryKey:Html::getInputId($this->model, $this->attribute).$this->model->primaryKey;
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Exception
	 * @throws Throwable
	 */
	public function run():string {
		if (self::DATA_MODE_AJAX === $this->loadingMode) {
			$this->ajaxPluginOptions = [
				'minimumInputLength' => $this->ajaxMinimumInputLength,
				'ajax' => [
					'url' => $this->ajaxSearchUrl,
					'dataType' => 'json',
					'data' => new JsExpression("function(params) { return {term:params.term, page: params.page}; }")
				]
			];

		} elseif ([] === $this->data) {
			/** @var LCQuery $selectionQuery */
			$selectionQuery = $this->loadedClass::find()->active();
			if (is_array($this->exclude)) {
				if ([] !== $this->exclude) {
					if ($this->exclude[0] instanceof ActiveRecordInterface) {
						$this->exclude = ArrayHelper::getColumn($this->exclude, $this->pkName);
					}
					$selectionQuery->where(['not in', $this->pkName, $this->exclude]);
				}
			} elseif ($this->exclude instanceof LCQuery) {
				$selectionQuery->{$this->exclude};
			}

			$this->data = ArrayHelper::map($selectionQuery->all(), $this->pkName, $this->mapAttribute);
		}

		if (method_exists($this->loadedClass, 'dataOptions')) {//если у модели есть опции для выбиралки, присунем их к стандартным опциям
			$this->options['options'] = ArrayHelper::merge(ArrayHelper::getValue($this->options, 'options', []), $this->loadedClass::dataOptions());
		}

		$pluginOptions = [//во всех вариантах одинаково
				'allowClear' => true,
				'multiple' => $this->multiple,
				'language' => 'ru',
				'templateResult' => (self::DATA_MODE_AJAX === $this->loadingMode)?new JsExpression('function(item) {return '.$this->jsPrefix.'TemplateResultAJAX(item)}'):new JsExpression('function(item) {return '.$this->jsPrefix.'TemplateResult(item)}'),
				'escapeMarkup' => new JsExpression('function(markup) {return '.$this->jsPrefix.'EscapeMarkup(markup);}')
			] + $this->ajaxPluginOptions;

		switch ($this->renderingMode) {
			default:
			case self::MODE_FIELD:
				return Select2::widget([
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $this->data,
					'options' => $this->options,
					'pluginOptions' => $pluginOptions
				]);
			break;
			case self::MODE_FORM://fixme: не используем режим формы, он глючит. Пока такой фикс
				return $this->render('@app/components/pozitronik/selectmodelwidget/views/form', [
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $this->data,
					'postUrl' => $this->postUrl,
					'options' => $this->options,
					'pluginOptions' => $pluginOptions
				]);
			break;
			case self::MODE_AJAX:
				return Select2::widget([
					'model' => $this->model,
					'attribute' => $this->attribute,
					'data' => $this->data,
					'options' => $this->options,
					'pluginOptions' => $pluginOptions,
					'pluginEvents' => [
						"change.select2" => "function(e) {ajax_submit_toggle(e,'ajax_post_button')}"
					],
					'addon' => [
						'append' => [
							'content' => Html::button("<i class='fa fa-plus'></i>", ['id' => 'ajax_post_button', 'class' => 'btn btn - primary', 'disabled' => 'disabled', 'onclick' => "ajax_post('$this->postUrl', 'ajax_post_button', {$this->pkName})"]),
							'asButton' => true
						]
					],
				]);
			break;
		}
	}
}
