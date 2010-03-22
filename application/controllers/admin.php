<?php
class Admin extends Controller {

    function Admin () {
        parent::Controller();
        $this->load->library('validation');
        $this->load->library('lightbox');
        $this->load->model('user');

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
        $data['downloads'] = $this->user->getAllMedia(array('status'=>1));
        $this->load->view('admin', $data);
    }

    function manageusers(){
        if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Administração', 'titulo'=>'Gerenciamento de usuários', 'description'=>'Administração geral de usuários');
        $data['users_admin'] = $this->user->getAllUserByGroup(array('group'=>1));
        $data['users_comum'] = $this->user->getAllUserByGroup(array('group'=>0));
        $this->load->view('admin-users', $data);
    }

    function aprovar($id=0) {
        $ids = $id?array($id):$this->input->post('edit');
        foreach($ids as $id)
        {
            if (!$this->auth->logged() OR !isAdmin()) {redirect('home'); die();}
            $media = getMediaById($id);
            if($media['id']) {
                $tipo = $media['media_type']==1?"Imagem":"Vídeo";
                if($this->user->aprovaImg($id)){
                    $texto = sprintf(" - %s <strong>%s</strong> de <strong>%s</strong> foi aprovado(a) com sucesso!<br />", $tipo, $media['nome_img'], $media['nome_autor']);
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

        redirect('admin');
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

    function remover ($id)
    {
        $media = getMediaById($id);
        if($media['id']) 
        {
            if( !(bool)$media['status'] )
            {
                if( $media['media_type']==1 ) {
                    $file = array(getMediaPathById($id), getThumbPathById($id));
                    array_walk($file, create_function('$item', 'unlink($item);'));
                }
                $texto = $media['media_type']==1?"Imagem excluída":"Vídeo excluído";
                if($this->user->deleteImg($id)){
                    $this->messages->add($texto . ' com sucesso!', 'success'); // ser user message
                }
            }else{
                $this->messages->add('Esta imagem não pode ser excluída!<br />Ela já foi aprovada e está participando da votação.', 'warning'); // ser user message
            }
        }
        redirect('admin'); die();
    }
    
}
?>