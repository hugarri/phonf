<?php

namespace Phonf;

class SQLInsert {

    private $connection;
    private $tableName;
    private $fields = array();

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
    public function addField(Field $field) {
        $this->fields[] = $field;

        return $this;
    }

    public function addFields($fieldsArray) {
        $this->fields = array_merge($this->fields, $fieldsArray);

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "INSERT INTO `" . $this->tableName . "` SET ";

        $fieldCounter = 0;
        foreach($this->fields as $field) {
            $name = $field->getName();
            $value = $field->getDBValue();
            if (is_null($value) OR $value == "" OR $value == '""') $value = 'NULL';
            $statement .= "`$name` = $value";
            $fieldCounter++;
            if ($fieldCounter != sizeof($this->fields)) {
                $statement .= ", ";
            }
        }

        return $statement;
    }

    public function execute() {
        $statement = $this->connection->stmt_init();
        $statement->prepare($this->getQuery());

        $statement->execute();

        $error = $statement->errno;

        if ($error == 0) {
            $result = $this->connection->insert_id;
        } else {
            $result = -1;
        }
        $statement->close();
        $this->connection->close();

        return $result;
    }

} 