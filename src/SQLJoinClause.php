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
    private $onDistinctClauseFields = array();

    function __construct($tableName = null, $tableAlias = null) {
        $this->setTable($tableName);
        $this->setTableAlias($tableAlias);
    }

    /**
     * @return $this
     */
    public function setInnerJoin() {
        $this->isLeftJoin = false;

        return $this;
    }

    /**
     * @param string $tableName
     * @return $this
     */
    public function setTable($tableName) {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @param string $tableAlias
     * @return $this
     */
    public function setTableAlias($tableAlias) {
        $this->tableAlias = $tableAlias;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addOriginField(Field $field) {
        $this->originFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addDestinyField(Field $field) {
        $this->destinyFields[] = $field;

        return $this;
    }

    /**
     * @param Field $originField
     * @param Field $destinyField
     * @return $this
     */
    public function addOnClauseFields(Field $originField, Field $destinyField) {
        $this->addOriginField($originField);
        $this->addDestinyField($destinyField);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addConditionField(Field $field) {
        $this->conditionFields[] = $field;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function addConditionValue($value) {
        $this->conditionValues[] = $value;

        return $this;
    }

    /**
     * @param Field $field
     * @param $value
     * @return $this
     */
    public function addOnClauseCondition(Field $field, $value) {
        $this->addConditionField($field);
        $this->addConditionValue($value);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addOnDistinctClauseFields(Field $field) {
        $this->onDistinctClauseFields[] = $field;

        return $this;
    }

    public function getGlobalWhereClausesCount() {
        return
            sizeof($this->originFields) +
            sizeof($this->conditionFields) +
            sizeof($this->onDistinctClauseFields);
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
            if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
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
            if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
        }

        if (sizeof($this->onDistinctClauseFields) > 0) {
            foreach ($this->onDistinctClauseFields as $field) {
                /** @var $field Field */
                if ($field->getDBValue() === "null") $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` IS NOT " . $field->getDBValue();
                else $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` != " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        return $statement;
    }

}