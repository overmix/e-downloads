<?php
class Admin extends Controller {

    function Admin () {
        parent::Controller();
        $this->load->library('validation');
        $this->load->library('lightbox');
        $this->load->model('product');
        $this->load->model('user');
        $this->load->config('upload');

		/*-------------validações------------*/
        $rules['nome']		= "trim|required|xss_clean";
        $rules['email']		= "trim|required|valid_email|callback_email_check";
        $rules['senha']		= "trim|required|matches[senha2]|min_length[5]|max_length[12]";
        $rules['senha2']	= "trim|required";
        $this->validation->set_rules($rules);

        $fields['nome']		= 'Nome';
        $fields['email']	= 'Email';
        $fields['senha']	= 'Senha';
        $fields['senha2']	= 'Confirme a senha';
        $this->validation->set_fields($fields);

        $this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
        $this->validation->set_message('email_check', 'Email já cadastrado');
        $this->validation->set_message('valid_email', 'O campo <i>%s</i> não contém um email válido');
        $this->validation->set_message('matches', 'Senhas não conferem');
        $this->validation->set_error_delimiters('<small class="error">', '</small>');

    }

    function index ($msg='') {
        if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Administração', 'titulo'=>'Administração e-Downloads', 'description'=>'Administração geral');
        $data['pedidos'] = $this->product->getAllPedidos();

        $data['produtos_ativos'] = $this->product->getAllProductsByStatus(1);
        $data['produtos_inativos'] = $this->product->getAllProductsByStatus(0);
        $data['usuarios'] = $this->user->getAllUsers(array('group'=>0));

        $this->load->view('admin', $data);
    }

    function manageusers(){
        if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Administração', 'titulo'=>'Gerenciamento de usuários', 'description'=>'Administração geral de usuários');
        $data['users_admin'] = $this->user->getAllUserByGroup(array('group'=>1));
        $data['users_comum'] = $this->user->getAllUserByGroup(array('group'=>0));
        $this->load->view('admin-users', $data);
    }

    function desativarproduto($id=0) {
        $ids = $id?array($id):$this->input->post('edit');
        foreach($ids as $id)
        {
            if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
            $product = getProductById($id);
            if($product['id_produto']) {
                if($this->product->desativarProduto($id)){
                    $texto = sprintf("O produto <strong>%s</strong> foi desativado com sucesso!<br />", $product['nome']);
                    $this->messages->add($texto, 'success'); // ser user message
                }
            }
        }
        redirect('admin'); die();
    }
    
    function reativarproduto($id=0) {
        $ids = $id?array($id):$this->input->post('edit');
        foreach($ids as $id)
        {
            if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
            $product = getProductById($id);
            if($product['id_produto']) {
                if($this->product->reativarProduto($id)){
                    $texto = sprintf("O produto <strong>%s</strong> foi ativado com sucesso!<br />", $product['nome']);
                    $this->messages->add($texto, 'success'); // ser user message
                }
            }
        }
        redirect('admin'); die();
    }

    function reprovar($id) {
        if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
        $media = getMediaById($id);
        if($media['id']) {
            $tipo = $media['media_type']==1?"Imagem":"Vídeo";
            if($this->user->reprovaImg($id)){
                $texto = sprintf("%s <strong>%s</strong> de <strong>%s</strong> foi reprovado(a).", $tipo, $media['nome_img'], $media['nome_autor']);
                $this->messages->add($texto, 'success'); // ser user message
            }
        }
        redirect('admin'); die();
    }

    /**
     * Bloqueia o usuario com id passado em $id
     * @param int $id Id do usuario
     */
    function bloquearuser($id=0) {
        if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
        $ids = $id?array($id):$this->input->post('edit');
        foreach($ids as $id)
        {
            $user = $this->user->getUserDataById($id);
            if(count($user)) {
                if($this->user->bloqueiaUser($id)){
                    $texto = sprintf("O usuário <strong>%s</strong> foi bloqueado com sucesso.", $user['nome']);
                    $this->messages->add($texto, 'success'); // ser user message
                }
            }
        }
        redirect('admin'); die();
    }

    /**
     * Ativa o usuario com id passado em $id
     * @param int $id Id do usuario
     */
    function ativaruser($id=0) {
        if (!$this->auth->logged() OR !isAdmin() OR !$id) {redirect('home'); die();}
        $ids = $id?array($id):$this->input->post('edit');
        foreach($ids as $id)
        {
            $user = $this->user->getUserDataById($id);
            if(count($user)) {
                if($this->user->ativaUser($id)){
                    $texto = sprintf("O usuário <strong>%s</strong> foi ativado com sucesso.", $user['nome']);
                    $this->messages->add($texto, 'success'); // ser user message
                }
            }
        }
        redirect('admin'); die();
    }

    /**
     * Remove o usuario com id passado em $id
     * @param int $id Id do usuario
     */
    function removeruser($id=0) {
        if (!$this->auth->logged() OR !isAdmin() OR !$id) {redirect('home'); die();}
        $ids = $id?array($id):$this->input->post('edit');
        foreach($ids as $id)
        {
            $user = $this->user->getUserDataById($id);
            if(count($user)) {
                if($this->user->removeUser($id)){
                    $texto = sprintf("O usuário <strong>%s</strong> foi removido com sucesso.", $user['nome']);
                    $this->messages->add($texto, 'success'); // ser user message
                }
            }
        }
        redirect('admin'); die();
    }

    function make_xls(){
        $ids = $this->input->post('edit');
        $reminders = getReminders("lembrete_id in (".implode(',',$ids).")");
        $content='';
        foreach ($reminders as $item) {
            // $status = $item['status']==0?'Não enviado':'Enviado';
            // $content .= sprintf("<tr><td>%s</td><td>%s</td></tr>",$item['email'], $status);
            $content .= sprintf("<tr><td>%s</td></tr>",$item['email']);
            atualizaStatusLembrete($item['lembrete_id']);
        }
        $remindersHtml = "
        <table style='width:100%' summary='Lista de emails'>
            <thead>
                <tr>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                $content
            </tbody>
        </table>";
        $xlsUrl = $this->config->item('xls_path');
        verifyPath($xlsUrl, 0777);
        $xlsUrl .= 'lista_de_emails.xls';      //date("d-m-Y", time()).".xls";
        file_put_contents($xlsUrl, utf8_decode($remindersHtml));

        redirect('admin'); die();
    }

    function addadmin($id) {
        if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
        $user = $this->user->getUserDataById($id);
        if($user['id']) {
            if($this->user->setCommonToAdmin($id)){
                $texto = sprintf("O usuário <strong>%s</strong> foi definido como administrador deste site.", $user['nome']);
                $this->messages->add($texto, 'success'); // ser user message
            }
        }
        redirect('admin/manageusers'); die();
    }

    function canceladmin($id) {
        if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
        if($this->user->userID()!=$id){
            $user = $this->user->getUserDataById($id);
            if($user['id']) {
                if($this->user->setAdminToCommon($id)){
                    $texto = sprintf("O usuário <strong>%s</strong> não é mais um administrador deste site.", $user['nome']);
                    $this->messages->add($texto, 'success'); // ser user message
                }
            }
        }else{
            $this->messages->add("Você não pode alterar a sua própria atribuição de administrador.", 'warning');
        }
        redirect('admin/manageusers'); die();
    }

    function email_check($str) {
        return !$this->user->checaUser(array('email'=>$str));
    }

    function removerpedido ($id)
    {
        $pedido = $this->product->getPedidoById($id);
        if($pedido['id_pedido'])
        {
            $this->db->where(array('id_pedido'=>$id));
            $this->db->delete('pedidos');
            $this->messages->add("O pedido n° $id foi deletado com sucesso.", 'done');
        }
        redirect('admin'); die();
    }

    function removerproduto ($id)
    {
        $produto = $this->product->getProductById($id);

        if($produto['id_produto'])
        {
            $this->db->where(array('id_produto'=>$id));
            $this->db->delete('produtos');
            $this->messages->add("O produto {$produto['nome']} foi deletado com sucesso.", 'done');
        }

        deleteArquivo($produto['arquivo']);

        redirect('admin'); die();
    }

    /**
     * Libera o pedido para o usuario com id passado em $id
     * @param int $id Id do usuario
     */
    function liberarpedido($id=0) {
        $ids = $id ? array($id) : $this->input->post('edit');
        if (!$this->auth->logged() OR !isAdmin() OR !count($ids)) {redirect('admin'); die();}

        foreach($ids as $id)
        {
            if($this->user->liberarPedido($id)){
                $texto = sprintf("O pedido N°<strong>%s</strong> foi liberado com sucesso.", $id);
                $this->messages->add($texto, 'success'); // ser user message
            }
        }
        redirect('admin'); die();
    }


}
?>
