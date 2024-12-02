<?php
require_once __DIR__ . '/../factory/Database.php';
require_once __DIR__ . '/../model/entities/ItemEstoque.php';
require_once __DIR__ . '/../model/entities/HistoricoEstoque.php';

class ItemEstoqueController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function adicionarItem($dados, $arquivo) {
        try {
            $codigoInterno = $dados['codigoInterno'] ?? null;
            $cliente = $dados['cliente'] ?? null;
            $revDesenho = $dados['revDesenho'] ?? null;
            $descricao = $dados['descricao'] ?? null;
            $quantidade = $dados['quantidade'] ?? 0;
            $dataEntrada = $dados['dataEntrada'] ?? null;
            $responsavel = $dados['responsavel'] ?? null;
            $comentario = $dados['comentario'] ?? '';
            $localizacao = $dados['localizacao'] ?? null;
            $imagemPath = null;

            if (!$codigoInterno || !$cliente || !$descricao || !$dataEntrada) {
                throw new Exception('Campos obrigatórios estão ausentes.');
            }

            if (isset($arquivo['imagem']) && $arquivo['imagem']['error'] === UPLOAD_ERR_OK) {
                $pasta = __DIR__ . '/../view/imagensItens/';
                $nomeArquivo = uniqid() . '-' . basename($arquivo['imagem']['name']);
                $caminhoArquivo = $pasta . $nomeArquivo;

                if (!is_dir($pasta)) {
                    if (!mkdir($pasta, 0777, true)) {
                        throw new Exception('Erro ao criar o diretório para upload de imagens.');
                    }
                }

                if (!move_uploaded_file($arquivo['imagem']['tmp_name'], $caminhoArquivo)) {
                    throw new Exception('Erro ao mover o arquivo para o diretório.');
                }

                $imagemPath = 'view/imagensItens/' . $nomeArquivo;
            }

        
            $item = new ItemEstoque(
                $this->db,
                $codigoInterno,
                $cliente,
                $comentario,
                $quantidade,
                $responsavel,
                $dataEntrada,
                $revDesenho,
                $descricao,
                $localizacao,
                'Em estoque',
                $imagemPath 
            );

            if (!$item->save($this->db)) {
                throw new Exception('Erro ao salvar o item no banco de dados.');
            }

            $historico = new HistoricoEstoque(
                $responsavel,
                $codigoInterno,
                $quantidade,
                'Novo', 
                'Entrada', 
                $dataEntrada,
                $comentario
            );

            if (!$historico->registrarHistorico($this->db)) {
                throw new Exception('Erro ao registrar o histórico de movimentação.');
            }

            $_SESSION['mensagem'] = "Item adicionado com sucesso!";
            $_SESSION['tipoMensagem'] = "success";
        } catch (Exception $e) {
            error_log("Erro ao adicionar item: " . $e->getMessage());
            $_SESSION['mensagem'] = "Erro ao adicionar item: " . $e->getMessage();
            $_SESSION['tipoMensagem'] = "error";
        }
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
            return ['error' => "Erro ao buscar item: " . $e->getMessage()];
        }
    }
    

    public function listarItens() {
        try {
            $stmt = $this->db->query("SELECT * FROM itens");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar itens: " . $e->getMessage());
            return [];
        }
    }

    public function listarItensSemAcao() {
        try {
            return ItemEstoque::getAll($this->db);
        } catch (PDOException $e) {
            return ['error' => "Erro ao buscar itens: " . $e->getMessage()];
        }
    }

    public function editarItem($id) {
        try {
            $itemModel = new ItemEstoque($this->db);
            $itemData = $itemModel->buscarPorId($id);

            if (!$itemData) {
                throw new Exception("Item não encontrado.");
            }

            return $itemData;
        } catch (Exception $e) {
            error_log("Erro ao buscar item: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function removerItem($id) {
        try {
            $itemModel = new ItemEstoque($this->db);

            $itemData = $itemModel->buscarPorId($id);

            if (!$itemData) {
                return ['error' => "Item não encontrado."];
            }

            if ($itemModel->removerPorId($id)) {
                return ['success' => "Item removido com sucesso!"];
            } else {
                return ['error' => "Erro ao remover o item do banco de dados."];
            }
        } catch (Exception $e) {
            return ['error' => "Erro ao remover item: " . $e->getMessage()];
        }
    }

    public function obterItemPorId($id) {
        try {
            $itemModel = new ItemEstoque($this->db);
            return $itemModel->buscarPorId($id);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function passarParaProximaFase($dados, $arquivo) {
        try {
            $this->db->beginTransaction();
    
            $codigoInternoOriginal = $dados['codigoInternoOriginal'];
            $novoCodigoInterno = $dados['codigoInterno'];
            $quantidade = $dados['quantidade'];
            $faseNova = $dados['faseNova'];
            $dataEntrada = $dados['dataEntrada'];
            $responsavel = $dados['responsavel'];
            $comentario = $dados['comentario'] ?? '';
            $localizacao = $dados['localizacao'] ?? null;
            $imagemPath = null;
    
            $itemOriginal = $this->buscarPorCodigoInterno($codigoInternoOriginal);
            if (!$itemOriginal) {
                throw new Exception("Item original não encontrado.");
            }
    
            $queryUpdate = "UPDATE itens SET quantidade_total = quantidade_total - :quantidade WHERE codigoInterno = :codigoInterno";
            $stmtUpdate = $this->db->prepare($queryUpdate);
            $stmtUpdate->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':codigoInterno', $codigoInternoOriginal, PDO::PARAM_STR);
            $stmtUpdate->execute();
    
            if (isset($arquivo['imagem']) && $arquivo['imagem']['error'] === UPLOAD_ERR_OK) {
                $pasta = __DIR__ . '/../view/imagensItens/';
                $nomeArquivo = uniqid() . '-' . basename($arquivo['imagem']['name']);
                $caminhoArquivo = $pasta . $nomeArquivo;
    
                if (!is_dir($pasta)) {
                    if (!mkdir($pasta, 0777, true)) {
                        throw new Exception('Erro ao criar o diretório para upload de imagens.');
                    }
                }
    
                if (!move_uploaded_file($arquivo['imagem']['tmp_name'], $caminhoArquivo)) {
                    throw new Exception('Erro ao mover o arquivo para o diretório.');
                }
    
                $imagemPath = 'view/imagensItens/' . $nomeArquivo;
            }
    
            $queryInsert = "INSERT INTO itens (codigoInterno, descricao, quantidade_total, cliente, revDesenho, responsavel, data_entrada, localizacao, comentarios, imagem, status) 
                            VALUES (:codigoInterno, :descricao, :quantidade, :cliente, :revDesenho, :responsavel, :dataEntrada, :localizacao, :comentarios, :imagem, :status)";
            $stmtInsert = $this->db->prepare($queryInsert);
            $statusNovoItem = 'Em processo';
            $stmtInsert->execute([
                ':codigoInterno' => $novoCodigoInterno,
                ':descricao' => $faseNova,
                ':quantidade' => $quantidade,
                ':cliente' => $itemOriginal['cliente'],
                ':revDesenho' => $itemOriginal['revDesenho'],
                ':responsavel' => $responsavel,
                ':dataEntrada' => $dataEntrada,
                ':localizacao' => $localizacao,
                ':comentarios' => $comentario,
                ':imagem' => $imagemPath,
                ':status' => $statusNovoItem,
            ]);
    
            $historico = new HistoricoEstoque(
                $responsavel,
                $codigoInternoOriginal,
                $quantidade,
                $faseNova,
                "Saída", 
                $dataEntrada,
                $comentario
            );
            
            if (!$historico->registrarHistorico($this->db)) {
                throw new Exception("Erro ao registrar o histórico.");
            }


        
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erro ao passar para a próxima fase: " . $e->getMessage());
            return false;
        }
    }
    


    public function liberarParaProducao($dados) {
        try {
            $this->db->beginTransaction();
    
            if (!isset($dados['id']) || !isset($dados['quantidade']) || empty($dados['quantidade'])) {
                throw new Exception("ID ou quantidade do item não fornecido.");
            }
    
            $id = filter_var($dados['id'], FILTER_SANITIZE_NUMBER_INT);
            $responsavel = filter_var($dados['responsavel'] ?? '', FILTER_SANITIZE_STRING);
            $dataEntrada = filter_var($dados['data_entrada'] ?? '', FILTER_SANITIZE_STRING);
            $quantidade = filter_var($dados['quantidade'], FILTER_VALIDATE_INT);
            $comentario = filter_var($dados['comentario'] ?? '', FILTER_SANITIZE_STRING);
    
            if (!$id || !$responsavel || !$dataEntrada || !$quantidade || $quantidade <= 0) {
                throw new Exception("Dados inválidos ou incompletos fornecidos.");
            }
    
            $itemModel = new ItemEstoque($this->db);
            $itemData = $itemModel->buscarPorId($id);
    
            if (!$itemData) {
                throw new Exception("Item não encontrado.");
            }
    
            $quantidadeAtual = (int)$itemData['quantidade_total'];
            if ($quantidade > $quantidadeAtual) {
                throw new Exception("Quantidade solicitada excede o estoque disponível.");
            }
    
            $historico = new HistoricoEstoque(
                $responsavel,
                $itemData['codigoInterno'],
                $quantidade,
                $itemData['descricao'],       
                'Produção',                  
                $dataEntrada,
                'Saída para produção',        
                $comentario                  
            );
    
            if (!$historico->registrarHistorico($this->db)) {
                throw new Exception("Erro ao registrar histórico.");
            }
    
            if ($quantidade === $quantidadeAtual) {
                $itemModel->removerPorId($id);
            } else {
                $novaQuantidade = $quantidadeAtual - $quantidade;
                $itemModel->atualizarQuantidade($id, $novaQuantidade);
            }
    
            $this->db->commit();
            return ['success' => "Item liberado para produção com sucesso!"];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['error' => $e->getMessage()];
        }
    }
    
    

    public function acrescentarItem($dados) {
        try {
            if (!isset($dados['id']) || !isset($dados['quantidade']) || empty($dados['quantidade']) || !isset($dados['responsavel']) || empty($dados['responsavel'])) {
                throw new Exception("Dados obrigatórios não foram fornecidos.");
            }

            $id = $dados['id'];
            $quantidadeAcrescentada = (int)$dados['quantidade'];
            $dataEntrada = $dados['dataEntrada'];
            $responsavel = $dados['responsavel'];
            $comentario = $dados['comentario'] ?? '';

            $itemModel = new ItemEstoque($this->db);
            $itemData = $itemModel->buscarPorId($id);

            if (!$itemData) {
                throw new Exception("Item não encontrado.");
            }

            $novaQuantidade = (int)$itemData['quantidade_total'] + $quantidadeAcrescentada;
            if (!$itemModel->atualizarQuantidade($id, $novaQuantidade)) {
                throw new Exception("Erro ao atualizar a quantidade do item.");
            }

            $historico = new HistoricoEstoque(
                $responsavel,
                $itemData['codigoInterno'],
                $quantidadeAcrescentada,
                'Adição', 
                'Entrada', 
                $dataEntrada,
                $comentario
            );

            if (!$historico->registrarHistorico($this->db)) {
                throw new Exception("Erro ao registrar histórico de estoque.");
            }

            $_SESSION['mensagem'] = "Quantidade acrescentada com sucesso e registrada no histórico.";
            $_SESSION['tipoMensagem'] = "success";

        } catch (Exception $e) {
            $_SESSION['mensagem'] = "Erro ao acrescentar item: " . $e->getMessage();
            $_SESSION['tipoMensagem'] = "error";
        }

        header("Location: ../view/Engenharia/itemEstoque/estoqueInternoView.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $controller = new ItemEstoqueController();

    if ($_GET['action'] === 'acrescentarItem') {
        $controller->acrescentarItem($_POST);
    }
}
?>
