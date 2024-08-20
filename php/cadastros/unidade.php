<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$nome = $_POST['nome'];
$estado = $_POST['estado'];
$cidade = $_POST['cidade'];

// Monta a consulta SQL para inserção
$query = "INSERT INTO cadastro_unidades (nome, estado, cidade) 
          VALUES (:nome, :estado, :cidade)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':cidade', $cidade);

// Executa a inserção
if ($stmt->execute()) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/forms/unidades.html");
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => $errorInfo[2]]);
}

?>
