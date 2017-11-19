<?php

class loader{
    private $server_name;
    private $db_name;
    private $username;
    private $password;
    private $filename;

    public function setConnectionData($server, $db, $user, $pass){
        $this->server_name = $server;
        $this->db_name = $db;
        $this->username = $user;
        $this->password = $pass;
        // return $this;
    }
    public function setDataFile($file){
        $this->filename = $file;
        // return $this;
    }

    private function connectToDb(){
        $conn = new mysqli($this->server_name, $this->username, $this->password, $this->db_name);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    public function loadToDb(){
        $conn = $this->connectToDb();
        $stores = $this->decodeSourceFile();
        $this->preprocesQuery($stores, $conn);
        mysqli_close($conn);
    }

    public function preprocesQuery($stores, $conn){
        foreach ($stores as $store) {
            foreach ($store as $field => $value) {
            //country
                $fields_ = ["country" => "name"];
                $country_id = $this->runObjectQuery($store, "countries", $fields_, [], $conn);
            //city
                $fields_ = ["city"=> "name"];
                $city_id = $this->runObjectQuery($store, "cities", $fields_, ["country_id" => $country_id], $conn);
            //address
                $fields_ = ["address_line_1" => "address_line_1", "address_line_2", "address_line_3", "county", "lat", "lon"];
                $address_id = $this->runObjectQuery($store, "addresses", $fields_, ["city_id" => $city_id], $conn);
            // store
                $fields_ = ["number"=>"id", "siteid", "phone_number", "name", "cfs_flag"];
                $this->runObjectQuery($store, "stores", $fields_, ["address_id" => $address_id], $conn);
            }
        }
    }

    private function runObjectQuery($source, $table, $fields_, $relationships, $conn){
        $fields = [];
        foreach ($fields_ as $field => $db_field) {
            if (array_key_exists($field, $source)) {
                $fields[$fields_[$field]] = $source[$field];
            } else {
                $fields[$fields_[$field]] = $source[$fields_[$field]];
            }
        }
        foreach($relationships as $key => $value){
            $fields[$key] = $value;
        }
        $sql = $this->generateQuery($table, array_keys($fields), array_values($fields));
        $this->runQuery($sql, $conn);
        $object_id = $conn->insert_id;
        //timestamps
        $sql_timestamps = $this->generateTimestampsQuery($table, $object_id);
        $this->runQuery($sql_timestamps, $conn);

        return $object_id;
    }

    private function generateTimestampsQuery($table, $id){
        $now = date('Y-m-d H:i:s');
        $sql_timestamps = "UPDATE " .$table. " SET created_at='" .$now. "', updated_at='" .$now. "' WHERE id = " .$id. ";";
        return $sql_timestamps;
    }

    private function decodeSourceFile(){
        $decoded_data = json_decode(file_get_contents($this->filename), true);
        return $decoded_data;
    }

    private function runQuery($sql, $conn, $fetch = false)
    {
        if ($result = mysqli_query($conn, $sql)) {
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn) . "\n";
        }
        if ($fetch) {
            $row = mysqli_fetch_array($result, MYSQLI_NUM);
            return $row[0];
        }
    }

    private function generateQuery($table, $fields, $values)
    {
        $base = "INSERT INTO ttt [] VALUES ();";
        $columns = $this->getCommaSeparatedValues($fields, "");
        $data = $this->getCommaSeparatedValues($values, "'");
        $base = $this->str_replace_json("ttt", $table, $base);
        $base = $this->str_replace_json("[]", $columns, $base);
        $base = $this->str_replace_json("()", $data, $base);
        return $base;
    }

    private function getCommaSeparatedValues($list, $charWrapper)
    {
        $result = "";
        foreach ($list as $item) {
            if ($item[0] === "@") {
                //trim @a 
                $item = ltrim($item, '@');
                $result .= $item . ", ";
            } else {
                $result .= $charWrapper . $item . $charWrapper . ", ";
            }
        }
        $result = rtrim($result, ', ');
        return "(" . $result . ")";
    }

    private function str_replace_json($search, $replace, $subject)
    {
        return json_decode(str_replace($search, $replace, json_encode($subject)));
    }
}
?>
