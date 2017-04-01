<?php

namespace Main\Helper;

use Main\Exception\ServiceRegisterException;
use Main\Service\Factory\FactoryInterface;
use Phalcon\DiInterface;

/**
 * Helper for DI of services
 *
 * @author Ands
 */
class DiHelper {

    public static function registerServicesFromConfig(DiInterface $di, array $config) {
        if (isset($config['invokables'])) {
            self::registerInvokables($di, $config['invokables']);
        }

        if (isset($config['factories'])) {
            self::registerFactories($di, $config['factories']);
        }

        if (isset($config['abstract_factories'])) {
            self::registerAbstractFactories($di, $config['abstract_factories']);
        }

        if (isset($config['aliases'])) {
            self::registerAliases($di, $config['aliases']);
        }
    }

    public static function registerInvokables(DiInterface $di, array $services) {
        foreach ($services as $name => $service) {
            if (is_numeric($name)) {
                if (!is_string($service)) {
                    throw new ServiceRegisterException(
                        'Service could not be created because it has no name. '
                    );
                }

                $name = $service;
            }

            $di->set($name, $service, true);
        }
    }

    public static function registerFactories(DiInterface $di, array $services) {
        foreach ($services as $name => $serviceFactory) {
            $factory = new $serviceFactory();

            if (is_callable($factory)) {
                $di->set($name, $factory($di), true);
            } else if ($factory instanceof FactoryInterface) {
                $di->set($name, $factory->createService($di), true);
            }
        }
    }

    public static function registerAbstractFactories(DiInterface $di, array $services) {

    }

    public static function registerAliases(DiInterface $di, array $services) {
        foreach ($services as $alias => $serviceName) {
            $di->set($alias, $di->get($serviceName), true);
        }
    }
}