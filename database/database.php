<?php
$host = "localhost";
$userName = "root";
$password = "";
$dbName = "peticare_db";

try {
    $conn = mysqli_connect(
        $host,
        $userName,
        $password,
        $dbName,
    );
} catch (mysqli_sql_exception) {
    echo "Could not connect";
}