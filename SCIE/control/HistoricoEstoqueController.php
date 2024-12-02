<?php
require_once '../../../config.php'; 
require_once BASE_PATH . '/factory/Database.php';

class HistoricoEstoqueController {
    public function listarHistorico() {
        try {
            $db = (new Database())->connect();

            $query = "SELECT responsavel, codigoInterno, quantidade, operacao, tipo_movimentacao, data_acao, comentario 
                      FROM historicoestoque 
                      ORDER BY data_acao DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => "Erro ao buscar histórico: " . $e->getMessage()];
        }
    }
}
