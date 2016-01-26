<?php

namespace Phonf;

class SQLSelect {

    private $tableName;
    private $distinct = false;
    private $fields = array();
    private $ifClauses = array();
    private $countClauses = array();
    private $joinClauses = array();
    private $whereClauseFields = array();
    private $whereLessThanClauseFields = array();
    private $whereLessOrEqualThanClauseFields = array();
    private $whereMoreThanClauseFields = array();
    private $whereMoreOrEqualThanClauseFields = array();
    private $whereIsNotNullClauseFields = array();
    private $whereClauseLikeFields = array();
    private $whereCustomClauses = array();
    private $orderByClauses = array();
    private $bindResult = array();
    private $limit;
    private $offset = 0;

    function __construct($table = null) {
        $this->setTable($table);
    }

    /**
     * @param string $tableName
     */
    public function setTable($tableName) {
        $this->tableName = $tableName;
    }

    public function setDistinct() {
        $this->distinct = true;
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field) {
        $this->fields[] = $field;
    }

    public function addFields($fieldsArray) {
        $this->fields = array_merge($this->fields, $fieldsArray);
    }

    /**
     * @param SQLIfClause $sqlIfClause
     */
    public function addIfClause(SQLIfClause $sqlIfClause) {
        $this->ifClauses[] = $sqlIfClause;
    }

    public function addIfClauses($fieldsArray) {
        $this->ifClauses = $fieldsArray;
    }

    /**
     * @param SQLCountClause $SQLCountClause
     */
    public function addCountClause(SQLCountClause $SQLCountClause) {
        $this->countClauses[] = $SQLCountClause;
    }

    public function addCountClauses($fieldsArray) {
        $this->countClauses = $fieldsArray;
    }

    /**
     * @param SQLJoinClause $sqlJoinClause
     */
    public function addJoinClause(SQLJoinClause $sqlJoinClause) {
        $this->joinClauses[] = $sqlJoinClause;
    }

    /**
     * @param Field $field
     */
    public function addWhereClauseField(Field $field) {
        $this->whereClauseFields[] = $field;
    }

    /**
     * @param Field $field
     */
    public function addWhereLessThanClauseField(Field $field) {
        $this->whereLessThanClauseFields[] = $field;
    }

    /**
     * @param Field $field
     */
    public function addWhereLessOrEqualThanClauseField(Field $field) {
        $this->whereLessOrEqualThanClauseFields[] = $field;
    }

    /**
     * @param Field $field
     */
    public function addWhereMoreThanClauseField(Field $field) {
        $this->whereMoreThanClauseFields[] = $field;
    }

    /**
     * @param Field $field
     */
    public function addWhereMoreOrEqualThanClauseField(Field $field) {
        $this->whereMoreOrEqualThanClauseFields[] = $field;
    }

    /**
     * @param Field $field
     */
    public function addWhereIsNotNullClauseField(Field $field) {
        $this->whereIsNotNullClauseFields[] = $field;
    }

    public function getGlobalWhereClausesCount() {
        return
            sizeof($this->whereClauseFields) +
            sizeof($this->whereLessThanClauseFields) +
            sizeof($this->whereMoreThanClauseFields) +
            sizeof($this->whereLessOrEqualThanClauseFields) +
            sizeof($this->whereMoreOrEqualThanClauseFields) +
            sizeof($this->whereIsNotNullClauseFields) +
            sizeof($this->whereClauseLikeFields) +
            sizeof($this->whereCustomClauses);
    }

    /**
     * @param Field $field
     */
    public function addWhereClauseLikeField(Field $field) {
        $this->whereClauseLikeFields[] = $field;
    }

    public function addWhereCustomClause($clause) {
        $this->whereCustomClauses[] = $clause;
    }

    /**
     * @param SQLOrderByClause $orderByClause
     */
    public function addOrderByClause(SQLOrderByClause $orderByClause) {
        $this->orderByClauses[] = $orderByClause;
    }

    /**
     * @param Field $field
     */
    public function addOrderByClauseAsc(Field $field) {
        $this->orderByClauses[] = new SQLOrderByClause($field, true);
    }

    /**
     * @param Field $field
     */
    public function addOrderByClauseDesc(Field $field) {
        $this->orderByClauses[] = new SQLOrderByClause($field, false);
    }

    /**
     * @param Field $field
     */
    public function addBindField(Field $field) {
        $this->bindResult[] = $field;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function setOffset($offset) {
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "SELECT ";

        if ($this->distinct) {
            $statement .= "DISTINCT ";
        }

        $fieldCounter = 0;
        foreach ($this->fields as $field) {
            /** @var $field Field */
            $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` AS `" . $field->getDatabase() . $field->getName() . "`";
            $fieldCounter++;
            if ($fieldCounter != (sizeof($this->fields) + sizeof($this->ifClauses) + sizeof($this->countClauses))) {
                $statement .= ", ";
            }
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
            if ($fieldCounter != (sizeof($this->fields) + sizeof($this->ifClauses) + sizeof($this->countClauses))) {
                $statement .= ", ";
            }
        }
        foreach ($this->countClauses as $countClause) {
            /** @var $countClause SQLCountClause */
            $field = $countClause->getField();
            $aliasField = $countClause->getAliasField();
            $statement .= "COUNT(DISTINCT `" . $field->getDatabase() . "`.`" . $field->getName() . "`) AS `" . $aliasField->getDatabase() . $aliasField->getName() . "`";
            $fieldCounter++;
            if ($fieldCounter != (sizeof($this->fields) + sizeof($this->ifClauses) + sizeof($this->countClauses))) {
                $statement .= ", ";
            }
        }

        $statement .= " FROM ";

        $statement .= "`" . $this->tableName . "`";

        foreach ($this->joinClauses as $joinClause) {
            /** @var $joinClause SQLJoinClause */
            $statement .= $joinClause->getQuery();
        }

        if ($this->getGlobalWhereClausesCount() > 0) {
            $statement .= " WHERE ";
            $fieldCounter = 0;
        }

        if (sizeof($this->whereClauseFields) > 0) {
            foreach ($this->whereClauseFields as $field) {
                /** @var $field Field */
                if ($field->getDBValue() == "null") {
                    $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` IS " . $field->getDBValue();
                } else {
                    $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` = " . $field->getDBValue();
                }
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                    $statement .= " AND ";
                }
            }
        }

        if (sizeof($this->whereLessThanClauseFields) > 0) {
            foreach ($this->whereLessThanClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` < " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                    $statement .= " AND ";
                }
            }
        }

        if (sizeof($this->whereLessOrEqualThanClauseFields) > 0) {
            foreach ($this->whereLessOrEqualThanClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` <= " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                    $statement .= " AND ";
                }
            }
        }

        if (sizeof($this->whereMoreThanClauseFields) > 0) {
            foreach ($this->whereMoreThanClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` > " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                    $statement .= " AND ";
                }
            }
        }

        if (sizeof($this->whereMoreOrEqualThanClauseFields) > 0) {
            foreach ($this->whereMoreOrEqualThanClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` >= " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                    $statement .= " AND ";
                }
            }
        }

        if (sizeof($this->whereIsNotNullClauseFields) > 0) {
            foreach ($this->whereIsNotNullClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` IS NOT NULL";
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                    $statement .= " AND ";
                }
            }
        }

        if (sizeof($this->whereClauseLikeFields) > 0) {
            $statement .= "(";
            $fieldCounterBis = 0;
            foreach ($this->whereClauseLikeFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` LIKE '%" . $field->getValue() . "%'";
                $fieldCounterBis++;
                $fieldCounter++;
                if ($fieldCounterBis != sizeof($this->whereClauseLikeFields) AND sizeof($this->whereClauseLikeFields) >= 1) {
                    $statement .= " OR ";
                }
            }
            $statement .= ")";
            if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                $statement .= " AND ";
            }
        }

        if (sizeof($this->whereCustomClauses) > 0) {
            foreach ($this->whereCustomClauses as $expression) {
                $statement .= "($expression)";
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                    $statement .= " AND ";
                }
            }
        }

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
                if ($fieldCounter != sizeof($this->orderByClauses) AND $fieldCounter >= 1) {
                    $statement .= ", ";
                }
            }
        }

        if (!is_null($this->limit) AND !is_null($this->offset)) {
            $statement .= " LIMIT " . $this->offset . ", " . $this->limit;
        }

        return $statement;
    }

    public function execute() {
        $connection = DAOFactory::getConnection();

        $result = $connection->query($this->getQuery());

        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        $connection->close();

        return $results;
    }
}