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
	public static function network():string {
		return "<i class='fa fa-chart-network'></i>";
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
		return "<i class='fa chevron-circle-down'></i>";
	}

	/**
	 * @return string
	 */
	public static function users_edit():string {
		return "<span class='fa-stack fa-2x'>
		<i class='fa fa-users'></i>
		<i class='fa fa-edit fa-stack-1x fa-inverse'></i>
		</span>";
	}
}