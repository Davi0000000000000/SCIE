<?php
require_once '../../../config.php';
require_once BASE_PATH . '/control/HistoricoEstoqueController.php';
require_once BASE_PATH . '/control/cabecalho.php';
session_start();

$controller = new HistoricoEstoqueController();
$historico = $controller->listarHistorico();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/Geral.css">
    <title>Histórico de Movimentação</title>
</head>
<?php
    $cabecalho = new Cabecalho($_SESSION['user_type'] ?? null);
    $cabecalho->render();
?>
<body>
    <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>

    <div id="historico">
        <h1 class="titulo">Histórico de Movimentação</h1>

        <?php if (isset($historico['error'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($historico['error']); ?></p>
        <?php elseif (!empty($historico)): ?>
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Código do Item</th>
                        <th>Quantidade</th>
                        <th>Operação</th>
                        <th>Tipo</th>
                        <th>Responsável</th>
                        <th>Comentário</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $registro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($registro['data_acao']))); ?></td>
                            <td><?php echo htmlspecialchars($registro['codigoInterno']); ?></td>
                            <td><?php echo htmlspecialchars($registro['quantidade']); ?></td>
                            <td><?php echo htmlspecialchars($registro['operacao']); ?></td>
                            <td><?php echo htmlspecialchars($registro['tipo_movimentacao']); ?></td>
                            <td><?php echo htmlspecialchars($registro['responsavel']); ?></td>
                            <td><?php echo htmlspecialchars($registro['comentario'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum histórico encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
