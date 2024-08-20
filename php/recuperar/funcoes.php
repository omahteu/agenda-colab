<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

$nome = isset($_POST['nome']) ? $_POST['nome'] : '';

$query = "SELECT nome FROM cadastro_funcoes";
$result = $crud->read($query);

header('Content-Type: application/json');
echo json_encode($result);
?>
