<?php
require_once('../../../phpcon/conn.php');

$data=json_decode(file_get_contents('php://input'),true);

if(!isset($data['TRAINER_ID']))


?>