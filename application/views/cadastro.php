<?php include ("header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>

<?=form_open("cadastro/novo", array('id' => 'form_obj'));?>
    <fieldset>
        <legend>Cadastro</legend>

        <label>Nome completo <input type="text" value="<?=$this->validation->nome;?>" name="nome" />
        <?=$this->validation->nome_error; ?></label>

        <label>Telefone <span>Só números: DDD+Telefone</span><input type="text" value="<?=$this->validation->telefone;?>" name="telefone" class="vTel" />
        <?=$this->validation->telefone_error; ?></label>

        <label>Email <input type="text" value="<?=$this->validation->email;?>" name="email" id="email" />
        <?=$this->validation->email_error; ?></label>

        <label>Senha <input type="password" value="<?=$this->validation->senha;?>" name="senha" />
        <?=$this->validation->senha_error; ?></label>

        <label>Confirme a senha <input type="password" value="" name="senha2" />
        <?=$this->validation->senha2_error; ?></label>

        <?=anchor(base_url() . "home", "Voltar para a home", array('class'=>'btn'));?>
        <button type="submit">Cadastrar</button>
        <?=anchor("/", "Limpar", array('class'=>'btn', 'id'=>'resetar'));?>
    </fieldset>
</form>

<?php include ("footer.php");?>
  
