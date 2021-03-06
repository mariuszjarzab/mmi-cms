<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element;

/**
 * Formularz edycji ACL dla roli
 */
class CategoryAclForm extends \Cms\Form\Form
{

    public function init()
    {
        //drzewo kategorii (dozwolone)
        $this->addElement((new Element\Tree('allow'))
                ->setLabel('dozwolone kategorie')
                ->setMultiple()
                ->setValue(implode(';', (new \Cms\Orm\CmsCategoryAclQuery)
                        ->whereCmsRoleId()->equals($this->getOption('roleId'))
                        ->andFieldAccess()->equals('allow')
                        ->findPairs('id', 'cms_category_id')))
                ->setStructure(['children' => (new \Cms\Model\CategoryModel)->getCategoryTree()]));

        //drzewo kategorii (zabronione)
        $this->addElement((new Element\Tree('deny'))
                ->setLabel('zabronione kategorie')
                ->setMultiple()
                ->setValue(implode(';', (new \Cms\Orm\CmsCategoryAclQuery)
                        ->whereCmsRoleId()->equals($this->getOption('roleId'))
                        ->andFieldAccess()->equals('deny')
                        ->findPairs('id', 'cms_category_id')))
                ->setStructure(['children' => (new \Cms\Model\CategoryModel)->getCategoryTree()]));

        $this->addElement((new Element\Submit('submit'))
                ->setLabel('zapisz'));
    }

    /**
     * Zapis uprawnień
     * @return boolean
     */
    public function beforeSave()
    {
        //czyszczenie uprawnień dla roli
        (new \Cms\Orm\CmsCategoryAclQuery)
            ->whereCmsRoleId()->equals($this->getOption('roleId'))
            ->find()
            ->delete();
        //zapis uprawnień "dozwól"
        foreach (explode(';', $this->getElement('allow')->getValue()) as $categoryId) {
            //brak kategorii
            if (!$categoryId) {
                continue;
            }
            $aclRecord = new \Cms\Orm\CmsCategoryAclRecord;
            $aclRecord->access = 'allow';
            $aclRecord->cmsCategoryId = $categoryId;
            $aclRecord->cmsRoleId = $this->getOption('roleId');
            $aclRecord->save();
        }
        //zapis uprawnień "zabroń"
        foreach (explode(';', $this->getElement('deny')->getValue()) as $categoryId) {
            //brak kategorii
            if (!$categoryId) {
                continue;
            }
            $aclRecord = new \Cms\Orm\CmsCategoryAclRecord;
            $aclRecord->access = 'deny';
            $aclRecord->cmsCategoryId = $categoryId;
            $aclRecord->cmsRoleId = $this->getOption('roleId');
            $aclRecord->save();
        }
        //usunięcie cache
        \App\Registry::$cache->remove('mmi-cms-category-acl');
        return true;
    }

}
