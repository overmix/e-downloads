<?php
class Pedido extends Controller {
    function Pedido() {
        parent::Controller();
        $this->load->library('validation');
        $this->load->model('user');
        $this->load->model('product');
        $this->load->config('upload');

		/*-------------validações------------*/
        $rules['usar_ate']      = "trim|required|callback_isdate";
        $rules['limite']        = "trim|required|callback_isdigit";

        $this->validation->set_rules($rules);

        $fields['pedido_em']    = 'Data do pedido';
        $fields['liberado_em']  = 'Data da liberação';
        $fields['usar_ate']     = 'Usar até';
        $fields['limite']       = 'Limite de downloads';
        $this->validation->set_fields($fields);

        $this->validation->set_message('required', ' O campo <i>%s</i> não pode ser vazio');
        $this->validation->set_message('isdigit', ' O campo <i>%s</i> precisa conter um número inteiro!');
        $this->validation->set_message('isdate', ' O campo <i>%s</i> precisa conter uma data válida!');
        $this->validation->set_error_delimiters('<small class="error">', '</small>');
    }

    function isdigit($int)
    {
        return ctype_digit($int);
    }
    function isdate($int)
    {
        return is_date($int);
    }
    
    function index ($id) {
		
        if (!$this->auth->logged() OR !isAdmin() OR !$id) {redirect('home'); die();}
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Administração', 'titulo'=>'Pedido N° ' . $id, 'description'=>'Detalhes do pedido');
        $data['pedido'] = $this->user->getPedidosById($id);
		if (!count($data['pedido']))
		{
            $this->messages->add('Número de pedido inexistente!', 'warning'); // ser user message
            redirect('admin'); die();
		}
        $this->validation->pedido_em = formataData('d/m/Y H:i',$data['pedido']['pedido_em']);
        $this->validation->liberado_em = is_date($data['pedido']['liberado_em']) ?
										formataData('d/m/Y H:i', $data['pedido']['liberado_em']) :
										'dd-mm-aaaa 00:00';
        $this->validation->downloads = $data['pedido']['downloads'];
        $this->validation->usar_ate = is_date($data['pedido']['usar_ate']) ? 
										formataData('d/m/Y', $data['pedido']['usar_ate']) :
										'dd-mm-aaaa';
        $this->validation->limite = $data['pedido']['limite'];
        //echo "<pre>"; print_r($data); echo "</pre>"; die('fim');
        $this->load->view('admin-pedido', $data);
    }

    function editar()
    {
        $id = $this->input->post('id_pedido');
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Administração', 'titulo'=>'Pedido N° ' . $id, 'description'=>'Detalhes do pedido');

        //caso a validação esteja ok
        if ($this->validation->run()) {
            $where = array('id_pedido' =>$this->input->post('id_pedido'));
            $usar_ate = is_date($this->input->post('usar_ate')) ? $this->input->post('usar_ate') : null;
            $dados = array (
                'usar_ate'  =>dateDb($usar_ate),
                'limite'    =>$this->input->post('limite'),
            );

            $dados = $this->input->xss_clean($dados);
            $this->product->updatePedido($where, $dados);

            $this->messages->add('Usuário atualizado com sucesso!', 'success'); // ser user message
            redirect('pedido/index/'.$id); die();
        }
        
        if ($this->auth->logged()) {
            $data['pedido'] = $this->user->getPedidosById($id);
            $this->validation->pedido_em = formataData('d/m/Y',$data['pedido']['pedido_em']);
            $this->validation->liberado_em = is_date(formataData('d/m/Y',$data['pedido']['liberado_em'])) ?
                                            formataData('d/m/Y',$data['pedido']['liberado_em']) :
                                            'dd-mm-aaaa 00:00';
            $this->validation->downloads = $data['pedido']['downloads'];
            $this->validation->usar_ate = is_date(formataData('d/m/Y',$data['pedido']['usar_ate'])) ?
                                            formataData('d/m/Y',$data['pedido']['usar_ate']) :
                                            'dd-mm-aaaa';
            $this->validation->limite = $data['pedido']['limite'];
        }
        $this->load->view('admin-pedido', $data);
    }
}

?>
