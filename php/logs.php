<?php
// Conexão com o banco de dados
require_once 'Database.php';
require_once 'Crud.php';

$database = new Database();
$db = $database->getConnection();
$crud = new Crud($db);

// Receber os dados enviados via POST
$dados = json_decode(file_get_contents("php://input"), true);

// Verifica se todos os campos obrigatórios estão presentes
if (
    isset(
        $dados['tipo_erro'], 
        $dados['mensagem_erro'], 
        $dados['arquivo'], 
        $dados['linha'], 
        $dados['funcao_metodo'], 
        $dados['usuario_id'], 
        $dados['url_requisicao'], 
        $dados['dados_requisicao'],
        $dados['dados_resposta'],
    )
) {
    // Query de inserção
    $query = "INSERT INTO log_erros 
        (tipo_erro, mensagem_erro, arquivo, linha, funcao_metodo, usuario_id, url_requisicao, dados_requisicao, dados_resposta, data_ocorrencia) 
        VALUES (:tipo_erro, :mensagem_erro, :arquivo, :linha, :funcao_metodo, :usuario_id, :url_requisicao, :dados_requisicao, :dados_resposta, NOW())";

    // Prepara a query
    $stmt = $db->prepare($query);

    // Substitui os parâmetros pelos valores
    $stmt->bindParam(':tipo_erro', $dados['tipo_erro']);
    $stmt->bindParam(':mensagem_erro', $dados['mensagem_erro']);
    $stmt->bindParam(':arquivo', $dados['arquivo']);
    $stmt->bindParam(':linha', $dados['linha']);
    $stmt->bindParam(':funcao_metodo', $dados['funcao_metodo']);
    $stmt->bindParam(':usuario_id', $dados['usuario_id']);
    
    $stmt->bindParam(':url_requisicao', $dados['url_requisicao']);
    $stmt->bindParam(':dados_requisicao', $dados['dados_requisicao']);
    $stmt->bindParam(':dados_resposta', $dados['dados_resposta']);

    // Executa a query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Log registrado com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falha ao registrar o log.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos para o log.']);
}
