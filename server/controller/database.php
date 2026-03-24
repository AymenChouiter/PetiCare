<?php
$databaseServerHostName = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseSchemaName = "animal-care-center";
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
/**
 * this is the database schema :
 * CREATE TABLE animal (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL,
      species VARCHAR(100) NOT NULL,
      gender ENUM('MALE', 'FEMALE') NOT NULL,
      birth_date DATE NOT NULL,
      description TEXT,
      health_status ENUM('HEALTHY', 'UNDER_TREATMENT') NOT NULL DEFAULT 'HEALTHY',
      adoption_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
      adoption_status ENUM('AVAILABLE', 'RESERVED', 'ADOPTED') NOT NULL DEFAULT 'AVAILABLE',
      picture_data LONGBLOB, 
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 * );
 */
?>