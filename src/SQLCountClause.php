<?php

namespace Phonf;

class SQLCountClause {

    private $field;
    private $aliasField;

    public function setField($field) {
        $this->field = $field;
    }

    public function getField() {
        return $this->field;
    }

    public function setAliasField($aliasField) {
        $this->aliasField = $aliasField;
    }

    public function getAliasField() {
        return $this->aliasField;
    }

}