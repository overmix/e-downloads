<?php include ("header.php");?>

<h2><?=output_msg($type = null);?></h2>

<?=form_open('inicio/login', array('id' => 'form_obj'));?>
    <fieldset>
        <legend><?=$titulo;?></legend>
        <label>Email <input type='text' name='email' id="email" value="<?=$this->validation->email;?>" /><?=$this->validation->email_error; ?></label>
        <label>Senha <input type='password' name='senha' id="senha" value="" /><?=$this->validation->senha_error; ?></label>

        <button type="submit">Enviar</button>
        <?=anchor("/", "Limpar", array('class'=>'btn', 'id'=>'resetar'));?>

        <p>
             <?php echo anchor("esqueci", "Esqueci minha senha", array('title'=>'Cadastre-se')) ?> |
             <?php echo anchor("cadastro", "Não é cadastrado?", array('title'=>'Cadastre-se')) ?>
        </p>
   </fieldset>
</form>

<?php include ("footer.php");?>
  
