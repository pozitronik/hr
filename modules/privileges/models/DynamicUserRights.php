<?php
declare(strict_types = 1);

namespace app\modules\privileges\models;


use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\Controller;

/**
 * This is the model class for table "sys_user_rights".
 *
 * @property int $id
 * @property string $name Название правила
 * @property array $rules Набор разрешений правила
 */
class DynamicUserRights extends ActiveRecord implements UserRightInterface {
	protected $_module;//Регистрирующий модуль, заполняется при инициализации

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'sys_user_rights';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'rules'], 'required'],
			[['rules'], 'safe'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'rules' => 'Набор разрешений правила',
			'description' => 'Описание',
			'module' => 'Модуль'
		];
	}

	/**
	 * Магическое свойство, необходимое для сравнения классов, например
	 * Предполагается, что будет использоваться имя класса
	 * @return string
	 */
	public function __toString():string {
		// TODO: Implement __toString() method.
	}

	/**
	 * Уникальный идентификатор (подразумевается имя класса)
	 * @return string
	 */
	public function getId():string {
		// TODO: Implement getId() method.
	}

	/**
	 * Вернуть true, если правило не должно быть доступно в выбиралке
	 * @return bool
	 */
	public function getHidden():bool {
		// TODO: Implement getHidden() method.
	}

	/**
	 * Имя права
	 * @return string
	 */
	public function getName():string {
		// TODO: Implement getName() method.
	}

	/**
	 * Подробное описание возможностей, предоставляемых правом
	 * @return string
	 */
	public function getDescription():string {
		// TODO: Implement getDescription() method.
	}

	/**
	 * @param Controller $controller Экземпляр класса контроллера
	 * @param string $action Имя экшена
	 * @param array $actionParameters Дополнительный массив параметров (обычно $_GET)
	 * @return bool|null Одна из констант доступа
	 */
	public static function getAccess(Controller $controller, string $action, array $actionParameters = []):?bool {
		// TODO: Implement getAccess() method.
	}

	/**
	 * @param Model $model Модель, к которой проверяется доступ
	 * @param int|null $method Метод доступа (см. AccessMethods)
	 * @param array $actionParameters Дополнительный массив параметров (обычно $_GET)
	 * @return bool|null
	 */
	public static function canAccess(Model $model, ?int $method = AccessMethods::any, array $actionParameters = []):?bool {
		// TODO: Implement canAccess() method.
	}

	/**
	 * Набор действий, предоставляемых правом. Пока прототипирую
	 *
	 * @return array
	 */
	public function getActions():array {
		// TODO: Implement getActions() method.
	}

	/**
	 * Для возможностей, которые можно и нужно включать только флагамм + прототипирование
	 * @param int $flag
	 * @return null|bool
	 */
	public function getFlag(int $flag):?bool {
		// TODO: Implement getFlag() method.
	}
}
