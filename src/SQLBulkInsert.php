<?php

namespace Phonf;

class SQLBulkInsert {

    private $connection;
    private $tableName;
    private $rows = array();
    private $ignore = "";

    function __construct(\mysqli $connection, $table = null) {
        $this->connection = $connection;
        $this->setTable($table);
    }

    public function setInsertIgnore() {
        $this->ignore = "IGNORE ";
    }

    /**
     * @param string $tableName
     */
    public function setTable($tableName) {
        $this->tableName = $tableName;
    }

    /**
     * @param array $row
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
        $statement = "INSERT " . $this->ignore . "INTO `$this->tableName` ";
        $fields = "";

        if (empty($this->rows)) return null;

        foreach($this->rows[0] as $field) {
            /** @var Field $field */
            $fields .= "`" . $field->getName() . "`,";
        }
        $fields = rtrim($fields, ",");
        $statement .= "($fields) VALUES ";
        foreach($this->rows as $row) {
            $rowsql = "";
            foreach($row as $field) {
                /** @var Field $field */
                $value = $field->getDBValue();
                if (is_null($value) OR $value === "" OR $value === '""') $value = 'NULL';
                $rowsql .= "$value,";
            }
            $rowsql = rtrim($rowsql, ",");
            $statement .= "($rowsql),";
        }
        $statement = rtrim($statement, ",");

        return $statement;
    }

    public function execute() {
        $statement = $this->connection->stmt_init();

        $query = $this->getQuery();

        if (empty($query)) return null;

        if ($statement->prepare($query)) $statement->execute();

        $error = $statement->errno;

        if ($error == 0) $result = $this->connection->insert_id;
        else $result = -1;

        $statement->close();
        $this->connection->close();

        return $result;
    }

} 