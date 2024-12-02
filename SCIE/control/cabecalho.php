<?php
class Cabecalho {
    private $homePage;
    private $userType;

    public function __construct($userType = null) {
        require_once __DIR__ . '/../config.php';

        $this->userType = $userType;
        $this->defineHomePage();
    }

    private function defineHomePage() {
        $homePageEngenharia = BASE_URL . "/control/HomeController.php?user_type=Engenharia";
        $homePageTI = BASE_URL . "/control/HomeController.php?user_type=TI";
    
        if ($this->userType === 'Engenharia') {
            $this->homePage = $homePageEngenharia;
        } elseif ($this->userType === 'TI') {
            $this->homePage = $homePageTI;
        } else {
            error_log("Usuário não autenticado ou tipo inválido: " . var_export($this->userType, true));
            $this->homePage = BASE_URL . '/view/login.php';
        }
    }
    
    
    public function render() {
        ?>
        <header class="header">
            <a href="<?php echo $this->homePage; ?>">
                <img src="<?php echo BASE_URL; ?>/view/imagens/ScieLogo.png" alt="Logo do SCIE" id="logoScie">
            </a>
            <h1>SCIE (Sistema de Controle de Itens de Engenharia)</h1>
            <a href="<?php echo BASE_URL; ?>/model/usuario/finalizarLogin.php">
                <img src="<?php echo BASE_URL; ?>/view/imagens/PaschoalLogo.svg" alt="Logo da Paschoal" class="Logo">
            </a>
        </header>
        <?php
    }
}
