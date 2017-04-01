<?php

namespace Main\Helper\View;

use Phalcon\Di\InjectionAwareInterface;
use Phalcon\DiInterface;

/**
 * Abstraction of a ViewHelper
 *
 * @author Ands
 */
abstract class AbstractViewHelper implements InjectionAwareInterface {

    /** @var DiInterface */
    private $di;

    abstract public function getHelper();

    public function __invoke() {
        return $this->getHelper();
    }

    /**
     * Sets the dependency injector
     *
     * @param mixed $dependencyInjector
     */
    public function setDI(DiInterface $dependencyInjector) {
        $this->di = $dependencyInjector;
    }

    /**
     * Returns the internal dependency injector
     *
     * @return DiInterface
     */
    public function getDI() {
        return $this->di;
    }
}