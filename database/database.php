<?php
$databaseServerHostName = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseSchemaName = "peticare_py";
$conn = null;

try{
    $conn = mysqli_connect(
        $databaseServerHostName,
        $databaseUsername,
        $databasePassword,
        $databaseSchemaName,
    );
}catch(mysqli_sql_exception){
    echo "Could not connect";
}
?>