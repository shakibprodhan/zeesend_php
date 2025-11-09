<?php

/*About this code...
  Basic: This code is global for browser-from signup requests.
         Code uses "mySQL" using "PHP" for logic. 
  Usage: This code doesn't change for any platform if "PHP" development options are met.
  Structure: This code uses two helper functions, "accountExists" and "createAccount". 
             1. "accountExists" fedges info to check if an user account exist in the "mySQL" database assigned for the platform.
             2. "creatAccount" is used when "accountExists" returns null and a new user has to be registered in the database.
  Future development options: can be (1)Implementing proper hashing or encryption-decryption methods (according to company "terms and usage"
                                        and "policy and agreement" papers) 
*/


#SEGMENT ONE {}
require_once("../con.php");
require_once("../base.php");

#SEGMENT TWO {}
$inputData = getDataFromJsonObj();

if ($inputData == null) return;

$email = $inputData["email"];
$name = $inputData["name"];
$password = $inputData["password"];
$username = $inputData["username"];

$tableName = "userinfo";

if (accountExists($email, $conn, $tableName)){

    echo json_encode(array("status" => "Account already available, please login"));
    return;
}

createAccount($email, $password, $name, $username, $tableName, $conn);

#SEGMENT THREE {}
#function accountExists implementation
function accountExists($email, $conn, $tableName){
    $query = "SELECT * FROM {$tableName} WHERE email = '$email'";
    $runQuery = mysqli_query($conn, $query);
    $rows = mysqli_num_rows($runQuery);
    if($rows > 0) return true;
    else return false;

}

#function createAccount implementation
function createAccount($email, $password, $name, $username, $tableName, $conn){
$sql = "INSERT INTO {$tableName} (email, password, name, username) VALUES ('$email', '$password', '$name', '$username')";
    $runSql = mysqli_query($conn, $sql);

    if ($runSql){
        echo json_encode(array("status" => "done", "id" => mysqli_insert_id($conn)));

    }else {
        echo json_encode(array("status" => "Unknown Error Occurred", "error" => mysqli_error($conn)));

    }
}

?>
