<?php
session_start();

require_once __DIR__ . '/../../factory/Database.php';
require_once __DIR__ . '/../entities/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['cxusuario'] ?? '');
    $password = trim($_POST['cxsenha'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['mensagem_erro'] = "Por favor, preencha todos os campos.";
        header('Location: ../../view/login.php');
        exit();
    }

    try {
        $pdo = (new Database())->connect();

        $usuario = new Usuario($pdo);
        if ($usuario->verificarLogin($username, $password)) {
            $_SESSION['user_id'] = $usuario->getId();
            $_SESSION['user_type'] = $usuario->getTipoUsuario();
            $_SESSION['nome_usuario'] = $usuario->getUsuario();

            header("Location: ../../control/HomeController.php");
            exit();
        }

        $_SESSION['mensagem_erro'] = "As credenciais fornecidas são inválidas.";
        header('Location: ../../view/login.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['mensagem_erro'] = "Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.";
        header('Location: ../../view/login.php');
        exit();
    }
}

$_SESSION['mensagem_erro'] = "Acesso inválido.";
header('Location: ../../view/login.php');
exit();
