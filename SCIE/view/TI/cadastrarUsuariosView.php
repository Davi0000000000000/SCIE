<?php 
session_start();
require_once '../../config.php';
require_once BASE_PATH . '/factory/Database.php';
require_once '../../control/cabecalho.php';

if (!isset($_SESSION['user_type'])) {
    $_SESSION['mensagem_erro'] = "Acesso negado. Por favor, faça login.";
    header('Location: ../../view/login.php');
    exit();
}

$userType = $_SESSION['user_type'];

$cabecalho = new Cabecalho($userType);
$cabecalho->render();

$mensagemErro = $_SESSION['mensagem_erro'] ?? '';
unset($_SESSION['mensagem_erro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="../../css/Geral.css">
</head>
<body>
    <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>
    <div class="form-container">
        <h1 class="titulo">Cadastrar Novo Usuário</h1>

        <?php if (!empty($mensagemErro)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($mensagemErro); ?>
            </div>
        <?php endif; ?>

        <form action="../../control/UsuarioController.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar">
            <label for="usuario" class="form-label">Nome de Usuário:</label>
            <input type="text" id="usuario" name="usuario" class="form-input" required><br>

            <label for="senha" class="form-label">Senha:</label>
            <input type="password" id="senha" name="senha" class="form-input" required><br>

            <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-input" required><br>

            <label for="tipo_usuario" class="form-label">Tipo de Usuário:</label>
            <select id="tipo_usuario" name="tipo_usuario" class="form-select" required>
                <option value="" disabled selected>Selecione</option>
                <option value="Engenharia">Engenharia</option>
                <option value="TI">TI</option>
            </select><br>

            <div class="form-actions">
                <button type="submit" class="button-forms">Salvar</button>
                <button type="button" class="button-forms" onclick="window.history.back()">Cancelar</button>
            </div>
        </form>
    </div>
</body>
</html>
