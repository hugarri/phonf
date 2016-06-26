<?php

namespace Phonf;

class SQLWhereClause {

    private $whereClauseFields = array();
    private $whereDistinctClauseFields = array();
    private $whereLessThanClauseFields = array();
    private $whereLessOrEqualThanClauseFields = array();
    private $whereMoreThanClauseFields = array();
    private $whereMoreOrEqualThanClauseFields = array();
    private $whereIsNullClauseFields = array();
    private $whereIsNotNullClauseFields = array();
    private $whereClauseLikeFields = array();
    private $whereInFields = array();
    private $whereNotInFields = array();
    private $whereCustomClauses = array();

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereClauseField(Field $field) {
        $this->whereClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereDistinctClauseField(Field $field) {
        $this->whereDistinctClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereLessThanClauseField(Field $field) {
        $this->whereLessThanClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereLessOrEqualThanClauseField(Field $field) {
        $this->whereLessOrEqualThanClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereMoreThanClauseField(Field $field) {
        $this->whereMoreThanClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereMoreOrEqualThanClauseField(Field $field) {
        $this->whereMoreOrEqualThanClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereIsNullClauseField(Field $field) {
        $this->whereIsNullClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereIsNotNullClauseField(Field $field) {
        $this->whereIsNotNullClauseFields[] = $field;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereClauseLikeField(Field $field) {
        $this->whereClauseLikeFields[] = $field;

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

    /**
     * @param Field $field
     * @return $this
     */
    public function addWhereNotInField(Field $field) {
        $this->whereNotInFields[] = $field;

        return $this;
    }

    /**
     * @param string $clause
     * @return $this
     */
    public function addWhereCustomClause($clause) {
        $this->whereCustomClauses[] = $clause;

        return $this;
    }

    public function getGlobalWhereClausesCount() {
        return
            sizeof($this->whereClauseFields) +
            sizeof($this->whereDistinctClauseFields) +
            sizeof($this->whereLessThanClauseFields) +
            sizeof($this->whereMoreThanClauseFields) +
            sizeof($this->whereLessOrEqualThanClauseFields) +
            sizeof($this->whereMoreOrEqualThanClauseFields) +
            sizeof($this->whereIsNullClauseFields) +
            sizeof($this->whereIsNotNullClauseFields) +
            sizeof($this->whereClauseLikeFields) +
            sizeof($this->whereInFields) +
            sizeof($this->whereNotInFields) +
            sizeof($this->whereCustomClauses);
    }

    /**
     * @return string
     */
    public function getSQL() {
        if ($this->getGlobalWhereClausesCount() == 0) return "";

        $statement = " WHERE ";
        $fieldCounter = 0;

        if (sizeof($this->whereClauseFields) > 0) {
            foreach ($this->whereClauseFields as $field) {
                /** @var $field Field */
                if ($field->getDBValue() === "null") $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` IS " . $field->getDBValue();
                else $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` = " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        if (sizeof($this->whereDistinctClauseFields) > 0) {
            foreach ($this->whereDistinctClauseFields as $field) {
                /** @var $field Field */
                if ($field->getDBValue() === "null") $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` IS NOT " . $field->getDBValue();
                else $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` != " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        if (sizeof($this->whereLessThanClauseFields) > 0) {
            foreach ($this->whereLessThanClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` < " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        if (sizeof($this->whereLessOrEqualThanClauseFields) > 0) {
            foreach ($this->whereLessOrEqualThanClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` <= " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        if (sizeof($this->whereMoreThanClauseFields) > 0) {
            foreach ($this->whereMoreThanClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` > " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        if (sizeof($this->whereMoreOrEqualThanClauseFields) > 0) {
            foreach ($this->whereMoreOrEqualThanClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` >= " . $field->getDBValue();
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        if (sizeof($this->whereIsNotNullClauseFields) > 0) {
            foreach ($this->whereIsNotNullClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` IS NOT NULL";
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        if (sizeof($this->whereIsNullClauseFields) > 0) {
            foreach ($this->whereIsNullClauseFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` IS NULL";
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        if (sizeof($this->whereClauseLikeFields) > 0) {
            $statement .= "(";
            $fieldCounterBis = 0;
            foreach ($this->whereClauseLikeFields as $field) {
                $statement .= "`" . $field->getDatabase() . "`.`" . $field->getName() . "` LIKE '%" . $field->getValue() . "%'";
                $fieldCounterBis++;
                $fieldCounter++;
                if ($fieldCounterBis != sizeof($this->whereClauseLikeFields) AND sizeof($this->whereClauseLikeFields) >= 1) $statement .= " AND ";
            }
            $statement .= ")";
            if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
        }

        if (sizeof($this->whereInFields) > 0) {
            foreach ($this->whereInFields as $field) {
                if (is_array($field->getValue())) {
                    $statement .= "`".$field->getDatabase()."`.`".$field->getName()."` IN (";
                    $items = "";
                    foreach ($field->getValue() as $item) $items .= "'$item',";
                    $statement .= rtrim($items,",");
                    $statement .= ")";
                    $fieldCounter++;
                    if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
                }
            }
        }

        if (sizeof($this->whereNotInFields) > 0) {
            foreach ($this->whereNotInFields as $field) {
                if (is_array($field->getValue())) {
                    $statement .= "`".$field->getDatabase()."`.`".$field->getName()."` NOT IN (";
                    $items = "";
                    foreach ($field->getValue() as $item) $items .= "'$item',";
                    $statement .= rtrim($items,",");
                    $statement .= ")";
                    $fieldCounter++;
                    if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
                }
            }
        }

        if (sizeof($this->whereCustomClauses) > 0) {
            foreach ($this->whereCustomClauses as $expression) {
                $statement .= "($expression)";
                $fieldCounter++;
                if ($fieldCounter != $this->getGlobalWhereClausesCount()) $statement .= " AND ";
            }
        }

        return $statement;
    }
}