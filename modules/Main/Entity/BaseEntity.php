<?php

namespace Main\Entity;

/**
 * Base entity for extends for new entities for use in Models
 *
 * @author Ands
 */
class BaseEntity {

    public function getArrayCopy() {
        $array = [];

        foreach (get_class_vars(get_class($this)) as $key => $attr) {
            if (isset($this->$key)) {
                $array[$key] = $this->$key;
            }
        }

        return $array;
    }

    public function exchangeArray(array $data = []) {
        foreach ($data as $key => $item) {
            $this->$key = $item;
        }
    }
}