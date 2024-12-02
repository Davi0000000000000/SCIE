<?php
session_start();
require_once __DIR__ . '/../factory/Database.php';
require_once __DIR__ . '/../model/entities/Usuario.php';

class UsuarioController {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = (new Database())->connect();
        } catch (Exception $e) {
            $this->redirecionar("../view/TI/cadastrarUsuariosView.php", "Erro ao conectar ao banco de dados: " . $e->getMessage(), "error");
        }
    }

    public function cadastrarUsuario($dados) {
        try {
            $usuario = trim($dados['usuario'] ?? '');
            $senha = trim($dados['senha'] ?? '');
            $confirmarSenha = trim($dados['confirmar_senha'] ?? '');
            $tipoUsuario = trim($dados['tipo_usuario'] ?? '');

            $this->validarCamposObrigatorios($usuario, $tipoUsuario, $senha, $confirmarSenha);

            $novoUsuario = new Usuario($this->pdo);
            $novoUsuario->setUsuario($usuario);
            $novoUsuario->setSenha($senha);
            $novoUsuario->setTipoUsuario($tipoUsuario);

            if ($novoUsuario->salvar()) {
                $this->redirecionar("../view/TI/listarUsuariosView.php", "Usuário cadastrado com sucesso.", "success");
            } else {
                throw new Exception("Erro ao salvar o usuário.");
            }
        } catch (Exception $e) {
            $this->redirecionar("../view/TI/cadastrarUsuariosView.php", $e->getMessage(), "error");
        }
    }

    public function atualizarUsuario($dados) {
        try {
            $id = (int)($dados['id'] ?? 0);
            $usuario = trim($dados['usuario'] ?? '');
            $senha = trim($dados['senha'] ?? '');
            $confirmarSenha = trim($dados['confirmar_senha'] ?? '');
            $tipoUsuario = trim($dados['tipo_usuario'] ?? '');

            $this->validarId($id);
            $this->validarCamposObrigatorios($usuario, $tipoUsuario, $senha, $confirmarSenha);

            $usuarioExistente = Usuario::carregarPorId($id, $this->pdo);
            $usuarioExistente->setUsuario($usuario);
            $usuarioExistente->setTipoUsuario($tipoUsuario);

            if ($senha) {
                $usuarioExistente->setSenha($senha);
            }

            if ($usuarioExistente->atualizar()) {
                $this->redirecionar("../view/TI/listarUsuariosView.php", "Usuário atualizado com sucesso.", "success");
            } else {
                throw new Exception("Erro ao atualizar o usuário.");
            }
        } catch (Exception $e) {
            $this->redirecionar("../view/TI/editarUsuariosView.php?id=$id", $e->getMessage(), "error");
        }
    }

    public function editarUsuario($id) {
        try {
            $this->validarId($id);
    
            $usuario = Usuario::carregarPorId((int)$id, $this->pdo);
            if (!$usuario) {
                throw new Exception("Usuário não encontrado.");
            }
    
            include_once __DIR__ . '/../view/TI/editarUsuariosView.php';
        } catch (Exception $e) {
            $this->redirecionar("../view/TI/listarUsuariosView.php", $e->getMessage(), "error");
        }
    }
    

    public function removerUsuario($id) {
        try {
            $this->validarId($id);

            $usuario = Usuario::carregarPorId($id, $this->pdo);
            if (!$usuario) {
                throw new Exception("Usuário não encontrado.");
            }

            if ($usuario->remover()) {
                $this->redirecionar("../view/TI/listarUsuariosView.php", "Usuário removido com sucesso.", "success");
            } else {
                throw new Exception("Erro ao remover o usuário.");
            }
        } catch (Exception $e) {
            $this->redirecionar("../view/TI/listarUsuariosView.php", $e->getMessage(), "error");
        }
    }

    private function validarCamposObrigatorios($usuario, $tipoUsuario, $senha = null, $confirmarSenha = null) {
        if (empty($usuario) || empty($tipoUsuario)) {
            throw new Exception("Por favor, preencha todos os campos obrigatórios.");
        }
        if ($senha !== null && $senha !== $confirmarSenha) {
            throw new Exception("As senhas não correspondem.");
        }
    }

    private function validarId($id) {
        if ($id <= 0 || !is_numeric($id)) {
            throw new Exception("ID inválido.");
        }
    }

    private function redirecionar($url, $mensagem = null, $tipoMensagem = null) {
        if ($mensagem) {
            $_SESSION['mensagem'] = $mensagem;
            $_SESSION['tipoMensagem'] = $tipoMensagem;
        }
        header("Location: $url");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $controller = new UsuarioController();

    switch ($acao) {
        case 'cadastrar':
            $controller->cadastrarUsuario($_POST);
            break;
        case 'atualizar':
            $controller->atualizarUsuario($_POST);
            break;
        case 'remover':
            $id = (int)($_POST['id'] ?? 0);
            $controller->removerUsuario($id);
            break;
        default:
            $_SESSION['mensagem'] = "Ação inválida.";
            $_SESSION['tipoMensagem'] = "error";
            header("Location: ../view/TI/listarUsuariosView.php");
            exit();
    }
}
