<?php
session_start();
require_once __DIR__ . '/../../control/ItemEstoqueController.php';

if (!isset($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do item não fornecido.";
    $_SESSION['tipoMensagem'] = "error";
    header("Location: ../../view/Engenharia/itemEstoque/estoqueInternoView.php");
    exit();
}

$id = $_GET['id'];
$controller = new ItemEstoqueController();
$result = $controller->removerItem($id);

if (isset($result['success'])) {
    $_SESSION['mensagem'] = $result['success'];
    $_SESSION['tipoMensagem'] = "success";
} else {
    $_SESSION['mensagem'] = $result['error'];
    $_SESSION['tipoMensagem'] = "error";
}

header("Location: ../../view/Engenharia/itemEstoque/estoqueInternoView.php");
exit();
