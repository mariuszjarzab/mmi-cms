<?php

namespace Cms\Orm;

/**
 * Rekord łączenia widget - kategoria
 */
class CmsCategoryWidgetCategoryRecord extends \Mmi\Orm\Record
{

    public $id;
    public $cmsCategoryWidgetId;
    public $cmsCategoryId;
    public $configJson;
    public $active = 1;
    
    //prefiks obiektów plików dla widgetu
    CONST FILE_OBJECT_PREFIX = 'categoryWidgetRelation';
    //prefiks atrybutów
    CONST CMS_ATTRIBUTE_PREFIX = 'cmsAttribute';

    /**
     * Kolejność
     * @var integer
     */
    public $order;

    /**
     * Zapis rekordu
     * @return boolean
     */
    public function save()
    {
        //zapis z usunięciem cache
        return parent::save() && $this->clearCache();
    }

    /**
     * Usunięcie rekordu
     * @return boolean
     */
    public function delete()
    {
        //usuwanie plików
        (new CmsFileQuery)->whereObject()->like(self::FILE_OBJECT_PREFIX . '%')
            ->andFieldObjectId()->equals($this->getPk())
            ->find()
            ->delete();
        //usunięcie z czyszczeniem bufora
        return parent::delete() && $this->clearCache();
    }

    /**
     * Zwraca rekord kategorii
     * @return CmsCategoryRecord
     * @throws \Cms\Exception\CategoryWidgetException
     */
    public function getCategoryRecord()
    {
        //zwrot dołączonegj kategorii
        if ($this->getJoined('cms_category')) {
            return $this->getJoined('cms_category');
        }
        //brak dołączonej kategorii
        throw new \Cms\Exception\CategoryWidgetException('Category not joined');
    }

    /**
     * Zwraca rekord widgeta
     * @return CmsCategoryWidgetRecord
     * @throws \Cms\Exception\CategoryWidgetException
     */
    public function getWidgetRecord()
    {
        //zwrot dołączonego widgeta
        if ($this->getJoined('cms_category_widget')) {
            return $this->getJoined('cms_category_widget');
        }
        //brak widgeta
        throw new \Cms\Exception\CategoryWidgetException('Widget not joined');
    }

    /**
     * Pobiera rekordy wartości atrybutów w formie obiektu danych
     * @see \Mmi\DataObiect
     * @return \Mmi\DataObject
     */
    public function getAttributeValues()
    {
        //próba pobrania atrybutów z cache
        if (null === $attributeValues = \App\Registry::$cache->load($cacheKey = 'category-widget-attributes-' . $this->id)) {
            //pobieranie atrybutów
            \App\Registry::$cache->save($attributeValues = (new \Cms\Model\AttributeValueRelationModel('categoryWidgetRelation', $this->id))->getGrouppedAttributeValues(), $cacheKey);
        }
        //zwrot atrybutów
        return $attributeValues;
    }

    /**
     * Zwraca konfigurację
     * @return \Mmi\DataObject
     */
    public function getConfig()
    {
        //próba dekodowania konfiguracji json
        try {
            $configArr = \json_decode($this->configJson, true);
        } catch (\Exception $e) {
            \Mmi\App\FrontController::getInstance()->getLogger()->warning('Unable to decode widget configJson #' . $this->id);
        }
        //tworznie pustego configa
        if (!isset($configArr)) {
            $configArr = [];
        }
        $config = (new \Mmi\DataObject())->setParams($configArr);
        return $config;
    }

    /**
     * Ustawia configJson na podstawie danych
     * z filtracją danych zapisanych w atrybutach
     * @param array $data
     * @return bool
     */
    public function setConfigFromArray(array $data = []) {
        //iteracja po danych
        foreach ($data as $key => $value) {
            //wyszukiwanie patternu (np. cmsAttribute123)
            if (preg_match('/' . self::CMS_ATTRIBUTE_PREFIX . '[0-9]+/', $key)) {
                //usuwanie danych, gdyż są zapisane w atrybucie
                unset($data[$key]);
            }
        }
        //kodowanie konfiguracji
        $this->configJson = empty($data) ? null : \json_encode($data);
        return $this->save();
    }

    /**
     * Przełączenie widoczności widgeta
     * @return boolean
     */
    public function toggle()
    {
        //aktywacja/deaktywacja
        $this->active = ($this->active == 1) ? 0 : 1;
        return $this->save();
    }

    /**
     * Usuwanie bufora
     */
    public function clearCache()
    {
        //usuwanie cache
        \App\Registry::$cache->remove('category-widget-model-' . $this->cmsCategoryId);
        \App\Registry::$cache->remove('category-html-' . $this->cmsCategoryId);
        \App\Registry::$cache->remove('category-widget-attributes-' . $this->id);
        \App\Registry::$cache->remove('category-widget-html-' . $this->id);
        return true;
    }

}
