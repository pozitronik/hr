<?php /** @noinspection BadExceptionsProcessingInspection */
declare(strict_types = 1);

namespace app\modules\import\models\beeline;

use app\components\pozitronik\core\traits\Upload;
use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\import\models\beeline\active_record\ImportBeelineBoss;
use app\modules\import\models\beeline\active_record\ImportBeelineBranch;
use app\modules\import\models\beeline\active_record\ImportBeelineBusinessBlock;
use app\modules\import\models\beeline\active_record\ImportBeelineCommand;
use app\modules\import\models\beeline\active_record\ImportBeelineDecomposed;
use app\modules\import\models\beeline\active_record\ImportBeelineDepartment;
use app\modules\import\models\beeline\active_record\ImportBeelineDirection;
use app\modules\import\models\beeline\active_record\ImportBeelineFunctionalBlock;
use app\modules\import\models\beeline\active_record\ImportBeelineGroup;
use app\modules\import\models\beeline\active_record\ImportBeelineProductOwner;
use app\modules\import\models\beeline\active_record\ImportBeelineService;
use app\modules\import\models\beeline\active_record\ImportBeelineTribe;
use app\modules\import\models\beeline\active_record\ImportBeelineTribeLeader;
use app\modules\import\models\beeline\active_record\ImportBeelineUsers;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Throwable;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Class ImportBeeline
 *
 * @property int $id
 * @property string $user_tn
 * @property string $user_name
 * @property string $business_block
 * @property string $functional_block
 * @property string $direction
 * @property string $department
 * @property string $service
 * @property string $branch
 * @property string $group
 * @property string $ceo_level
 * @property string $user_type
 * @property string $position_name
 * @property string $administrative_boss_name
 * @property string $administrative_boss_position_name
 * @property string $functional_boss_name
 * @property string $functional_boss_position_name
 * @property string $affiliation
 * @property string $position_profile_number
 * @property string $is_boss
 * @property string $company_code
 * @property string $cbo
 * @property string $location
 * @property string $commentary
 * @property string $tribe
 * @property string $tribe_leader
 * @property string $command
 * @property string $product_owner
 * @property int $domain
 */
class ImportBeeline extends ActiveRecord {
	use Upload;

	public const STEP_REFERENCES = 0;
	public const STEP_USERS = 1;
	public const STEP_GROUPS = 2;
	public const STEP_BOSSES = 3;
	public const STEP_FINISH = 4;
	public const LAST_STEP = self::STEP_FINISH + 1;

	public const step_labels = [
		self::STEP_REFERENCES => '???????????????????????? ???????????????????? ????????????',
		self::STEP_USERS => '???????????????????????? ??????????????????????????',
		self::STEP_GROUPS => '???????????????????????? ??????????',
		self::STEP_BOSSES => '???????????????????????? ??????????????????????????',
		self::STEP_FINISH => '???????????????? ????????????',
		self::LAST_STEP => '????????????!'
	];

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'import_beeline';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['domain'], 'integer'],
			[['business_block', 'functional_block', 'direction', 'department', 'service', 'branch', 'group', 'ceo_level',
				'user_type', 'position_name', 'user_name', 'user_tn', 'administrative_boss_name', 'administrative_boss_position_name',
				'functional_boss_name', 'functional_boss_position_name', 'affiliation', 'position_profile_number', 'is_boss',
				'company_code', 'cbo', 'location', 'commentary', 'tribe', 'tribe_leader', 'command', 'product_owner'], 'string', 'max' => 255],
			['user_tn', 'filter', 'filter' => function($attribute) {
				return is_numeric($attribute)?$attribute:null;
			}],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [/*???? ????????????????????????????, ?? ???? ???????????? ????????????!*/
			'user_tn' => '?????????????????? ??????????',
			'user_name' => '?????? ????????????????????',
			'business_block' => '????????????-????????',
			'functional_block' => '???????????????????????????? ????????',
			'direction' => '????????????????',
			'department' => '??????????????????????',
			'service' => '????????????',
			'branch' => '??????????',
			'group' => '????????????',
			'ceo_level' => 'CEO-level',
			'user_type' => '??????',
			'position_name' => '???????????????? ??????????????????',
			'administrative_boss_name' => '?????? ?????????????????????????????????? ????????????????????????',
			'administrative_boss_position_name' => '?????????????????? ?????????????????????????????????? ????????????????????????',
			'functional_boss_name' => '?????? ?????????????????????????????? ????????????????????????',
			'functional_boss_position_name' => '?????????????????? ?????????????????????????????? ????????????????????????',
			'affiliation' => '?????????????????????? ????????????????????????????',
			/*?????????? ????????????????*/
			'position_profile_number' => '?????????? ?????????????? ??????????????????',
			'is_boss' => '????????????????????????',
			'company_code' => '?????? ????????????????',
			'cbo' => '??????',
			'location' => '??????????????',
			'commentary' => '??????????????????????',
			/*????????????????, ?????????????? ?????????????? ???? ?????????? ??????????????????????*/
			'tribe' => '??????????',
			'tribe_leader' => '?????????? ????????????',
			'command' => '??????????????',
			'product_owner' => '???????????????? ????????????????'
		];
	}

	/**
	 * ??????????????????, ???????????????? ???? ???????????? ???????????????????? ??????????????
	 * @param array $row
	 * @return bool
	 * @throws Throwable
	 */
	private static function isHeaderRow(array $row):bool {
		return ArrayHelper::getValue($row, 0) === ArrayHelper::getValue((new self())->attributeLabels(), 'user_tn');
	}

	/**
	 * @param string $filename
	 * @param int|null $domain
	 * @return bool
	 * @throws Throwable
	 */
	public static function Import(string $filename, ?int $domain = null):bool {
		try {
			$reader = new Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($filename);
			$spreadsheet->setActiveSheetIndex(0);
			$dataArray = $spreadsheet->getActiveSheet()->toArray();
		} catch (Throwable) {
			throw new Exception('???????????? ?????????? ???? ????????????????????????????');
		}
		$domain = $domain??time();
		$labels = (new self())->attributeLabels();
		$keys = array_keys($labels);
		$headerProcessedFlag = false;
		$cKeys = count($keys);
		foreach ($dataArray as $importRow) {
			$is_header = false;
			if (!$headerProcessedFlag && (true === $is_header = self::isHeaderRow($importRow))) {//???????????????????? ?????????????????? ???????????????????? ??????????????
				$columnHeaderIndex = 0;
				foreach (array_slice($labels, 0, 23) as $value) {
					if ($value !== $headerValue = ArrayHelper::getValue($importRow, $columnHeaderIndex)) {
						throw new Exception("?????????????????????? ???????????? ?????????? ??????????????. ?????????????? {$columnHeaderIndex}, ?????????????????? ??????????????????: {$value}, ?? ??????????: {$headerValue}.");
					}
					$columnHeaderIndex++;
				}
				$headerProcessedFlag = true;
			}
			if ($is_header) continue;//???????????????????? ??????????????????
			$cRow = count($importRow);
			$t = $cKeys - $cRow;
			for ($x = 0; $x !== $t; $x++) {//????????????????
				$importRow[] = '';
			}
			$importRow = array_slice($importRow, 0, $cKeys);//?? ???????????????? ?????????? ???????? ???? ???????? ???????????? ????????????????

			$data = array_combine($keys, $importRow);
			$data = array_map(function($value) {//?????? ???????????????????? ?? ?string, ?????????? ???????????????? ???????????????? ??????????????????
				return empty($value)?null:(string)$value;
			}, $data);

			$row = new self($data);
			$row->domain = $domain;
			$row->save();//???????? ?????????????????? ?????? ?????????????? ??????????????????
		}
		return true;
	}

	/**
	 * ?????????????????????? ?????????????????????? ????????????, ???????????????????????? ???????????? ???? ???????????????? ?? ?????????????????? ?????????????? ?????????????? ??????????????
	 * @param int $domain
	 * @param int $step
	 * @param array $messages ???????????? ??????????????????
	 * @return int ?????????????? ?????????????????????? ??????
	 */
	public static function Decompose(int $domain, int $step = self::STEP_REFERENCES, array &$messages = []):int {
		/** @var self[] $data */
		$data = self::find()->where(['domain' => $domain])->all();

		switch ($step) {
			case self::STEP_REFERENCES: /*?? ?????? ???????? ?????? ????????????????????????  */
			break;
			case self::STEP_USERS:
				foreach ($data as $row) {
					try {
						ImportBeelineUsers::addInstance(['user_tn' => $row->user_tn, 'domain' => $row->domain], [
							'user_tn' => $row->user_tn,
							'name' => $row->user_name,
							'position' => $row->position_name,
							'level' => abs((int)filter_var($row->ceo_level, FILTER_SANITIZE_NUMBER_INT)),
							'user_type' => $row->user_type,
							'affiliation' => $row->affiliation,
							'position_profile_number' => $row->position_profile_number,
							'company_code' => $row->company_code,
							'cbo' => $row->cbo,
							'location' => $row->location,
							'is_boss' => ('????' === $row->is_boss),
							'domain' => $row->domain
						]);
						/**
						 * ?????????????????????????? ???????????????? ?????????????????????????????????? ?? ?????????????????????????????? ??????????????????????????
						 * ????????????????????????????, ?????? ???? "????????????????????????" ?????? ???????? ?? ??????????????, ?????????? ?????????????????? ?? ??????, ?????? ?????? ????????????????.
						 */
					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}
			break;
			case self::STEP_BOSSES:
				/**
				 * ?????????????????????????? ???????????????? ?????????????????????????????????? ???????????????????????? (?????????????????????????????? ???????? ???? ??????????????)
				 * ?????? ???????????????????????? ???????????????????? ???????? ?????????????????????????? ???????????????? ?? ?????????????????????????????? ????????????.
				 */
				foreach ($data as $row) {
					try {
						ImportBeelineBoss::addInstance(['name' => $row->administrative_boss_name, 'domain' => $row->domain], [
							'name' => $row->administrative_boss_name,
							'position' => $row->administrative_boss_position_name,
							'level' => abs((int)filter_var($row->ceo_level, FILTER_SANITIZE_NUMBER_INT)) - 1,
							'domain' => $row->domain
						]);

						$tribe_leader_user_id = ImportBeelineUsers::addInstance(['name' => $row->tribe_leader], [
							'name' => $row->tribe_leader,
							'domain' => $row->domain
						])?->id;

						ImportBeelineTribeLeader::addInstance(['user_id' => $tribe_leader_user_id], [
							'user_id' => $tribe_leader_user_id,
							'domain' => $row->domain
						]);

						$product_owner_user_id = ImportBeelineUsers::addInstance(['name' => $row->product_owner], [
							'name' => $row->product_owner,
							'domain' => $row->domain
						])?->id;

						ImportBeelineProductOwner::addInstance(['user_id' => $product_owner_user_id], [
							'user_id' => $product_owner_user_id,
							'domain' => $row->domain
						]);
					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}
			break;
			case self::STEP_GROUPS:
				/**
				 * ?????????????????????????? ???????????????? ??????????: ???? ????????????-?????????? ???? ????????????
				 */
				foreach ($data as $row) {
					try {
						ImportBeelineBusinessBlock::addInstance(['name' => $row->business_block], [
							'name' => $row->business_block,
							'domain' => $row->domain
						]);
						ImportBeelineFunctionalBlock::addInstance(['name' => $row->functional_block], [
							'name' => $row->functional_block,
							'domain' => $row->domain
						]);
						ImportBeelineDirection::addInstance(['name' => $row->direction], [
							'name' => $row->direction,
							'domain' => $row->domain
						]);
						ImportBeelineDepartment::addInstance(['name' => $row->department], [
							'name' => $row->department,
							'domain' => $row->domain
						]);
						ImportBeelineService::addInstance(['name' => $row->service], [
							'name' => $row->service,
							'domain' => $row->domain
						]);
						ImportBeelineBranch::addInstance(['name' => $row->branch], [
							'name' => $row->branch,
							'domain' => $row->domain
						]);
						ImportBeelineGroup::addInstance(['name' => $row->group], [
							'name' => $row->group,
							'domain' => $row->domain
						]);
						ImportBeelineTribe::addInstance(['name' => $row->tribe], [
							'name' => $row->tribe,
							'user_id' => ImportBeelineUsers::findModelAttribute(['name' => $row->tribe_leader, 'domain' => $domain], 'id'),
							'domain' => $row->domain
						]);
						ImportBeelineCommand::addInstance(['name' => $row->command], [
							'name' => $row->command,
							'user_id' => ImportBeelineUsers::findModelAttribute(['name' => $row->product_owner, 'domain' => $domain], 'id'),
							'domain' => $row->domain
						]);

					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
			case self::STEP_FINISH:

				/* ?????????????????????? ?????????????????? ?????????????????????????????????? ?????????????????? ?? ?????????????????????????????? ?????????????? */
				foreach ($data as $row) {
					try {
						$decomposedRow = new ImportBeelineDecomposed([
							'domain' => $row->domain,
							'user_id' => ImportBeelineUsers::findModelAttribute(['user_tn' => $row->user_tn, 'domain' => $domain], 'id'),
							'business_block_id' => ImportBeelineBusinessBlock::findModelAttribute(['name' => $row->business_block], 'id'),
							'functional_block_id' => ImportBeelineFunctionalBlock::findModelAttribute(['name' => $row->functional_block], 'id'),
							'direction_id' => ImportBeelineDirection::findModelAttribute(['name' => $row->direction], 'id'),
							'department_id' => ImportBeelineDepartment::findModelAttribute(['name' => $row->department], 'id'),
							'service_id' => ImportBeelineService::findModelAttribute(['name' => $row->service], 'id'),
							'branch_id' => ImportBeelineBranch::findModelAttribute(['name' => $row->branch], 'id'),
							'group_id' => ImportBeelineGroup::findModelAttribute(['name' => $row->group], 'id'),
							'tribe_id' => ImportBeelineTribe::findModelAttribute(['name' => $row->tribe], 'id'),
							'command_id' => ImportBeelineCommand::findModelAttribute(['name' => $row->command], 'id'),
						]);
						$decomposedRow->save();
					} catch (Throwable $throwable) {
						$messages[] = ['row' => $row, 'error' => $throwable->getMessage()];
					}
				}

			break;
		}
		return $step;
	}
}