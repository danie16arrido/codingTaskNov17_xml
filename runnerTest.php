<?php
include "parser.php";

$myParser = new XmlParser;
$myParser->setSourceFile("stores.xml");
$myParser->setFieldsRequired ([
    "number",
    "name",
    "address",
    "siteid",
    "lat",
    "lon",
    "cfs_flag"
]);
$myParser->parseData();