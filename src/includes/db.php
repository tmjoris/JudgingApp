<?php
$host = 'db';
$db = 'judging_system';
$user = 'root';
$pass = 'hard-to-crack';
$port = 3306;

$retries = 10;
while($retries > 0) {
    $conn = @new mysqli($host, $user, $pass, $db, $port);
    if ($conn && !$conn->connect_error){
        break;
    }
    $retries--;
    sleep(2);
}

if($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>