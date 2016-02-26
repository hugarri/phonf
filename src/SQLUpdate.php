<?php

namespace Phonf;

class SQLUpdate {

    private $connection;
    private $tableName;
    private $fields = array();
    private $whereClauseFields = array();

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
    public function addField($field) {
        $this->fields[] = $field;
    }

    /**
     * @param Field $field
     */
    public function addWhereClauseField($field) {
        $this->whereClauseFields[] = $field;
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "UPDATE `" . $this->tableName . "` SET ";

        $fieldCounter = 0;
        foreach($this->fields as $field) {
            $statement .= "`" . $field->getName() . "` = " . $field->getDBValue();
            $fieldCounter++;
            if ($fieldCounter != sizeof($this->fields)) {
                $statement .= ", ";
            }
        }
        $fieldCounter = 0;
        $statement .= " WHERE ";
        foreach($this->whereClauseFields as $field) {
            $statement .= "`" . $field->getName() . "` = " . $field->getDBValue();
            $fieldCounter++;
            if ($fieldCounter != sizeof($this->whereClauseFields) AND $fieldCounter >= 1) {
                $statement .= " AND ";
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