<?php

namespace Phonf;

class SQLBulkInsert {

    private $tableName;
    private $rows = array();

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
     * @param Array $row
     */
    public function addRow($row) {
        $this->rows[] = $row;
    }

    public function addRows($rows) {
        $this->rows = $rows;
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "INSERT INTO `" . $this->tableName . "` ";
        $fields = "";
        foreach($this->rows[0] as $field) {
            /** @var Field $field */
            $fields .= $field->getName() . ",";
        }
        $fields = rtrim($fields, ",");
        $statement .= "($fields) VALUES ";
        foreach($this->rows as $row) {
            $rowsql = "";
            foreach($row as $field) {
                /** @var Field $field */
                $value = $field->getDBValue();
                if (is_null($value) OR $value == "" OR $value == '""') $value = 'NULL';
                $rowsql .= "$value,";
            }
            $rowsql = rtrim($rowsql, ",");
            $statement .= "($rowsql),";
        }
        $statement = rtrim($statement, ",");

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