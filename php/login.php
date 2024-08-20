<?php
session_start(); // Iniciar a sessão

require_once 'Database.php';
require_once 'Crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'];
    $password = $_POST['senha'];
    
    // Obter a conexão com o banco de dados
    $database = new Database();
    $db = $database->getConnection();

    // Instanciar o CRUD
    $crud = new Crud($db);

    // Query para verificar o usuário
    $query = "SELECT * FROM usuarios WHERE cpf = :cpf";
    
    // Preparar e executar a query
    $stmt = $db->prepare($query);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['senha'])) {
        // Verificar se o usuário está ativo
        if ($user['status'] != 1) {
            echo "<script>alert('Usuário desativado. Por favor, entre em contato com o administrador.'); window.location.href='../index.html';</script>";
            exit();
        }

        // Verificar se o usuário é staff
        if ($user['is_staff'] != 1) {
            echo "<script>alert('Você não tem permissão para acessar o sistema.'); window.location.href='../index.html';</script>";
            exit();
        }

        // Senha correta, criar a sessão do usuário
        $_SESSION['user_id'] = $user['id'];

        // Definir cookie com o ID do usuário
        setcookie('user_id', $user['id'], time() + 43200, '/'); // Cookie válido por 12 horas

        // Atualizar o campo "last_login" com a data e hora atual
        $updateQuery = "UPDATE usuarios SET last_login = :last_login WHERE id = :id";
        $stmt = $db->prepare($updateQuery);
        $stmt->bindParam(':last_login', date('Y-m-d H:i:s'));
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();

        // Redirecionar para o dashboard
        header("Location: ../pages/dash.html");
        exit();
    } else {
        // Senha incorreta, exibir alerta
        echo "<script>alert('Login falhou. Por favor, verifique suas credenciais e tente novamente.'); window.location.href='../index.html';</script>";
    }
} else {
    // Redirecionar para a página de login se a requisição não for POST
    header("Location: login.html");
    exit();
}
?>
