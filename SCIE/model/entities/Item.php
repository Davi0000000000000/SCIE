<?php
class Item {
    protected $codigo_interno;
    protected $cliente;
    protected $comentarios;
    protected $quantidade_total;
    protected $responsavel;
    protected $descricao;
    protected $rev_desenho;

    public function __construct($codigo_interno, $cliente, $comentarios, $quantidade_total, $responsavel, $rev_desenho, $descricao) {
        $this->codigo_interno = $codigo_interno;
        $this->cliente = $cliente;
        $this->comentarios = $comentarios;
        $this->quantidade_total = $quantidade_total;
        $this->responsavel = $responsavel;
        $this->descricao = $descricao;
        $this->rev_desenho = $rev_desenho;
    }

    public function getCodigoInterno() {
        return $this->codigo_interno;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getComentarios() {
        return $this->comentarios;
    }

    public function getQuantidadeTotal() {
        return $this->quantidade_total;
    }

    public function getResponsavel() {
        return $this->responsavel;
    }

    public function getRevDesenho() {
        return $this->rev_desenho;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function save($db) {
        $query = "INSERT INTO itens (codigo_interno, cliente, comentarios, quantidade_total, responsavel, descricao, rev_desenho)
                  VALUES (:codigo_interno, :cliente, :comentarios, :quantidade_total, :responsavel, :descricao, :rev_desenho)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':codigo_interno', $this->codigo_interno);
        $stmt->bindParam(':cliente', $this->cliente);
        $stmt->bindParam(':comentarios', $this->comentarios);
        $stmt->bindParam(':quantidade_total', $this->quantidade_total);
        $stmt->bindParam(':responsavel', $this->responsavel);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':rev_desenho', $this->rev_desenho);

        return $stmt->execute();
    }

    public static function getAll($db) {
        $query = "SELECT * FROM itens";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    
}
?>
