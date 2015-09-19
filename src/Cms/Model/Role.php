<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use \Cms\Orm;

class Role {

	/**
	 * Nadaje (i opcjonalnie usuwa) uprawnienia
	 * @param integer $cmsAuthId id użytkownika cms
	 * @param array $roles tablica z id ról
	 * @param boolean $revoke czy odwołać pozostałe uprawnienia (domyślnie włączone)
	 */
	public static function grant($cmsAuthId, array $roles, $revoke = true) {
		//usuwa wszystkie role
		if ($revoke) {
			Orm\Auth\Role\Query::byAuthId($cmsAuthId)
				->find()
				->delete();
		}
		//iteracja po rolach
		foreach ($roles as $roleId) {
			//rola istnieje
			if (null !== Orm\Auth\Role\Query::factory()
					->whereCmsRoleId()->equals($roleId)
					->andFieldCmsAuthId()->equals($cmsAuthId)
					->findFirst()) {
				continue;
			}
			//zapis rekordu
			$record = new Orm\Auth\Role\Record();
			$record->cmsAuthId = $cmsAuthId;
			$record->cmsRoleId = $roleId;
			$record->save();
		}
	}

}