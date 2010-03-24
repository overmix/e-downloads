<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuario
 *
 * @author ldmotta
 */

class Usuario extends Controller {

    function Usuario () {
        parent::Controller();
        $this->load->library('validation');
        $this->load->library('session');
        $this->load->model('user');

        $this->auth->verificaLogin();

		/*-------------validações------------*/
        $rules['nome']		= "trim|required|xss_clean";
        $rules['telefone']	= "trim|required|xss_clean";
        $requerido          = $this->pass_check($this->input->post('senha2'));
        $rules['senha']		= "trim".$requerido."|callback_senha_check";
        $requerido          = $this->pass_check($this->input->post('senha'));
        $rules['senha2']	= "trim|matches[senha3]".$requerido;
        $rules['senha3']	= "trim|".$requerido;

        $this->validation->set_rules($rules);

        $fields['nome']		= 'Nome';
        $fields['telefone']	= 'Telefone';
        $fields['senha']	= 'Senha antiga';
        $fields['senha2']	= 'Nova senha';
        $fields['senha3']	= 'Confirmação da senha';
        $this->validation->set_fields($fields);

        $this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio!');
        $this->validation->set_message('senha_check', '%s não confere!');
        $this->validation->set_message('valid_email', 'O campo <i>%s</i> não contém um email válido!');
        $this->validation->set_message('matches', 'Senhas não conferem!');
        $this->validation->set_error_delimiters('<small class="error">', '</small>');
    }

    function index($id) {
        if (!$this->auth->logged()) redirect('inicio');
        $this->load->library('lightbox');

        $data = array(
            'logged'		=>$this->auth->logged(),
            'page_title'	=>'Editar usuário',
            'titulo'		=>'INFORMAÇÕES DO USUÁRIO',
        );

        $result = $this->user->getUserDataById($id);

        $data['id'] = $result['id_usuario'];

        $this->validation->nome =$result['nome'];
        $this->validation->telefone =$result['telefone'];

        $this->load->view('admin-usuario', $data);
    }

    function senha_check($str) {
        $where = array(
            'senha'=>md5($str),
            'email'=>$this->session->userdata('email')
        );
        return $this->user->checaSenha($where);
    }

    function pass_check($str) {
        return ($str!='')?"|required":"";
    }

    function salvar() {
        $data = array(
            'logged'        =>$this->auth->logged(),
            'page_title'    =>'Informações de Cadastro',
            'titulo'        =>'INFORMAÇÕES DE CADASTRO',
        );

        //caso a validação esteja ok
        if ($this->validation->run()) {
            $dados = array (
                'nome'      =>$this->input->post('nome'),
                'telefone'  =>$this->input->post('telefone'),
            );

            if($this->input->post('senha2')) $dados += array('senha'=>md5($this->input->post('senha2')));

            $id = $this->input->post('id_usuario');
            
            $where = array('id_usuario' => $id);
            $this->user->updateUser($dados, $where);
            
            $this->messages->add('Usuário atualizado com sucesso!', 'success'); // ser user message
            redirect('usuario/index/'.$id); die();
        }

        if ($this->auth->logged()) {
            $result = $this->user->getUserDataByEmail($this->session->userdata('email'));
            $this->validation->nome     =$result['nome'];
            $this->validation->telefone =$result['telefone'];
        }

        $this->load->view('usuario', $data);
    }

    function deleteMedia($id)
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
        redirect('usuario'); die();
    }
}
?>
