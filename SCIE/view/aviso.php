<?php
session_start();
require_once __DIR__ . '/../config.php';

if (isset($_SESSION['user_type'])) {
    $homeController = BASE_URL . "/control/HomeController.php";
} else {
    $_SESSION['mensagem_erro'] = "Acesso negado. Por favor, faça login.";
    header('Location: ' . BASE_URL . '/view/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVISO</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/aviso.css">
</head>
<body>
    <div class="alert-box">
        <img src="<?php echo BASE_URL; ?>/view/imagens/avisoIcone.png" alt="Aviso">
        
        <h1>ATENÇÃO!!</h1>
        <p>PÁGINA EM DESENVOLVIMENTO! VOLTE DEPOIS!</p>
        <p>
            <a href="<?php echo $homeController; ?>">CLIQUE AQUI PARA RETORNAR À PÁGINA INICIAL</a>
        </p>
    </div>
</body>
</html>
