<?php

namespace Phonf;

class SQLSelect {

    private $connection;
    private $tableName;
    private $distinct = false;
    private $fields = array();
    private $ifClauses = array();
    private $countClauses = array();
    private $joinClauses = array();
    private $whereClause;
    private $orderByClauses = array();
    private $bindResult = array();
    private $limit;
    private $offset = 0;

    function __construct(\mysqli $connection, $table = null) {
        $this->connection = $connection;
        $this->setTable($table);
        $this->whereClause = new SQLWhereClause();
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
     * @return $this
     */
    public function setDistinct() {
        $this->distinct = true;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addField(Field $field) {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @param array
     * @return $this
     */
    public function addFields($fieldsArray) {
        $this->fields = array_merge($this->fields, $fieldsArray);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearFields() {
        $this->fields = array();

        return $this;
    }

    /**
     * @param SQLIfClause $sqlIfClause
     * @return $this
     */
    public function addIfClause(SQLIfClause $sqlIfClause) {
        $this->ifClauses[] = $sqlIfClause;

        return $this;
    }

    /**
     * @param array $fieldsArray
     * @return $this
     */
    public function addIfClauses($fieldsArray) {
        $this->ifClauses = $fieldsArray;

        return $this;
    }

    /**
     * @param SQLCountClause $SQLCountClause
     * @return $this
     */
    public function addCountClause(SQLCountClause $SQLCountClause) {
        $this->countClauses[] = $SQLCountClause;

        return $this;
    }

    /**
     * @param array $fieldsArray
     * @return $this
     */
    public function addCountClauses($fieldsArray) {
        $this->countClauses = $fieldsArray;

        return $this;
    }

    /**
     * @param SQLWhereClause $whereClause
     */
    public function setWhereClause(SQLWhereClause $whereClause) {
        $this->whereClause = $whereClause;
    }

    /**
     * @param SQLJoinClause $sqlJoinClause
     * @return $this
     */
    public function addJoinClause(SQLJoinClause $sqlJoinClause) {
        $this->joinClauses[] = $sqlJoinClause;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereClauseField($field) {
        $this->whereClause->addWhereClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereDistinctClauseField(Field $field) {
        $this->whereClause->addWhereDistinctClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereLessThanClauseField(Field $field) {
        $this->whereClause->addWhereLessThanClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereLessOrEqualThanClauseField(Field $field) {
        $this->whereClause->addWhereLessOrEqualThanClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereMoreThanClauseField(Field $field) {
        $this->whereClause->addWhereMoreThanClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereMoreOrEqualThanClauseField(Field $field) {
        $this->whereClause->addWhereMoreOrEqualThanClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereIsNullClauseField(Field $field) {
        $this->whereClause->addWhereIsNullClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereIsNotNullClauseField(Field $field) {
        $this->whereClause->addWhereIsNotNullClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereClauseLikeField(Field $field) {
        $this->whereClause->addWhereClauseLikeField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereInField(Field $field) {
        $this->whereClause->addWhereInField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereNotInField(Field $field) {
        $this->whereClause->addWhereNotInField($field);

        return $this;
    }

    /**
     * @param string $clause
     * @return $this
     */
    public function addWhereCustomClause($clause) {
        $this->whereClause->addWhereCustomClause($clause);

        return $this;
    }

    /**
     * @param SQLOrderByClause $orderByClause
     * @return $this
     */
    public function addOrderByClause(SQLOrderByClause $orderByClause) {
        $this->orderByClauses[] = $orderByClause;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addOrderByClauseAsc(Field $field) {
        $this->orderByClauses[] = new SQLOrderByClause($field, true);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addOrderByClauseDesc(Field $field) {
        $this->orderByClauses[] = new SQLOrderByClause($field, false);

        return $this;
    }

    public function clearOrderByClauses() {
        $this->orderByClauses = array();

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addBindField(Field $field) {
        $this->bindResult[] = $field;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit) {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset) {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "SELECT ";

        if ($this->distinct) $statement .= "DISTINCT ";

        $fieldCounter = 0;
        foreach ($this->fields as $field) {
            /** @var $field Field */
            $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` AS `" . $field->getDatabase() . $field->getName() . "`";
            $fieldCounter++;
            if ($fieldCounter != (sizeof($this->fields) + sizeof($this->ifClauses) + sizeof($this->countClauses))) $statement .= ", ";
        }
        foreach ($this->ifClauses as $ifClause) {
            /** @var $ifClause SQLIfClause */
            $conditionField = $ifClause->getConditionField();
            $condition = $ifClause->getCondition();
            $condition = "`" . $conditionField->getDatabase() . "`.`" . $conditionField->getName() . "`" . " " . $condition;
            $trueField = $ifClause->getTrueClauseField();
            $trueClause = "`" . $trueField->getDatabase() . "`.`" . $trueField->getName() . "`";
            $falseField = $ifClause->getFalseClauseField();
            $falseClause = "`" . $falseField->getDatabase() . "`.`" . $falseField->getName() . "`";
            $alias = "`" . $ifClause->getAlias() . "`";

            $statement .= "IF ($condition, $trueClause, $falseClause) AS $alias";
            $fieldCounter++;
            if ($fieldCounter != (sizeof($this->fields) + sizeof($this->ifClauses) + sizeof($this->countClauses))) $statement .= ", ";
        }
        foreach ($this->countClauses as $countClause) {
            /** @var $countClause SQLCountClause */
            $field = $countClause->getField();
            $aliasField = $countClause->getAliasField();
            $statement .= "COUNT(DISTINCT `" . $field->getDatabase() . "`.`" . $field->getName() . "`) AS `" . $aliasField->getDatabase() . $aliasField->getName() . "`";
            $fieldCounter++;
            if ($fieldCounter != (sizeof($this->fields) + sizeof($this->ifClauses) + sizeof($this->countClauses))) $statement .= ", ";
        }

        $statement .= " FROM `" . $this->tableName . "`";

        foreach ($this->joinClauses as $joinClause) {
            /** @var $joinClause SQLJoinClause */
            $statement .= $joinClause->getQuery();
        }

        $statement .= $this->whereClause->getSQL();

        if (sizeof($this->orderByClauses) > 0) {
            $fieldCounter = 0;
            $statement .= " ORDER BY ";
            foreach ($this->orderByClauses as $orderByClause) {
                /** @var $orderByClause SQLOrderByClause */
                $field = $orderByClause->getField();
                if ($orderByClause->getAsc()) {
                    $asc = "ASC";
                } else {
                    $asc = "DESC";
                }
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` $asc";
                $fieldCounter++;
                if ($fieldCounter != sizeof($this->orderByClauses)) $statement .= ", ";
            }
        }

        if (!is_null($this->limit) AND !is_null($this->offset)) $statement .= " LIMIT " . $this->offset . ", " . $this->limit;

        return $statement;
    }

    public function execute() {
        $result = $this->connection->query($this->getQuery());

        $results = array();
        if ($result == false) return $results;

        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        $this->connection->close();

        return $results;
    }
}