<?php include('header.php') ;?>

<h1><?php echo $title;?></h1>

<div>
    <p>Obrigado por adquirir e instalar o nosso sistema de downloads.</p>
    <p>Abaixo estão os dados do administrador, copie a senha gerada e não se
    esqueça de troca-la logo após efetuar o login.</p>
    <ul>
        <li>Email: <?php echo $email;?></li>
        <li>Senha: <?php echo $senha;?></li>
    </ul>
    <p><a href="<?php echo $redirect ?>">Clique aqui para começar a utilizar a aplicação</a></p>
</div>

<?php include('footer.php');?>
