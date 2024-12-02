<?php
session_start();
require '../../factory/Database.php';
require '../../model/entities/ItemEstoque.php';

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            throw new Exception("ID do item não fornecido.");
        }

        $codigoInterno = $_POST['codigoInterno'] ?? null;
        $cliente = $_POST['cliente'] ?? null;
        $revDesenho = $_POST['revDesenho'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $quantidade = $_POST['quantidade_total'] ?? 0;
        $comentario = $_POST['comentario'] ?? '';
        $localizacao = $_POST['localizacao'] ?? null;
        $responsavel = $_POST['responsavel'] ?? null;

        $imagem = null;

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $pasta = __DIR__ . '/../../view/imagensItens/';
            $nomeArquivo = uniqid() . '-' . basename($_FILES['imagem']['name']);
            $caminhoArquivo = $pasta . $nomeArquivo;

            if (!is_dir($pasta)) {
                mkdir($pasta, 0777, true);
            }

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoArquivo)) {
                $imagem = "view/imagensItens/" . $nomeArquivo;
            } else {
                throw new Exception("Erro ao fazer upload da imagem.");
            }
        }

        $db = (new Database())->connect();

        $item = new ItemEstoque(
            $db,
            $codigoInterno,
            $cliente,
            $comentario,
            $quantidade,
            $responsavel,
            null, 
            $revDesenho,
            $descricao,
            $localizacao,
            null, 
            $imagem 
        );

        if (!$item->editarItem($id, $imagem)) {
            throw new Exception("Erro ao atualizar o item.");
        }

        $_SESSION['mensagem'] = "Item atualizado com sucesso!";
        $_SESSION['tipoMensagem'] = "success";
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = "Erro ao atualizar item: " . $e->getMessage();
    $_SESSION['tipoMensagem'] = "error";
}

header("Location: ../../view/Engenharia/itemEstoque/estoqueInternoView.php");
exit();
