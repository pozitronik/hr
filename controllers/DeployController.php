<?php
declare(strict_types = 1);

namespace app\controllers;

use pozitronik\helpers\Utils;
use yii\web\Controller;

/**
 * Class DeployController
 * @package app\controllers
 */
class DeployController extends Controller {

	private $REPO_DIR = '/var/www/hr/';
	private $GIT_BIN = 'git';
	private $MIGRATE_CMD = 'php yii migrate up --interactive=0';
	private $FLUSH_CMD = 'php yii cache/flush-all';

	/**
	 * @return void
	 */
	public function actionIndex():void {
		$this->layout = false;
//		$commit_hash = 'git rev-parse HEAD > /var/www/hr/commit.hash';
		$output = [];
		exec("cd $this->REPO_DIR && $this->GIT_BIN pull", $output);
		$output = implode("\n", $output);
		Utils::fileLog($output, 'Webhook triggered:', "deploy.log");
		echo $output."\n";
		$output = [];
		exec("cd $this->REPO_DIR && $this->MIGRATE_CMD", $output);
		$output = implode("\n", $output);
		Utils::fileLog($output, 'Applying migrations:', "deploy.log");
		echo $output."\n";
		$output = [];
		exec("cd $this->REPO_DIR && $this->FLUSH_CMD", $output);
		$output = implode("\n", $output);
		Utils::fileLog($output, 'Flushing cache:', "deploy.log");
		echo $output."\n";
	}

	public function actionFlush():void {
		$this->layout = false;
		$output = [];
		exec("cd $this->REPO_DIR && $this->FLUSH_CMD", $output);
		$output = implode("\n", $output);
		Utils::fileLog($output, 'Flushing cache:', "deploy.log");
		echo $output."\n";
	}

	/**
	 * @inheritdoc
	 */
	public function beforeAction($action):bool {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
}
