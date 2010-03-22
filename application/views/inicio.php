<?php include ("header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>

<div id="login" class="bg">
    <?=form_open('inicio/login', array('id' => 'form_obj'));?>
        <fieldset>
            <legend>Login</legend>
            <label for="email"><span>Email</span><input type='text' name='email' id="email" value="<?=$this->validation->email;?>" /><?=$this->validation->email_error; ?></label>
            <label for="senha"><span>Senha</span><input type='password' name='senha' id="senha" value="" /><?=$this->validation->senha_error; ?></label>
            <div class="botoes">
				<button type="submit">Enviar</button>
                <?=anchor("/", "Limpar", array('class'=>'btn', 'id'=>'resetar'));?>
            </div>
			<p class="botoes">
				 <?php echo anchor("esqueci", "Esqueci minha senha", array('title'=>'Cadastre-se')) ?> | 
				 <?php echo anchor("cadastro", "Não é cadastrado?", array('title'=>'Cadastre-se')) ?>				 
			</p>
       </fieldset>
    </form>
</div>

<?php include ("footer.php");?>
  
