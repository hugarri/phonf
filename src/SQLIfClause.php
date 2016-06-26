<?php

namespace Phonf;

class SQLIfClause {

    private $conditionField;
    private $condition;
    private $trueClauseField;
    private $falseClauseField;
    private $alias;

    public function setConditionField(Field $conditionField) {
        $this->conditionField = $conditionField;
    }

    /**
     * @return Field
     */
    public function getConditionField() {
        return $this->conditionField;
    }

    public function setCondition($condition) {
        $this->condition = $condition;
    }

    public function getCondition() {
        return $this->condition;
    }

    /**
     * @param Field $falseClauseField
     */
    public function setFalseClauseField($falseClauseField) {
        $this->falseClauseField = $falseClauseField;
    }

    /**
     * @return Field
     */
    public function getFalseClauseField() {
        return $this->falseClauseField;
    }

    /**
     * @param Field $trueClauseField
     */
    public function setTrueClauseField($trueClauseField) {
        $this->trueClauseField = $trueClauseField;
    }

    /**
     * @return Field
     */
    public function getTrueClauseField() {
        return $this->trueClauseField;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }

    public function getAlias() {
        return $this->alias;
    }

}