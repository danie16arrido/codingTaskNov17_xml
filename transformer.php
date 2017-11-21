<?php

class transformer{
    private $source_file;
    private $transformers;
    private $payload;
    
    public function setSource($file){
        $this->source_file = $file;
        // $this->source_file = $file;
        // $this->payload = "tf_".$file;
    }

    public function getTransformedFile(){
        return $this->payload;
    }

    public function setTransformers($transformers){
        $this->transformers = $transformers;
    }

    public function transformData(){
        // $stores = $this->decodeFile($this->source_file);
        $stores = $this->source_file;
        $result = [];
        foreach($stores as $store){
            foreach($this->transformers as $field => $transformer){
                if(array_key_exists($field, $store)){ 
                    $store[$field] = $this->transformField($store[$field], $transformer);
                }
            }
            array_push($result, $store);
        }
        // $this->saveToJsonFile($result);
        return $result;
        
    }

    private function saveToJsonfile($data){
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->payload, $jsonData);
        echo "transformed data located at: ".$this->payload."\n.";
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
