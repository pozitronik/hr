<?php
declare(strict_types = 1);

namespace app\widgets\ribbon;

use app\models\core\CachedWidget;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Model;

/**
 * Ленточный контрол а-ля MS Word
 * Class RibbonWidget
 * @package app\widgets\ribbon
 *
 * @property RibbonPage[] $pages
 * @property array $options -- set of HTML options of widget container
 */
class RibbonWidget extends CachedWidget {
	public $pages = [];
	public $options = [];

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		RibbonWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		$pageIndex = 1;
		foreach ($this->pages as $page) {
			$page->init($pageIndex);
			$pageIndex++;
		}

		$optionsString = '';

		$class = ArrayHelper::getValue($this->options, 'class', '');

		$this->options['class'] = "panel ribbon $class";

		foreach ($this->options as $optionName => $optionValue) {
			$optionsString .= " {$optionName}='$optionValue' ";
		}

		return $this->render('ribbon', [
			'pages' => $this->pages,
			'options' => $optionsString
		]);
	}
}

/**
 * Class RibbonPage
 * @package app\widgets\ribbon
 *
 * @property bool $active //li active presentation
 * @property-read string $activeString
 * @property-read string $inString
 * @property string $id
 * @property bool $expanded //aria-expanded presentation
 * @property-read string $expandedString
 * @property string $caption //tab header
 * @property string $content //tab contents
 */
class RibbonPage extends Model {
	private $_active;
	private $_id;
	private $_expanded;
	private $_caption;
	private $_content;

	/**
	 * @param int|null $order -- при инициализации массивом передаём порядковый номер для автогенерации значений
	 */
	public function init(?int $order = null):void {
		parent::init();
		if (null !== $order) {
			$this->_id = "tab{$order}";
			$this->_active = 1 === $order;
			$this->_expanded = 1 === $order;
		}
	}

	/**
	 * @return bool
	 */
	public function getActive():bool {
		return $this->_active;
	}

	/**
	 * @param bool $active
	 */
	public function setActive(bool $active):void {
		$this->_active = $active;
	}

	/**
	 * @return string
	 */
	public function getActiveString():string {
		return $this->active?'active':'';
	}

	/**
	 * @return string
	 */
	public function getId():string {
		return $this->_id;
	}

	/**
	 * @param string $id
	 */
	public function setId(string $id):void {
		$this->_id = $id;
	}

	/**
	 * @return bool
	 */
	public function getExpanded():bool {
		return $this->_expanded;
	}

	/**
	 * @param bool $expanded
	 */
	public function setExpanded(bool $expanded):void {
		$this->_expanded = $expanded;
	}

	/**
	 * @return string
	 */
	public function getExpandedString():string {
		return $this->expanded?'true':'false';
	}

	/**
	 * @return string
	 */
	public function getCaption():string {
		return $this->_caption;
	}

	/**
	 * @param string $caption
	 */
	public function setCaption(string $caption):void {
		$this->_caption = $caption;
	}

	/**
	 * @return string
	 */
	public function getContent():string {
		return $this->_content;
	}

	/**
	 * @param string $content
	 */
	public function setContent(string $content):void {
		$this->_content = $content;
	}

	/**
	 * @return string
	 */
	public function getInString():string {
		return $this->active?'in':'';
	}
}