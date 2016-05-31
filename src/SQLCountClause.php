<?php

namespace Phonf;

class SQLCountClause {

    private $field;
    private $aliasField;

    public function __construct(Field $field) {
        $this->field = $field;
        $this->aliasField = new NumericField($field->getDatabase(), "Count");
    }

    /**
     * @param Field $field
     */
    public function setField(Field $field) {
        $this->field = $field;
    }

    /**
     * @return Field
     */
    public function getField() {
        return $this->field;
    }

    /**
     * @param Field $aliasField
     */
    public function setAliasField(Field $aliasField) {
        $this->aliasField = $aliasField;
    }

    /**
     * @return Field
     */
    public function getAliasField() {
        return $this->aliasField;
    }

}