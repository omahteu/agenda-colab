<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

$nome = isset($_POST['nome']) ? $_POST['nome'] : '';

$query = "SELECT nomeFantasia, cnpj, telefone, cep, rua, numero, complemento, bairro, estado, cidade, observacao FROM cadastro_empresas WHERE nomeFantasia = :nome";
$params = [':nome' => $nome];
$result = $crud->read($query, $params);

header('Content-Type: application/json');
echo json_encode($result);
?>
