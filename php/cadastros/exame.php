<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$options = $_POST['options'];
$nome = $_POST['nome'];

// Determina a tabela com base na opção selecionada
if ($options === 'tipoExame') {
    $query = "INSERT INTO cadastro_tiposexames (nome) VALUES (:nome)";
} else if ($options === 'nomeExame') {
    $query = "INSERT INTO cadastro_nomesexames (nome) VALUES (:nome)";
}

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':nome', $nome);

// Executa a inserção
if ($stmt->execute()) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/forms/exames.html");
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => $errorInfo[2]]);
}

?>
