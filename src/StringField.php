<?php

namespace Phonf;

class StringField extends Field {

    public function getDBValue() {

        if (!is_null($this->value)) {
            $statement = '"' . str_replace('"','\"',$this->value) . '"';
        } else {
            $statement = "null";
        }
        return $statement;

    }

} 