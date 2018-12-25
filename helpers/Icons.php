<?php
declare(strict_types = 1);

namespace app\helpers;

/**
 * Class Icons
 * @package app\helpers
 * Хелпер с HTML-иконками
 */
class Icons {

	/**
	 * @return string
	 */
	public static function trash():string {
		return "<i class='fa fa-trash' title='Отметить к удалению'></i>";
	}

	/**
	 * @return string
	 */
	public static function users():string {
		return "<i class='fa fa-user-friends' title='Пользователи'></i>";
	}

	/**
	 * @return string
	 */
	public static function subgroups():string {
		return "<i class='fa fa-users' title='Подгруппы'></i>";
	}

	/**
	 * @return string
	 */
	public static function attributes():string {
		return "<i class='fa fa-address-card' title='Подгруппы'></i>";
	}

	/**
	 * @return string
	 */
	public static function network():string {
		return "<i class='fa fa-chart-network'></i>";
	}

	/**
	 * @return string
	 */
	public static function chart():string {
		return "<i class='fa fa-chart-pie'></i>";
	}

	/**
	 * @return string
	 */
	public static function menu():string {
		return "<i class='fa fa-bars'></i>";
	}

	/**
	 * @return string
	 */
	public static function menu_caret():string {
		return "<i class='fa fa-chevron-circle-down'></i>";
	}

	/**
	 * @return string
	 */
	public static function clear():string {
		return "<i class='fa fa-toilet'></i>";
	}

	/**
	 * @return string
	 */
	public static function users_edit():string {
		return "<i class='fa fa-user-edit'></i>";
	}

	/**
	 * @return string
	 */
	public static function users_edit_red():string {
		return "<i class='fa fa-user-edit' style='color: Tomato;'></i>";
	}

	/**
	 * @return string
	 */
	public static function hierarchy():string {
		return "<i class='fa fa-level-down-alt'></i>";
	}

	/**
	 * @return string
	 */
	public static function hierarchy_red():string {
		return "<i class='fa fa-level-down-alt' style='color: Tomato;'></i>";
	}

	/**
	 * @return string
	 */
	public static function group():string {
		return "<i class='fa fa-users'></i>";
	}
}