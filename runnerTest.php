<?php
include "parser.php";
include "validator.php";

$myFile = "stores.xml";
$myParser = new XmlParser;
// $myParser->setSourceFile("stores.xml");
$myParser->setFieldsRequired ([
    "number",
    "name",
    "address",
    "siteid",
    "lat",
    "lon",
    "phone_number",
    "cfs_flag"
]);
$stores = $myParser->parseData($myFile);

$myValidator = new validator;
$validatorValues = [
    "number" => "/^[0-9]{2,3}$/",
    "name" => "/^[a-z\d\-_\s]+$/i",
    "siteid" => "/^[A-Z]{2}$/",
    "lat" => "/^[+\-]?[0-9]{1,3}\.[0-9]{3,}\z/",
    "lon" => "/^[+\-]?[0-9]{1,3}\.[0-9]{3,}\z/",
    "phone_number" => "/^[0-9 ]+$/",
    // "phone_number1" => "/^[0-9]{4} [0-9]{3} [0-9]{3,4}$/",
    "cfs_flag" => "/^(?:Y|N|y|n)$/"
];
$myValidator->setValidators($validatorValues);
$myValidator->validateData($stores);



