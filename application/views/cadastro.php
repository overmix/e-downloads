<?php include ("header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>
<div id="contato" class="bg">
    <?=form_open("cadastro/novo", array('id' => 'form_obj'));?>
        <fieldset>
            <legend>Cadastro</legend>
            
            <label for="nome"><span>Nome completo</span><input type="text" value="<?=$this->validation->nome;?>" name="nome" id="nome" />
            <?=$this->validation->nome_error; ?></label>

            <label><span>Telefone</span><input type="text" value="<?=$this->validation->telefone;?>" name="telefone" class="vTel" /><small>Só números: DDD+Telefone</small>
            </label>

            <label for="email"><span>Email</span><input type="text" value="<?=$this->validation->email;?>" name="email" id="email" /> 
            <?=$this->validation->email_error; ?></label>
            <label for="senha"><span>Senha</span><input type="password" value="<?=$this->validation->senha;?>" name="senha" id="senha" /> 
            <?=$this->validation->senha_error; ?></label>
            <label for="senha2"><span>Confirme a senha</span><input type="password" value="" name="senha2" id="senha2" /> 
            <?=$this->validation->senha2_error; ?></label>

            <div class="botoes">
                <button type="submit">Cadastrar</button>
 				<a href="home" style="display:block; float:left; padding:10px;">Cancelar e voltar para Home</a>
                <?//=anchor("/", "Limpar", array('class'=>'btn', 'id'=>'resetar'));?>
            </div>
            
        </fieldset>
    </form>
 </div>
<?php include ("footer.php");?>
  
