<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler kategorii
 */
class CategoryController extends \Mmi\Mvc\Controller {

	/**
	 * Akcja dispatchera kategorii
	 */
	public function dispatchAction() {
		//wyszukanie kategorii
		if ((null === $category = (new Model\CategoryModel)
			->getCategoryByUri($this->uri))) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $this->uri);
		}
		//kategoria nieaktywna i brak roli redaktora treści
		if ($category->active != 1 && !\App\Registry::$acl->isAllowed(\App\Registry::$auth->getRoles(), 'cmsAdmin:category:index')) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $this->uri);
		}
		//kategoria posiada customUri, a wejście jest na natywny uri
		if ($category->customUri && $this->uri == $category->uri) {
			//przekierowanie na customUri
			$this->getResponse()->redirect('cms', 'category', 'dispatch', ['uri' => $category->customUri]);
		}
		//tworzenie nowego requestu na podstawie obecnego
		$request = clone $this->getRequest();
		$request->setModuleName('cms')
			->setControllerName('category')
			->setActionName('article');
		//pobranie typu i ustalenie template
		if ($category->getJoined('cms_category_type')->template != '') {
			//tablica z tpl
			$mcaArr = explode('/', $category->getJoined('cms_category_type')->template);
			//zła ilość argumentów
			if (count($mcaArr) != 3) {
				throw new \Exception('Template invalid: "' . $category->getJoined('cms_category_type')->template . '"');
			}
			//ustawienie request
			$request->setModuleName($mcaArr[0])
				->setControllerName($mcaArr[1])
				->setActionName($mcaArr[2]);
		}
		//iteracja po dzieciach kategorii
		foreach ($category->getOption('parents') as $cat) {
			//brak widoczności w menu
			if (!$cat->active) {
				continue;
			}
			//dodawanie okruszka
			$this->view->navigation()->appendBreadcrumb($cat->name, $this->view->url(['uri' => $cat->uri]), $cat->title ? $cat->title : $cat->name, $cat->description ? $cat->description : $cat->lead);
		}
		//dodawanie okruszka z kategorią główną
		$this->view->navigation()->appendBreadcrumb($category->name, $this->view->url(['uri' => $category->uri]), $category->title ? $category->title : $category->name, $category->description ? $category->description : $category->lead);
		//przypisanie modelu widgetów do rejestru
		\App\Registry::$widget = new Model\CategoryWidgetModel($category->id);
		//model widgetu do widoku
		$this->view->widgetModel = \App\Registry::$widget;
		//forward do akcji docelowej
		return \Mmi\Mvc\ActionHelper::getInstance()->forward($request);
	}

	/**
	 * Akcja artykułu
	 */
	public function articleAction() {
		//wyszukanie kategorii
		if (null === $category = (new Model\CategoryModel)->getCategoryByUri($this->uri)) {
			//404
			throw new \Mmi\Mvc\MvcNotFoundException('Category not found: ' . $this->uri);
		}
		//przekazanie kategorii
		$this->view->category = $category;
		//przekazanie atrybutów
		$this->view->attributes = (new Model\AttributeValueRelationModel('category', $category->id))->getAttributeValues();
		$this->view->tags = (new Model\TagRelationModel('category', $category->id))->getTagRelations();
	}

}
