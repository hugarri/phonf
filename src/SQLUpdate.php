<?php

namespace Phonf;

class SQLUpdate {

    private $connection;
    private $tableName;
    private $fields = array();
    private $whereClause;

    function __construct(\mysqli $connection, $table = null) {
        $this->connection = $connection;
        $this->setTable($table);
        $this->whereClause = new SQLWhereClause();
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
     * @param SQLWhereClause $whereClause
     */
    public function setWhereClause(SQLWhereClause $whereClause) {
        $this->whereClause = $whereClause;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereClauseField($field) {
        $this->whereClause->addWhereClauseField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereInField(Field $field) {
        $this->whereClause->addWhereInField($field);

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereIsNullClauseField(Field $field) {
        $this->whereClause->addWhereIsNullClauseField($field);

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery() {
        $statement = "UPDATE `" . $this->tableName . "` SET ";
        $fieldCounter = 0;
        foreach($this->fields as $field) {
            /** @var Field $field */
            $statement .= "`" . $field->getName() . "` = " . $field->getDBValue();
            $fieldCounter++;
            if ($fieldCounter != sizeof($this->fields)) {
                $statement .= ", ";
            }
        }
        $statement .= $this->whereClause->getSQL();

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