<?php
class validator{
    private $validators;
    private $logger_file;
    private $destination_file;

    public function validator(){
        $this->setLoggerFile("err.json");
        $this->setDestinationFile("todb.json");
    }

    public function setDestinationFile($file){
        $this->destination_file = $file;
        return $this;
    }

    public function getDestinationFile(){
        return $this->destination_file;
    }

    public function setLoggerFile($file){
        $this->logger_file = $file;
        return $this;
    }

    public function getLoggerFile(){
        return $this->logger_file;
    }

    public function setValidators($validators_in){
        $this->validators = $validators_in;
        return $this;
    }

    public function getValidators(){
        return $this->validators;
    }

    private function validate($value, $regex_name){
        if($value === ""){
            return array("result" => false, "value" => ["data" => $value, "error_type"=>"empty"]);
        }
        if(null === $value){
            return array("result" => false, "value" => ["data" => null, "error_type"=> null]);
        }
        if(!preg_match($this->validators[$regex_name], $value)){
            return array("result" => false, "value" =>  ["data" => $value, "error_type" => "not_valid"]);
        }else{
            return array("result" => true);
        }
    }

    public function validateData($data){
        $error_logger = [];
        $toDb = [];
        //loop through data
        foreach($data as $item){
            $tmp = [];
            foreach($this->validators as $field => $reg_ex){             
                if(!array_key_exists($field, $item)){
                    $error_logger[$item["number"]][$field] = array("data" => null, "error_type"=> "field_missing");
                }else{
                    $isFieldValid = $this->validate($item[$field], $field);
                    if(!$isFieldValid["result"]){
                        //send to logger
                        $error_logger[$item["number"]][$field] = $isFieldValid["value"];
                    }else{
                        //send to dB
                        $tmp[$field] = $item[$field];
                    }
                }    
            }
            array_push($toDb, $tmp);
        }
        $this->logDataTo("logger", $error_logger);
        $this->logDataTo("todb", $toDb);
    }

    private function logDataTo($option, $data){
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        switch ($option){
            case "logger":
                $this->setLoggerFile($this->addTimestampToFile($this->logger_file));
                file_put_contents($this->logger_file, $jsonData);
                break;
            case "todb":
                $this->setDestinationFile($this->addTimestampToFile($this->destination_file));
                file_put_contents($this->destination_file, $jsonData);
                break;    
            default:
                echo "not a valid selection";
        }
    }

    private function addTimestampToFile($file){
        $result = date('d_m_Y_His')."__".$file;
        return $result;
    }
}







