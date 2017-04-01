<?php

namespace Main\Service;

use Main\Routing\NavigationItem;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\DiInterface;

/**
 * @author Ands
 */
class NavigationService implements InjectionAwareInterface {

    /** @var DiInterface */
    protected $di;

    /** @var NavigationItem[] */
    protected $navigationItems = [];

    /** @var NavigationItem */
    protected $current;

    /**
     * @return NavigationItem[]
     */
    public function getNavigationItems() {
        $navItems = [];
        $navConfig = $this->getDI()->get('config')['navigation'];
        $router = $this->getDI()->get('router');

        foreach ($navConfig as $key => $navItemArray) {
            $navItemArray['router'] = $router;
            $navItem = NavigationItem::createFromArray($navItemArray);

            $navItems[$key] = $navItem;
        }

        return $navItems;
    }

    public function getCurrentNavigationItem() {
        if (!$this->current) {
            $navigationItems = $this->getNavigationItems();

            foreach ($navigationItems as $navItem) {
                if ($navItem->isCurrent()) {
                    $this->current = $navItem;
                }
            }
        }

        return $this->current;
    }

    /**
     * @param mixed $dependencyInjector
     * @return $this
     */
    public function setDI(DiInterface $dependencyInjector) {
        $this->di = $dependencyInjector;
        return $this;
    }

    /**
     * @return DiInterface
     */
    public function getDI() {
        return $this->di;
    }
}