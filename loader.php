#! /usr/bin/php
<?php
$servername = "192.168.10.10";
$username = "homestead";
$password = "secret";
$dbname = "nn4m";

$file = "18_11_2017_121741__todb.json";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 



$stores = json_decode(file_get_contents($file), true);
foreach($stores as $store){
    
    foreach($store as $field => $value){
        $country = false;
        //country
        if(array_key_exists("country", $store)){
            $country = true;
            $fields = ["name"];
            $data = [$store["country"]];
            $sql = generateQuery("countries", $fields, $data);
            $sql1 = "select id from countries where name = '" . $store["country"] . "';";
            runQuery($sql, $conn);
            $country_id = runQuery($sql1, $conn, "fetch");
        }else{
            
        }
        //city
        if(array_key_exists("city", $store)){
            $fields = ["name", "country_id"];
            $data = [$store["city"], $country_id];
            if(!$country){
                unset($fields["country_id"]);
            }
            $sql = generateQuery("cities", $fields, $data);
            $sql1 = "select id from cities where name = '" . $store["city"] . "';";
            runQuery($sql, $conn);
            $city_id = runQuery($sql1, $conn, "fetch");
        }else{
            
        }
        //address
        $fields_ = ["address_line_1", "address_line_2", "address_line_3", "county", "lat", "lon"];
        $fields = [];
        foreach($fields_ as $field){
            if (array_key_exists($field, $store)) {
                $fields[$field] = $store[$field];
            }else {
                // unset($field, $fields);
            }
        }
        $fields["city_id"] = $city_id;
        $sql = generateQuery("addresses", array_keys($fields), array_values($fields));
        runQuery($sql, $conn);
        $address_id = $conn->insert_id;

        // store
        $fields_ = ["number", "siteid", "phone_number", "name"];
        $fields = [];
        foreach($fields_ as $field){
            if(array_key_exists($field, $store)){
                $fields[$field] = $store[$field];
            }else{
                // unset($field, $fields);
            }
        }
        $fields["address_id"] = $address_id;
        $sql = generateQuery("stores", array_keys($fields), array_values($fields));
        runQuery($sql, $conn);
        

    //create country query
    //get country id
    //create city query
    //create address query
    //create store query
    }

}
mysqli_close($conn);

function runQuery($sql, $conn, $fetch = false){
    if ($result = mysqli_query($conn, $sql)) {
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn) . "\n";
    }
    if($fetch){
        $row = mysqli_fetch_array($result, MYSQLI_NUM);
        return $row[0];
    }
}

function generateQuery($table, $fields, $values){
    $base = "INSERT INTO ttt [] VALUES ();";
    $columns = getCommaSeparatedValues($fields,"");
    $data = getCommaSeparatedValues($values, "'");
    $base = str_replace_json("ttt", $table, $base);
    $base = str_replace_json("[]", $columns, $base);
    $base = str_replace_json("()", $data, $base);

    return $base;
}
function getCommaSeparatedValues($list, $charWrapper){
    $result = "";
    foreach($list as $item){
        if($item[0] === "@"){
            $result .= $item.", ";
        }else{
            $result .= $charWrapper.$item.$charWrapper.", ";
        }
    }
    $result = rtrim($result, ', ');
    return "(".$result.")";
}

function str_replace_json($search, $replace, $subject){ 
    return json_decode(str_replace($search, $replace,  json_encode($subject))); 
}
?>
