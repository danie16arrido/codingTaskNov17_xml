#! /usr/bin/php
<?php
$servername = "192.168.10.10";
$username = "homestead";
$password = "secret";
$dbname = "nn4m";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$file = "18_11_2017_121741__todb.json";

$stores = json_decode(file_get_contents($file), true);
foreach($stores as $store){
    
    foreach($store as $field => $value){
        $country = false;
        $city = false;
        $address = false;
        $store_number = false;
        
        if(array_key_exists("country", $store)){
            $country = true;
            $fields = ["name"];
            $data = [$store["country"]];
            $sql = generateQuery("countries", $fields, $data);
            $sql1 = "select id from countries where name = '".$data[0]."';";
            runQuery($sql, $conn);
            $country_id = runQuery($sql1, $conn, "fetch");
        }else{
            
        }
        if(array_key_exists("city", $store)){
            $city = true;
            $fields = ["name", "country_id"];
            $data = [$store["city"], $country_id];
            if(!$country){
                unset($fields["country_id"]);
            }
            $sql = generateQuery("cities", $fields, $data);
            runQuery($sql, $conn);
            $city_id = runQuery($sql, $conn, "fetch");
        }else{
            
        }     
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
