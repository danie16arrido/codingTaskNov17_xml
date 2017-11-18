<?php
include "parser.php";
include "validator.php";
// include "dbloader.php";

$myFile = "stores.xml";
$myParser = new XmlParser;

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
$myParser->setFieldsRequired(array_keys($validatorValues));
$stores = $myParser->parseData($myFile);

$myValidator = new validator;
$myValidator->setValidators($validatorValues);
$myValidator->validateData($stores);

//  echo $myValidator->getDestinationFile();
// $loader = new dbloader;
// $loader->setConnectioData(db, user, password);
// $myfile =  $myValidator->getDestinationFile();
// $loader->loadToDB($myfile);






