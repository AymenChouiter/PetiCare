<?php include 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Animal Care Center</title>
</head>
<body>
  <?php
    $result = $connection->query("SELECT * FROM animals");
    while($row = $result->fetch_assoc()) {
      echo "<p>hello</p>";
      echo"
        <div>
                    <img src='{$row['picture_url']}' >
                    <div>
                        <h2>{$row['name']}</h2>
                        <p>{$row['species']}, {$row['color']}</p>
                        <p>{$row['description']}</p>
                        <div >
                            <span>
                                {$row['health_status']}
                            </span>
                            <button>
                                Adopt Me
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
                ";
    }
  ?>
</body>
</html>