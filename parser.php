<?php
$source_file = "https://s3-eu-west-1.amazonaws.com/coding-task-espejos/stores.xml";
$destination_file = date('d_m_Y_His A e')."_fromXmlTo".".json";
$xml_file = simplexml_load_file($source_file);
$fields_required = [
    "number",
    "name",
    "address",
    "siteid",
    "lat",
    "lon",
    "cfs_flag"
];

function getStoresData($xml_file, $fields_required){
    $trimmed_stores = [];
    foreach($xml_file as $store){
        $trimmed_store = [];
        foreach($store->children() as $field => $value){      
            if(in_array($field, $fields_required)){       
                if($store->$field->children()->count() > 1){
                    foreach($store->$field->children() as $childField => $childValue){
                        $trimmed_store[$field][$childField] = $childValue->__toString();
                    }
                }else{
                    $trimmed_store[$field] = $value->__toString();
                }
            }
            else{
                foreach($store->$field->children() as $subField => $subValue){
                    if(in_array($subField, $fields_required)){
                        $trimmed_store[$subField] = $subValue->__toString();
                    }              
                }
            }  
        }   
        array_push($trimmed_stores, $trimmed_store);
    }  
    return $trimmed_stores;
}

saveDataToFile($destination_file, getStoresData($xml_file, $fields_required));

function saveDataToFile($file_name, $source){
    $jsonData = json_encode($source, JSON_PRETTY_PRINT);
    file_put_contents($file_name, $jsonData);
}



?>
