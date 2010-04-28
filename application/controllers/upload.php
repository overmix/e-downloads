<?php
/**
 * Description of upload
 *
 * @author ldmotta
 */
class Upload extends Controller {
    function Upload($tipo=1) {
        parent::Controller();
        $this->load->library('validation');
        $this->load->library('upload');
        $this->load->config('upload');
        $this->load->model('user');
        $tipo = $this->input->post('tipo');

        /*-------------validações------------*/
        $rules['titulo']	= "trim|required|xss_clean";
        $rules['preco']		= "trim|required|xss_clean";
        $rules['descricao'] = "trim|required|xss_clean";
        $this->validation->set_rules($rules);

        $fields['titulo']		= 'Título';
        $fields['preco']        = 'Preço';
        $fields['descricao']	= 'Descrição';
        $this->validation->set_fields($fields);

        $this->validation->set_message('required', 'O campo <i>%s</i> não pode ser vazio');
        $this->validation->set_message('xss_clean', 'O campo <i>%s</i> possue caracteres não permitidos!');
        $this->validation->set_error_delimiters('<div class="error">', '</div>');

        $this->auth->verificaLogin();

    }

    function index($tipo=1) {
        $infocat = $this->config->item('image_categories_info');

        $data = array(
                'tipo'          =>$tipo,
                'logged'        =>$this->auth->logged(),
                'page_title'    =>'Upload',
                'titulo'        =>'UPLOAD DE FOTO E VÍDEO',
                'description'   =>'Efetuar cadastro',
                'info_cat'      => $infocat,
                'youtubclass'   => $tipo==1?'youtub':'youtubshow'
        );
        $this->load->view('upload', $data);
    }

    function doUpload() {
        //informações para o template
        $data = array(
            'tipo'          =>$this->input->post('tipo'),
            'logged'        =>$this->auth->logged(),
            'page_title'    =>'Upload',
            'titulo'        =>'UPLOAD DE FOTO E VÍDEO',
            'description'   =>'Efetuar cadastro',
            'youtubclass'   =>'youtub',
        );

        //só avalia o upload se for uma imagem

        // Redifine as preferências baseado na decisão do usuário
        $categoria = $this->config->item($this->input->post('imgcat'));

        $this->upload->set_max_width($categoria['max_width']);
        $this->upload->set_max_height($categoria['max_height']);

        $upload_path = 'uploads/' . $this->input->post('imgcat') . '/' . $this->user->userID() . '/';

        $this->upload->set_upload_path($upload_path);
        verifyPath($upload_path);

        if($this->validation->run()) {
            if ($data['tipo']==1 AND !$this->upload->do_upload()) {
                $this->messages->add($this->upload->display_errors('',''));
            }
            else {
                $data += array('upload_data' => $this->upload->data());

                if($data['tipo']==1) $this->_createThumbnail($data['upload_data']['file_name']);

                $id = $this->user->getUserIdByEmail($this->auth->userMail());
                $media = $data['tipo']==1?$data['upload_data']['file_name']:$this->input->post('userfile');

                $dados_img = array(
                        'id_usuario'    =>$id,
                        'media_url'     =>$media,
                        'nome_img'      =>$this->input->post('nome'),
                        'local_img'     =>$this->input->post('local'),
                        'media_type'    =>$data['tipo'],
                        'media_category'=>$this->input->post('imgcat'),
                        'dtcadastro'    =>date('Y-m-d H:i:s'),
                );

                $result = $this->user->gravaImg($dados_img);
                if ( $result ) $this->messages->add("Dados inseridos com sucesso!", 'success');
            }
            $this->auth->clearCache();
            redirect('profile', 'refresh');
        }else{
            $this->load->view('upload', $data);
        }
        $this->auth->clearCache();
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
?>
