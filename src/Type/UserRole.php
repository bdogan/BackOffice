<?php

namespace BackOffice\Type;

class UserRole
{
	const ADMIN = [ 'admin', 'Administrator' ];
	const USER = [ 'user', 'User' ];

	/**
	 * @param $role
	 *
	 * @return mixed|null
	 */
	public static function getTitle($role)
	{
		// Check given rule
		switch ($role) {
			case self::ADMIN[0]: return self::ADMIN[1];
			case self::USER[0]: return self::USER[1];
		}

		// Return null
		return null;
	}

	/**
	 * Check Role is alive
	 *
	 * @param $role
	 *
	 * @return bool
	 */
	public static function hasRole($role)
	{
		return self::getTitle($role) !== null;
	}
}

