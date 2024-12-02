<?php
require '../../../factory/Database.php';
require '../../../model/entities/ItemEstoque.php';

if (!isset($_GET['id'])) {
    echo "ID do item não fornecido.";
    exit();
}

$id = $_GET['id'];

try {
    $db = (new Database())->connect();

    $itemModel = new ItemEstoque($db);

    $itemData = $itemModel->buscarPorId($id);

    if (!$itemData) {
        echo "Item não encontrado.";
        exit();
    }

    $item = new ItemEstoque(
        $db,
        $itemData['codigoInterno'],
        $itemData['cliente'],
        $itemData['comentarios'],
        $itemData['quantidade_total'],
        $itemData['responsavel'],
        $itemData['data_entrada'],
        $itemData['revDesenho'],
        $itemData['descricao'],
        $itemData['localizacao']
    );
} catch (PDOException $e) {
    echo "Erro ao buscar o item: " . htmlspecialchars($e->getMessage());
    exit();
} catch (Exception $e) {
    echo "Erro ao criar a instância do item: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/Geral.css">
    <title>SCIE - Liberar para Produção</title>
</head>
<body>
    <?php include '../../../control/cabecalho.php'; ?>
    <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>
    <div class="form-container">
        <h1 class="titulo">Liberar para Produção</h1>
        <form action="../../../model/itemEstoque/liberarProducaoEstoque.php" method="POST" class="form-padrao">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">

            <label for="responsavel" class="form-label">Responsável:</label>
            <input type="text" name="responsavel" value="<?php echo htmlspecialchars($item->getResponsavel() ?? '', ENT_QUOTES, 'UTF-8'); ?>" required class="form-input">

            <label for="data_entrada" class="form-label">Data:</label>
            <input type="date" name="data_entrada" required class="form-input">

            <label for="quantidade" class="form-label">Quantidade:</label>
            <input type="number" name="quantidade" min="1" max="<?php echo htmlspecialchars($item->getQuantidadeTotal() ?? 0, ENT_QUOTES, 'UTF-8'); ?>" required class="form-input">

            <label for="comentario" class="form-label">Comentário:</label>
            <textarea name="comentario" rows="4" cols="50" class="form-textarea"><?php echo htmlspecialchars($item->getComentarios() ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

            <div class="form-actions">
                <button type="submit" class="button-forms">Confirmar</button>
                <button type="button" class="button-forms" onclick="window.history.back()">Cancelar</button>
            </div>
        </form>
    </div>
</body>
</html>
