<?php
require_once '../../factory/Database.php';
require_once '../../model/entities/Usuario.php';

session_start();

try {
    $pdo = (new Database())->connect();

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        throw new Exception("ID inválido ou não fornecido.");
    }

    $usuario = Usuario::carregarPorId($id, $pdo);

    if ($usuario->remover()) {
        $_SESSION['mensagem_sucesso'] = "Usuário removido com sucesso.";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao remover o usuário.";
    }
} catch (Exception $e) {
    $_SESSION['mensagem_erro'] = "Erro: " . $e->getMessage();
}

header('Location: ../../view/TI/listarUsuariosView.php');
exit();
