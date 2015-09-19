<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

class Captcha extends \Mmi\Form\Element\ElementAbstract {

	/**
	 * Ignorowanie tego pola, pole obowiązkowe, automatyczna walidacja
	 */
	public function init() {
		$this->setIgnore()
			->setRequired()
			->addValidator('Captcha', ['name' => $this->getOption('name')]);
	}

	/**
	 * Buduje pole
	 * @return string
	 */
	public function fetchField() {
		if (!$this->getValue()) {
			$this->setValue(str_replace('"', '&quot;', $this->getOption('value')));
		}
		$view = \Mmi\Controller\Front::getInstance()->getView();
		$html = '<div class="image"><img src="' . $view->url(['module' => 'cms', 'controller' => 'captcha', 'action' => 'index', 'name' => $this->_options['name']]) . '" alt="" /></div>';
		$html .= '<div class="input"><input ';
		$html .= 'type="text" ' . $this->_getHtmlOptions() . '/></div>';
		return $html;
	}

}