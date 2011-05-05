<?php include ("header.php");?>

<h1><?=$titulo;?></h1>
<h2><?=output_msg($type = null);?></h2>

<div id="login" class="bg">
    <?=form_open('trocasenha/alterar', array('id' => 'form_obj'));?>
        <fieldset>
            <legend>Altarar senha</legend>
            <!--label for="email"><span>Confirme o email</span><input type='text' name='email' id="email" value="<?=$this->validation->email;?>" /><?=$this->validation->email_error; ?></label-->
            <label for="senha"><span>Nova senha</span><input type='password' name='senha' id="senha" value="" /><?=$this->validation->senha_error; ?></label>
            <label for="senha2"><span>Confirmação da senha:</span><input type="password" value="" name="senha2" id="senha2" /><?=$this->validation->senha2_error; ?></label>
            <input type="hidden" name="uid" value="<?=$uid;?>" />
            <div class="botoes">
				<button type="submit">Enviar</button>
                <?=anchor("/", "Limpar", array('class'=>'btn', 'id'=>'resetar'));?>
            </div>
       </fieldset>
    </form>
</div>

<?php include ("footer.php");?>
  
