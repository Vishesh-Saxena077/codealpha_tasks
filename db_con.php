<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname  = 'codealpha';

mysqli_report(MYSQLI_REPORT_STRICT);

try{
    $connection =  new mysqli($servername,$username,$password,$dbname);
    date_default_timezone_set('Asia/kolkata');
} catch (Exception $ex){
    echo "Connection Failed". $ex -> getMessage();
    exit();
}
?>
