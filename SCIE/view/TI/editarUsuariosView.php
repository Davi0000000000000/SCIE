<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../css/Geral.css">
</head>
<body>
    <div class="form-container">
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="mensagem <?php echo $_SESSION['tipoMensagem']; ?>">
                <?php echo $_SESSION['mensagem']; ?>
            </div>
            <?php unset($_SESSION['mensagem'], $_SESSION['tipoMensagem']); ?>
        <?php endif; ?>

        <button type="button" class="button-forms" onclick="window.history.back()">Voltar</button>
        <h1 class="titulo">Editar Usuário</h1>
        <form action="../control/UsuarioController.php" method="POST" class="form-padrao">
    <input type="hidden" name="acao" value="atualizar">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario->getId(), ENT_QUOTES, 'UTF-8'); ?>">

    <label for="usuario" class="form-label">Usuário:</label>
    <input type="text" id="usuario" name="usuario" 
           value="<?php echo htmlspecialchars($usuario->getUsuario(), ENT_QUOTES, 'UTF-8'); ?>" 
           required class="form-input"><br>

    <label for="senha" class="form-label">Senha:</label>
    <input type="password" id="senha" name="senha" 
           placeholder="Digite nova senha para alterar" 
           class="form-input"><br>

    <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
    <input type="password" id="confirmar_senha" name="confirmar_senha" 
           placeholder="Confirme a nova senha" 
           class="form-input"><br>

    <label for="tipo_usuario" class="form-label">Tipo de Usuário:</label>
    <select id="tipo_usuario" name="tipo_usuario" required class="form-select">
        <option value="Engenharia" <?php echo $usuario->getTipoUsuario() === 'Engenharia' ? 'selected' : ''; ?>>Engenharia</option>
        <option value="TI" <?php echo $usuario->getTipoUsuario() === 'TI' ? 'selected' : ''; ?>>TI</option>
    </select><br>

    <div class="form-actions">
        <button type="submit" class="button-forms">Salvar Alterações</button>
        <button type="button" class="button-forms" onclick="window.history.back()">Cancelar</button>
    </div>
</form>


    </div>
</body>
</html>
