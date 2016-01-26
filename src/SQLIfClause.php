<?php

namespace Phonf;

class SQLIfClause {

    private $conditionField;
    private $condition;
    private $trueClauseField;
    private $falseClauseField;
    private $alias;

    public function setConditionField($conditionField) {
        $this->conditionField = $conditionField;
    }

    public function getConditionField() {
        return $this->conditionField;
    }

    public function setCondition($condition) {
        $this->condition = $condition;
    }

    public function getCondition() {
        return $this->condition;
    }

    public function setFalseClauseField($falseClauseField) {
        $this->falseClauseField = $falseClauseField;
    }

    public function getFalseClauseField() {
        return $this->falseClauseField;
    }

    public function setTrueClauseField($trueClauseField) {
        $this->trueClauseField = $trueClauseField;
    }

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