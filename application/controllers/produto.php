<?php
class Produto extends Controller {
    function Produto () {
        parent::Controller();
        $this->load->model('product');
        $this->load->library('validation');
        $this->load->model('user');

        $this->load->library('upload');
        $this->load->config('upload');

        $has_responsavel = (bool)(isset($_POST['faixaetaria']) AND $_POST['faixaetaria']=="-");

        $_POST['userfile'] = isset($_FILES['userfile']['name'])?$_FILES['userfile']['name']:'';
        $_POST['arquivo'] = isset($_FILES['arquivo']['name'])?$_FILES['arquivo']['name']:'';

		/*-------------validações------------*/
        $rules['nome']           = "trim|required|xss_clean";
        $rules['preco']          = "trim|required|callback_isnumeric_check";
        $rules['descricao']      = "trim|required|xss_clean";
        $rules['userfile']       = "trim|required";
        $rules['arquivo']        = "trim|required";
        

        $this->validation->set_rules($rules);

        $fields['nome']          = 'Nome';
        $fields['preco']         = 'Preço';
        $fields['descricao']     = 'Descrição';
        $fields['userfile']      = 'Imagem';
        $fields['arquivo']       = 'Arquivo';
        $this->validation->set_fields($fields);

        $this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
        $this->validation->set_message('isnumeric_check', 'O campo <i>%s</i> precisa conter um valor numérico válido.');
        $this->validation->set_error_delimiters('<small class="error">', '</small>');

    }

    function isnumeric_check($digit)
    {
        return is_numeric($digit);
    }

    function index ($id=0) {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Produto', 'titulo'=>'Detalhes do produto', 'description'=>'Detalhes do produto');
        $data['product'] = $this->product->getProductById($id);
        $this->load->view('produto', $data);
    }

    function novo () {
        $data = array(
            'logged'        =>$this->auth->logged(),
            'page_title'    =>'Adicionar produto',
            'titulo'        =>'ADICIONAR NOVO PRODUTO',
            'description'   =>'Adicionar novo produto',
            'action'        =>'produto/salvar',
        );
        $this->load->view('admin-produto', $data);
    }

    function editar($id)
    {
        $dados = $this->product->getProductById($id);
        $data = array(
            'logged'        =>$this->auth->logged(),
            'page_title'    =>'Editar produto',
            'titulo'        =>'EDITANDO O PRODUTO ' . $dados['nome'],
            'description'   =>'Editar produto',
            'action'        =>'produto/salvar/'.$id,
        );
        $this->validation->nome         = $dados['nome'];
        $this->validation->preco        = $dados['preco'];
        $this->validation->descricao    = $dados['descricao'];


        $this->load->view('admin-produto', $data);
    }

    function enviaImagem()
    {
        $this->upload->set_max_width($this->config->item('max_width'));
        $this->upload->set_max_height($this->config->item('max_width'));

        $upload_path = $this->config->item('upload_path') . 'image/';
        $this->upload->set_upload_path($upload_path);
        
        verifyPath($upload_path);

        if (!$this->upload->do_upload()) {
            $this->messages->add($this->upload->display_errors('',''));
            return array();
        }
        else
        {
            $image_data = $this->upload->data();
            $this->_createThumbnail($image_data['file_name']);
            $this->auth->clearCache();
            return $image_data;
        }
    }

    function enviaArquivo()
    {
        $upload_path = $this->config->item('upload_path') . 'arquivo/';
        $this->upload->set_upload_path($upload_path);

        $this->upload->set_max_width(0);
        $this->upload->set_max_height(0);

        $this->upload->set_allowed_types('tgz|rar|tar|zip');
        
        verifyPath($upload_path);

        if (!$this->upload->do_upload('arquivo')) {
            $this->messages->add($this->upload->display_errors('',''));
            return array();
        }
        else
        {
            $this->auth->clearCache();
            return $this->upload->data();
        }
    }

    function salvar()
    {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Adicionar produto', 'titulo'=>'ADICIONAR NOVO PRODUTO', 'description'=>'Adicionar novo produto');

        //caso a validação esteja ok
        if ($this->validation->run()) {
            $data += array('image_data' => $this->enviaImagem());
            $data += array('file_data'  => $this->enviaArquivo());

            $imagename = isset($data['image_data']['file_name'])?$data['image_data']['file_name']:'';
            $filename = isset($data['file_data']['file_name'])?$data['file_data']['file_name']:'';

            $dados = array (
                'nome'          =>$this->input->post('nome'),
                'preco'         =>$this->input->post('preco'),
                'descricao'     =>$this->input->post('descricao'),
            );
            if($imagename) $dados += array('image'=>$imagename);
            if($filename)  $dados += array('arquivo'=>$filename);

            $dados = $this->input->xss_clean($dados);

            if (!$this->product->insertProduct($dados)) {
                $this->messages->add('Erro ao gravar dados!');
                redirect('produto/novo', 'refresh'); die();
            }
            $msg = sprintf('Produto %s adicionando com sucesso!', $dados['nome']);
            $this->messages->add($msg);
        }

        redirect('produto/novo'); die();
    }

    function atualiza($id=0)
    {
        $data = array();
        $dados = array();
        if(!$id) redirect('admin', 'refresh'); die();

        $prod = $this->product->getProductById($id);
        if ($_FILES['userfile']['name'] AND !$_FILES['userfile']['error']) {
            deleteImage($prod['image']);
            $data += array('image_data' => $this->enviaImagem());
        }
        if ($_FILES['arquivo']['name'] AND !$_FILES['arquivo']['error']) {
            unlink(FCPATH . $this->config->item('upload_path') . 'arquivo/'. $prod['arquivo']);
            $data += array('file_data'  => $this->enviaArquivo());
        }
        $dados += array('arquivo' => isset($data['file_data']['file_name'])?$data['file_data']['file_name']:$prod['arquivo']);
        $dados += array('image'   => isset($data['image_data']['file_name'])?$data['image_data']['file_name']:$prod['image']);

        //caso a validação esteja ok
        if ($this->validation->run()) {
            $dados += array (
                'nome'          =>$this->input->post('nome'),
                'preco'         =>$this->input->post('preco'),
                'descricao'     =>$this->input->post('descricao'),
            );
            $dados = $this->input->xss_clean($dados);

            if ($id) {
                if (!$this->product->updateProduct(array('id_produto'=>$id), $dados)) $this->messages->add('Erro ao atualizar dados!', 'error');
            }else{
                if (!$this->product->insertProduct($dados)) $this->messages->add('Erro ao gravar dados!', 'error');
            }
            redirect('admin', 'refresh'); die();
        }
        redirect('produto/editar/'.$id, 'refresh'); die();
    }

    function _createThumbnail($fileName) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $this->upload->get_upload_path() . $fileName;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 120;
        $config['height'] = 90;

        $this->load->library('image_lib', $config);
        if(!$this->image_lib->resize()) echo $this->image_lib->display_errors();
    }

}