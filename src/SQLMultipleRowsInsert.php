<?php

namespace Phonf;

class SQLMultipleRowsInsert {

    private $tableName;
    private $fields = array();
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
     * @param Field $field
     */
    public function addField(Field $field) {
        $this->fields[] = $field;
    }

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "INSERT " . "INTO `" . $this->tableName . "` (";

        $fieldCounter = 0;
        foreach($this->fields as $field) {
            $fieldCounter++;
            $statement .= "`" . $field->getName() . "`";
            if ($fieldCounter != sizeof($this->fields)) {
                $statement .= ", ";
            }
        }

        $statement .= ") VALUES ";

        $rowCounter = 0;
        foreach ($this->rows as $row) {
            $fieldCounter = 0;
            $rowCounter++;
            $statement .= "(";
            foreach ($row as $field) {
                $statement .= $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != sizeof($this->fields)) {
                    $statement .= ", ";
                }
            }
            $statement .= ")";
            if ($rowCounter != sizeof($this->rows)) {
                $statement .= ", ";
            } else {
                $statement .= ";";
            }

        }

        return $statement;
    }

    public function execute() {
        $connection = MySQLDAOFactory::getConnection();
        $statement = $connection->stmt_init();
        $statement->prepare($this->getQuery());
        $statement->execute();

        $error = $statement->errno;

        if ($error == 0) {
            $result = 0;
        } else {
            $result = -1;
        }
        $statement->close();
        $connection->close();

        return $result;
    }

} 