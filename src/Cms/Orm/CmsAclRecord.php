<?php

namespace Cms\Orm;

/**
 * Rekord uprawnień
 */
class CmsAclRecord extends \Mmi\Orm\Record
{

    public $id;
    public $cmsRoleId;
    public $module;
    public $controller;
    public $action;
    public $access;

    /**
     * Pobranie parametrów MVC w postaci ciągu
     * @return string
     */
    public function getMvcParams()
    {
        $mvcParams = 'module=' . $this->module;
        //kontroler
        if ($this->controller) {
            $mvcParams .= '&controller=' . $this->controller;
        }
        //akcja
        if ($this->action) {
            $mvcParams .= '&action=' . $this->action;
        }
        return $mvcParams;
    }

    /**
     * Zapis rekordu uprawnień
     * @return boolean
     */
    public function save()
    {
        return parent::save() && $this->clearCache();
    }

    /**
     * Usunięcie
     * @return boolean
     */
    public function delete()
    {
        return parent::delete() && $this->clearCache();
    }

    /**
     * Usunięcie cache
     * @return boolean
     */
    public function clearCache()
    {
        \App\Registry::$cache->remove('mmi-cms-navigation-');
        \App\Registry::$cache->remove('mmi-cms-navigation-' . \Mmi\App\FrontController::getInstance()->getRequest()->lang);
        \App\Registry::$cache->remove('mmi-cms-acl');
        return true;
    }

}
