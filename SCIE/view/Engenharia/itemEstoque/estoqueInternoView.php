<?php
session_start();


require_once __DIR__ . '/../../../control/ItemEstoqueController.php';
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../control/cabecalho.php';

$controller = new ItemEstoqueController();
$itens = $controller->listarItens();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/Geral.css">
    <script src="<?php echo BASE_URL; ?>/js/js.js" defer></script>
    <title>SCIE - Estoque Interno</title>
</head>
<body>
    <?php
    $cabecalho = new Cabecalho($_SESSION['user_type'] ?? null);
    $cabecalho->render();
    ?>

    <div class="grid-container-estoque">
        <a href="<?php echo BASE_URL; ?>/view/Engenharia/itemEstoque/adicionarItemEstoqueView.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/adicionarItemIcone.png" alt="Adicionar Item">
            <span>Adicionar Item ao Estoque</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/view/Engenharia/itemEstoque/listarItensEstoqueView.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/listarItensIcone.png" alt="Listar Itens">
            <span>Listar Itens de Estoque</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/view/Engenharia/itemEstoque/historicoEstoqueView.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/historicoIcone.png" alt="Histórico de Movimentação">
            <span>Histórico de Movimentação</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/control/HomeController.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/itemEntregaIcone.png" alt="Estoque Engenharia">
            <span>Itens de Desenvolvimento</span>
        </a>
        <a href="<?php echo BASE_URL; ?>/view/aviso.php" class="grid-item">
            <img src="<?php echo BASE_URL; ?>/view/imagens/beneficiamentoIcone.png" alt="Item em Beneficiamento">
            <span>Itens em Beneficiamento</span>
        </a>
    </div>

    <div class="form-container-estoque">
        <h1 class="titulo">Lista de Itens (Estoque)</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Código Interno</th>
                    <th>Imagem</th>
                    <th>Cliente</th>
                    <th>RevDesenho</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Localização</th>
                    <th>Responsável</th>
                    <th>Comentário</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($itens) > 0): ?>
                    <?php foreach ($itens as $itemData): ?>
                        <tr class="item-principal">
                            <td><?php echo htmlspecialchars($itemData['codigoInterno']); ?></td>
                            <td>
                                <?php if (!empty($itemData['imagem'])): ?>
                                    <a href="<?php echo BASE_URL . '/' . htmlspecialchars($itemData['imagem']); ?>" target="_blank">
                                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($itemData['imagem']); ?>" alt="Imagem do item">
                                    </a>
                                <?php else: ?>
                                    Sem imagem
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($itemData['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($itemData['revDesenho']); ?></td>
                            <td><?php echo htmlspecialchars($itemData['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($itemData['quantidade_total']); ?></td>
                            <td><?php echo htmlspecialchars($itemData['localizacao']); ?></td>
                            <td><?php echo htmlspecialchars($itemData['responsavel']); ?></td>
                            <td><?php echo htmlspecialchars($itemData['comentarios']); ?></td>
                            <td>
                            <button type="button" class="status-button <?php echo $itemData['status'] === 'Em estoque' ? 'status-em-estoque' : 'status-em-transito'; ?>" 
                                    data-status="<?php echo htmlspecialchars($itemData['status']); ?>"  
                                    onclick="abrirDataPopup(<?php echo $itemData['id']; ?>, this)">
                                <?php echo htmlspecialchars($itemData['status']); ?>
                            </button>
                        </td>
                        <td class="acoes">                                
                            
    <a href="<?php echo BASE_URL; ?>/view/Engenharia/itemEstoque/editarItemEstoqueView.php?id=<?php echo $itemData['id']; ?>">
        <img src="<?php echo BASE_URL; ?>/view/imagens/editarIcone.png" alt="Editar Item">
    </a>
    <button class="itemAcao" onclick="abrirPopupRemocao('<?php echo $itemData['id']; ?>')">
        <img src="<?php echo BASE_URL; ?>/view/imagens/removerIcone.png" alt="Remover Item">
    </button>
    <button class="itemAcao" onclick="abrirPopup('<?php echo $itemData['id']; ?>')">
        <img src="<?php echo BASE_URL; ?>/view/imagens/acaoIcone.png" alt="Ação">
    </button>
</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">Nenhum item encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>



<div class="overlay" id="overlay" onclick="fecharPopup()"></div>
    <div class="popup" id="popup">
        <h2>Escolha uma ação</h2>
        <div class="popup-options">
            <a id="passarEtapa"><button>Passar para a próxima etapa</button></a>
            <a id="liberarProducao"><button>Liberar para a produção</button></a>
            <a id="acrescentarItem"><button>Acrescentar item igual</button></a>
        </div>
        <button onclick="fecharPopup()" class="button-fechar">Fechar</button>
    </div>

    <div class="overlay" id="removalOverlay" onclick="fecharPopupRemocao()"></div>
    <div class="popup" id="removalPopup">
        <h2>Confirmar Remoção</h2>
        <p>Tem certeza que deseja remover este item?</p>
        <div class="popup-options">
            <a id="confirmarRemocao" href="#"><button>Sim</button></a>
            <button onclick="fecharPopupRemocao()">Não</button>
        </div>
    </div>

    <div class="overlay" id="dataPopupOverlay" onclick="fecharDataPopup()"></div>
    <div class="popup" id="dataPopup">
        <h2>Digite a Data de Entrada</h2>
        <form id="dataForm" method="POST" action="../../../model/itemEstoque/alterarStatusItemEstoque.php">
            <input type="hidden" name="id" id="popupItemId">
            <label for="dataEntrada">Data (yyyy-mm-dd):</label>
            <input type="date" name="dataEntrada" id="dataEntrada" required>
            <div class="popup-options">
                <button type="submit" class="popup-submit">Confirmar</button>
                <button type="button" class="popup-cancel" onclick="fecharDataPopup()">Cancelar</button>
            </div>
        </form>
    </div>
</body>
</html>
