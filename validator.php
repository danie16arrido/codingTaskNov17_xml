<?php
class validator{
    private $validators;
    private $logger_file;
    private $destination_file;

    public function validator(){
        $this->logger_file = "err.json";
        $this->destination_file = "todb.json";
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
                $isFieldValid = $this->validate($item[$field], $field);
                if(!$isFieldValid["result"]){
                    //send to logger
                    $error_logger[$item["number"]][$field] = $isFieldValid["value"];
                }else{
                    //send to dB
                    $tmp[$field] = $item[$field];
                }
            }
            array_push($toDb, $tmp);
        }
        $this->logDataTo($this->logger_file, $error_logger);
        $this->logDataTo($this->destination_file, $toDb);
    }

    private function logDataTo($file, $data){
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $file = $this->addTimestampToFile($file);
        file_put_contents($file, $jsonData);
    }

    private function addTimestampToFile($file){
        return date('d_m_Y_His')."__".$file;
    }
}







