<?php

namespace Auth;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

/**
 * @author Ands
 */
class Module implements ModuleDefinitionInterface {

    /** @inheritdoc */
    public function registerAutoloaders(DiInterface $di = null) {
        $loader = new Loader();

        $loader->registerNamespaces(array(
            'Auth\Controller' => __DIR__ . '/Controller',
        ));

        $loader->register();
    }

    /** @inheritdoc */
    public function registerServices(DiInterface $di) {
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();

            $dispatcher->setDefaultNamespace('Auth\Controller');

            return $dispatcher;
        });
    }
}