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
echo "Connected successfully";
$file = "17_11_2017_165152__todb.json";
$stores = json_decode(file_get_contents($file), true);


foreach($stores as $key => $value){

    echo $key.":: ".$value."\n";

    // $sql = "INSERT INTO COUNTRIES (name) VALUES (".$items["country"].");";
    
}

function hasField($value, $myArray){
    return (array_key_exists($value, $myArray));
}
// $sql = "INSERT INTO MyGuests (firstname, lastname, email)
// VALUES ('John', 'Doe', 'john@example.com')";

// if (mysqli_query($conn, $sql)) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $sql . "<br>" . mysqli_error($conn);
// }

// mysqli_close($conn);


?>
<!-- 
{
        "number": "214",
        "name": "Dublin - Tallaght",
        "siteid": "IE",
        "address_line_1": "Debenhams Retail plc",
        "address_line_2": "The Square",
        "address_line_3": "Tallaght",
        "city": "Dublin",
        "county": "Dublin",
        "country": "Republic of Ireland",
        "lat": "53.286706",
        "lon": "-6.371848",
        "phone_number": "01 4685783",
        "cfs_flag": "Y"
    }, -->