<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="app.php" method="post">
        <label >username :</label>
        <input type="text" name="username"><br>
        <label >password :</label>
        <input type="password" name="password"><br>
        <input type="submit" name="login" value="log in">
    </form>
</body>
</html>
<?php
    if(isset($_POST["login"])){
        $username = filter_input(INPUT_POST, "username" , FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password" , FILTER_SANITIZE_SPECIAL_CHARS);
        if(empty($username)){
            echo "no username added";
        }else{
            echo "hello \${$username}";
        }
    }
?>