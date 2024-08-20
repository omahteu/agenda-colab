<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$credenciada = $_POST['credenciada'];
$tipos_exame = $_POST['tiposExame'];
$nomes_exames = $_POST['nomesExames'];
$valor_exame = $_POST['valorExame'];

// Monta a consulta SQL para inserção
$query = "INSERT INTO cadastro_precos (credenciada, tipos_exame, nomes_exames, valor_exame) 
          VALUES (:credenciada, :tipos_exame, :nomes_exames, :valor_exame)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':credenciada', $credenciada);
$stmt->bindParam(':tipos_exame', $tipos_exame);
$stmt->bindParam(':nomes_exames', $nomes_exames);
$stmt->bindParam(':valor_exame', $valor_exame);

// Executa a inserção
if ($stmt->execute()) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/forms/precos.html");
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => $errorInfo[2]]);
}

?>
