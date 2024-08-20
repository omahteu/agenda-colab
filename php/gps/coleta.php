<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados da requisição POST
$colaborador = $_POST['user_id'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Monta a consulta SQL para inserção
$query = "INSERT INTO localizacoes (colaborador, latitude, longitude) 
          VALUES (:colaborador, :latitude, :longitude)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':colaborador', $colaborador);
$stmt->bindParam(':latitude', $latitude);
$stmt->bindParam(':longitude', $longitude);

if ($stmt->execute()) {
    // Responde com sucesso
    echo json_encode(['success' => 'Localização salva com sucesso.']);
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => $errorInfo[2]]);
}

?>
