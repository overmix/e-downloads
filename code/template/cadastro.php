<?php include 'header.php';?>

<h1><?php echo $titulo;?></h1>

<?php the_msg($msg = null);?>

<form action="<?php echo BASE_URL . 'cadastro' ;?>" method="POST">
    <fieldset>
        <legend>Dados do usuário</legend>
        <label>
            <span>Nome</span>
            <input type="text" name="nome" value="" />
        </label>
        <label>
            <span>Email</span>
            <input type="text" name="email" />
        </label>
        <label>
            <span>Login</span>
            <input type="text" name="login" />
        </label>
        <label>
            <span>Senha</span>
            <input type="text" name="senha" />
        </label>
        <label>
            <span>Confirmação</span>
            <input type="text" name="senha2" />
        </label>
        <button type="submit">Cadastrar!</button>
    </fieldset>
</form>

<?php include 'footer.php';?>