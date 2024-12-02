<?php
session_start();
require_once 'cabecalho.php';

$userType = $_SESSION['user_type'] ?? null;

$cabecalho = new Cabecalho($userType);
$cabecalho->render();

if (!isset($_SESSION['user_type'])) {
    $_SESSION['mensagem_erro'] = "Acesso negado. Por favor, faça login.";
    header('Location: ../../view/login.php');
    exit();
}

$tipoUsuario = $_SESSION['user_type'];

if ($tipoUsuario === 'Engenharia') {
    include_once __DIR__ . '/../view/Engenharia/homeEngenharia.php';
} elseif ($tipoUsuario === 'TI') {
    include_once __DIR__ . '/../view/TI/homeTI.php';
} else {
    $_SESSION['mensagem_erro'] = "Acesso negado. Tipo de usuário inválido.";
    header('Location: ../../view/login.php');
    exit();
}
?>
