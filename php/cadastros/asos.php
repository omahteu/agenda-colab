<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$unidade = $_POST['unidade'];
$empresa = $_POST['empresa'];
$credenciada = $_POST['credenciada'];
$nome = $_POST['nome'];
$rg = $_POST['rg'];
$cpf = $_POST['cpf'];
$nascimento = $_POST['nascimento'];
$sexo = $_POST['sexo'];
switch ($sexo) {
    case 'm':
        $sexo = 'masculino';
        break;
    case 'f':
        $sexo = 'feminino';
        break;
    case 'o':
        $sexo = 'outro';
        break;
    default:
        $sexo = 'indefinido';
        break;
}
$funcao = $_POST['funcao'];
$dataexame = $_POST['dataexame'];
$tiposexames = $_POST['tiposexames'];
$nomesexames = $_POST['nomesexames']; // Supõe-se que seja uma lista
$observacao = $_POST['observacao'];

// Verifica se $nomesexames é uma lista
if (!is_array($nomesexames)) {
    echo json_encode(['error' => 'nomesexames deve ser uma lista']);
    exit();
}

// Monta a consulta SQL para inserção
$query = "INSERT INTO asos (unidade, empresa, credenciada, nome, rg, cpf, nascimento, sexo, funcao, data_exame, tipo_exame, nomes_exames, observacao) 
          VALUES (:unidade, :empresa, :credenciada, :nome, :rg, :cpf, :nascimento, :sexo, :funcao, :dataexame, :tiposexames, :nomesexames, :observacao)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros comuns
$stmt->bindParam(':unidade', $unidade);
$stmt->bindParam(':empresa', $empresa);
$stmt->bindParam(':credenciada', $credenciada);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':rg', $rg);
$stmt->bindParam(':cpf', $cpf);
$stmt->bindParam(':nascimento', $nascimento);
$stmt->bindParam(':sexo', $sexo);
$stmt->bindParam(':funcao', $funcao);
$stmt->bindParam(':dataexame', $dataexame);
$stmt->bindParam(':tiposexames', $tiposexames);
$stmt->bindParam(':observacao', $observacao);

$errors = [];

foreach ($nomesexames as $nome_exame) {
    // Liga o parâmetro específico de cada nome_exame
    $stmt->bindParam(':nomesexames', $nome_exame);
    
    // Executa a consulta
    if (!$stmt->execute()) {
        // Coleta os erros, se houver
        $errorInfo = $stmt->errorInfo();
        $errors[] = $errorInfo[2];
    }
}

if (empty($errors)) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/forms/emitir.html");
    exit();
} else {
    // Exibe a mensagem de erro
    echo json_encode(['error' => $errors]);
}
?>
