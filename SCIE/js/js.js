    function rota() 
{
    var site = "/SCIE/view/login.php";
    window.location.href = site;
}



document.getElementById('imagem').addEventListener('change', function() {
    const fileName = this.files.length > 0 ? this.files[0].name : 'Nenhum arquivo selecionado';
    document.getElementById('file-name').textContent = fileName;
});


document.addEventListener("DOMContentLoaded", () => {
    if (typeof mensagemErro !== "undefined" && mensagemErro) {
        alert(mensagemErro);
    }
});




function abrirPopup(itemId) {
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('popup');
    const passarEtapa = document.getElementById('passarEtapa');
    const liberarProducao = document.getElementById('liberarProducao');
    const acrescentarItem = document.getElementById('acrescentarItem');

    passarEtapa.href = `proximaFaseView.php?id=${itemId}`;
    liberarProducao.href = `liberarProducaoView.php?id=${itemId}`;
    acrescentarItem.href = `acrescentarItemEstoqueView.php?id=${itemId}`;

    overlay.style.display = 'block';
    popup.style.display = 'block';
}

function fecharPopup() {
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('popup');
    overlay.style.display = 'none';
    popup.style.display = 'none';
}




function abrirPopupRemocao(itemId) {
    const overlay = document.getElementById('removalOverlay');
    const popup = document.getElementById('removalPopup');
    const confirmarRemocao = document.getElementById('confirmarRemocao');

    confirmarRemocao.href = `../../../model/itemEstoque/removerItemEstoque.php?id=${itemId}`;

    overlay.style.display = 'block';
    popup.style.display = 'block';
}

function fecharPopupRemocao() {
    const overlay = document.getElementById('removalOverlay');
    const popup = document.getElementById('removalPopup');

    overlay.style.display = 'none';
    popup.style.display = 'none';
}




function abrirDataPopup(itemId, buttonElement) {
    const overlay = document.getElementById('dataPopupOverlay');
    const popup = document.getElementById('dataPopup');
    const itemIdInput = document.getElementById('popupItemId');
    const status = buttonElement.getAttribute('data-status');

    if (status !== 'Em processo') {
        alert('O status só pode ser alterado quando estiver "Em processo".');
        return; 
    }

    if (overlay && popup && itemIdInput) {
        itemIdInput.value = itemId; 
        overlay.style.display = 'block';
        popup.style.display = 'block'; 
    } else {
        console.error('Elementos do popup de solicitação de data não encontrados.');
    }
}

function fecharDataPopup() {
    const overlay = document.getElementById('dataPopupOverlay');
    const popup = document.getElementById('dataPopup');

    if (overlay && popup) {
        overlay.style.display = 'none';
        popup.style.display = 'none'; 
    } else {
        console.error('Elementos do popup de solicitação de data não encontrados.');
    }
}



function abrirPopupRemocaoUsuario(userId) {
    const overlay = document.getElementById('removalOverlay');
    const popup = document.getElementById('removalPopup');
    const confirmarRemocao = document.getElementById('confirmarRemocao');

    if (overlay && popup && confirmarRemocao) {
        confirmarRemocao.href = `../../model/usuario/removerUsuarios.php?id=${userId}`;
        overlay.style.display = 'block';
        popup.style.display = 'block';
    } else {
        console.error('Elementos do popup de remoção não encontrados.');
    }
}

function fecharPopupRemocaoUsuario() {
    const overlay = document.getElementById('removalOverlay');
    const popup = document.getElementById('removalPopup');

    if (overlay && popup) {
        overlay.style.display = 'none';
        popup.style.display = 'none';
    } else {
        console.error('Elementos do popup de remoção não encontrados.');
    }
}

   
 
    

   