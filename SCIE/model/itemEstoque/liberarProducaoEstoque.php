<?php
session_start();
require '../../factory/Database.php';
require '../../model/entities/ItemEstoque.php';
require '../../model/entities/HistoricoEstoque.php';

try {
    $pdo = (new Database())->connect();

    if (!isset($_POST['id'], $_POST['quantidade'], $_POST['responsavel'], $_POST['data_entrada']) || empty($_POST['quantidade'])) {
        throw new Exception("ID, quantidade, responsável ou data de entrada não fornecidos.");
    }

    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $responsavel = filter_var($_POST['responsavel'], FILTER_SANITIZE_STRING);
    $dataEntrada = filter_var($_POST['data_entrada'], FILTER_SANITIZE_STRING);
    $quantidade = filter_var($_POST['quantidade'], FILTER_VALIDATE_INT);
    $comentario = filter_var($_POST['comentario'] ?? '', FILTER_SANITIZE_STRING);

    if (!$id || !$responsavel || !$dataEntrada || !$quantidade || $quantidade <= 0) {
        throw new Exception("Dados inválidos ou incompletos fornecidos.");
    }

    $itemModel = new ItemEstoque($pdo);
    $itemData = $itemModel->buscarPorId($id);

    if (!$itemData) {
        throw new Exception("Item não encontrado.");
    }

    $quantidadeAtual = (int)$itemData['quantidade_total'];
    if ($quantidade > $quantidadeAtual) {
        throw new Exception("Quantidade solicitada excede o estoque disponível.");
    }

    $historico = new HistoricoEstoque(
        $responsavel,
        $itemData['codigoInterno'],
        $quantidade,
        $itemData['descricao'],       
        'Saída',                   
        $dataEntrada,
        $comentario
    );

    if (!$historico->registrarHistorico($pdo)) {
        throw new Exception("Erro ao registrar o histórico de movimentação.");
    }

    if ($quantidade === $quantidadeAtual) {
        $itemModel->removerPorId($id);
    } else {
        $novaQuantidade = $quantidadeAtual - $quantidade;
        $itemModel->atualizarQuantidade($id, $novaQuantidade);
    }

    $_SESSION['mensagem'] = "Item liberado para produção com sucesso e registrado no histórico.";
    $_SESSION['tipoMensagem'] = "success";
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro ao liberar para produção: " . $e->getMessage();
    $_SESSION['tipoMensagem'] = "error";
}

header("Location: ../../view/Engenharia/itemEstoque/estoqueInternoView.php");
exit();
