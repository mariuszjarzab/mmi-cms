<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz edycji widgetu kategorii
 */
class CategoryWidget extends \Cms\Form\Form {

	public function init() {
		
		//lista widgetów
		$widgets = [null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard(3, '/widget/');
		
		$this->addElementText('name')
			->setLabel('nazwa')
			->setRequired()
			->addValidatorStringLength(3, 64);

		$this->addElementSelect('mvcParams')
			->setLabel('adres modułu wyświetlania')
			->setMultioptions($widgets)
			->setRequired()
			->addValidatorNotEmpty();
		
		$this->addElementSelect('mvcPreviewParams')
			->setLabel('adres modułu podglądu')
			->setMultioptions($widgets)
			->setRequired()
			->addValidatorNotEmpty();
		
		$this->addElementText('recordClass')
			->setLabel('klasa rekordu danych')
			->addValidatorStringLength(3, 64);

		$this->addElementText('formClass')
			->setLabel('klasa formularza')
			->setDescription('dane i konfiguracja')
			->addValidatorStringLength(3, 64);
		
		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}

}
