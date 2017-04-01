<?php

namespace Main\Service\Factory;

use Phalcon\DiInterface;

interface FactoryInterface {

    public function createService(DiInterface $di);
}