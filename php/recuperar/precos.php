<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

//$nome = isset($_POST['credenciada']) ? $_POST['credenciada'] : '';
$nome = '2';

$query = "SELECT valor_exame FROM cadastro_precos WHERE credenciada = :nome";
$params = [':nome' => $nome];
$result = $crud->read($query, $params);

// Decodifica a string JSON para um array associativo
$resultArray = json_decode($result, true); // O segundo parâmetro `true` faz com que o JSON seja convertido em array associativo

if (is_array($resultArray)) {
    // Adiciona o prefixo "R$" ao valor do exame
    foreach ($resultArray as &$row) {
        $row['valor_exame'] = 'R$ ' . $row['valor_exame'];
    }
}

header('Content-Type: application/json');
echo json_encode($resultArray);
?>
