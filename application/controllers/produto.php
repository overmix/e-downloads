<?php
class Produto extends Controller {
    var $allowed = 'exe|tgz|rar|tar|zip|jpg|jpeg|png|gif|bmp|jpe';

    function Produto () {
        parent::Controller();
        $this->load->model('product');
        $this->load->library('validation');
        $this->load->model('user');

        $this->load->library('upload');
        $this->load->config('upload');

        $has_responsavel = (bool)(isset($_POST['faixaetaria']) AND $_POST['faixaetaria']=="-");

        $_POST['userfile']  = isset($_FILES['userfile']['name'])? $_FILES['userfile']['name'] : '';
        $_POST['arquivo']   = isset($_FILES['arquivo']['name']) ? $_FILES['arquivo']['name']  : '';

        /*-------------validações------------*/
        $rules['nome']           = "trim|required|xss_clean";
        $rules['preco']          = "trim|required|callback_isnumeric_check";
        $rules['descricao']      = "trim|required|xss_clean";
        //$rules['arquivo']        = "trim|callback_allowed_check";
        //$rules['userfile']       = "trim|callback_allowed_check";

        $this->validation->set_rules($rules);

        $fields['nome']          = 'Nome';
        $fields['preco']         = 'Preço';
        $fields['descricao']     = 'Descrição';
        $fields['userfile']      = 'Imagem';
        $fields['arquivo']       = 'Arquivo';
        $this->validation->set_fields($fields);

        $this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
        $this->validation->set_message('isnumeric_check', 'O campo <i>%s</i> precisa conter um valor numérico válido.');
        $this->validation->set_message('allowed_check', 'O tipo de arquivo que você está tentando carregar não é permitido.');
        $this->validation->set_error_delimiters('<small class="error">', '</small>');
    }

    function isnumeric_check($digit) {
        return is_numeric($digit);
    }

    function allowed_check($file) {
        $allowed_types  = explode('|', $this->allowed);
        $image_types    = array('gif', 'jpg', 'jpeg', 'png', 'jpe');

        foreach ($allowed_types as $val){
            $mime = $this->upload->mimes_types(strtolower($val));
            
            if (in_array($val, $image_types)) {
                $file_type = $_FILES['userfile']['type'];
            }else{
                $file_type = $_FILES['arquivo']['type'];
            }
            
            if (is_array($mime)) {
                if (in_array($file_type, $mime, TRUE)) {
                    return TRUE;
                }
            }
            else {
                if ($mime == $file_type) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    function index ($id=0) {
        $data = array('logged'=>$this->auth->logged(),'page_title'=>'Produto', 'titulo'=>'Detalhes do produto', 'description'=>'Detalhes do produto');
        $data['product'] = $this->product->getProductById($id);
        $this->load->view('produto', $data);
    }

    function novo () {
        $data = $this->_getDataNew();
        $this->load->view('admin-produto', $data);
    }

    function editar($id) {
        $data = $this->_getDataEdit($id);
        $this->load->view('admin-produto', $data);
    }
    
    function enviaImagem() {
        $this->upload->set_max_width($this->config->item('max_width'));
        $this->upload->set_max_height($this->config->item('max_width'));

        $this->upload->set_allowed_types($this->allowed);

        $upload_path = $this->config->item('upload_path') . 'image/';

        $this->upload->set_upload_path($upload_path);
        
        // Verifica a existencia da pasta uploads/image, e cria caso não exista
        $path = verifyPath($upload_path);
        
        // Define o nome simplificado da imagem
        if (isset($_FILES['userfile']['name'])) {
            $_FILES['userfile']['name'] = simplificaString($_FILES['userfile']['name'], '_') ;
        }
        
        if (!$this->upload->do_upload()) {
            $this->messages->add($this->upload->display_errors());
            return array();
        } else {
            $image_data = $this->upload->data();
            $this->_createThumbnail($image_data['file_name']);
            $this->auth->clearCache();
            return $image_data;
        }
    }

    function enviaArquivo() {
        $upload_path = $this->config->item('upload_path') . 'arquivo/';
        $this->upload->set_upload_path($upload_path);

        $this->upload->set_max_width(0);
        $this->upload->set_max_height(0);
        $this->upload->set_max_filesize(0);

        $this->upload->set_allowed_types($this->allowed);

        $path = verifyPath($upload_path);

        // Define o nome simplificado do arquivo
        if (isset($_FILES['arquivo']['name'])) {
            $_FILES['arquivo']['name'] = simplificaString($_FILES['arquivo']['name'], '_') ;
        }

        if (!$this->upload->do_upload('arquivo')) {
            $this->messages->add($this->upload->display_errors());
            return array();
        }
        else {
            $this->auth->clearCache();
            return $this->upload->data();
        }
    }

    function salvar() {
        $data = $this->_getDataNew();
        
        // Verifica se é arquivo existente ou novo
        $file_existente = (bool)$this->input->post('file_existente');
        
        // Se for de arquivo existente, pega do select buttom
        if($file_existente) $_POST['arquivo'] = $this->input->post('file_select');
        
        // Caso a validação dos campos obrigatórios esteja ok
        if ($this->validation->run()) {
            $data += array('image_data' => $this->enviaImagem());

            $imagename = isset($data['image_data']['file_name'])?$data['image_data']['file_name']:'';
            if(!$file_existente) {
                $data += array('file_data'  => $this->enviaArquivo());
                $filename = isset($data['file_data']['file_name'])?$data['file_data']['file_name']:'';
            }else {
                $filename = $this->input->post('file_select');
            }

            $dados = array (
                    'nome'        =>$this->input->post('nome'),
                    'preco'       =>$this->input->post('preco'),
                    'descricao'   =>$this->input->post('descricao'),
            );
            if($imagename) $dados += array('image'=>$imagename);
            if($filename)  $dados += array('arquivo'=>$filename);

            if (!$imagename || !$filename) {
                redirect('produto/novo');
                die();
            }

            $dados = $this->input->xss_clean($dados);

            $id = $this->product->insertProduct($dados);
            if (!$id) {
                $this->messages->add('Erro ao gravar dados!', 'error');
                redirect('produto/novo');
                die();
            }
            $msg = sprintf('Produto <span>"%s"</span> adicionando com sucesso!', $dados['nome']);
            $this->messages->add($msg, 'done');
            redirect('produto/editar/'. $id);
            die();
        }
        $this->load->view('admin-produto', $data);
        //redirect('produto/novo'); die();
    }

    function atualizar($id=0) {
        $data = array();
        $dados = array();

        $file_existente = (bool)$this->input->post('file_existente');
        if($file_existente) $_POST['arquivo'] = $this->input->post('file_select');

        if(!$id) {
            redirect('admin');
            die();
        }

        $prod = $this->product->getProductById($id);

        if ($_FILES['userfile']['name'] AND !$_FILES['userfile']['error']) {
            deleteImage($prod['image']);
            $data += array('image_data' => $this->enviaImagem());
        }

        if(!$file_existente) {
            if ($_FILES['arquivo']['name'] AND !$_FILES['arquivo']['error']) {
                $data += array('file_data' => $this->enviaArquivo());
                $_POST['arquivo']  = $data['file_data']['file_name'] ?
                        $data['file_data']['file_name']  :
                        $prod['arquivo'];
            }else {
                $_POST['arquivo']  = $_FILES['arquivo']['name'] ?
                        $_FILES['arquivo']['name'] :
                        $prod['arquivo'];
            }
        }

        $_POST['userfile'] = $_FILES['userfile']['name'] ? $_FILES['userfile']['name'] : $prod['image'];

        $data += $this->_getDataEdit($id);

        //caso a validação esteja ok
        if ($this->validation->run()) {
            $dados += array (
                    'nome'          =>$this->input->post('nome'),
                    'preco'         =>$this->input->post('preco'),
                    'descricao'     =>$this->input->post('descricao'),
                    'image'         =>$this->input->post('userfile'),
                    'arquivo'       =>$this->input->post('arquivo'),
            );

            $dados = $this->input->xss_clean($dados);

            if ($id) {
                $this->product->updateProduct(array('id_produto'=>$id), $dados);
            }
            $msg = sprintf('Produto <span>"%s"</span> foi atualizado com sucesso!', $dados['nome']);
            $this->messages->add($msg, 'done');
        }
        $this->load->view('admin-produto', $data);
        //redirect('produto/editar/'.$id); die();
    }

    /*
     * Blibliotecas suportadas GD, GD2, ImageMagick, NetPBM
     * Default GD
     */
    function _createThumbnail($filename) {
        $config['image_library']    = 'GD';
        $config['source_image']     = $this->upload->get_upload_path() . $filename;
        $config['create_thumb']     = TRUE;
        $config['maintain_ratio']   = FALSE;
        $config['width']            = 100;
        $config['height']           = 100;
        
        $this->load->library('image_lib', $config);
        
        if(!$this->image_lib->resize()){
            $this->messages->add($this->upload->display_errors(), "error");
        }
    }

    function _getDataNew() {
        $data = array(
                'logged'        =>$this->auth->logged(),
                'page_title'    =>'Adicionar produto',
                'titulo'        =>'ADICIONAR NOVO PRODUTO',
                'description'   =>'Adicionar novo produto',
                'action'        =>'produto/salvar',
                'arquivos'      =>$this->_listaArquivos(),
        );
        return $data;
    }

    function _getDataEdit($id) {
        $dados = $this->product->getProductById($id);

        $data = array(
                'logged'        =>$this->auth->logged(),
                'page_title'    =>'Editar produto',
                'titulo'        =>'EDITANDO O PRODUTO ' . $dados['nome'],
                'description'   =>'Editar produto',
                'action'        =>'produto/atualizar/'.$id,
                'product'       =>$dados,
                'arquivos'      =>$this->_listaArquivos(),
        );
        $this->validation->nome         = $dados['nome'];
        $this->validation->preco        = $dados['preco'];
        $this->validation->descricao    = $dados['descricao'];
    
        return $data;
    }

    function _listaArquivos() {
        $arquivos = getFilesByPath(uploadPath().'arquivo');
        $arrFile = array(''=>'Selecione...');
        foreach ($arquivos as $item) {
            $arrFile[$item]=$item;
        }
        return $arrFile;
    }

}
