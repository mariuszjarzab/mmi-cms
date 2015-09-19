<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

/**
 * Kontroler użytkownika
 */
class User extends \Mmi\Controller\Action {

	/**
	 * Logowanie
	 */
	public function loginAction() {
		$form = new \Cms\Form\Login();
		$this->view->loginForm = $form;
		if (!$form->isMine()) {
			return;
		}
		//błędne logowanie
		if (!$form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Logowanie błędne', false);
			return;
		}
		//lowowanie poprawne
		$this->getHelperMessenger()->addMessage('Zalogowano poprawnie', true);
		\Cms\Model\Stat::hit('user-login');
		$this->getResponse()->redirectToUrl('/');
	}

	/**
	 * Logout
	 */
	public function logoutAction() {
		\App\Registry::$auth->clearIdentity();
		$this->getHelperMessenger()->addMessage('Wylogowano poprawnie', true);
		\Cms\Model\Stat::hit('user-logout');
		$this->getResponse()->redirectToUrl('/');
	}

	/**
	 * Nowe konto
	 */
	public function registerAction() {
		$form = new \Cms\Form\Register(new \Cms\Orm\Auth\Record());
		$this->view->registerForm = $form;
		if (!$form->isMine()) {
			return;
		}
		//błędy formularza
		if (!$form->isSaved()) {
			$this->getHelperMessenger()->addMessage('Formularz zawiera błędy', false);
			return;
		}
		//rejestracja poprawna
		$this->getHelperMessenger()->addMessage('Zarejestrowano poprawnie. Sprawdź e-mail i kliknij potwierdzenie konta.', true);
		\Cms\Model\Stat::hit('user-register');
		$this->getResponse()->redirectToUrl('/');
	}

	/**
	 * Widget logowania
	 */
	public function loginWidgetAction() {
		return $this->loginAction();
	}

}