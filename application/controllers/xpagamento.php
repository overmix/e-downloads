<?php

class Pagamentos
{
    function get($pedido)
    {
        $mPag = new Pagamento;
        $pagamento = $mPag->select(array('num_pedido'=>$pedido));
        if (!$pagamento OR $pagamento->status != 'Aguardando Pagto') {
            header('Location: ' . BASE_URL);
        }
        include('app/template/public/pagamento.php');
    }
}