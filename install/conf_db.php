<?php include('header.php') ;?>

<h1><?php echo $title;?></h1>

<?php if(isset($error)) {
    echo '<p>'. $error . '</p>';
} ;?>

<div id="conf_db">
    <form method="post" action="index.php?passo=2">
    <fieldset>
        <legend>Dados de conexão</legend>
        <p>Abaixo você deve preencher os detalhes de conexão do seu Banco de Dados. Se você não tem certeza, entre em contato com seu provedor.</p>

        <label>Email do administrador:
            <input type="text" name="useremail" title="Email do administrador" /></label>
        <label>Nome do banco de dados:
            <input type="text" name="dbname" title="Nome do banco de dados" /></label>
        <label>Nome do usuário:
            <input type="text" name="dbuser" title="Nome do usuário do banco de dados" /></label>
        <label>Senha:
            <input type="text" name="dbpass" title="Senha do banco de dados" /></label>
        <label>Servidor do banco de dados: 
            <input type="text" name="dbserver" title="Url do servidor do banco de dados" /></label>
        <input type="submit" value="Enviar"  />
    </fieldset>
    </form>
</div>

<?php include('footer.php');?>