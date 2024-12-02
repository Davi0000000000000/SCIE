<?php
require_once '../../../config.php';
require_once BASE_PATH . '/control/itemEstoqueController.php';

if (!isset($_GET['id'])) {
    echo "ID do item não fornecido.";
    exit();
}

$id = $_GET['id'];

$controller = new itemEstoqueController();
$itemData = $controller->obterItemPorId($id);

if (isset($itemData['error'])) {
    echo "<p class='error-message'>" . htmlspecialchars($itemData['error']) . "</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/Geral.css">
    <script src="/SCIE/js/js.js" defer></script>
    <title>SCIE - Próxima Etapa</title>
</head>
<body>
    <?php include '../../../control/cabecalho.php'; ?>
    <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>

    <h1>Passar para a Próxima Etapa</h1>
    <form action="../../../model/itemEstoque/proximaFaseEstoque.php" method="POST" enctype="multipart/form-data" class="form-container">
        <input type="hidden" name="codigoInternoOriginal" value="<?php echo htmlspecialchars($itemData['codigoInterno']); ?>">

        <label for="codigoInterno">Novo Código Interno:</label>
        <input type="text" name="codigoInterno" required><br/>

        <label for="faseNova">Nova Fase (Descrição):</label>
        <select name="faseNova" required>
            <?php
            $fases = [
                'laser', 'soldaPonto', 'soldaMig', 'soldaSuspensa', 'estamparia',
                'inspecaoFinal', 'pintura', 'zincagem', 'oleamento', 'rebitagem',
                'usinagem', 'escovar'
            ];
            foreach ($fases as $fase) {
                echo "<option value=\"$fase\">" . ucfirst($fase) . "</option>";
            }
            ?>
        </select><br/>

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" value="<?php echo htmlspecialchars($itemData['quantidade_total']); ?>" min="1" max="<?php echo htmlspecialchars($itemData['quantidade_total']); ?>" required><br/>

        <label for="dataEntrada">Data:</label>
        <input type="date" name="dataEntrada" value="<?php echo htmlspecialchars($itemData['data_entrada']); ?>" required><br/>

        <label for="responsavel">Responsável:</label>
        <input type="text" name="responsavel" value="<?php echo htmlspecialchars($itemData['responsavel']); ?>" required><br/>

        <label for="localizacao">Localização:</label>
        <input type="text" name="localizacao" value="<?php echo htmlspecialchars($itemData['localizacao']); ?>"><br/>

        <label for="comentario">Comentário (opcional):</label>
        <textarea name="comentario" rows="4" cols="50" placeholder="Insira um comentário se necessário..."></textarea><br/>

        <label for="imagem" class="form-label">Imagem (PNG, PDF, TIF, DWG):</label>
<label class="upload-button" for="imagem">Selecionar Arquivo</label>
<input type="file" id="imagem" name="imagem" accept=".png, .pdf, .tif, .dwg" class="inserirImagem">
<span id="file-name" class="file-name">Nenhum arquivo selecionado</span>


        <button type="submit" class='button-forms'>Confirmar</button>
        <button type="button" class='button-forms' onclick="window.location.href='estoqueInternoView.php'">Cancelar</button>
    </form>
</body>
</html>
