<?php
session_start();
$_SESSION['posted']=0;
?><!DOCTYPE HTML>
<html lang="pt-BR">
    <head>
        <title><?php echo $page_title;?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="<?=base_url();?>skin/css/style.css" media="screen" charset="utf-8" />
        <script type="text/javascript" src="<?=base_url();?>skin/js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?=base_url();?>skin/js/functions.js"></script>

        <script type="text/javascript" src="<?=base_url();?>skin/js/jquerycorner.js"></script>
        
        <script type="text/javascript" charset="utf-8" src="<?=base_url();?>skin/js/jquery.prettyPhoto.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" charset="utf-8" href="<?=base_url();?>skin/css/prettyPhoto.css" />
        
        <script type="text/javascript">
        $(document).ready(function() {
            $(".borderradius").corner('5px');
        });
        </script>
    </head>
    <body>
        <?php if(logged()):?>
            <ul>
                <li>Olá <?php echo anchor('profile', logged(TRUE)->nome, array('title'=>'Editar dados do perfil')) ?>, seja bem-vindo!</li>
            </ul>
        <?php endif;?>
        <ul>
            <li><?php echo anchor('home', 'Home', array('title'=>'Home'));?></li>
            <?php if(logged()):?>
            <li><?php echo anchor('downloads', 'Meus downloads', array('title'=>'Meus downloads'));?></li>
            <li><?php echo anchor('profile', 'Perfil', array('title'=>'Visualizar meu perfil'));?></li>
            <?php else: ?>
            <li><?php echo anchor('cadastro', 'Cadastre-se', array('title'=>'Cadastre-se'));?></li>
			<?php endif;?>
            <li><?php echo anchor('inicio', 'Login', array('title'=>'Login')); ?></li>
            <li><?php echo anchor('inicio/sair', 'Sair', array('title'=>'Sair do sistema'));?></li>
        </ul>

