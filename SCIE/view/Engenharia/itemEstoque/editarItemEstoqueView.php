<?php
require_once '../../../config.php';
require_once BASE_PATH . '/control/ItemEstoqueController.php';

if (!isset($_GET['id'])) {
    echo "ID do item não fornecido.";
    exit();
}

$id = $_GET['id'];

$controller = new ItemEstoqueController();
$itemData = $controller->buscarPorId($id); 

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
    <title>SCIE - Editar Item</title>
</head>
<body>
    <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>

    <div id="editarItem" class="form-container">
        <h1 class="titulo">Editar Item - Código: <?php echo htmlspecialchars($itemData['codigoInterno']); ?></h1>
        <form action="../../../model/itemEstoque/editarItemEstoque.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>"/>

            <label for="codigoInterno" class="form-label">Código Interno:</label>
            <input type="text" id="codigoInterno" name="codigoInterno" value="<?php echo htmlspecialchars($itemData['codigoInterno']); ?>" required/>

            <label for="cliente" class="form-label">Cliente:</label>
            <input type="text" id="cliente" name="cliente" value="<?php echo htmlspecialchars($itemData['cliente']); ?>" required/>

            <label for="revDesenho" class="form-label">RevDesenho:</label>
            <input type="text" id="revDesenho" name="revDesenho" value="<?php echo htmlspecialchars($itemData['revDesenho']); ?>" required/>

            <label for="descricao" class="form-label">Descrição:</label>
            <select id="descricao" name="descricao" class="form-select" required>
                <?php
                $opcoesDescricao = [
                    'laser', 'soldaPonto', 'soldaMig', 'soldaSuspensa', 'estamparia', 
                    'inspecaoFinal', 'pintura', 'zincagem', 'oleamento', 'rebitagem', 
                    'usinagem', 'carepa'
                ];
                foreach ($opcoesDescricao as $opcao) {
                    $selected = $itemData['descricao'] === $opcao ? 'selected' : '';
                    echo "<option value='$opcao' $selected>" . ucfirst($opcao) . "</option>";
                }
                ?>
            </select>

            <label for="quantidade_total" class="form-label">Quantidade:</label>
            <input type="number" id="quantidade_total" name="quantidade_total" class="form-input" value="<?php echo htmlspecialchars($itemData['quantidade_total']); ?>" required/>

            <label for="responsavel" class="form-label">Responsável:</label>
            <input type="text" id="responsavel" name="responsavel" class="form-input" value="<?php echo htmlspecialchars($itemData['responsavel']); ?>" required/>

            <label for="localizacao" class="form-label">Localização:</label>
            <input type="text" id="localizacao" name="localizacao" class="form-input" value="<?php echo htmlspecialchars($itemData['localizacao']); ?>" required/>

            <label for="comentario" class="form-label">Comentário:</label>
            <textarea id="comentario" name="comentario" class="form-textarea" rows="4"><?php echo htmlspecialchars($itemData['comentarios']); ?></textarea>

            <label for="imagem" class="form-label">Imagem (PNG, PDF, TIF, DWG):</label>
            <label class="upload-button" for="imagem">Selecionar Arquivo</label>
            <input type="file" id="imagem" name="imagem" accept=".png, .pdf, .tif, .dwg" class="inserirImagem">
            <span id="file-name" class="file-name">Nenhum arquivo selecionado</span>

            <input type="submit" value="Atualizar" class="button-forms">
        </form>
    </div>
</body>
</html>
