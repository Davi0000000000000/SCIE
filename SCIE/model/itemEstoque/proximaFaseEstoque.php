<?php
session_start();
require '../../factory/Database.php';
require '../../model/entities/ItemEstoque.php';
require '../../model/entities/HistoricoEstoque.php';

try {
    $pdo = (new Database())->connect();
    $pdo->beginTransaction();

    $responsavel = $_POST['responsavel'] ?? null;
    $codigoInternoOriginal = $_POST['codigoInternoOriginal'];
    $novoCodigoInterno = $_POST['codigoInterno'];
    $quantidade = $_POST['quantidade'];
    $faseNova = $_POST['faseNova'];
    $dataEntrada = $_POST['dataEntrada'];
    $comentario = $_POST['comentario'] ?? '';
    $localizacao = $_POST['localizacao'] ?? null;

    $itemOriginalStmt = $pdo->prepare("SELECT * FROM itens WHERE codigoInterno = :codigoInterno");
    $itemOriginalStmt->bindParam(':codigoInterno', $codigoInternoOriginal, PDO::PARAM_STR);
    $itemOriginalStmt->execute();
    $itemOriginal = $itemOriginalStmt->fetch(PDO::FETCH_ASSOC);

    if (!$itemOriginal) {
        throw new Exception("Item original não encontrado.");
    }

    $updateQuantidadeStmt = $pdo->prepare("UPDATE itens SET quantidade_total = quantidade_total - :quantidade WHERE codigoInterno = :codigoInterno");
    $updateQuantidadeStmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
    $updateQuantidadeStmt->bindParam(':codigoInterno', $codigoInternoOriginal, PDO::PARAM_STR);
    $updateQuantidadeStmt->execute();

    $imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../view/imagensItens/';
        $fileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
        $filePath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $filePath)) {
            $imagem = 'view/imagensItens/' . $fileName;
        } else {
            throw new Exception("Erro ao fazer upload da imagem.");
        }
    }

    $insertNovoItemStmt = $pdo->prepare("
        INSERT INTO itens (codigoInterno, descricao, quantidade_total, cliente, revDesenho, responsavel, data_entrada, localizacao, comentarios, imagem, status) 
        VALUES (:codigoInterno, :descricao, :quantidade, :cliente, :revDesenho, :responsavel, :dataEntrada, :localizacao, :comentarios, :imagem, :status)
    ");
    $statusNovoItem = 'Em processo';
    $insertNovoItemStmt->execute([
        ':codigoInterno' => $novoCodigoInterno,
        ':descricao' => $faseNova,
        ':quantidade' => $quantidade,
        ':cliente' => $itemOriginal['cliente'],
        ':revDesenho' => $itemOriginal['revDesenho'],
        ':responsavel' => $responsavel,
        ':dataEntrada' => $dataEntrada,
        ':localizacao' => $localizacao,
        ':comentarios' => $comentario,
        ':imagem' => $imagem,
        ':status' => $statusNovoItem,
    ]);

    $historico = new HistoricoEstoque(
        $responsavel,
        $codigoInternoOriginal,
        $quantidade,
        $faseNova,                 
        "Saída",  
        $dataEntrada,
        $comentario
    );

    if (!$historico->registrarHistorico($pdo)) {
        throw new Exception("Erro ao registrar o histórico de movimentação.");
    }

    $pdo->commit();
    $_SESSION['mensagem'] = "Movimentação registrada com sucesso!";
    $_SESSION['tipoMensagem'] = "success";
} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    $_SESSION['mensagem'] = "Erro ao registrar movimentação: " . $e->getMessage();
    $_SESSION['tipoMensagem'] = "error";
}

header("Location: ../../view/Engenharia/itemEstoque/estoqueInternoView.php");
exit();
