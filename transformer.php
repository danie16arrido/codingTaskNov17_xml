<?php

class transformer{
    private $source_file;
    private $transformers;
    private $destination_file;
    
    public function setSource($file){
        $this->source_file = $file;
        $this->destination_file = "tf_".$file;
    }

    public function getTransformedFile(){
        return $this->destination_file;
    }

    public function setTransformers($transformers){
        $this->transformers = $transformers;
    }

    public function transformData(){
        $stores = $this->decodeFile($this->source_file);
        $result = [];
        foreach($stores as $store){
            foreach($this->transformers as $field => $transformer){
                if(array_key_exists($field, $store)){ 
                    $store[$field] = $this->transformField($store[$field], $transformer);
                }
            }
            array_push($result, $store);
        }
        $this->saveToJsonFile($result);
        
    }

    private function saveToJsonfile($data){
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->destination_file, $jsonData);
        echo "transformed data located at: ".$this->destination_file."\n.";
    }

    private function decodeFile()
    {
        $decoded_data = json_decode(file_get_contents($this->source_file), true);
        return $decoded_data;
    }

    private function transformField($data, $transformer){
        switch ($transformer) {
            case "boolean":
                if (preg_match("/^(?:Y|y)$/", $data)) {
                    return "@true";
                }else if (preg_match("/^(?:N|n)$/", $data)) {
                    return "@false";
                }else{
                    return "@false";
                }
                break;

            default:
                return $data;
                break;
        }
    }
}
