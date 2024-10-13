<?php
$host='localhost';
$user='webuser';
$pass='password';
$dbname='rent_system';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die ("Unable to connect");
}

?>