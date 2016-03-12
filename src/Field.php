<?php

namespace Phonf;

abstract class Field {

    protected $database;
    protected $name;
    protected $value;

    function __construct($database, $name, $value = null) {
        $this->database = $database;
        $this->name = $name;
        if (!is_null($value)) $this->setValue($value);
    }

    public function getDatabase() {
        return $this->database;
    }

    public function setTableAlias($alias) {
        $this->database = $alias;
    }

    public function getName() {
        return $this->name;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function getDBValue() {
        if (!is_null($this->value)) {
            $statement = $this->value;
        } else {
            $statement = "null";
        }
        return $statement;
    }

    public function getDBAlias() {
        return $this->database . $this->name;
    }

} 