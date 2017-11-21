<?php
include "parser.php";
include "validator.php";
include "loader.php";
include "transformer.php";

$myFile = "stores.xml";
$validatorValues = [
    "number" => "/^[0-9]{2,3}$/",
    "name" => "/\p{L}/",
    "siteid" => "/^[A-Z]{2}$/",
    "address_line_1" => "/\p{L}/",
    "address_line_2" => "/^(?!\s*$).+/",
    "address_line_3" => "/\p{L}/",
    "city" => "/\p{L}/",
    "county" => "/^[a-z\d\-_\s]+$/i",
    "country" => "/^[a-z\d\-_\s]+$/i",
    "lat" => "/^[+\-]?[0-9]{1,3}\.[0-9]{3,}\z/",
    "lon" => "/^[+\-]?[0-9]{1,3}\.[0-9]{3,}\z/",
    "phone_number" => "/^[0-9 ]+$/",
    // "phone_number1" => "/^[0-9]{4} [0-9]{3} [0-9]{3,4}$/",
    "cfs_flag" => "/^(?:Y|N|y|n)$/"
];

$myParser = new XmlParser;
$myParser->setFieldsRequired(array_keys($validatorValues));
$stores_parsed = $myParser->parseData($myFile);


$myValidator = new validator;
$myValidator->setValidators($validatorValues);
$stores_validated = $myValidator->validateData($stores_parsed);
// print_r($stores_validated);

$myTransformer = new transformer;
$myTransformer->setSource($stores_validated);
$myTransformer->setTransformers(['cfs_flag' => 'boolean']);
$stores_transformed = $myTransformer->transformData();
// print_r($stores_transformed);

$servername = "192.168.10.10";
$username = "homestead";
$password = "secret";
$dbname = "nn4m";

$myloader = new loader;
$myloader->setConnectionData($servername, $dbname, $username, $password);
$myloader->setSource($stores_transformed);
$myloader->loadToDB();







