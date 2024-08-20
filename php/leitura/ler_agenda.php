<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Inicializa a query base
$query = "SELECT * FROM diario WHERE 1=1";

// Verifica se o parâmetro colaborador foi passado e o adiciona à query
if (isset($_GET['colaborador']) && !empty($_GET['colaborador'])) {
    $colaborador = $_GET['colaborador'];
    $query .= " AND colaborador = :colaborador";
}

// Prepara a consulta e vincula os parâmetros se necessário
$stmt = $db->prepare($query);

if (isset($colaborador)) {
    $stmt->bindParam(':colaborador', $colaborador);
}

// Executa a consulta
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retorna os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($result);
?>
