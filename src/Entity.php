<?php

namespace Phonf;

abstract class Entity {

    protected $hasAlias = false;

    protected $fields = array();

    protected $entities = array();
    protected $collections = array();

    public function setTableAlias($alias) {
        $this->hasAlias = true;
        foreach($this->fields as $field) :
            /* @var $field Field */
            $field->setTableAlias($alias);
        endforeach;
    }

    protected function createField($fieldname, Field $field) {
        $this->fields[$fieldname] = $field;
    }

    public function setFieldValue($fieldname, $value, $queryResult = false) {
        if($queryResult) :
            $tableAlias = array_values($this->fields)[0]->getDatabase();
            if(strpos($fieldname, $tableAlias) !== false) :
                foreach($this->fields as $id => $field) :
                    if ($field->getDBAlias() == $fieldname) :
                        $this->fields[$id]->setValue($value);
                    endif;
                endforeach;
            endif;
        else :
            if(array_key_exists($fieldname, $this->fields)) :
                $this->fields[$fieldname]->setValue($value);
            endif;
        endif;
    }

    public function getField($fieldname) {
        if(array_key_exists($fieldname, $this->fields)) :
            return $this->fields[$fieldname];
        endif;

        return null;
    }

    public function getFieldValue($fieldname) {
        if(array_key_exists($fieldname, $this->fields)) :
            return $this->fields[$fieldname]->getValue();
        endif;

        return null;
    }

    public function getFields() {
        $aux = array();
        foreach ($this->fields as $id => $field) :
            /** @var Field $field */
            $key = $field->getDatabase() . "$id";
            $aux[$key] = $field;
        endforeach;

        return $aux;
    }

    public function createEntity($entityName, Entity $entity) {
        $this->entities[$entityName] = $entity;
    }

    public function createCollection($collectionName, $collection) {
        $this->collections[$collectionName] = $collection;
    }

    public function getArray() {
        $array = array();
        $null = true;
        foreach($this->fields as $id => $field) :
            $value = $this->getFieldValue($id);
            $array[$id] = $value;
            if (!is_null($value) and $null == true) $null = false;
        endforeach;

        if ($this->entities) :
            foreach($this->entities as $id => $entity) :
                /* @var $entity Entity */
                $array[$id] = $entity->getArray();
            endforeach;
        endif;

        if ($this->collections) :
            foreach($this->collections as $id => $entities) :
                if ($entities) :
                    $collection = array();
                    foreach($entities as $internalId => $entity) :
                        /* @var $entity Entity */
                        $collection[$internalId] = $entity->getArray();
                    endforeach;
                    $array[$id] = $collection;
                endif;
            endforeach;
        endif;

        if ($null) return null;

        return $array;
    }

    public function getJson() {
        $array = $this->getArray();

        if (is_null($array)) return null;

        return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array $array
     */
    public function setFromArray($array) {
        foreach ($array as $key => $value) {
            $this->setFieldValue($key, $value);
        }
    }

}