<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// $empresa = $_POST['empresa'] ?? '';
// $credenciada = $_POST['credenciada'] ?? '';

$empresa = '1';
$credenciada = '2';

$inicio = $_POST['inicio'] ?? '';
$fim = $_POST['fim'] ?? '';

$query = "SELECT nome, rg, cpf, nascimento, sexo, data_exame, tipo_exame, nomes_exames, observacao 
          FROM asos 
          WHERE empresa = :empresa 
          AND credenciada = :credenciada";

$params = [
    ':empresa' => $empresa,
    ':credenciada' => $credenciada
];

if (!empty($inicio) && !empty($fim)) {
    $query .= " AND data_exame BETWEEN :inicio AND :fim";
    $params[':inicio'] = $inicio;
    $params[':fim'] = $fim;
} elseif (!empty($inicio)) {
    $query .= " AND data_exame >= :inicio";
    $params[':inicio'] = $inicio;
} elseif (!empty($fim)) {
    $query .= " AND data_exame <= :fim";
    $params[':fim'] = $fim;
}

$result = $crud->read($query, $params);

// Verifica e converte o retorno da função read em array associativo, caso seja uma string JSON
function convertToArray($result) {
    if (is_string($result)) {
        $arrayResult = json_decode($result, true); // Decodifica JSON como array associativo
        return $arrayResult ?? []; // Retorna array ou array vazio se a decodificação falhar
    }
    return $result;
}

// Prepare arrays to store the mappings of names
$tiposExames = [];
$nomesExames = [];

// Fetch tipos_exames names
$tiposExamesQuery = "SELECT id, nome FROM cadastro_tiposexames";
$tiposExamesResult = convertToArray($crud->read($tiposExamesQuery, []));

foreach ($tiposExamesResult as $tipoExame) {
    $tiposExames[$tipoExame['id']] = $tipoExame['nome'];
}

// Fetch nomes_exames names
$nomesExamesQuery = "SELECT id, nome FROM cadastro_nomesexames";
$nomesExamesResult = convertToArray($crud->read($nomesExamesQuery, []));

foreach ($nomesExamesResult as $nomeExame) {
    $nomesExames[$nomeExame['id']] = $nomeExame['nome'];
}

// Map the results with the names
$result = convertToArray($result);

foreach ($result as &$row) {
    


    // Fetch the price from cadastro_precos table
    $precoQuery = "SELECT valor_exame 
                   FROM cadastro_precos 
                   WHERE tipos_exame = :tipos_exame 
                   AND nomes_exames = :nomes_exames 
                   LIMIT 1";

    $precoParams = [
        ':tipos_exame' => $row['tipo_exame'],
        ':nomes_exames' => $row['nomes_exames']
    ];
    
    $precoResult = convertToArray($crud->read($precoQuery, $precoParams));
    
    // Add the price to the result row
    $row['valor_exame'] = $precoResult[0]['valor_exame'] ?? 'Preço não encontrado';

    $row['tipo_exame'] = $tiposExames[$row['tipo_exame']] ?? 'Desconhecido';
    $row['nomes_exames'] = $nomesExames[$row['nomes_exames']] ?? 'Desconhecido';
}

header('Content-Type: application/json');
echo json_encode($result);
?>



<?php

// require_once '../Database.php';
// require_once '../Crud.php';

// $database = new Database();
// $db = $database->getConnection();

// $crud = new Crud($db);

// $empresa = $_POST['empresa'] ?? '';
// $credenciada = $_POST['credenciada'] ?? '';
// $inicio = $_POST['inicio'] ?? '';
// $fim = $_POST['fim'] ?? '';

// $query = "SELECT id, nome, rg, cpf, nascimento, sexo, data_exame, tipo_exame, nomes_exames, observacao 
//           FROM asos 
//           WHERE empresa = :empresa 
//           AND credenciada = :credenciada";

// $params = [
//     ':empresa' => $empresa,
//     ':credenciada' => $credenciada
// ];

// if (!empty($inicio) && !empty($fim)) {
//     $query .= " AND data_exame BETWEEN :inicio AND :fim";
//     $params[':inicio'] = $inicio;
//     $params[':fim'] = $fim;
// } elseif (!empty($inicio)) {
//     $query .= " AND data_exame >= :inicio";
//     $params[':inicio'] = $inicio;
// } elseif (!empty($fim)) {
//     $query .= " AND data_exame <= :fim";
//     $params[':fim'] = $fim;
// }

// $result = $crud->read($query, $params);

// // Verifica e converte o retorno da função read em array associativo, caso seja uma string JSON
// function convertToArray($result) {
//     if (is_string($result)) {
//         $arrayResult = json_decode($result, true); // Decodifica JSON como array associativo
//         return $arrayResult ?? []; // Retorna array ou array vazio se a decodificação falhar
//     }
//     return $result;
// }

// // Prepare arrays to store the mappings of names
// $tiposExames = [];
// $nomesExames = [];

// // Fetch tipos_exames names
// $tiposExamesQuery = "SELECT id, nome FROM cadastro_tiposexames";
// $tiposExamesResult = convertToArray($crud->read($tiposExamesQuery, []));

// foreach ($tiposExamesResult as $tipoExame) {
//     $tiposExames[$tipoExame['id']] = $tipoExame['nome'];
// }

// // Fetch nomes_exames names
// $nomesExamesQuery = "SELECT id, nome FROM cadastro_nomesexames";
// $nomesExamesResult = convertToArray($crud->read($nomesExamesQuery, []));

// foreach ($nomesExamesResult as $nomeExame) {
//     $nomesExames[$nomeExame['id']] = $nomeExame['nome'];
// }

// // Map the results with the names
// $result = convertToArray($result);


// foreach ($result as &$row) {
    
//     $row['tipo_exame'] = $tiposExames[$row['tipo_exame']] ?? 'Desconhecido';
//     $row['nomes_exames'] = $nomesExames[$row['nomes_exames']] ?? 'Desconhecido';
// }

// header('Content-Type: application/json');
// echo json_encode($result);
?>


<?php

// require_once '../Database.php';
// require_once '../Crud.php';

// $database = new Database();
// $db = $database->getConnection();

// $crud = new Crud($db);

// $empresa = $_POST['empresa'] ?? '';
// $credenciada = $_POST['credenciada'] ?? '';
// $inicio = $_POST['inicio'] ?? '';
// $fim = $_POST['fim'] ?? '';

// $query = "SELECT id, nome, rg, cpf, nascimento, sexo, data_exame, tipo_exame, nomes_exames, observacao 
//           FROM asos 
//           WHERE empresa = :empresa 
//           AND credenciada = :credenciada";

// $params = [
//     ':empresa' => $empresa,
//     ':credenciada' => $credenciada
// ];

// if (!empty($inicio) && !empty($fim)) {
//     $query .= " AND data_exame BETWEEN :inicio AND :fim";
//     $params[':inicio'] = $inicio;
//     $params[':fim'] = $fim;
// } elseif (!empty($inicio)) {
//     $query .= " AND data_exame >= :inicio";
//     $params[':inicio'] = $inicio;
// } elseif (!empty($fim)) {
//     $query .= " AND data_exame <= :fim";
//     $params[':fim'] = $fim;
// }

// $result = $crud->read($query, $params);

// Verifica e converte o retorno da função read em array associativo, caso seja uma string JSON
// function convertToArray($result) {
//     if (is_string($result)) {
//         $arrayResult = json_decode($result, true); 
//         return $arrayResult ?? []; 
//     }
//     return $result;
// }

// Prepare arrays to store the mappings of names
// $tiposExames = [];
// $nomesExames = [];

// Fetch tipos_exames names
// $tiposExamesQuery = "SELECT id, nome FROM cadastro_tiposexames";
// $tiposExamesResult = convertToArray($crud->read($tiposExamesQuery, []));

// foreach ($tiposExamesResult as $tipoExame) {
//     $tiposExames[$tipoExame['id']] = $tipoExame['nome'];
// }

// Fetch nomes_exames names
// $nomesExamesQuery = "SELECT id, nome FROM cadastro_nomesexames";
// $nomesExamesResult = convertToArray($crud->read($nomesExamesQuery, []));

// foreach ($nomesExamesResult as $nomeExame) {
//     $nomesExames[$nomeExame['id']] = $nomeExame['nome'];
// }

// Map the results with the names
// $result = convertToArray($result);

// Array to hold the grouped results
// $groupedResults = [];

// foreach ($result as $row) {
//     $userKey = $row['nome'] . '-' . $row['rg'] . '-' . $row['cpf']; // Unique key for each user
    
//     if (!isset($groupedResults[$userKey])) {
//         $groupedResults[$userKey] = $row;
//         $groupedResults[$userKey]['nomes_exames'] = [];
//     }

    // Append the exam name to the 'nomes_exames' array
//     $examName = $nomesExames[$row['nomes_exames']] ?? 'Desconhecido';
//     $groupedResults[$userKey]['nomes_exames'][] = $examName;
// }

// Re-index the grouped results
// $finalResults = array_values($groupedResults);

// header('Content-Type: application/json');
// echo json_encode($finalResults);

?>




