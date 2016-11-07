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
 * Formularz typu kategorii
 */
class CategoryType extends \Cms\Form\Form {

	public function init() {

		//nazwa
		$this->addElementText('name')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorNotEmpty()
			->addValidatorRecordUnique(new \Cms\Orm\CmsCategoryTypeQuery, 'name', $this->getRecord()->id)
			->setLabel('nazwa');
		
		//klasa modułu wyświetlania
		$this->addElementSelect('mvcParams')
			->setMultioptions([null => '---'] + \CmsAdmin\Model\Reflection::getOptionsWildcard(3))
			->setRequired()
			->addValidatorNotEmpty()
			->setLabel('moduł wyświetlania');

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz szablon');
	}

	/**
	 * Przed zapisem
	 * @return boolean
	 */
	public function beforeSave() {
		//kalkulacja klucza
		$this->getRecord()->key = (new \Mmi\Filter\Url)->filter($this->getRecord()->name);
		return parent::beforeSave();
	}

	/**
	 * Po zapisie
	 * @return boolean
	 */
	/*public function afterSave() {
		//model relacji
		$relationModel = new \Cms\Model\AttributeRelationModel('cmsCategoryType', $this->getRecord()->id);
		//nowe id atrybutów
		$newAttributeIds = $this->getElement('attributeIds')->getValue();
		//bieżące id atrybutów
		$currentAttributeIds = $relationModel->getAttributeIds();
		//atrybuty do dodania
		foreach (array_diff($newAttributeIds, $currentAttributeIds) as $attributeId) {
			//dodawanie relacji
			$relationModel->createAttributeRelation($attributeId);
		}
		//atrybuty do usunięcia
		foreach (array_diff($currentAttributeIds, $newAttributeIds) as $attributeId) {
			//usuwanie wartości
			$this->_deleteValueRelationsByAttributeId($attributeId);
			//usuwanie relacji
			$relationModel->deleteAttributeRelation($attributeId);
		}
		return parent::afterSave();
	}*/

	/**
	 * Usuwanie relacji ze wszystkich kategorii dla danego atrybutu
	 * @param integer $attributeId
	 */
	protected function _deleteValueRelationsByAttributeId($attributeId) {
		
	}

}
