<?php include ("header.php");?>

<h1><?=$titulo;?></h1>
<h2><?=output_msg($type = null);?></h2>

<div id="contato" class="bg">
    <?=form_open('esqueci/lembrar', array('id' => 'form_obj'));?>
        <fieldset>
            <legend>Esqueci minha senha</legend>
            <p>Após a confirmação, um email será enviado com as informações para<br />alteração da senha.</p>
            <label for="email"><span>Email</span><input type='text' name='email' id="email" value="<?=$this->validation->email;?>" /><?=$this->validation->email_error; ?></label>
            <div class="botoes">
                <button type="submit">Enviar</button>
                <?=anchor("/", "Limpar", array('class'=>'btn', 'id'=>'resetar'));?>
            </div>
       </fieldset>
    </form>
</div>

<?php include ("footer.php");?>
  
