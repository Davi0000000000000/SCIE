<?php
session_start();
require_once '../../control/ItemEstoqueController.php';

$controller = new ItemEstoqueController();
$controller->adicionarItem($_POST, $_FILES);

header("Location: ../../view/Engenharia/itemEstoque/adicionarItemEstoqueView.php");
exit();
?>
