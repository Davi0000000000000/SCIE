<?php
session_start();

require_once realpath(__DIR__ . '/../../../config.php');

require_once BASE_PATH . '/control/cabecalho.php';
require_once BASE_PATH . '/factory/Database.php';
require_once BASE_PATH . '/model/entities/ItemEstoque.php';

$item = new ItemEstoque(null, null, null, null, null, null, null, null, null);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/Geral.css">
    <script src="<?php echo BASE_URL; ?>/js/js.js" defer></script>
    <title>SCIE</title>
</head>
<body>

    <?php
    $cabecalho = new Cabecalho($_SESSION['user_type'] ?? null);
    $cabecalho->render();
    ?>

    <button type="button" class="button-forms" onclick="window.location.href='estoqueInternoView.php'">Voltar</button>

    <div id="mensagem" class="mensagem">
        <?php
        if (isset($_SESSION['mensagem'])) {
            echo "<p class='{$_SESSION['tipoMensagem']}'>{$_SESSION['mensagem']}</p>";
            unset($_SESSION['mensagem'], $_SESSION['tipoMensagem']);
        }
        ?>
    </div>

    <div id="addItem" class="form-container">
        <h1 class="titulo">Adicionar Item ao Estoque</h1>
        <form action="<?php echo BASE_URL; ?>/model/itemEstoque/adicionarItemEstoque.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="adicionar">

            <label for="codigoInterno" class="form-label">Código Interno:</label>
            <input type="text" id="codigoInterno" name="codigoInterno" class="form-input" required>

            <label for="cliente" class="form-label">Cliente:</label>
            <input type="text" id="cliente" name="cliente" class="form-input" required>

            <label for="revDesenho" class="form-label">RevDesenho:</label>
            <input type="text" id="revDesenho" name="revDesenho" class="form-input" required>

            <label for="descricao" class="form-label">Descrição:</label>
            <select id="descricao" name="descricao" class="form-select" required>
                <option value="" disabled selected>Escolha uma descrição</option>
                <option value="laser">Laser</option>
                <option value="soldaPonto">Solda Ponto</option>
                <option value="soldaMig">Solda MIG</option>
                <option value="soldaSuspensa">Solda Suspensa</option>
                <option value="estamparia">Estamparia</option>
                <option value="inspecaoFinal">Inspeção final</option>
                <option value="pintura">Pintura</option>
                <option value="zincagem">Zincagem</option>
                <option value="oleamento">Oleamento</option>
                <option value="rebitagem">Rebitagem</option>
                <option value="usinagem">Usinagem</option>
                <option value="carepa">Retirar carepa</option>
            </select>

            <label for="quantidade" class="form-label">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" class="form-input" min="1" required>

            <label for="dataEntrada" class="form-label">Data de Entrada:</label>
            <input type="date" id="dataEntrada" name="dataEntrada" class="form-input" required>

            <label for="responsavel" class="form-label">Responsável:</label>
            <input type="text" id="responsavel" name="responsavel" class="form-input" required>

            <label for="localizacao" class="form-label">Localização:</label>
            <input type="text" id="localizacao" name="localizacao" class="form-input">

            <label for="comentario" class="form-label">Comentário:</label>
            <textarea id="comentario" name="comentario" class="form-textarea" rows="4"></textarea>

            <label for="imagem" class="form-label">Imagem (PNG, PDF, TIF, DWG):</label>
            <label class="upload-button" for="imagem">Selecionar Arquivo</label>
            <input type="file" id="imagem" name="imagem" accept=".png, .pdf, .tif, .dwg" class="inserirImagem">
            <span id="file-name" class="file-name">Nenhum arquivo selecionado</span>

            <input type="submit" value="Salvar" class="button-forms">
            <button type="button" class="button-forms" onclick="window.location.href='estoqueInternoView.php'">Cancelar</button>
        </form>
    </div>
</body>
</html>
