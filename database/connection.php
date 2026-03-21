<?php
 $da_server = "127.0.0.1";
 $da_user = "root";
 $da_pass = "farouk1975";
 $db_name = "peticare_db";
 
 try{
    $conn = mysqli_connect(
        $da_server ,
        $da_user ,
        $da_pass ,
        $db_name
    );
 }catch(mysqli_sql_exception){
    echo "Could not connect";
 }
?>