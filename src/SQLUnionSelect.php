<?php

namespace Phonf;

class SQLUnionSelect {

    private $sqlSelects = array();

    public function addSqlSelect(SQLSelect $sqlSelect) {
        $this->sqlSelects[] = $sqlSelect;
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "";
        $selectCounter = 0;
        foreach ($this->sqlSelects as $sqlSelect) {
            $statement .= "(" . $sqlSelect->getQuery() . ")";
            $selectCounter++;
            if ($selectCounter != sizeof($this->sqlSelects) AND sizeof($this->sqlSelects) > 1) {
                $statement .= " UNION ";
            }
        }

        return $statement;
    }

    public function execute() {
        $connection = MySQLDAOFactory::getConnection();

        $result = $connection->query($this->getQuery());

        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        $connection->close();

        return $results;
    }

} 