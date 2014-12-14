<?php
$data = file_get_contents("php://input");
$data = json_decode($data, TRUE);
var_dump($data);
var_dump($_POST[0]);
?>