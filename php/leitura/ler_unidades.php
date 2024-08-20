<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

$query = "SELECT id, nome FROM cadastro_unidades";
$result = $crud->read($query);

header('Content-Type: application/json');
echo $result;
?>
