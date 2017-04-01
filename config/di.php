<?php

use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Annotations as AnnotationsRouter;
use Main\Helper\Tag as CustomTag;
use Main\Helper\View\Navigation;
use Phalcon\Mvc\View\Engine\Volt;
use \Main\Service\NavigationService;

return array(
    'aliases' => array(
        'url' => UrlProvider::class,
        'db' => DbAdapter::class,
        'router' => Router::class,
        'view' => View::class,
        'flash' => FlashDirect::class,
        'flashSession' => FlashSession::class,
        'tag' => CustomTag::class,
        'navigation' => Navigation::class,
    ),
    'invokables' => array(
        NavigationService::class,
        Volt::class => function (\Phalcon\Mvc\ViewInterface $view) {
            $volt = new Volt($view);

            $volt->setOptions(array(
                "compiledPath" => function ($templatePath) {
                    $templatePath = str_replace('../', '', $templatePath);
                    $filePath = __DIR__ . "/../data/volt/$templatePath";

                    if (!is_dir(dirname($filePath))) {
                        mkdir(dirname($filePath), 0777, true);
                    }

                    return "$filePath.php";
                },
                'compileAlways' => true
            ));

            return $volt;
        },
        Router::class => function () {
            $router = new AnnotationsRouter(false);

            $router->addModuleResource('Main', 'Main\Controller\Index', '/');

            $router->setDefaults(array(
                'action' => 'notFound',
                'controller' => 'Index',
                'module' => 'Main',
            ));

            return $router;
        },
        View::class => function () {
            $view = new View();
            $layoutName = 'index';

            $view->setViewsDir('../modules/Main/View/');
            $view->setLayoutsDir('layouts/');

            $view->registerEngines(array(
                '.volt' => Volt::class,
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ));

            if (!$view->exists($view->getLayoutsDir() . $layoutName)) {
                throw new View\Exception("Layout with name '$layoutName' not found. ");
            }

            $view->setLayout($layoutName);

            return $view;
        },
        UrlProvider::class => function () {
            return (new UrlProvider())->setBasePath('/');
        },
        CustomTag::class,
        FlashSession::class,
        FlashDirect::class,
    ),
    'instantiables' => array(),
    'factories' => array(
        DbAdapter::class => \Main\Service\Factory\MysqlDbAdapterFactory::class,
    ),
    'abstract_factories' => array(),
);