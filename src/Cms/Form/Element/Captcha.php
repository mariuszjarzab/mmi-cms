<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element captcha
 */
class Captcha extends \Mmi\Form\Element\ElementAbstract {

	/**
	 * Ignorowanie tego pola, pole obowiązkowe, automatyczna walidacja
	 */
	public function __construct($name) {
		parent::__construct($name);
		$this->setIgnore()
			->setRequired()
			->addValidator(new \Cms\Validator\Captcha(['name' => $name]));
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$html = '<div class="image"><img src="' . $view->url(['module' => 'cms', 'controller' => 'captcha', 'action' => 'index', 'name' => $this->_options['name']]) . '" alt="" /></div>';
		$html .= '<div class="input"><input ';
		$html .= 'type="text" ' . $this->_getHtmlOptions() . '/></div>';
		return $html;
	}

}
