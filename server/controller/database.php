<?php
 $da_server = "localhost";
 $da_user = "root";
 $da_pass = "";
 $db_name = "animal-care-center";
 $conn = null;
 
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