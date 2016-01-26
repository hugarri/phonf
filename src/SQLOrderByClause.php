<?php

namespace Phonf;

class SQLOrderByClause {

    private $field;
    private $asc;

    public function __construct(Field $field, $asc = true) {
        $this->field = $field;
        $this->asc = $asc;
    }

    public function getAsc() {
        return $this->asc;
    }

    public function getField() {
        return $this->field;
    }

} 