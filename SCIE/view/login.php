<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Geral.css">
    <title>SCIE - Login</title>
</head>
<body>
<?php
    include_once "../control/cabecalho.php";
?>

<div id="login">
    <fieldset id="cxlogin">
        <img src="../view/imagens/ScieLogo.png" alt="Logo do SCIE" id="logoLogin">

        <h1 class="titulo">Login</h1>

        <?php
        session_start();
        if (isset($_SESSION['mensagem_erro'])) {
            echo "<div class='error-message'>" . htmlspecialchars($_SESSION['mensagem_erro']) . "</div>";
            unset($_SESSION['mensagem_erro']);
        }
        if (isset($_SESSION['mensagem_sucesso'])) {
            echo "<div class='success-message'>" . htmlspecialchars($_SESSION['mensagem_sucesso']) . "</div>";
            unset($_SESSION['mensagem_sucesso']);
        }
        ?>

        <form action="../model/usuario/consultarlogin.php" method="POST" onsubmit="return validarFormulario()">
            <label for="cxusuario">Usuário:</label><br>
            <input type="text" id="cxusuario" name="cxusuario" required placeholder="Digite seu usuário"/><br/><br/>

            <label for="cxsenha">Senha:</label><br/>
            <input type="password" id="cxsenha" name="cxsenha" required placeholder="Digite sua senha"/><br/><br/>

            <input type="submit" value="Entrar" id="botaoLogin">
        </form>
    </fieldset>
</div>

<script>
    function validarFormulario() {
        const usuario = document.getElementById('cxusuario').value.trim();
        const senha = document.getElementById('cxsenha').value.trim();

        if (!usuario || !senha) {
            alert("Todos os campos são obrigatórios.");
            return false;
        }
        return true;
    }
</script>

</body>
</html>
