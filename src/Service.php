<?php

namespace Phonf;

abstract class Service {

    protected $dao;

    function __construct() {}

    abstract function execute($method, $id);

    protected function returnCollectionJson($collection) {
        if(is_null($collection)) return null;

        $jsonArray = array();
        foreach($collection as $item) {
            /** @var Entity $item */
            $jsonArray[] = $item->getArray();
        }
        return json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

}