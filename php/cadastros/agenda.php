<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$data = $_POST['data'];
$colaborador = $_POST['colaborador'];
$cep = $_POST['cep'];
$numero = $_POST['numero'];
$horario = $_POST['horario'];
$observacoes = $_POST['observacoes'];
$status = 1;


// Monta a consulta SQL para inserção
$query = "INSERT INTO diario (colaborador, data, cep, numero, horario, observacoes, status) 
          VALUES (:colaborador, :data, :cep, :numero, :horario, :observacoes, :status)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros comuns
$stmt->bindParam(':colaborador', $colaborador);
$stmt->bindParam(':data', $data);
$stmt->bindParam(':cep', $cep);
$stmt->bindParam(':numero', $numero);
$stmt->bindParam(':horario', $horario);
$stmt->bindParam(':observacoes', $observacoes);
$stmt->bindParam(':status', $status);

if ($stmt->execute()) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/criar-agenda.html");
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => $errorInfo[2]]);
}
?>
