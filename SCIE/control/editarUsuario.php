<?php
require_once __DIR__ . '/UsuarioController.php';

require_once 'cabecalho.php';

$userType = $_SESSION['user_type'] ?? null;

$cabecalho = new Cabecalho($userType);
$cabecalho->render();

if (!isset($_SESSION['user_type'])) {
    $_SESSION['mensagem_erro'] = "Acesso negado. Por favor, faça login.";
    header('Location: ../../view/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $controller = new UsuarioController();
    $controller->editarUsuario($_GET['id']);
} else {
    $_SESSION['mensagem'] = "ID inválido ou não fornecido.";
    $_SESSION['tipoMensagem'] = "error";
    header('Location: ../../view/TI/listarUsuariosView.php');
    exit();
}
