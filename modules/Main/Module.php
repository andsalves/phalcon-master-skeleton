<?php

namespace Main;

use Main\Service\NavigationService;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\ViewInterface;

/**
 * @author Ands
 */
class Module implements ModuleDefinitionInterface {

    //** @inheritdoc */
    public function registerAutoloaders(DiInterface $di = null) {
        $loader = new Loader();

        $loader->registerNamespaces(array(
            'Main\Controller' => __DIR__ . '/Controller',
        ));

        $loader->register();
    }

    /** @inheritdoc */
    public function registerServices(DiInterface $di) {
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();

            $dispatcher->setDefaultNamespace('Main\Controller');

            return $dispatcher;
        });

        /** @var NavigationService $navService */
        $navService = $di->get(NavigationService::class);
        /** @var ViewInterface $view */
        $view = $di->get('view');

        if ($navService->getCurrentNavigationItem()) {
            $title = '';

            $title .= $navService->getCurrentNavigationItem()->getTitle();

            if ($navService->getCurrentNavigationItem()->getCurrentSubpage()) {
                $title .= ' | ' . $navService->getCurrentNavigationItem()->getCurrentSubpage()->getTitle();
            }

            $view->setVar('title', $title);
        } else {
            $view->setVar('title', 'My Application');
        }
    }
}