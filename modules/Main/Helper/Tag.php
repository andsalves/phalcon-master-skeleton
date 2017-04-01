<?php

namespace Main\Helper;

use Main\Helper\View\AbstractViewHelper;
use Phalcon\Tag as PhalconTag;

/**
 * Custom phalcon tag implementation
 *
 * @author Ands
 */
class Tag extends PhalconTag {

    public function __call($name, $arguments) {
        if ($this->getDI()->has($name)) {
            $helper = $this->getDI()->get($name, $arguments);

            if ($helper instanceof AbstractViewHelper) {
                $helper->setDI($this->getDI());

                return $helper();
            } else {
                throw new \Exception("Trying to get view helper with name '$name', "
                    . "but service doesn't extends " . AbstractViewHelper::class . " class. ");
            }
        }

        throw new \Exception("Tag with name '$name' not found in DI scope. ");
    }
}