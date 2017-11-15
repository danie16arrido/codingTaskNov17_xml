<?php
$source_file = "stores.xml";
$xml = simplexml_load_file($source_file);
$fields_required = [
    "number",
    "name",
    "address",
    "siteid",
    "lat",
    "lon",
    "cfs_flag"
];
foreach($xml as $store){
    foreach($store->children() as $field => $value){
        if(in_array($field, $fields_required)){
            if($store->$field->children()->count() >= 1){
                echo $field.":\n";
                foreach($store->$field->children() as $childField => $childValue){
                    echo "\t".$childField.": ".$childValue."\n";
                }
            }else{
                echo $field.": ".$value."\n";
            }
        }else{
            foreach($store->$field->children() as $subField => $subValue){
                if(in_array($subField, $fields_required)){
                    echo $subField.": ".$subValue."\n";
                }              
            }
        }
    }   
}
?>
