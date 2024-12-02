<?php
session_start();
require '../../factory/Database.php';
require '../../model/entities/ItemEstoque.php';
require '../../model/entities/HistoricoEstoque.php';

try {
    $id = $_POST['id'] ?? null;
    $dataEntrada = $_POST['dataEntrada'] ?? null;

    if (!$id || !$dataEntrada) {
        throw new Exception("ID do item ou data de entrada não fornecidos.");
    }

    $db = (new Database())->connect();

    $itemModel = new ItemEstoque($db);

    $novoStatus = 'Em estoque';
    $itemOriginal = $itemModel->alterarStatus($id, $novoStatus, $dataEntrada);

    if (!$itemOriginal) {
        throw new Exception("Erro ao alterar o status do item.");
    }

    $historico = new HistoricoEstoque(
        $itemOriginal['responsavel'],
        $itemOriginal['codigoInterno'],
        $itemOriginal['quantidade_total'],
        $itemOriginal['descricao'],               
        "Entrada",                    
        $dataEntrada,
        "Retorno da produção"   
    );

    if (!$historico->registrarHistorico($db)) {
        throw new Exception("Erro ao registrar o histórico de movimentação.");
    }

    $_SESSION['mensagem'] = "Status alterado e registrado no histórico com sucesso!";
    $_SESSION['tipoMensagem'] = "success";
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro ao alterar status: " . $e->getMessage();
    $_SESSION['tipoMensagem'] = "error";
}

header("Location: ../../view/Engenharia/itemEstoque/estoqueInternoView.php");
exit();
