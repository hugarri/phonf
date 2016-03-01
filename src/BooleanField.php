<?php

namespace Phonf;

class BooleanField extends Field {

    public function getDBValue() {
        if (!is_null($this->value)) {
            if ($this->value == 0) {
                $statement = "0";
            } else {
                $statement = $this->value;
            }
        } else {
            $statement = "null";
        }
        return $statement;
    }

}