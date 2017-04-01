<?php

namespace Main\Routing;

use Main\Exception\RoutingException;
use Phalcon\Mvc\RouterInterface;

/**
 * Navigation item representation class
 *
 * @author Ands
 */
class NavigationItem {

    /** @var string */
    protected $title;

    /** @var NavigationItem[] */
    protected $subpages = [];

    /** @var string */
    protected $path;

    /** @var string */
    protected $iconClass;

    /** @var bool */
    protected $current = false;

    /** @var NavigationItem */
    protected $parent;

    /** @var RouterInterface */
    protected $router;

    /** @var string */
    protected $description;

    /** @return string */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     * @return NavigationItem
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @return NavigationItem[]
     */
    public function getSubpages() {
        return $this->subpages;
    }

    /**
     * @param array $subpagesArray
     * @return NavigationItem
     */
    public function setSubpages(array $subpagesArray) {
        foreach ($subpagesArray as $subpageArray) {
            $subpageArray['parent'] = $this;
            $subpageArray['router'] = $this->getRouter();
            $subpage = self::createFromArray($subpageArray);

            $this->addSubpage($subpage);
        }

        return $this;
    }

    public static function createFromArray(array $navArray) {
        if (!isset($navArray['title']) || !isset($navArray['path'])) {
            throw new RoutingException("Menu item must have a 'path' and 'title' fields. ");
        }

        $navItem = new NavigationItem();
        $navItem->setTitle($navArray['title']);
        $navItem->setPath($navArray['path']);

        if (isset($navArray['icon_class'])) {
            $navItem->setIconClass($navArray['icon_class']);
        }

        if (isset($navArray['router'])) {
            $navItem->setRouter($navArray['router']);
        }

        if (isset($navArray['parent'])) {
            $navItem->setParent($navArray['parent']);
        }

        if (isset($navArray['subpages'])) {
            $navItem->setSubpages($navArray['subpages']);
        }

        if ($navItem->getRouter()) {
            if (in_array($navItem->getPath(), ['', '/'])) {
                if (
                    $navItem->getRouter()->getControllerName() == 'index' &&
                    $navItem->getRouter()->getActionName() == 'index'
                ) {
                    $navItem->setCurrent();
                }
            } else {
                $navItemParams = array_replace(['', 'index', ''], explode('/', $navItem->getPath()));

                if (
                    $navItem->getRouter()->getControllerName() == $navItemParams[0] &&
                    $navItem->getRouter()->getActionName() == $navItemParams[1]
                ) {
                    $navItem->setCurrent();
                }
            }
        }

        return $navItem;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param string $path
     * @return NavigationItem
     */
    public function setPath($path) {
        if (substr($path, 0, 1) == '/') {
            $path = substr($path, 1);
        }

        if (substr($path, -1, 1) == '/') {
            $path = substr($path, 0, strlen($path) - 1);
        }

        $this->path = $path;

        return $this;
    }

    public function addSubpage(NavigationItem $page) {
        $this->subpages[] = $page;
    }

    /**
     * @return string
     */
    public function getIconClass() {
        return $this->iconClass;
    }

    /**
     * @param string $iconClass
     */
    public function setIconClass($iconClass) {
        $this->iconClass = $iconClass;
    }

    public function setCurrent($isCurrent = true, $parentPropag = true) {
        $this->current = boolval($isCurrent);

        if ($parentPropag && $this->getParent()) {
            $this->getParent()->setCurrent($isCurrent, $parentPropag);
        }

        return $this;
    }

    public function isCurrent() {
        return $this->current;
    }

    /**
     * @return NavigationItem
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param NavigationItem $parent
     * @return NavigationItem
     */
    public function setParent($parent) {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     * @return NavigationItem
     */
    public function setRouter($router) {
        $this->router = $router;
        return $this;
    }

    public function getCurrentSubpage() {
        foreach ($this->getSubpages() as $subpage) {
            if ($subpage->isCurrent()) {
                return $subpage;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     * @return NavigationItem
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
}