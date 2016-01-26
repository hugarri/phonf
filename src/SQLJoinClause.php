<?php

namespace Phonf;

class SQLJoinClause {

    private $isLeftJoin = true;
    private $tableName;
    private $tableAlias = null;
    private $originFields = array();
    private $destinyFields = array();
    private $conditionFields = array();
    private $conditionValues = array();

    function __construct($tableName = null, $tableAlias = null) {
        $this->setTable($tableName);
        $this->setTableAlias($tableAlias);
    }

    public function setInnerJoin() {
        $this->isLeftJoin = false;
    }

    /**
     * @param string $tableName
     */
    public function setTable($tableName) {
        $this->tableName = $tableName;
    }

    /**
     * @param string $tableAlias
     */
    public function setTableAlias($tableAlias) {
        $this->tableAlias = $tableAlias;
    }

    /**
     * @param Field $field
     */
    public function addOriginField(Field $field) {
        $this->originFields[] = $field;
    }

    /**
     * @param Field $field
     */
    public function addDestinyField(Field $field) {
        $this->destinyFields[] = $field;
    }

    public function addOnClauseFields(Field $originField, Field $destinyField) {
        $this->addOriginField($originField);
        $this->addDestinyField($destinyField);
    }

    /**
     * @param Field $field
     */
    public function addConditionField(Field $field) {
        $this->conditionFields[] = $field;
    }

    public function addConditionValue($value) {
        $this->conditionValues[] = $value;
    }

    public function addOnClauseCondition(Field $field, $value) {
        $this->addConditionField($field);
        $this->addConditionValue($value);
    }

    public function getQuery() {
        if ($this->isLeftJoin) {
            $statement = " LEFT JOIN ";
        } else {
            $statement = " JOIN ";
        }
        $statement .= "`" . $this->tableName . "`";
        if (!is_null($this->tableAlias)) {
            $statement .= " `" . $this->tableAlias . "`";
        }
        $statement .= " ON ";

        $fieldCounter = 0;
        for($i=0; $i<sizeof($this->originFields); $i++) {
            $originField = $this->originFields[$i];
            $destinyField = $this->destinyFields[$i];
            $statement .= "`" . $originField->getDatabase() . "`.`" . $originField->getName() . "` = `";
            $statement .= $destinyField->getDatabase() . "`.`" . $destinyField->getName() . "`";
            $fieldCounter++;
            if (($fieldCounter != sizeof($this->originFields) AND $fieldCounter >= 1) OR (sizeof($this->conditionFields) > 0)) {
                $statement .= " AND ";
            }
        }

        $fieldCounter = 0;
        for($i=0; $i<sizeof($this->conditionFields); $i++) {
            $field = $this->conditionFields[$i];
            $value = $this->conditionValues[$i];
            $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` ";
            if (!is_null($value)) {
                $statement .= "= '$value'";
            } else {
                $statement .= "IS NULL";
            }
            $fieldCounter++;
            if ($fieldCounter != sizeof($this->conditionFields) AND $fieldCounter >= 1) {
                $statement .= " AND ";
            }
        }

        return $statement;
    }

} 