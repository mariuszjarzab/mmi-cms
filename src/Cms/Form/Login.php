<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form;

/**
 * Formularz logowania do CMS
 */
class Login extends \Mmi\Form {

	public function init() {
		$this->addElementText('username')
			->setLabel('Nazwa użytkownika')
			->addFilter('stringTrim');

		$this->addElementPassword('password')
			->setLabel('Hasło')
			->addFilter('stringTrim');

		$this->addElementCheckbox('remember')
			->setLabel('Pamiętaj mnie');

		$this->addElementSubmit('submit')
			->setLabel('Zaloguj się');
	}
	
	/**
	 * Logowanie
	 * @return boolean
	 */
	public function beforeSave() {
		//brak loginu lub hasła
		if (!$this->getElement('username')->getValue() || !$this->getElement('password')->getValue()) {
			return false;
		}
		//autoryzacja
		$auth = \App\Registry::$auth;
		\App\Registry::$auth->setIdentity($this->getElement('username')->getValue());
		\App\Registry::$auth->setCredential($this->getElement('password')->getValue());
		//autoryzacja
		if (!\App\Registry::$auth->authenticate()) {
			return false;
		}
		//zapamiętanie jeśli zaznaczona opcja
		if ($this->getElement('remember')) {
			\App\Registry::$auth->rememberMe(\App\Registry::$config->session->authRemember);
		}
		return true;
	}

}