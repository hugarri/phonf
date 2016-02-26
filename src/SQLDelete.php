<?php

namespace Phonf;

class SQLDelete {

    private $connection;
    private $tableName;
    private $whereClauseFields = array();
    private $whereCustomClauses = array();

    function __construct(\mysqli $connection, $table = null) {
        $this->connection = $connection;
        $this->setTable($table);
    }

    /**
     * @param string $tableName
     */
    public function setTable($tableName) {
        $this->tableName = $tableName;
    }

    /**
     * @param Field $field
     */
    public function addWhereClauseField(Field $field) {
        $this->whereClauseFields[] = $field;
    }

    public function addWhereCustomClause($clause) {
        $this->whereCustomClauses[] = $clause;
    }

    public function getGlobalWhereClausesCount() {
        return sizeof($this->whereClauseFields) + sizeof($this->whereCustomClauses);
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
                if ($fieldCounter != $this->getGlobalWhereClausesCount() AND $fieldCounter >= 1) {
                    $statement .= " AND ";
                }
            }
        }
        if (sizeof($this->whereCustomClauses) > 0) {
            foreach ($this->whereCustomClauses as $expression) {
                $statement .= "($expression)";
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount() AND $fieldCounter >= 1) {
                    $statement .= " AND ";
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