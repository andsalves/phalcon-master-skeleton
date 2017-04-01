<?php

namespace Main\Helper\View;

use Main\Exception\NavigationException;
use Main\Routing\NavigationItem;
use Main\Service\NavigationService;
use Phalcon\Mvc\View;

/**
 * View helper for navigation
 *
 * @author Ands
 */
class Navigation extends AbstractViewHelper {

    /** @var NavigationItem[] */
    protected $navigationItems = [];

    /** @var NavigationItem */
    protected $current;

    /** @var NavigationService */
    protected $navigationService;

    public function getHelper() {
        $this->navigationService = $this->getDI()->get(NavigationService::class);
        return $this;
    }

    public function __call($name, $arguments) {
        $nameParts = explode('render', $name);

        if (count($nameParts) == 2 && empty($nameParts[0])) {
            return $this->renderDefaultNavigation(strtolower($nameParts[1]));
        }

        throw new NavigationException("Navigation helper with name '$name' could not be loaded. ");
    }

    public function renderDefaultNavigation($partialName = 'menu') {
        return $this->renderPartial($partialName, array(
            'navigationItems' => $this->navigationService->getNavigationItems()
        ));
    }

    public function renderBreadcrumbs() {
        $currentNavItem = $this->navigationService->getCurrentNavigationItem();

        if (!$currentNavItem) {
            $navItems = $this->navigationService->getNavigationItems();
            $currentNavItem = reset($navItems);
            $currentNavItem->addSubpage(NavigationItem::createFromArray(array(
                'title' => '?',
                'path' => ''
            ))->setCurrent(true));
        }

        return $this->renderPartial('breadcrumbs', ['navigationItem' => $currentNavItem]);
    }

    public function renderPageTitle() {
        $currentNavItem = $this->navigationService->getCurrentNavigationItem();

        return $this->renderPartial('page-title', ['navigationItem' => $currentNavItem]);
    }

    private function renderPartial($partialName, array $params) {
        /** @var View $view */
        $view = $this->getDI()->get('view');

        if ($view->exists("_partial/$partialName")) {
            return $view->partial("_partial/$partialName", $params);
        }

        throw new NavigationException("Template view _partial/$partialName not found in views directory. ");
    }
}