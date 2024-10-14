<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();

try {
    $db = $database->getConnection();
} catch (PDOException $e) {
    // Retorna erro 500 se não conseguir conectar ao banco de dados
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Erro ao conectar ao banco de dados: " . $e->getMessage()]);
    exit(); // Para a execução do script
}

$crud = new Crud($db);

// Verificar se o user_id foi recebido na requisição
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    // Retorna erro 400 se o user_id não for fornecido
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "O parâmetro 'user_id' é obrigatório."]);
    exit(); // Para a execução do script
}

// Obter o user_id da requisição
$user_id = $_GET['user_id'];

// Query com JOIN para trazer o nome do colaborador
$query = "
    SELECT d.*, u.nome as colaborador 
    FROM diario d
    JOIN usuarios u ON d.colaborador = u.id
    WHERE d.colaborador = :user_id
";

try {
    // Executa a query com o parâmetro user_id
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
} catch (PDOException $e) {
    // Se a query falhar, retorna erro 500
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Erro ao executar a consulta: " . $e->getMessage()]);
    exit(); // Para a execução do script
}

// Converte o resultado para um array associativo
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se houve um erro ao converter o resultado
if ($data === false) {
    // Retorna erro 500 se houve falha ao buscar os dados
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Erro ao buscar os dados."]);
    exit(); // Para a execução do script
}

// Verifica se há dados retornados
if (empty($data)) {
    // Se não houver dados, retorna erro 404
    http_response_code(404); // Not Found
    echo json_encode(["error" => "Nenhum dado encontrado para o usuário."]);
    exit(); // Para a execução do script
}

// Formata o campo 'data' para o padrão brasileiro (dia/mês/ano)
foreach ($data as &$row) {
    if (isset($row['data'])) {
        $row['data'] = date('d/m/Y', strtotime($row['data']));
    }
}

// Retorna os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($data);

?>
