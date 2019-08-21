<?php
declare(strict_types = 1);

namespace app\controllers;

use app\helpers\Utils;
use Yii;
use yii\web\Controller;

/**
 * Class DeployController
 * @package app\controllers
 */
class DeployController extends Controller {

	/**
	 * @return void
	 */
	public function actionIndex():void {
		$this->layout = false;
		$repo_dir = '/var/www/hr/';
		$git_bin_path = 'git';
		$migrate_command = 'php yii migrate up --interactive=0';
		$flushing_cache = 'php yii cache/flush-all';
//		$commit_hash = 'git rev-parse HEAD > /var/www/hr/commit.hash';

		$output = [];
		exec("cd $repo_dir && $git_bin_path pull", $output);
		$output = implode("\n", $output);
		Utils::fileLog($output, 'Webhook triggered:', "deploy.log");
		echo $output."\n";
		$output = [];
		exec("cd $repo_dir && $migrate_command", $output);
		$output = implode("\n", $output);
		Utils::fileLog($output, 'Applying migrations:', "deploy.log");
		echo $output."\n";
		$output = [];
		exec("cd $repo_dir && $flushing_cache", $output);
		$output = implode("\n", $output);
		Utils::fileLog($output, 'Flushing cache:', "deploy.log");
		echo $output."\n";
	}

	/**
	 * Деплой!
	 */
	public function actionIndexDebug():void {
		echo Yii::$app->request->rawBody;

		if (isset(Yii::$app->request->rawBody)) {
			$payload = Yii::$app->request->rawBody;
		} else {
			die ('No candy');
		}

		$this->layout = false;
		file_put_contents('deploy.log', date('m/d/Y h:i:s a')."$payload\n", FILE_APPEND);
		$repo_dir = '/var/www/hr/.git';
		$web_root_dir = '/var/www/hr';

		// Full path to git binary is required if git is not in your PHP user's path. Otherwise just use 'git'.
		$git_bin_path = 'git';

		$update = false;
		$branch = 'undefined';

		// Parse data from Bitbucket hook payload
		$payload = json_decode($payload, true);
		if (isset($payload['commits'])) {
			/** @noinspection ForeachSourceInspection */
			foreach ($payload->commits as $commit) {
				$branch = $commit->branch;
				if ('dev' === $branch || (isset($commit->branches) && in_array('dev', $commit->branches))) {
					$update = true;
					break;
				}
			}
		} else {
			// When merging and pushing to bitbucket, the commits array will be empty.
			// In this case there is no way to know what branch was pushed to, so we will do an update.
			$update = true;
		}

		if ($update) {
			// Do a git checkout to the web root
			exec('cd '.$repo_dir.' && '.$git_bin_path.' fetch');
			exec('cd '.$repo_dir.' && GIT_WORK_TREE='.$web_root_dir.' '.$git_bin_path.' checkout -f');

			// Log the deployment
			$commit_hash = shell_exec('cd '.$repo_dir.' && '.$git_bin_path.' rev-parse --short HEAD');
			file_put_contents('deploy.log', date('m/d/Y h:i:s a').' Deployed branch: '.$branch.' Commit: '.$commit_hash."\n", FILE_APPEND);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function beforeAction($action):bool {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
}
