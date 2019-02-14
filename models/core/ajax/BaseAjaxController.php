<?php
declare(strict_types = 1);

namespace app\models\core\ajax;

use app\models\core\Magic;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

/**
 * Дефолтные методы аяксовых контроллеров (они у нас могут быть в разных модулях), для унификации API
 * Class AjaxController
 * @package app\models\core
 *
 * @property AjaxAnswer $answer
 */
class BaseAjaxController extends Controller {
	private $_answer;

	public function init():void {
		parent::init();
		$this->_answer = new AjaxAnswer();
	}

	/**
	 * {@inheritDoc}
	 */
	public function behaviors():array {
		$controllerActions = Magic::GetControllerActions($this);
		$actions = [];
		foreach ($controllerActions as $controllerAction) {
			$actions[] = Magic::GetActionRequestName($controllerAction);
		}
		return [
			[
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
//					'application/xml' => Response::FORMAT_XML,
//					'text/html' => Response::FORMAT_HTML
				]
			],
			'access' => [
				'class' => AccessControl::class,
				'denyCallback' => function() {
					return null;
				},
				'rules' => [
					[
						'allow' => Yii::$app->user->identity,
						'actions' => $actions,
						'roles' => ['@', '?']
					]
				]
			]
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeAction($action):bool {
		$this->enableCsrfValidation = false;//todo
		return parent::beforeAction($action);
	}

	/**
	 * @return AjaxAnswer
	 */
	public function getAnswer():AjaxAnswer {
		return $this->_answer;
	}

	/**
	 * @param AjaxAnswer $answer
	 */
	public function setAnswer($answer):void {
		$this->_answer = $answer;
	}

}