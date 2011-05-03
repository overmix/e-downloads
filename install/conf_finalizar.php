<?php include('header.php') ;?>

<h1><?php echo $title;?></h1>

<div>
    <p>Obrigado por adquirir e instalar o nosso sistema de downloads.</p>
    <p>Abaixo estão os dados de acesso a área administrativa, copie a senha gerada automaticamente, e não se
    esqueça de troca-la logo após efetuar o login.</p>
    <div class='done message'>
    <ul>
        <li>Email: <?php echo $email;?></li>
        <li>Senha: <?php echo $senha;?></li>
    </ul>
    </div>
    <p>Após efetuar login, recomendamos enfaticamente que você exclua a pasta de instalação 
    (install) do diretório da sua aplicação.</p>
    <p><a href="<?php echo $redirect ?>">Clique aqui para começar a utilizar a aplicação</a></p>
</div>

<?php include('footer.php');?>
