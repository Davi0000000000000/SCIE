<?php
session_start();
require_once '../../config.php';
require_once BASE_PATH . '/factory/Database.php';
require_once '../../control/cabecalho.php';

$userType = $_SESSION['user_type'];

$cabecalho = new Cabecalho($userType);
$cabecalho->render();

try {
    $pdo = (new Database())->connect();

    $stmt = $pdo->query("SELECT id, usuario, tipo_usuario FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Erro ao buscar usuários: ' . $e->getMessage());
    $usuarios = [];
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/Geral.css">
    <script src="<?php echo BASE_URL; ?>/js/js.js" defer></script>
    <title>SCIE - Usuários</title>
</head>
<body>
    <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>

    <main>
        <h1>Usuários Cadastrados</h1>
        <table class="tabela-usuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['tipo_usuario']); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/control/editarUsuario.php?id=<?php echo htmlspecialchars($usuario['id']); ?>" class="btn-editar-usuario">
                                    <img src="<?php echo BASE_URL; ?>/view/imagens/editarIcone.png" alt="Editar Usuário">
                                </a>
                                <button class="btn-remover-usuario itemAcao" onclick="abrirPopupRemocaoUsuario(<?php echo htmlspecialchars($usuario['id']); ?>)">
                                    <img src="<?php echo BASE_URL; ?>/view/imagens/removerIcone.png" alt="Remover Usuário">
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>

    <div id="removalOverlay" class="overlay" onclick="fecharPopupRemocaoUsuario()"></div>
    <div id="removalPopup" class="popup">
        <h2>Confirmar Remoção</h2>
        <p>Tem certeza que deseja remover este usuário?</p>
        <div class="popup-options">
            <a id="confirmarRemocao" href="#">
                <button class="btn-confirmar">Sim</button>
            </a>
            <button class="btn-cancelar" onclick="fecharPopupRemocaoUsuario()">Não</button>
        </div>
    </div>
</body>
</html>
