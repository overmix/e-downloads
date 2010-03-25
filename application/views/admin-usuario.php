<?php include ("admin-header.php"); ?>

<h1><?=$titulo;?></h1>

<?=output_msg($type = null);?>

<div id="profile" class="bg">
    <div class="dadossobre">
        <?=form_open("usuario/salvar");?>
        <input type="hidden" value="<?php echo $id;?>" name="id_usuario" />
            <ul>
                <li>
                    <span>Nome:</span>
                    <strong class="obj" style="display:none"><input type="text" name="nome" value="<?=$this->validation->nome;?>" /></strong>
                    <strong class="txt"><?=$this->validation->nome;?></strong>
                    <a href="#" title=" " class="edit">editar</a><?=$this->validation->nome_error; ?>
                </li>
                <li>
                    <span>Telefone:</span>
                    <strong class="obj" style="display:none"><input type="text" name="telefone" value="<?=$this->validation->telefone;?>" /></strong>
                    <strong class="txt"><?=$this->validation->telefone;?></strong>
                    <a href="#" title=" " class="edit">editar</a><?=$this->validation->telefone_error; ?></li>
            </ul>

            <h1>Caso queira alterar a senha:</h1>
            <?=$this->output->get_output();?>

            <fieldset class="dadossegurança">
                <legend></legend>
                <label for="senha">
                    <span>Senha antiga:</span>
                    <input type="password" value="" name="senha" id="senha" />
                    <?=$this->validation->senha_error; ?>
                </label>
                <label for="senha2">
                    <span>Senha nova:</span>
                    <input type="password" value="" name="senha2" id="senha2" />
                    <?=$this->validation->senha2_error; ?>
                </label>
                <label for="senha3">
                    <span>Confirmação da senha:</span>
                    <input type="password" value="" name="senha3" id="senha3" />
                    <?=$this->validation->senha3_error; ?>
                </label>
            </fieldset>

            <button type="submit" class="btn">Salvar alterações</button>
        </form>
    </div>

</div>

<?php include ("admin-footer.php"); ?>