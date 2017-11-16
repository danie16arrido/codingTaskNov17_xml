<?php

class XmlParser{
    private $source_file;
    private $destination_file;
    private $fields_required;

    public function XmlParser($source_file = "", $destination_file = "xmlToJson.json"){
        $this->source_file = $source_file;
        $this->destination_file = $destination_file;
        $this->fields_required = array();
    }
    
    public function setSourceFile($file_name){
        $this->source_file = $file_name;
        // return $this;
    }

    public function getSourceFileName(){
        return $this->source_file;
    }

    public function setFieldsRequired($fields){
        $this->fields_required = $fields;
        return $this;
    }

    public function getFieldsRequired($fields){
        return $this->fields_required;
    }

    private function getStoresData($xml_file, $fields_required){
        $trimmed_stores = [];
        foreach($xml_file as $store){
            $trimmed_store = [];
            foreach($store->children() as $field => $value){
                if(in_array($field, $fields_required)){
                    if($store->$field->children()->count() > 1){
                        foreach($store->$field->children() as $childField => $childValue){
                            // $trimmed_store[$field][$childField] = $childValue->__toString();
                            $trimmed_store[$childField] = $childValue->__toString();
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

    private function saveDataToFile($file_name, $source){
        $jsonData = json_encode($source, JSON_PRETTY_PRINT);
        file_put_contents($file_name, $jsonData);
    }

    private function addTimestampToDestinationFile(){
        $this->destination_file = date('d_m_Y_His A e').$this->destination_file;
    }

    public function parseData(){
        echo "parsing";
        $xml_file = simplexml_load_file($this->source_file);
        $this->addTimestampToDestinationFile();
        $this->saveDataToFile($this->destination_file, $this->getStoresData($xml_file, $this->fields_required));
    }
}

?>