<?php
class HistoricoEstoque {
    private $responsavel;
    private $codigoInterno;
    private $quantidade;
    private $operacao;
    private $tipoMovimentacao; 
    private $dataAcao;
    private $comentario;

    public function __construct($responsavel, $codigoInterno, $quantidade, $operacao, $tipoMovimentacao, $dataAcao, $comentario) {
        $this->responsavel = $responsavel;
        $this->codigoInterno = $codigoInterno;
        $this->quantidade = $quantidade;
        $this->operacao = $operacao;
        $this->tipoMovimentacao = $tipoMovimentacao;
        $this->dataAcao = $dataAcao;
        $this->comentario = $comentario;
    }

    public function registrarHistorico($db) {
        try {
            $query = "INSERT INTO historicoestoque (responsavel, codigoInterno, quantidade, operacao, tipo_movimentacao, data_acao, comentario)
                      VALUES (:responsavel, :codigoInterno, :quantidade, :operacao, :tipoMovimentacao, :dataAcao, :comentario)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':responsavel', $this->responsavel);
            $stmt->bindParam(':codigoInterno', $this->codigoInterno);
            $stmt->bindParam(':quantidade', $this->quantidade);
            $stmt->bindParam(':operacao', $this->operacao);
            $stmt->bindParam(':tipoMovimentacao', $this->tipoMovimentacao);
            $stmt->bindParam(':dataAcao', $this->dataAcao);
            $stmt->bindParam(':comentario', $this->comentario);

            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Erro ao registrar histórico: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erro ao registrar histórico: " . $e->getMessage());
            return false;
        }
    }
}
