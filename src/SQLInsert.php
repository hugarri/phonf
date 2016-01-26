<?php

namespace Phonf;

class SQLInsert {

    private $tableName;
    private $fields = array();

    function __construct($table = null) {
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
    public function addField(Field $field) {
        $this->fields[] = $field;
    }

    public function addFields($fieldsArray) {
        $this->fields = array_merge($this->fields, $fieldsArray);
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
        $connection = DAOFactory::getConnection();
        $statement = $connection->stmt_init();
        $statement->prepare($this->getQuery());

        $statement->execute();

        $error = $statement->errno;

        if ($error == 0) {
            $result = $connection->insert_id;
        } else {
            $result = -1;
        }
        $statement->close();
        $connection->close();

        return $result;
    }

} 