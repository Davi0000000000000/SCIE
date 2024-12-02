<?php
require_once 'Item.php';

class ItemEstoque extends Item {
    protected $data_entrada;
    protected $localizacao;
    protected $status;
    protected $imagem;
    private $db;

    public function __construct(
        $db,
        $codigo_interno = null,
        $cliente = null,
        $comentarios = null,
        $quantidade_total = 0,
        $responsavel = null,
        $data_entrada = null,
        $rev_desenho = null,
        $descricao = null,
        $localizacao = null,
        $status = null,
        $imagem = null
    ) {
        parent::__construct($codigo_interno, $cliente, $comentarios, $quantidade_total, $responsavel, $rev_desenho, $descricao);
        $this->db = $db;
        $this->data_entrada = $data_entrada;
        $this->localizacao = $localizacao;
        $this->status = $status;
        $this->imagem = $imagem;
    }

    public function getDataEntrada() {
        return $this->data_entrada;
    }

    public function setDataEntrada($data_entrada) {
        $this->data_entrada = $data_entrada;
    }

    public function getLocalizacao() {
        return $this->localizacao;
    }

    public function setLocalizacao($localizacao) {
        $this->localizacao = $localizacao;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getImagem() {
        return $this->imagem;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }

    public function save($db) {
        try {
            $query = "INSERT INTO itens (codigoInterno, cliente, comentarios, quantidade_total, responsavel, descricao, revDesenho, data_entrada, localizacao, status, imagem)
                      VALUES (:codigoInterno, :cliente, :comentarios, :quantidade_total, :responsavel, :descricao, :revDesenho, :data_entrada, :localizacao, :status, :imagem)";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':codigoInterno', $this->codigo_interno);
            $stmt->bindParam(':cliente', $this->cliente);
            $stmt->bindParam(':comentarios', $this->comentarios);
            $stmt->bindParam(':quantidade_total', $this->quantidade_total);
            $stmt->bindParam(':responsavel', $this->responsavel);
            $stmt->bindParam(':descricao', $this->descricao);
            $stmt->bindParam(':revDesenho', $this->rev_desenho);
            $stmt->bindParam(':data_entrada', $this->data_entrada);
            $stmt->bindParam(':localizacao', $this->localizacao);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':imagem', $this->imagem);

            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Erro ao salvar item: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erro ao salvar item: " . $e->getMessage());
            return false;
        }
    }


    public static function getAll($db) {
        $query = "SELECT * FROM itens";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        try {
            $query = "SELECT * FROM itens WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar item pelo ID: " . $e->getMessage());
            return null;
        }
    }

    public function atualizarQuantidade($id, $novaQuantidade) {
        try {
            $query = "UPDATE itens SET quantidade_total = :quantidade_total WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':quantidade_total', $novaQuantidade, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar a quantidade: " . $e->getMessage());
            return false;
        }
    }

    public function alterarStatus($id, $novoStatus, $dataEntrada) {
        try {
            $query = "SELECT * FROM itens WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $itemOriginal = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$itemOriginal) {
                throw new Exception("Item não encontrado.");
            }
    
            $queryUpdate = "UPDATE itens SET status = :status, data_entrada = :dataEntrada WHERE id = :id";
            $stmtUpdate = $this->db->prepare($queryUpdate);
            $stmtUpdate->bindParam(':status', $novoStatus);
            $stmtUpdate->bindParam(':dataEntrada', $dataEntrada);
            $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdate->execute();
    
            return $itemOriginal; 
        } catch (PDOException $e) {
            error_log("Erro ao alterar status do item $id: " . $e->getMessage());
            return false;
        }
    }
    
    

    public function removerPorId($id) {
        try {
            $query = "DELETE FROM itens WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao remover item: " . $e->getMessage());
            return false;
        }
    }

    public function editarItem($id, $imagem = null) {
        try {
            if ($imagem) {
                $query = "UPDATE itens 
                          SET codigoInterno = :codigoInterno, cliente = :cliente, revDesenho = :revDesenho, descricao = :descricao, 
                              quantidade_total = :quantidade_total, comentarios = :comentarios, localizacao = :localizacao, 
                              responsavel = :responsavel, imagem = :imagem
                          WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':imagem', $imagem);
            } else {
                $query = "UPDATE itens 
                          SET codigoInterno = :codigoInterno, cliente = :cliente, revDesenho = :revDesenho, descricao = :descricao, 
                              quantidade_total = :quantidade_total, comentarios = :comentarios, localizacao = :localizacao, 
                              responsavel = :responsavel
                          WHERE id = :id";
                $stmt = $this->db->prepare($query);
            }
    
            $stmt->bindParam(':codigoInterno', $this->codigo_interno);
            $stmt->bindParam(':cliente', $this->cliente);
            $stmt->bindParam(':revDesenho', $this->rev_desenho);
            $stmt->bindParam(':descricao', $this->descricao);
            $stmt->bindParam(':quantidade_total', $this->quantidade_total);
            $stmt->bindParam(':comentarios', $this->comentarios);
            $stmt->bindParam(':localizacao', $this->localizacao);
            $stmt->bindParam(':responsavel', $this->responsavel);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao editar item: " . $e->getMessage());
            return false;
        }
    }
    



}
?>
