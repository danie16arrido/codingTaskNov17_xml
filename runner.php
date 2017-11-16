<?php
// "https://s3-eu-west-1.amazonaws.com/coding-task-espejos/stores.xml"
$number_of_params = sizeof($argv);
$options = [
    "--check-file" => "needs a valid .xml file", 
    "--generate-report" => "needs a name for the report file", 
    "--write-toDb" => "needs a db name", 
    "--help" => ""
];

if ($number_of_params === 3){
    $option = $argv[1];
    $value = $argv[2];
    if($option === "--check-file"){
        checkIfFileExists($value);
    }if($option === "--parse"){
        parse($value);
    }
    else if($option === "--generate-report"){
        runReport($value);
    }else if($option === "--write-toDb"){
        print_r($value);
    }else if($option === "--help"){
        print_r($value);
    }else{
        print_r("options available are:");
    }
}
else{
    showHelp($options);
}

function runReport($file){
    checkIfFileExists($file);
    print_r("parsing..");
    
}

function checkIfFileExists($file){
    $xml_file=simplexml_load_file($file) or die("Error: Cannot not get source file.\n");
    print_r("File exists.\n");
}

function showHelp($options){
    print_r("OPTIONS AVAILABLE:\n\n");
    foreach($options as $option => $value){
        print_r("option: ".$option."\t".$value."\n");
    }
}

?>



