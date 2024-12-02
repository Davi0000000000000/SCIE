<?php
require_once '../../../control/ItemEstoqueController.php';
require_once '../../../config.php'; 
require_once BASE_PATH . '/control/cabecalho.php';
session_start();


$controller = new ItemEstoqueController();
$itensData = $controller->listarItensSemAcao();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/Geral.css">
    <title>SCIE</title>
</head>
<?php
    $cabecalho = new Cabecalho($_SESSION['user_type'] ?? null);
    $cabecalho->render();
    ?>
<body>
    <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>

    <div id="listarItensSemAcao">
        <h1 class="titulo">Lista de Itens (Estoque)</h1>

        <?php if (isset($itensData['error'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($itensData['error']); ?></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Código Interno</th>
                        <th>Imagem</th>
                        <th>Cliente</th>
                        <th>RevDesenho</th>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Localização</th>
                        <th>Responsável</th>
                        <th>Status</th>
                        <th>Comentários</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($itensData)): ?>
                        <?php foreach ($itensData as $itemData): ?>
                            <tr class="item-principal">
                                <td><?php echo htmlspecialchars($itemData['codigoInterno']); ?></td>
                                <td>
                                    <?php if (!empty($itemData['imagem'])): ?>
                                        <a href="<?php echo BASE_URL . '/' . htmlspecialchars($itemData['imagem']); ?>" target="_blank">
                                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($itemData['imagem']); ?>" alt="Imagem do item" style="max-width: 100px; max-height: 100px;">
                                        </a>
                                    <?php else: ?>
                                        Sem imagem
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($itemData['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($itemData['revDesenho']); ?></td>
                                <td><?php echo htmlspecialchars($itemData['descricao']); ?></td>
                                <td><?php echo htmlspecialchars($itemData['quantidade_total']); ?></td>
                                <td><?php echo htmlspecialchars($itemData['localizacao']); ?></td>
                                <td><?php echo htmlspecialchars($itemData['responsavel']); ?></td>
                                <td><?php echo htmlspecialchars($itemData['status']); ?></td>
                                <td><?php echo htmlspecialchars($itemData['comentarios']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">Nenhum item encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
