<?php
declare(strict_types = 1);

namespace app\models\groups;

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use app\models\groups\traits\Graph;
use app\widgets\alert\AlertModel;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserRoles;
use app\models\relations\RelGroupsGroups;
use app\models\relations\RelUsersGroups;
use app\models\relations\RelUsersGroupsRoles;
use app\models\user\CurrentUser;
use app\models\users\Users;
use Throwable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $name Название
 * @property integer $type Тип группы
 * @property string $comment Описание
 * @property integer|null $daddy Пользователь, создавший группу
 * @property string $logotype Название файла-логотипа
 * @property ActiveQuery|Users[] $relUsers Пользователи в группе
 * @property ActiveQuery|RelUsersGroups[] $relUsersGroups Связь с релейшеном пользователей
 * @property ActiveQuery|Groups[] $relChildGroups Группы, дочерние по отношению к текущей
 * @property-write array $dropChildGroups Свойство для передачи массива отлинкуемых дочерних групп
 * @property ActiveQuery|RelGroupsGroups[] $relGroupsGroupsParent Релейшен групп для получения дочерних групп
 * @property array $dropParentGroups Свойство для передачи массива отлинкуемых родительских групп
 * @property ActiveQuery|RelGroupsGroups[] $relGroupsGroupsChild Релейшен групп для получения родительских групп
 * @property ActiveQuery|Groups[] $relParentGroups Группы, родительские по отношению к текущей
 * @property ActiveQuery|RefGroupTypes $relGroupTypes Тип группы через релейшен
 *
 * @property-read Users[] $leaders Пользюки, прописанне в группе с релейшеном лидера (владелец/руководитель)
 * @property-read Users|null $leader Один пользователь из лидеров (для презентации)
 * @property ActiveQuery|RefUserRoles[] $relRefUserRoles
 * @property RelUsersGroupsRoles[]|ActiveQuery $relUsersGroupsRoles
 * @property array $rolesInGroup
 * @property array $dropUsers
 * @property boolean $deleted
 * @property LCQuery $relUsersHierarchy Пользователи во всех группах вниз по иерархии
 * @property-read string $logo Полный путь к логотипу/дефолтной картинке
 *
 * @property-read integer $usersCount Количество пользователей в группе
 *
 */
class Groups extends ActiveRecord {
	use ARExtended;
	use Graph;
	public const OWNER = 4;
	public const LEADER = 1;
	public const LEADER_IT = 2;
//	public const TRIBE = 6;
//	public const CHAPTER = 5;

	public const LOGO_IMAGE_DIRECTORY = '@app/web/group_logotypes/';

	/*Переменная для инстанса заливки логотипа*/
	public $upload_image;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_groups';
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['comment'], 'string'],
			[['daddy', 'type'], 'integer'],
			[['create_date'], 'safe'],
			[['deleted'], 'boolean'],
			[['name'], 'string', 'max' => 512],
			[['logotype'], 'string', 'max' => 255],
			[['relChildGroups', 'dropChildGroups', 'relParentGroups', 'dropParentGroups', 'relUsers', 'dropUsers'], 'safe'],
			[['upload_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize' => 1048576]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'type' => 'Тип группы',
			'comment' => 'Описание',
			'daddy' => 'Создатель',
			'create_date' => 'Дата создания',
			'leaders' => 'Руководители',
			'logotype' => 'Логотип',
			'upload_image' => 'Логотип',
			'deleted' => 'Deleted',
			'usersCount' => 'Количество пользователей'
		];
	}

	/**
	 * @return ActiveQuery|RelUsersGroups[]
	 */
	public function getRelUsersGroups() {
		return $this->hasMany(RelUsersGroups::class, ['group_id' => 'id']);
	}

	/**
	 * @return ActiveQuery|Users[]|LCQuery
	 */
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersGroups');
	}

	/**
	 * @param array $relGroupsUsers
	 * @throws Throwable
	 */
	public function setRelUsers($relGroupsUsers):void {
		RelUsersGroups::linkModels($relGroupsUsers, $this);
	}

	/**
	 * @param array $dropUsers
	 * @throws Throwable
	 */
	public function setDropUsers(array $dropUsers):void {
		RelUsersGroupsRoles::deleteAll(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $this->id, 'user_id' => $dropUsers])->select('id')]);
		RelUsersGroups::unlinkModels($dropUsers, $this);
	}

	/**
	 * Релейшен к назначению ролей в этой группе
	 * @return ActiveQuery|RelUsersGroupsRoles[]
	 */
	public function getRelUsersGroupsRoles() {
		return $this->hasMany(RelUsersGroupsRoles::class, ['user_group_id' => 'id'])->via('relUsersGroups');
	}

	/**
	 * Все назначенные роли в этой группе
	 * @return ActiveQuery|RefUserRoles[]
	 */
	public function getRelRefUserRoles() {
		return $this->hasMany(RefUserRoles::class, ['id' => 'role'])->via('relUsersGroupsRoles');
	}

	/**
	 * @param array $paramsArray
	 * @return bool
	 * @throws Exception
	 */
	public function createGroup($paramsArray):bool {
		$transaction = self::getDb()->beginTransaction();
		if ($this->loadArray($paramsArray)) {
			$this->updateAttributes([
				'daddy' => CurrentUser::Id(),
				'create_date' => Date::lcDate(),
				'deleted' => false
			]);
			if ($this->save()) {/*Возьмём разницу атрибутов и массива параметров - в нем будут новые атрибуты, которые теперь можно заполнить*/
				$this->loadArray(ArrayHelper::diff_keys($this->attributes, $paramsArray));
				/** @noinspection NotOptimalIfConditionsInspection */
				if ($this->save()) {
					$transaction->commit();
					AlertModel::SuccessNotify();
					return true;
				}
				AlertModel::ErrorsNotify($this->errors);
			}
		}
		$transaction->rollBack();
		return false;
	}

	/**
	 * @param array $paramsArray
	 * @return bool
	 */
	public function updateGroup($paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			if ($this->save()) {
				AlertModel::SuccessNotify();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
		}
		return false;
	}

	/**
	 * @return ActiveQuery|RelGroupsGroups[]
	 */
	public function getRelGroupsGroupsChild() {
		return $this->hasMany(RelGroupsGroups::class, ['parent_id' => 'id']);
	}

	/**
	 * Вернет все группы, дочерние по отношению к текущей
	 * @return Groups[]|ActiveQuery|LCQuery
	 */
	public function getRelChildGroups() {
		return $this->hasMany(self::class, ['id' => 'child_id'])->via('relGroupsGroupsChild');
	}

	/**
	 * Внесёт группу в релейшен дочерних к текущей
	 * @param $childGroups
	 * @throws Throwable
	 */
	public function setRelChildGroups($childGroups):void {
		RelGroupsGroups::linkModels($this, $childGroups);
	}

	/**
	 * Дропнет дочерние группы
	 * @param array $dropChildGroups
	 * @throws Throwable
	 */
	public function setDropChildGroups(array $dropChildGroups):void {
		RelGroupsGroups::unlinkModels($this, $dropChildGroups);
	}

	/**
	 * @return ActiveQuery|RelGroupsGroups[]
	 */
	public function getRelGroupsGroupsParent() {
		return $this->hasMany(RelGroupsGroups::class, ['child_id' => 'id']);
	}

	/**
	 * Вернет все группы, дочерние по отношению к текущей
	 * @return Groups[]|ActiveQuery|LCQuery
	 */
	public function getRelParentGroups() {
		return $this->hasMany(self::class, ['id' => 'parent_id'])->via('relGroupsGroupsParent');
	}

	/**
	 * Внесёт группу в релейшен дочерних к текущей
	 * @param $parentGroups
	 * @throws Throwable
	 */
	public function setRelParentGroups($parentGroups):void {
		RelGroupsGroups::linkModels($parentGroups, $this);
	}

	/**
	 * Дропнет дочерние группы
	 * @param array $dropParentGroups
	 * @throws Throwable
	 */
	public function setDropParentGroups(array $dropParentGroups):void {
		RelGroupsGroups::unlinkModels($dropParentGroups, $this);
	}

	/**
	 * @return RefGroupTypes|ActiveQuery
	 */
	public function getRelGroupTypes() {
		return $this->hasOne(RefGroupTypes::class, ['id' => 'type']);
	}

	/**
	 * Не очень чёткая логика выбора главнюка
	 * @return Users[]
	 * @throws Throwable
	 */
	public function getLeaders():array {
		return Users::find()->joinWith(['relUsersGroups', 'relUsersGroupsRoles'])->where(['rel_users_groups_roles.role' => self::LEADER, 'rel_users_groups.group_id' => $this->id])->all();
	}

	/**
	 * Если у группы есть лидеры - покажет одного. Презентационная штука.
	 * @return Users
	 */
	public function getLeader():Users {
		return $this->leaders?$this->leaders[0]:new Users();
	}

	/**
	 * Простая функция проверки, является ли пользователь лидером в этой группе
	 * @param Users $user
	 * @return bool
	 * temporary
	 */
	public function isLeader(Users $user):bool {
		return self::find()->joinWith(['relUsersGroups', 'relUsersGroupsRoles'])->where(['rel_users_groups_roles.role' => self::LEADER, 'rel_users_groups.user_id' => $user->id, 'rel_users_groups.group_id' => $this->id])->count() > 0;
	}

	/**
	 * Добавляет массив ролей пользователя к группе
	 * @param array<integer, array<integer>> $userRoles
	 */
	public function setRolesInGroup(array $userRoles):void {
		foreach ($userRoles as $user => $roles) {
			RelUsersGroupsRoles::deleteAll(['user_group_id' => RelUsersGroups::find()->where(['group_id' => $this->id, 'user_id' => $user])->select('id')]);
			/** @var integer[] $roles */
			foreach ($roles as $role) {
				RelUsersGroupsRoles::setRoleInGroup($role, $this->id, $user);
			}
		}
	}

	/**
	 * Пытается подгрузить файл картинки, если он есть
	 * @return bool
	 */
	public function uploadLogotype():bool {
		$uploadedFile = UploadedFile::getInstance($this, 'upload_image');
		if ($uploadedFile && $this->validate('upload_image') && $uploadedFile->saveAs(Yii::getAlias(self::LOGO_IMAGE_DIRECTORY."/{$this->id}.{$uploadedFile->extension}"), false)) {
			$this->setAndSaveAttribute('logotype', "{$this->id}.{$uploadedFile->extension}");
			return true;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function getLogo():string {
		return is_file(Yii::getAlias(self::LOGO_IMAGE_DIRECTORY.$this->logotype))?"/group_logotypes/{$this->logotype}":"/img/group_logo.jpg";
	}

	/**
	 * Собираем рекурсивно айдишники всех групп вниз по иерархии
	 * @return array<int>
	 */
	private function collectRecursiveIds():array {
		$groupsId = [[]];//Сюда соберём айдишники всех обходимых групп
		foreach ((array)$this->relChildGroups as $childGroup) {
			$groupsId[] = $childGroup->collectRecursiveIds();
		}
		$groupsId = array_merge(...$groupsId);
		$groupsId[] = $this->id;
		return $groupsId;
	}

	/**
	 * Прототипируемая функция: обходим всю иерархию групп вниз по дереву, возвращаем всех пользователей групп в иерархии.
	 * Голым запросом не сделано, потому что в MySQL рекурсивные вызовы (а без них придётся задавать глубину обхода вручную) появились только в 8.0, и с ними я просто не знаком.
	 * К тому же непонятно, как это делать средствами фреймворка. В общем, прототипирую идею, дальше как получится.
	 */
	public function getRelUsersHierarchy():LCQuery {
		return Users::find()->joinWith(['relUsersGroups'])->where(['rel_users_groups.group_id' => $this->collectRecursiveIds()])->active();
	}

	/**
	 * @return int
	 */
	public function getUsersCount():int {
		return (int)$this->getRelUsers()->count();
	}

}
