<?php include ("admin-header.php"); ?>

<h1><?=$titulo;?></h1>

<?=output_msg($type = null);?>

<div id="profile" class="bg">
    <div class="dadossobre">
        <?=form_open("usuario/salvar");?>
        <input type="hidden" value="<?php echo $id;?>" name="id_usuario" />
            <ul>
                <li>
                    Nome:
                    <strong class="obj" style="display:none"><input type="text" name="nome" value="<?=$this->validation->nome;?>" /></strong>
                    <strong class="txt"><?=$this->validation->nome;?></strong>
                    <?php echo anchor("#",'editar',array('title'=>'Editar','class'=>"edit")) ?><?=$this->validation->nome_error; ?>
                </li>
                <li>
                    Telefone:
                    <strong class="obj" style="display:none"><input type="text" name="telefone" value="<?=$this->validation->telefone;?>" /></strong>
                    <strong class="txt"><?=$this->validation->telefone;?></strong>
                    <?php echo anchor("#",'editar',array('title'=>'Editar','class'=>"edit")) ?><?=$this->validation->telefone_error; ?>
                </li>
            </ul>

            <?=$this->output->get_output();?>

            <fieldset class="dadossegurança">
                <legend>Caso queira alterar a senha:</legend>
                <label for="senha">
                    Senha antiga: <input type="password" value="" name="senha" id="senha" />
                    <?=$this->validation->senha_error; ?>
                </label>
                <label for="senha2">
                    Senha nova: <input type="password" value="" name="senha2" id="senha2" />
                    <?=$this->validation->senha2_error; ?>
                </label>
                <label for="senha3">
                    Confirmação da senha: <input type="password" value="" name="senha3" id="senha3" />
                    <?=$this->validation->senha3_error; ?>
                </label>
            </fieldset>

            <div class="botoes">
                <?php echo anchor('admin','Voltar para a administração',array('title'=>'Voltar para a administração','class'=>'btn'));?>
                <button type="submit" class="btn">Salvar alterações</button>
            </div>
            
        </form>
    </div>

</div>

<?php include ("admin-footer.php"); ?>
