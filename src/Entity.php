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
    
    public function val($fieldName, $value = null) {
        if (!is_null($value)) $this->setFieldValue($fieldName, $value);

        return $this->getFieldValue($fieldName);
    }

    protected function createField($fieldName, Field $field) {
        $this->fields[$fieldName] = $field;
    }

    public function setFieldValue($fieldName, $value, $queryResult = false) {
        if($queryResult) :
            $tableAlias = array_values($this->fields)[0]->getDatabase();
            if(strpos($fieldName, $tableAlias) !== false) :
                foreach($this->fields as $id => $field) :
                    if ($field->getDBAlias() == $fieldName) :
                        $this->fields[$id]->setValue($value);
                    endif;
                endforeach;
            endif;
        else :
            if(array_key_exists($fieldName, $this->fields)) :
                $this->fields[$fieldName]->setValue($value);
            endif;
        endif;
    }

    /**
     * @param $fieldName
     * @return Field | null
     */
    public function getField($fieldName) {
        if(array_key_exists($fieldName, $this->fields)) :
            return $this->fields[$fieldName];
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

    public function getEssentialFields() {
        return $this->getFields();
    }

    public function getFieldValue($fieldName) {
        if(array_key_exists($fieldName, $this->fields)) :
            return $this->fields[$fieldName]->getValue();
        endif;

        return null;
    }

    public function createEntity($entityName, Entity $entity) {
        $this->entities[$entityName] = $entity;
    }

    /**
     * @param $entityName
     * @return Entity|null
     */
    public function getEntity($entityName) {
        if (array_key_exists($entityName, $this->entities)) {
            return $this->entities[$entityName];
        }
        return null;
    }

    public function createCollection($collectionName, $collection) {
        $this->collections[$collectionName] = $collection;
    }

    public function getCollection($collectionName) {
        if (array_key_exists($collectionName, $this->collections)) {
            return $this->collections[$collectionName];
        }
        return null;
    }

    public function getArray($structureToReturn = null) {
        $array = array();
        $null = true;

        $fieldsToReturn = $entitiesToReturn = $collectionsToReturn = array();
        if (!empty($structureToReturn)) {
            if (isset($structureToReturn->Fields)) $fieldsToReturn = $structureToReturn->Fields;
            if (isset($structureToReturn->Entities)) :
                foreach ($structureToReturn->Entities as $entity) $entitiesToReturn[$entity->Entity] = $entity;
            endif;
            if (isset($structureToReturn->Collections)) :
                foreach ($structureToReturn->Collections as $collection) $collectionsToReturn[$collection->Collection] = $collection;
            endif;
        }

        foreach($this->fields as $id => $field) :
            if (empty($fieldsToReturn) or in_array($id, $fieldsToReturn)) :
                $value = $this->getFieldValue($id);
                $array[$id] = $value;
                if (!is_null($value) and $null == true) $null = false;
            endif;
        endforeach;

        if ($this->entities) :
            foreach($this->entities as $id => $entity) :
                /* @var $entity Entity */
                if (empty($entitiesToReturn) or isset($entitiesToReturn[$id])) :
                    $entityStructureToReturn = null;
                    if (array_key_exists($id, $entitiesToReturn)) $entityStructureToReturn = $entitiesToReturn[$id];
                    $array[$id] = $entity->getArray($entityStructureToReturn);
                endif;
            endforeach;
        endif;

        if ($this->collections) :
            foreach($this->collections as $id => $entities) :
                if ($entities) :
                    $collection = array();
                    foreach ($entities as $internalId => $entity) :
                        /* @var $entity Entity */
                        $collectionStructureToReturn = null;
                        if (array_key_exists($id, $collectionsToReturn)) $collectionStructureToReturn = $collectionsToReturn[$id];
                        $collection[$internalId] = $entity->getArray($collectionStructureToReturn);
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