<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$nomeFantasia = $_POST['nomeFantasia'];
$cnpj = $_POST['cnpj'];
$telefone = $_POST['telefone'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$bairro = $_POST['bairro'];
$estado = $_POST['estado'];
$cidade = $_POST['cidade'];
$observacao = $_POST['observacao'];

// Monta a consulta SQL para inserção
$query = "INSERT INTO cadastro_empresas (nomeFantasia, cnpj, telefone, cep, rua, numero, complemento, bairro, estado, cidade, observacao) 
          VALUES (:nomeFantasia, :cnpj, :telefone, :cep, :rua, :numero, :complemento, :bairro, :estado, :cidade, :observacao)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':nomeFantasia', $nomeFantasia);
$stmt->bindParam(':cnpj', $cnpj);
$stmt->bindParam(':telefone', $telefone);
$stmt->bindParam(':cep', $cep);
$stmt->bindParam(':rua', $rua);
$stmt->bindParam(':numero', $numero);
$stmt->bindParam(':complemento', $complemento);
$stmt->bindParam(':bairro', $bairro);
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':cidade', $cidade);
$stmt->bindParam(':observacao', $observacao);

if ($stmt->execute()) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/forms/empresas.html");
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => $errorInfo[2]]);
}
?>
