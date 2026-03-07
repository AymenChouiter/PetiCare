<?php
require_once __DIR__ . '/../app.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ACC</title>
  </head>
  <body>
    <h1>Welcome home page</h1>
    <hr>
    <?php
      include "./server/controller/database.php";
      
      $sql = "INSERT INTO animal-information (animalId,animalName,species,gender,animalAge,description,illnesses,animalPrice,status)";
      $getAll = "SELECT * FROM `animal-information`";
      try{
          $result = mysqli_query($conn , $getAll);
          if(mysqli_num_rows($result)){
            while($row = mysqli_fetch_assoc($result)){
              echo $row["animalId"] . "<br>";
              echo $row["animalName"] . "<br>";
              echo $row["species"] . "<br>";
              echo $row["gender"] . "<br>";
              echo $row["animalAge"] . "<br>";
              echo $row["description"] . "<br>";
              echo $row["illnesses"] . "<br>";
              echo $row["animalPrice"] . "<br>";
              echo $row["status"] . "<br>";
              if (!empty($row["picture"])) {
                // Converting BLOB data to Base64
                $imageData = base64_encode($row["picture"]);
                // Specify the image type (you can improve it by making the type dynamic if necessary)
                $src = 'data:image/jpeg;base64,' . $imageData;
            
                echo "<b>Picture:</b><br>";
                echo '<img src="' . $src . '"><br>';
              } else {
                echo "<b>Picture:</b> No image available<br>";
              }
              echo $row["createdAt"] . "<br>";
              echo "<hr>";
            }
          }
      }catch(mysqli_sql_exception){
          echo "error to get";
      }
      mysqli_close($conn);
    ?>
  </body>
</html>
