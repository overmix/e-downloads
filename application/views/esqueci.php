<?php include ("header.php");?>

<h2><?=output_msg($type = null);?></h2>

<?=form_open('esqueci/lembrar', array('id' => 'form_obj'));?>
    <fieldset>
        <legend><?=$titulo;?></legend>
        <p>Após a confirmação, um email será enviado com as informações para<br />alteração da senha.</p>
        <label>Email <input type='text' name='email' id="email" value="<?=$this->validation->email;?>" /><?=$this->validation->email_error; ?></label>
        
        <button type="submit">Enviar</button>
        <?=anchor("/", "Limpar", array('class'=>'btn', 'id'=>'resetar'));?>
        
   </fieldset>
</form>

<?php include ("footer.php");?>
  
