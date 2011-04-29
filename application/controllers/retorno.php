<?php
class Retorno extends Controller {
    
    function Retorno() {
        parent::Controller();
        $config = (object)$this->config->item('dados_pgs');
        define('TOKEN', $config->token);
        $this->load->library('retornopagseguro');
    }
    function index() {
        // Não efetua retorno automático se estiver configurado com false
        $com_retorno = $this->config->item('usar_retorno');
        if (!$com_retorno){
            die();    
        }
        if ($_SERVER['REQUEST_METHOD']=='POST') {
            $this->retornopagseguro->verifica($_POST);
            die();
        }
        $data = array(
            'page_title' =>'e-Downloads',
            'titulo'     =>'Home',
        );
        $this->load->view('retorno', $data);
    }

    // Função que captura os dados do retorno
    function retorno_automatico ( $VendedorEmail, $TransacaoID,
        $Referencia, $TipoFrete, $ValorFrete, $Anotacao, $DataTransacao,
        $TipoPagamento, $StatusTransacao, $CliNome, $CliEmail,
        $CliEndereco, $CliNumero, $CliComplemento, $CliBairro, $CliCidade,
        $CliEstado, $CliCEP, $CliTelefone, $produtos, $NumItens) {
        
        if (strtoupper($StatusTransacao)=="COMPLETO" || strtoupper($StatusTransacao)=='APROVADO'){
            $_ci =& get_instance();
            $_ci->load->model('product');
            $_ci->load->model('user');
            $pedido=$_ci->product->getPedidoById($Referencia);
            $_ci->user->liberarPedido($pedido['id_pedido']);
        }
        
        // AQUI VOCÊ TEM OS DADOS RECEBIDOS DO PAGSEGURO, JÁ VERIFICADOS.
        // CONFIRA A LISTA DE PRODUTOS E O VALOR COM O QUE VOCÊ TEM NO
        // BANCO DE DADOS E, SE ESTIVER TUDO CERTO, ATUALIZE O STATUS
        // DO PEDIDO.

    }

}



/* End of file retorno.php */
/* Location: ./system/application/controllers/welcome.php */
