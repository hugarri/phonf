<?php

namespace Phonf;

class SQLDelete {

    private $connection;
    private $tableName;
    private $whereClauseFields = array();
    private $whereCustomClauses = array();
    private $whereInFields = array();

    function __construct(\mysqli $connection, $table = null) {
        $this->connection = $connection;
        $this->setTable($table);
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
     * @param Field $field
     * @return $this
     */
    public function addWhereClauseField(Field $field) {
        $this->whereClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereInField(Field $field) {
        $this->whereInFields[] = $field;

        return $this;
    }

    /**
     * @param string $clause
     * @return $this
     */
    public function addWhereCustomClause($clause) {
        $this->whereCustomClauses[] = $clause;

        return $this;
    }

    public function getGlobalWhereClausesCount() {
        return
            sizeof($this->whereClauseFields) +
            sizeof($this->whereCustomClauses) +
            sizeof($this->whereInFields);
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "DELETE " . "FROM `" . $this->tableName . "`";

        $fieldCounter = 0;
        $statement .= " WHERE ";
        if (sizeof($this->whereClauseFields) > 0) {
            foreach ($this->whereClauseFields as $field) {
                /** @var $field Field */
                $statement .= "`" . $field->getName() . "` = " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                    $statement .= " AND ";
                }
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
        if (sizeof($this->whereInFields) > 0) {
            foreach ($this->whereInFields as $field) {
                if (is_array($field->getValue())) {
                    $statement .= "`".$field->getDatabase()."`.`".$field->getName()."` IN (";
                    $items = "";
                    foreach ($field->getValue() as $item) {
                        $items .= "'$item',";
                    }
                    $statement .= rtrim($items,",");
                    $statement .= ")";
                    $fieldCounter++;
                    if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
                        $statement .= " AND ";
                    }
                }
            }
        }

        return $statement;
    }

    public function execute() {
        $statement = $this->connection->stmt_init();
        $statement->prepare($this->getQuery());
        $statement->execute();
        $error = $statement->errno;
        $statement->close();
        $this->connection->close();

        return $error;
    }

}