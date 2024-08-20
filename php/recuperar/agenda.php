<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Inicializa a query base
$query = "SELECT * FROM diario WHERE 1=1";

// Verifica se os parâmetros foram passados e os adiciona à query
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $data = $_GET['data'];
    $query .= " AND data = :data";
}

if (isset($_GET['colaborador']) && !empty($_GET['colaborador'])) {
    $colaborador = $_GET['colaborador'];
    $query .= " AND colaborador = :colaborador";
}

// Prepara a consulta e vincula os parâmetros se necessário
$stmt = $db->prepare($query);

if (isset($data)) {
    $stmt->bindParam(':data', $data);
}

if (isset($colaborador)) {
    $stmt->bindParam(':colaborador', $colaborador);
}

// Executa a consulta
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
?>
