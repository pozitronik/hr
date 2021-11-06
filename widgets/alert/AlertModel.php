<?php
declare(strict_types = 1);

namespace app\widgets\alert;

use app\models\core\SQueue;
use app\models\user\CurrentUser;
use kartik\growl\Growl;
use yii\base\Model;

/**
 * Модель передачи алертов
 * @property string $type
 * @property string $body
 * @property string $icon
 * @property string|null $title
 * @property string|null $linkUrl
 * @property string $linkTarget
 * @property bool $showSeparator
 * @property int $delay
 * @property bool $useAnimation
 * @property array $pluginOptions
 */
class AlertModel extends Model {
	public const IDENTIFY_MARKER = 'alert_flash';

	private $identify = self::IDENTIFY_MARKER;
	private $type = Growl::TYPE_INFO;
	private $body;
	private $icon = false;
	private $title;
	private $linkUrl;
	private $linkTarget = '_blank';
	private $showSeparator = false;
	private $delay = false;//unlimited delay by default
	private $closeButton = [];
	private $useAnimation = true;
	private $iconOptions = [];
	private $titleOptions = [];
	private $bodyOptions = [];
	private $progressContainerOptions = [];
	private /** @noinspection PropertyCanBeStaticInspection */
		$progressBarOptions = [
		'role' => 'progressbar',
		'aria-valuenow' => '0',
		'aria-valuemin' => '0',
		'aria-valuemax' => '100',
		'style' => '100'
	];
	private $linkOptions = [];
	private $pluginOptions = [
		'showProgressbar' => false,
		'delay' => 3000,
		'placement' => [
			'from' => 'top',
			'align' => 'right'
		]
	];
	private $options = [];

	/**
	 * Форматирует стандартный массив ошибок модели в читаемую строку, чисто для удобства заказчика
	 * @param array $errors
	 * @return string
	 */
	public static function ArrayErrors2String(array $errors):string {
		$array_values = [];
		array_walk_recursive($errors, static function($v, $k) use (&$array_values) {
			if (!empty($v)) {
				$array_values[] = $v;
			}
		});
		return implode(',</br>', $array_values);
	}

	/**
	 * Создаёт модель и пушает её в стек сообщений
	 * @param array $config
	 */
	public static function Notify(array $config = []):void {
		$model = new self($config);
		$model->push();
	}

	/**
	 * Пушает модель в стек алертов
	 */
	public function push():void {
		SQueue::push(CurrentUser::Id(),[
			'identify' => self::IDENTIFY_MARKER,
			'type' => $this->type,
			'title' => $this->title,
			'icon' => $this->icon,
			'body' => $this->body,
			'linkUrl' => $this->linkUrl,
			'linkTarget' => $this->linkTarget,
			'showSeparator' => $this->showSeparator,
			'delay' => $this->delay,
			'useAnimation' => $this->useAnimation
		]);
	}

	/**
	 *
	 */
	public static function SuccessNotify():void {
		self::Notify([
			'type' => Growl::TYPE_SUCCESS,
			'body' => "Успешно"
		]);
	}

	/**
	 * @param array $errors
	 */
	public static function ErrorsNotify(array $errors):void {
		self::Notify([
			'type' => Growl::TYPE_DANGER,
			'body' => self::ArrayErrors2String($errors)
		]);
	}

	/**
	 *
	 */
	public static function AccessNotify():void {
		self::Notify([
			'type' => Growl::TYPE_WARNING,
			'body' => "Нет доступа"
		]);
	}

	/**
	 * @return string
	 */
	public function getType():string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type):void {
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getBody():string {
		return $this->body??$this->type;
	}

	/**
	 * @param string $body
	 */
	public function setBody(string $body):void {
		$this->body = $body;
	}

	/**
	 * @return string
	 */
	public function getIcon():string {
		return (string)$this->icon;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon(string $icon):void {
		$this->icon = $icon;
	}

	/**
	 * @return string|null
	 */
	public function getTitle():?string {
		return $this->title;
	}

	/**
	 * @param string|null $title
	 */
	public function setTitle(?string $title):void {
		$this->title = $title;
	}

	/**
	 * @return null|string
	 */
	public function getLinkUrl():?string {
		return $this->linkUrl;
	}

	/**
	 * @param null|string $linkUrl
	 */
	public function setLinkUrl(?string $linkUrl):void {
		$this->linkUrl = $linkUrl;
	}

	/**
	 * @return string|null
	 */
	public function getLinkTarget():?string {
		return $this->linkTarget;
	}

	/**
	 * @param string $linkTarget
	 */
	public function setLinkTarget(string $linkTarget):void {
		$this->linkTarget = $linkTarget;
	}

	/**
	 * @return bool
	 */
	public function getShowSeparator():bool {
		return $this->showSeparator;
	}

	/**
	 * @param bool $showSeparator
	 */
	public function setShowSeparator(bool $showSeparator):void {
		$this->showSeparator = $showSeparator;
	}

	/**
	 * @return bool|false
	 */
	public function getDelay():bool {
		return $this->delay;
	}

	/**
	 * @param int $delay
	 */
	public function setDelay(int $delay):void {
		$this->delay = $delay;
	}

	/**
	 * @return bool
	 */
	public function getUseAnimation():bool {
		return $this->useAnimation;
	}

	/**
	 * @param bool $useAnimation
	 */
	public function setUseAnimation(bool $useAnimation):void {
		$this->useAnimation = $useAnimation;
	}

	/**
	 * @return array
	 */
	public function getPluginOptions():array {
		return $this->pluginOptions;
	}

	/**
	 * @param array $pluginOptions
	 */
	public function setPluginOptions(array $pluginOptions):void {
		$this->pluginOptions = $pluginOptions;
	}

	/**
	 * @return array
	 */
	public function getLinkOptions():array {
		return $this->linkOptions;
	}

	/**
	 * @param array $linkOptions
	 */
	public function setLinkOptions(array $linkOptions):void {
		$this->linkOptions = $linkOptions;
	}

	/**
	 * @return array
	 */
	public function getCloseButton():array {
		return $this->closeButton;
	}

	/**
	 * @param array $closeButton
	 */
	public function setCloseButton(array $closeButton):void {
		$this->closeButton = $closeButton;
	}

	/**
	 * @return array
	 */
	public function getIconOptions():array {
		return $this->iconOptions;
	}

	/**
	 * @param array $iconOptions
	 */
	public function setIconOptions(array $iconOptions):void {
		$this->iconOptions = $iconOptions;
	}

	/**
	 * @return array
	 */
	public function getTitleOptions():array {
		return $this->titleOptions;
	}

	/**
	 * @param array $titleOptions
	 */
	public function setTitleOptions(array $titleOptions):void {
		$this->titleOptions = $titleOptions;
	}

	/**
	 * @return array
	 */
	public function getBodyOptions():array {
		return $this->bodyOptions;
	}

	/**
	 * @param array $bodyOptions
	 */
	public function setBodyOptions(array $bodyOptions):void {
		$this->bodyOptions = $bodyOptions;
	}

	/**
	 * @return array
	 */
	public function getProgressContainerOptions():array {
		return $this->progressContainerOptions;
	}

	/**
	 * @param array $progressContainerOptions
	 */
	public function setProgressContainerOptions(array $progressContainerOptions):void {
		$this->progressContainerOptions = $progressContainerOptions;
	}

	/**
	 * @return array
	 */
	public function getProgressBarOptions():array {
		return $this->progressBarOptions;
	}

	/**
	 * @param array $progressBarOptions
	 */
	public function setProgressBarOptions(array $progressBarOptions):void {
		$this->progressBarOptions = $progressBarOptions;
	}

	/**
	 * @return array
	 */
	public function getOptions():array {
		return $this->options;
	}

	/**
	 * @param array $options
	 */
	public function setOptions(array $options):void {
		$this->options = $options;
	}

	/**
	 * @return string
	 */
	public function getIdentify():string {
		return $this->identify;
	}

	/**
	 * @param string $identify
	 */
	public function setIdentify(string $identify):void {
		$this->identify = $identify;
	}

}


