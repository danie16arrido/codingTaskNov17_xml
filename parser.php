<?php
$source_file = "https://s3-eu-west-1.amazonaws.com/coding-task-espejos/stores1.xml";
$xml_file=simplexml_load_file($source_file) or die("Error: Cannot not get source file.\n");
?>