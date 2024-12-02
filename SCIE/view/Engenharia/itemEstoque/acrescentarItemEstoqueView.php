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
} catch (Exception $e) {
    echo "Erro ao buscar o item: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/Geral.css">
    <title>SCIE - Acrescentar Item</title>
</head>
<body>
    <?php include '../../../control/cabecalho.php'; ?>
    <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>

    <div class="form-container">
        <h1 class="titulo">Acrescentar Item Igual</h1>
        <form action="../../../control/ItemEstoqueController.php?action=acrescentarItem" method="POST" class="form-padrao">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <label for="quantidade" class="form-label">Quantidade a Acrescentar:</label>
            <input type="number" name="quantidade" min="1" required class="form-input"><br/>

            <label for="dataEntrada" class="form-label">Data:</label>
            <input type="date" name="dataEntrada" value="<?php echo date('Y-m-d'); ?>" required class="form-input"><br/>

            <label for="responsavel" class="form-label">Responsável:</label>
            <input type="text" name="responsavel" placeholder="Digite o nome do responsável" required class="form-input"><br/>

            <label for="comentario" class="form-label">Comentário:</label>
            <textarea name="comentario" rows="4" cols="50" class="form-textarea" placeholder="Adicione informações relevantes sobre o acréscimo."></textarea><br/>

            <div class="form-actions">
                <button type="submit" class="button-forms">Confirmar</button>
                <button type="button" class="button-forms" onclick="window.location.href='estoqueInternoView.php'">Cancelar</button>
            </div>
        </form>
    </div>
</body>
</html>
