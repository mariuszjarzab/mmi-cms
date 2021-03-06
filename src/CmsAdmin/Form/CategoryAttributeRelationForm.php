<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element,
    Mmi\Validator;

/**
 * Formularz wiązania szablon <-> atrybut
 */
class CategoryAttributeRelationForm extends \Cms\Form\Form
{

    public function init()
    {
        $query = (new \Cms\Orm\CmsAttributeRelationQuery)
                ->whereObject()->equals($this->getRecord()->object)
                ->andFieldObjectId()->equals($this->getRecord()->objectId);

        //atrybut
        $this->addElement((new Element\Select('cmsAttributeId'))
                ->setRequired()
                ->addValidator(new Validator\NotEmpty)
                ->setMultioptions([null => '---'] + (new \Cms\Orm\CmsAttributeQuery)
                    ->orderAscName()
                    ->findPairs('id', 'name'))
                //unikalność atrybutu dla wybranego szablonu
                ->addValidator(new Validator\RecordUnique([$query, 'cmsAttributeId', $this->getRecord()->id]))
                ->setLabel('atrybut'));

        //zablokowana edycja
        if ($this->getRecord()->id) {
            $this->getElement('cmsAttributeId')
                ->setIgnore()
                ->setDisabled();
        }

        //rekord wartości domyślnej		
        $defaultValueRecord = (new \Cms\Orm\CmsAttributeValueQuery)
            ->findPk($this->getRecord()->cmsAttributeValueId);

        //wartość domyślna
        $this->addElement((new Element\Text('defaultValue'))
                ->setLabel('wartość domyślna')
                ->addFilter(new \Mmi\Filter\EmptyToNull)
                //string odpowiadający wartości domyślnej
                ->setValue($defaultValueRecord ? $defaultValueRecord->value : null));

        //filtry
        $this->addElement((new Element\Text('filterClasses'))
                ->setLabel('filtry'));

        //walidatory
        $this->addElement((new Element\Text('validatorClasses'))
                ->setLabel('walidatory'));

        //wymagany
        $this->addElement((new Element\Checkbox('required'))
                ->setLabel('wymagany'));

        //unikalny
        $this->addElement((new Element\Checkbox('unique'))
                ->setLabel('unikalny'));

        //zmaterializowany
        $this->addElement((new Element\Select('materialized'))
                ->setMultioptions([0 => 'nie', 1 => 'tak', 2 => 'tak, odziedziczony'])
                ->setLabel('zmaterializowany')
                ->setDescription('opcja administracyjna, zmiana może uszkodzić formularze zawierające ten atrybut'));

        //kolejność
        $this->addElement((new Element\Text('order'))
                ->setRequired()
                ->setLabel('kolejność')
                ->addValidator(new Validator\NumberBetween([0, 10000000]))
                ->setValue(0));

        //zapis
        $this->addElement((new Element\Submit('submit'))
                ->setLabel('zapisz wiązanie'));
    }

    /**
     * Przed zapisem
     * @return boolean
     */
    public function beforeSave()
    {
        //brak domyślnej wartości
        if (null === $defaultValue = $this->getElement('defaultValue')->getValue()) {
            return true;
        }
        //wszukiwanie rekordu z domyślną wartością
        if (null === $record = (new \Cms\Orm\CmsAttributeValueQuery)
            ->whereCmsAttributeId()->equals($this->getRecord()->cmsAttributeId)
            ->whereValue()->equals($defaultValue)
            ->findFirst()) {
            //tworzenie rekordu domyślnej wartości
            $record = new \Cms\Orm\CmsAttributeValueRecord;
            $record->value = $defaultValue;
            $record->cmsAttributeId = $this->getRecord()->cmsAttributeId;
            $record->save();
        }
        //ustawianie domyślnej wartości
        $this->getRecord()->cmsAttributeValueId = $record->id;
        return true;
    }

}
