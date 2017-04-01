<?php

namespace Main\Service\Factory;

use Phalcon\DiInterface;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

/**
 * @author Ands
 */
class MysqlDbAdapterFactory implements FactoryInterface {

    public function createService(DiInterface $di) {
        $config = $di->get('config');

        return new DbAdapter(array(
            "host" => $config['connection']['host'],
            "username" => $config['connection']['username'],
            "password" => $config['connection']['password'],
            "dbname" => $config['connection']['dbname']
        ));
    }
}