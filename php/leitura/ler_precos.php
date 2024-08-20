<?php
// Inclua a conexão com o banco de dados
require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Verifica se os IDs foram recebidos
if (isset($_POST['ids']) && !empty($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Converte os IDs em uma string para a query SQL
    $idsString = implode(',', array_map('intval', $ids));

    // Primeira query para buscar os nomes e valores dos exames
    $query = "SELECT nomes_exames, valor_exame FROM cadastro_precos WHERE id IN ($idsString)";
    $result = $crud->read($query);

    // Decodifica o resultado JSON em um array PHP
    $resultArray = json_decode($result, true);

    // Verifica se a decodificação foi bem-sucedida e se retornou um array
    if (json_last_error() === JSON_ERROR_NONE && is_array($resultArray)) {
        // Extrai os IDs dos exames do resultado
        $examesIds = array();
        foreach ($resultArray as $row) {
            // Verifica se 'nomes_exames' é um valor válido e adicione ao array
            if (isset($row['nomes_exames']) && is_numeric($row['nomes_exames'])) {
                $examesIds[] = intval($row['nomes_exames']);
            }
        }

        // Verifica se há IDs de exames para buscar
        if (!empty($examesIds)) {
            // Converte os IDs dos exames em uma string para a segunda query SQL
            $examesIdsString = implode(',', $examesIds);

            // Segunda query para buscar os nomes dos exames
            $queryNomes = "SELECT id, nome FROM cadastro_nomesexames WHERE id IN ($examesIdsString)";
            $resultNomes = $crud->read($queryNomes);
            // Decodifica o resultado JSON em um array PHP
            $resultNomesArray = json_decode($resultNomes, true);

            // Verifica se a decodificação foi bem-sucedida e se retornou um array
            if (json_last_error() === JSON_ERROR_NONE && is_array($resultNomesArray)) {
                // Cria um array associativo para facilitar a busca dos nomes dos exames
                $nomesExames = array();
                foreach ($resultNomesArray as $rowNome) {
                    // Verifica se 'id' e 'nome' são valores válidos e adicione ao array associativo
                    if (isset($rowNome['id']) && isset($rowNome['nome'])) {
                        $nomesExames[intval($rowNome['id'])] = $rowNome['nome'];
                    }
                }

                // Adiciona os nomes dos exames ao resultado original
                foreach ($resultArray as &$row) {
                    if (isset($nomesExames[intval($row['nomes_exames'])])) {
                        $row['nome'] = $nomesExames[intval($row['nomes_exames'])];
                    } else {
                        $row['nome'] = null;
                    }
                }

                // Libera a referência ao último elemento para evitar problemas
                unset($row);

                // Imprime o resultado final
                echo json_encode($resultArray, JSON_PRETTY_PRINT);
            } else {
                echo json_encode(['error' => 'Nenhum nome de exame encontrado para os IDs fornecidos.']);
            }
        } else {
            echo json_encode(['error' => 'Nenhum ID de exame válido encontrado nos resultados da primeira consulta.']);
        }
    } else {
        echo json_encode(['error' => 'Nenhum resultado encontrado para os IDs fornecidos.']);
    }
} else {
    echo json_encode(['error' => 'Nenhum ID fornecido.']);
}
?>
