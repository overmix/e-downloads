<?php if(isAdmin()):
    include ("admin-header.php");
else:
    include ("header.php");?>
    <link rel="stylesheet" type="text/css" media="screen" charset="utf-8" href="<?=base_url();?>skin/css/prettyPhoto.css" />
    <script type="text/javascript" charset="utf-8" src="<?=base_url();?>skin/js/jquery.prettyPhoto.js"></script>
<?php endif;?>

<h1><?=$titulo;?></h1>

<?=output_msg($type = null);?>

<div id="profile" class="bg">
    <div class="dadossobre">
        <?=form_open("profile/salvar");?>
            <ul>
                <li>
                    <span>Nome:</span>
                    <strong class="obj" style="display:none"><input type="text" name="nome" value="<?=$this->validation->nome;?>" /></strong>
                    <strong class="txt"><?=$this->validation->nome;?></strong>
                    <a href="#" title=" " class="edit">editar</a><?=$this->validation->nome_error; ?>
                </li>
                <?php if(!isAdmin()):?>
                <li>
                    <span>Telefone:</span>
                    <strong class="obj" style="display:none"><input type="text" name="telefone" value="<?=$this->validation->telefone;?>" /></strong>
                    <strong class="txt"><?=$this->validation->telefone;?></strong>
                    <a href="#" title=" " class="edit">editar</a><?=$this->validation->telefone_error; ?></li>
                <?php endif;?>
            </ul>

            <?=$this->output->get_output();?>

            <fieldset class="dadossegurança">
                <legend>Alterar de senha</legend>
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

<?php if(isAdmin()):
    include ("admin-footer.php");
else:
    include ("footer.php");
endif;?>