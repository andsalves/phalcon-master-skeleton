<?php

use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;

if (!session_id()) {
    session_start();
}

$loader = new Loader();
$application = new Application();
$di = new FactoryDefault();
$namespaces = [];
$config = include_once(__DIR__ . '/config/config.php');
$di->set('config', function () use ($config) {
    return $config;
});

foreach ($config['modules'] as $key => $moduleName) {
    $namespaces["$moduleName"] = __DIR__ . "/modules/$moduleName";
    $namespaces["$moduleName\\Controller"] = __DIR__ . "/modules/$moduleName/Controller";
    $namespaces["$moduleName\\Model"] = __DIR__ . "/modules/$moduleName/Model";
    $namespaces["$moduleName\\Model\\Factory"] = __DIR__ . "/modules/$moduleName/Model";
    $namespaces["$moduleName\\Service"] = __DIR__ . "/modules/$moduleName/Service";
    $namespaces["$moduleName\\Service\\Factory"] = __DIR__ . "/modules/$moduleName/Service";
    $namespaces["$moduleName\\Helper"] = __DIR__ . "/modules/$moduleName/Helper";
    $namespaces["$moduleName\\Listener"] = __DIR__ . "/modules/$moduleName/Listener";

    $application->registerModules(array(
        $moduleName => array(
            'className' => "$moduleName\\Module",
            'path' => __DIR__ . "/modules/$moduleName/Module.php"
        ),
    ), true);

    if ($key === 0) {
        $application->setDefaultModule($moduleName);
    }
}

$loader->registerNamespaces($namespaces);
$loader->register();

if (isset($config['di'])) {
    \Main\Helper\DiHelper::registerServicesFromConfig($di, $config['di']);
}

$application->setDI($di);

$eventsManager = new Phalcon\Events\Manager();

$application->getDI()->set('EventsManager', $eventsManager);

$application->setEventsManager($eventsManager);
//$application->getEventsManager()->attach('application', function (\Phalcon\Events\Event $event, $application) {
//    // Some work here, if you want, man
//});

print $application->handle()->getContent();

//$response->send();