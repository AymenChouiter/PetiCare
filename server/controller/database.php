<?php
 $da_server = "127.0.0.1";
 $da_user = "root";
 $da_pass = "";
 $db_name = "animal-care-center";
 $conn = "";
 
 try{
    $conn = mysqli_connect(
        $da_server ,
        $da_user ,
        $da_pass ,
        $db_name ,
    );
 }catch(mysqli_sql_exception){
    echo "Could not connect";
 }
?>