<?php

namespace Cms\Orm;

/**
 * Rekord kategorii CMSowych
 */
class CmsCategoryRecord extends \Mmi\Orm\Record {

	public $id;
	public $cmsCategoryTypeId;
	public $lang;
	public $name;
	public $lead;
	public $text;

	/**
	 * Breadcrumbs
	 * @var string
	 */
	public $uri;

	/**
	 * Opcjonalny adres strony
	 * @var string
	 */
	public $customUri;
	public $mvcParams;
	public $redirectUri;

	/**
	 * Identyfikator rodzica
	 * @var integer
	 */
	public $parentId;

	/**
	 * Kolejność elementów
	 * @var integer
	 */
	public $order;
	public $dateAdd;
	public $dateModify;
	public $title;
	public $description;
	public $https;
	public $follow;
	public $blank;
	public $active;
	
	public $publishUp;
	public $publishDown;

	/**
	 * Zapis rekordu
	 * @return boolean
	 */
	public function save() {
		//usunięcie uri
		$this->uri = '';
		//ustawiamy uri na podstawie rodzica
		if ($this->parentId && (null !== $parent = (new CmsCategoryQuery)->findPk($this->parentId))) {
			//nieaktywny rodzic -> nie wlicza się do ścieżki
			if (!$parent->active) {
				$parent->uri = substr($parent->uri, 0, strrpos($parent->uri, '/'));
			}
			$this->uri = ltrim($parent->uri . '/', '/');
		}
		//doklejanie do uri przefiltrowanej końcówki
		$this->uri .= (new \Mmi\Filter\Url)->filter($this->name);
		//domyślnie wstawienie na koniec
		if (null === $this->order) {
			$this->order = $this->_maxChildOrder() + 1;
		}
		//pola dat
		if (empty($this->publishUp)) {
			$this->publishUp = NULL;
		}
		if (empty($this->publishDown)) {
			$this->publishDown = NULL;
		}
		//zapis
		return parent::save();
	}

	/**
	 * Wstawienie kategorii z obliczeniem kodu i przebudową drzewa
	 * @return boolean
	 */
	protected function _insert() {
		//data aktualizacji
		$this->dateAdd = date('Y-m-d H:i:s');
		//próba utworzenia rekordu
		return parent::_insert();
	}

	/**
	 * Aktualizacja kategorii
	 * @return boolean
	 */
	protected function _update() {
		//zmodyfikowany parent
		$parentModified = $this->isModified('parentId');
		//zmodyfikowany order
		$orderModified = $this->isModified('order');
		//data modyfikacji
		$this->dateModify = date('Y-m-d H:i:s');
		//aktualizacja rekordu
		if (!parent::_update()) {
			return false;
		}
		//sortowanie dzieci po przestawieniu rodzica, lub kolejności
		if (($parentModified || $orderModified) && !$this->getOption('block-ordering')) {
			//sortuje dzieci
			$this->_sortChildren();
		}
		//przebudowa dzieci
		$this->_rebuildChildren($this->id);
		return true;
	}

	/**
	 * Kasowanie obiektu
	 * @return boolean
	 * @throws \Cms\Exception\ChildrenExistException
	 */
	public function delete() {
		if ($this->getPk() === null) {
			return false;
		}
		$children = (new \Cms\Model\CategoryModel)->getCategoryTree($this->getPk());
		if (!empty($children)) {
			throw new \Cms\Exception\ChildrenExistException();
		}
		return parent::delete();
	}

	/**
	 * 
	 */
	public function getWidgets() {
		
	}

	/**
	 * Przebudowuje dzieci (wywołuje save)
	 * @param integer $parentId rodzic
	 */
	protected function _rebuildChildren($parentId) {
		$i = 0;
		//iteracja po dzieciach
		foreach ($this->_getChildren($parentId) as $categoryRecord) {
			//wyznaczanie kolejności
			$categoryRecord->order = $i++;
			$categoryRecord->setOption('block-ordering', true);
			//zapis dziecka
			$categoryRecord->save();
			//zejście rekurencyjne
			$this->_rebuildChildren($categoryRecord->id);
		}
	}

	/**
	 * Zwraca dzieci danego rodzica
	 * @param integer $parentId id rodzica
	 * @return \Mmi\Orm\RecordCollection
	 */
	protected function _getChildren($parentId) {
		//zapytanie wyszukujące dzieci (z sortowaniem)
		return (new CmsCategoryQuery)
				->whereParentId()->equals($parentId)
				->orderAscOrder()
				->orderAscId()
				->find()
				->toObjectArray();
	}

	/**
	 * Wyszukuje maksymalną wartość kolejności w dzieciach wybranego rodzica
	 * @param integer $parentId id rodzica
	 * @return integer
	 */
	protected function _maxChildOrder() {
		//wyszukuje maksymalny order
		$maxOrder = (new CmsCategoryQuery)
			->whereParentId()->equals($this->parentId)
			->findMax('order');
		//będzie inkrementowany
		return $maxOrder === null ? -1 : $maxOrder;
	}

	/**
	 * Sortuje dzieci wybranego rodzica
	 * @param integer $parentId rodzic
	 */
	protected function _sortChildren() {
		$children = $this->_getChildren($this->parentId);
		//usuwanie bieżącej kategorii
		foreach ($children as $key => $categoryRecord) {
			if ($categoryRecord->id == $this->id) {
				unset($children[$key]);
			}
		}
		//sklejanie kategorii
		$ordered = array_merge(array_slice($children, 0, $this->order, true), [$this->order => $this], array_slice($children, $this->order, count($children), true));
		$i = 0;
		//ustawianie orderów
		foreach ($ordered as $key => $categoryRecord) {
			//wyznaczanie kolejności
			$categoryRecord->order = $i++;
			$categoryRecord->setOption('block-ordering', true);
			//zapis dziecka
			$categoryRecord->save();
		}
	}

}
