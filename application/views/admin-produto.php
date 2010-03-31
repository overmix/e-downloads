<?php include ("header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>
<div id="contato" class="bg">
    <?=form_open_multipart($action);?>
        <fieldset>
            <legend>Cadastro</legend>            

            <?php echo form_upload('userfile', 'Selecione uma imagem'); ?>
            <?=$this->validation->userfile_error; ?>

            <label><span>Nome do produto</span><input type="text" value="<?=$this->validation->nome;?>" name="nome" />
            <?=$this->validation->nome_error; ?></label>

            <label><span>Preço</span><input type="text" value="<?=$this->validation->preco;?>" name="preco" />
            <?=$this->validation->preco_error; ?></label>

            <label><span>Descrição</span>
                <textarea rows="4" cols="36" name="descricao"><?=$this->validation->descricao;?></textarea>
                <?=$this->validation->descricao_error; ?></label>

            <?php echo form_upload('arquivo', 'Selecione um arquivo'); ?>
            <?=$this->validation->arquivo_error; ?>
        </fieldset>

        <div class="botoes">
            <?=anchor(base_url() . "admin", "Voltar para a administração", array('class'=>'btn'));?>
            <button type="submit">Cadastrar</button>
            <?=anchor("/", "Limpar", array('class'=>'btn', 'id'=>'resetar'));?>
        </div>

    </form>
 </div>
<?php include ("footer.php");?>

