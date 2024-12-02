<?php

class Usuario {
    private $id;
    private $usuario;
    private $senha;
    private $tipoUsuario;
    private $db;

    public function __construct($db, $usuario = null, $tipoUsuario = null, $id = null, $senha = null) {
        if (!$db) {
            throw new Exception("Conexão com o banco de dados não fornecida.");
        }
        $this->db = $db;
        $this->id = $id;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->tipoUsuario = $tipoUsuario;
    }

    public function getId() { return $this->id; }
    public function getUsuario() { return $this->usuario; }
    public function getTipoUsuario() { return $this->tipoUsuario; }
    public function setUsuario($usuario) {
        if (empty($usuario)) {
            throw new Exception("O nome de usuário não pode estar vazio.");
        }
        $this->usuario = $usuario;
    }
    public function setSenha($senha) {
        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres.");
        }
        $this->senha = password_hash($senha, PASSWORD_BCRYPT);
    }
    public function setTipoUsuario($tipoUsuario) {
        if (empty($tipoUsuario)) {
            throw new Exception("O tipo de usuário não pode estar vazio.");
        }
        $this->tipoUsuario = $tipoUsuario;
    }

    public function salvar() {
        $stmt = $this->db->prepare('INSERT INTO usuarios (usuario, senha, tipo_usuario) VALUES (?, ?, ?)');
        if (!$stmt->execute([$this->usuario, $this->senha, $this->tipoUsuario])) {
            throw new Exception("Erro ao salvar o usuário no banco de dados.");
        }
        $this->id = $this->db->lastInsertId();
        return $this->id;
    }

    public function atualizar() {
        if (!$this->id) {
            throw new Exception("Usuário não carregado para atualização.");
        }
        $query = "UPDATE usuarios SET usuario = ?, tipo_usuario = ?";
        $params = [$this->usuario, $this->tipoUsuario];

        if ($this->senha) {
            $query .= ", senha = ?";
            $params[] = $this->senha;
        }

        $query .= " WHERE id = ?";
        $params[] = $this->id;

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($params)) {
            throw new Exception("Erro ao atualizar o usuário.");
        }
        return true;
    }

    public function remover() {
        if (!$this->id) {
            throw new Exception("Usuário não carregado para remoção.");
        }
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        if (!$stmt->execute([$this->id])) {
            throw new Exception("Erro ao remover o usuário.");
        }
        return true;
    }

    public function verificarLogin($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['senha'])) {
            $this->id = $user['id'];
            $this->usuario = $user['usuario'];
            $this->tipoUsuario = $user['tipo_usuario'];
            return true;
        }
        return false;
    }

    public static function carregarPorId($id, $db) {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Usuario($db, $data['usuario'], $data['tipo_usuario'], $data['id'], $data['senha']);
        }
        return null;
    }

    public function finalizarSessao() {
        session_unset();
        session_destroy();
    }
}
