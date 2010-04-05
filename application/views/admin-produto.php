<?php include ("header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>
<script type="text/javascript">
    jQuery(function(){
        $('#menoridade').click(function(){$('#dadosresponsavel').slideDown()})
        $('#maioridade').click(function(){$('#dadosresponsavel').slideUp()})
        if($('#menoridade').attr('checked')){$('#dadosresponsavel').slideDown()}
    });
</script>
<div id="contato" class="bg">
    <?=form_open_multipart($action);?>
        <fieldset>
            <legend><?php echo $page_title;?></legend>
            
            <?php if(isset($product)){?>
                <img src="<?=getThumbUrlById($product['id_produto']);?>" alt="foto 1" title="<?=$product['nome']?>" />
            <?php };?>

            <label>Escolher imagem
            <?php echo form_upload('userfile', 'Selecione uma imagem'); ?></label>
            <?=$this->validation->userfile_error; ?>

            <label>Nome do produto <input type="text" value="<?=$this->validation->nome;?>" name="nome" />
            <?=$this->validation->nome_error; ?></label>

            <label>Preço <input type="text" value="<?=$this->validation->preco;?>" name="preco" />
            <?=$this->validation->preco_error; ?></label>

            <label>Descrição <textarea rows="4" cols="36" name="descricao"><?=$this->validation->descricao;?></textarea>
            <?=$this->validation->descricao_error; ?></label>

            <label>Escolher <?php echo form_radio('file_existente', '0', TRUE);?></label>
                
            <label>Arquivo existente <?php echo form_radio('file_existente', '1', FALSE);?></label>

            <div id="existe" style="display:none">
            <?php echo form_dropdown('file_select', $arquivos,''); ?>
            </div>
            
            <div id="escolhe">
            <?php echo form_upload('arquivo', 'Selecione um arquivo'); ?>
            </div>

            <?=$this->validation->arquivo_error; ?>
        </fieldset>

        <div class="botoes">
            <?=anchor(base_url() . "admin", "Voltar para a administração", array('class'=>'btn'));?>
            <button type="submit">Cadastrar</button>
        </div>

    </form>
 </div>


<?php include ("footer.php");?>

