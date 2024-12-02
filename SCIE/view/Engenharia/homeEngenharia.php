<?php
require_once BASE_PATH . '/control/cabecalho.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Engenharia') {
    $_SESSION['mensagem_erro'] = "Acesso negado. Por favor, faça login.";
    header('Location: ' . BASE_URL . '/view/login.php');
    exit();
}

$cabecalho = new Cabecalho($_SESSION['user_type']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/Geral.css">
    <title>SCIE - Home Engenharia</title>
</head>
<body>

    <div class="grid-container">
        <a href="<?php echo BASE_URL; ?>/view/aviso.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/adicionarItemIcone.png" alt="Adicionar Item">
            <span>Adicionar Item de Desenvolvimento</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/view/aviso.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/listarItensIcone.png" alt="Listar Itens de desenvolvimento">
            <span>Listar Itens de Desenvolvimento</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/view/Engenharia/itemEstoque/estoqueInternoView.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/estoqueInternoIcone.png" alt="Estoque Engenharia">
            <span>Estoque Engenharia</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/view/aviso.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/beneficiamentoIcone.png" alt="Item em Beneficiamento">
            <span>Itens em Beneficiamento</span>
        </a>
    </div>
</body>
</html>
