<?php
session_start();
require_once 'db_con.php';
 

// checking for setted session
if(!isset($_SESSION['user_data'])){
    header("Location:login.php");
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display details</title>
    <!-- angular js library -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
</head>
<body>
    <?php
        if(isset($_SESSION['user_data'])){
            echo "<a href='logout.php'>logout</a>";
        }
    ?>
    <h1>
        Wellcome
    </h1>
</body>
</html>