<?php
session_start();
require '../../factory/Database.php';
require '../../model/entities/ItemEstoque.php';
require '../../model/entities/HistoricoEstoque.php';

try {
    if (!isset($_POST['id']) || !isset($_POST['quantidade']) || empty($_POST['quantidade']) || !isset($_POST['responsavel']) || empty($_POST['responsavel'])) {
        throw new Exception("Dados obrigatórios não foram fornecidos.");
    }

    $id = $_POST['id'];
    $quantidadeAcrescentada = (int)$_POST['quantidade'];
    $dataEntrada = $_POST['dataEntrada'];
    $responsavel = $_POST['responsavel'];
    $comentario = $_POST['comentario'] ?? '';

    $db = (new Database())->connect();

    $itemModel = new ItemEstoque($db);

    $itemData = $itemModel->buscarPorId($id);
    if (!$itemData) {
        throw new Exception("Item não encontrado.");
    }

    $novaQuantidade = (int)$itemData['quantidade_total'] + $quantidadeAcrescentada;
    if (!$itemModel->atualizarQuantidade($id, $novaQuantidade)) {
        throw new Exception("Erro ao atualizar a quantidade do item.");
    }
    
    $historico = new HistoricoEstoque(
        $responsavel,
        $itemData['codigoInterno'],
        $quantidadeAcrescentada,
        $itemData['descricao'], 
        $dataEntrada,
        $comentario
    );

    if (!$historico->registrarHistorico($db)) {
        throw new Exception("Erro ao registrar histórico de estoque.");
    }

    $_SESSION['mensagem'] = "Quantidade acrescentada com sucesso e registrada no histórico.";
    $_SESSION['tipoMensagem'] = "success";
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro ao acrescentar item: " . $e->getMessage();
    $_SESSION['tipoMensagem'] = "error";
}

header("Location: ../../view/Engenharia/itemEstoque/estoqueInternoView.php");
exit();
