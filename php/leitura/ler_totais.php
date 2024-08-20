<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Consulta para obter os nomes dos exames da tabela 'asos'
$query = "SELECT nomes_exames FROM asos";
$result = $crud->read($query);

// Inicializa um array para armazenar os resultados finais
$finalResult = [];

if ($result) {
    $asosResults = json_decode($result, true); // Decodifica o JSON para um array associativo

    foreach ($asosResults as $asosRow) {
        $nomes_exames = $asosRow['nomes_exames'];

        // Consulta para obter o preço do exame correspondente ao nome do exame
        $priceQuery = "SELECT id, nomes_exames, valor_exame FROM cadastro_precos WHERE nomes_exames = :nomes_exames";
        $stmt = $db->prepare($priceQuery);
        $stmt->bindParam(':nomes_exames', $nomes_exames);
        $stmt->execute();

        $priceResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($priceResult) {
            // Adiciona o resultado ao array final
            foreach ($priceResult as $priceRow) {
                $finalResult[] = $priceRow;
            }
        }
    }
}

// Define o cabeçalho de conteúdo como JSON e retorna o resultado
header('Content-Type: application/json');
echo json_encode($finalResult);
?>
