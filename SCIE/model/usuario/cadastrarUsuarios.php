<?php
session_start(); 
require_once '../../factory/Database.php'; 
require_once '../../control/cabecalho.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'TI') {
    $_SESSION['mensagem_erro'] = "Acesso negado. Você não tem permissão para acessar esta página.";
    header("Location: ../../view/login.php");
    exit();
}

$userType = $_SESSION['user_type'];
$cabecalho = new Cabecalho($userType);
$cabecalho->render();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pdo = (new Database())->connect();

        $usuario = trim($_POST['usuario'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        $confirmar_senha = trim($_POST['confirmar_senha'] ?? '');
        $tipo_usuario = trim($_POST['tipo_usuario'] ?? '');

        if (empty($usuario) || empty($senha) || empty($confirmar_senha) || empty($tipo_usuario)) {
            throw new Exception("Todos os campos são obrigatórios.");
        }

        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres.");
        }
        
        if ($senha !== $confirmar_senha) {
            throw new Exception("As senhas não correspondem.");
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("O nome de usuário já está em uso.");
        }
        
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
        if (!$senhaHash) {
            throw new Exception("Erro ao gerar o hash da senha.");
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha, tipo_usuario) VALUES (?, ?, ?)");
        $stmt->execute([$usuario, $senhaHash, $tipo_usuario]);

        $_SESSION['mensagem_sucesso'] = "Usuário cadastrado com sucesso.";
        header("Location: ../../view/TI/listarUsuariosView.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['mensagem_erro'] = $e->getMessage();
        header("Location: ../../view/TI/cadastrarUsuariosView.php");
        exit();
    }
} else {
    header("Location: ../../view/TI/cadastrarUsuariosView.php");
    exit();
}
