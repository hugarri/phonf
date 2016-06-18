<?php

namespace Phonf;

class SQLUpdate {

    private $connection;
    private $tableName;
    private $fields = array();
    private $whereClauseFields = array();
    private $whereInFields = array();
    private $whereIsNullClauseFields = array();

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
    public function addField($field) {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereClauseField($field) {
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

    public function getGlobalWhereClausesCount() {
        return
            sizeof($this->whereClauseFields) +
            sizeof($this->whereInFields) +
            sizeof($this->whereIsNullClauseFields);
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

        if (sizeof($this->whereClauseFields) > 0) {
            foreach ($this->whereClauseFields as $field) {
                /** @var $field Field */
                if ($field->getDBValue() === "null") {
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

        if (sizeof($this->whereInFields) > 0) {
            foreach ($this->whereInFields as $field) {
                /** @var $field Field */
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

        if (sizeof($this->whereIsNullClauseFields) > 0) {
            foreach ($this->whereIsNullClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` IS NULL";
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) {
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