<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$nome = $_POST['nome'];

// Monta a consulta SQL para inserção
$query = "INSERT INTO cadastro_funcoes (nome) VALUES (:nome)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':nome', $nome);

// Executa a inserção
if ($stmt->execute()) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/forms/funcoes.html");
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => $errorInfo[2]]);
}

?>
