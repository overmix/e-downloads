<form action="<?php echo BASE_URL ?>/cadastro" method="POST">
    <fieldset>
        <legend>Dados do usuário</legend>
        <label>
            <span>Nome</span>
            <input type="text" name="nome" />
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

