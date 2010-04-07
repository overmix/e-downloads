<?php include('header.php') ;?>

<h1><?php echo $title;?></h1>

<div>
    <p><?php echo $title_msg;?></p>
    <p>Você pode criar o config.php manualmente e colar o seguinte texto nele.<br />
        O arquivo config.php deve ficar dentro da pasta 'application/config/' da sua aplicação.</p>
    <textarea cols="80" rows="14"><?php echo $content_config;?></textarea>
    <p>Depois que você fizer isso clique em "Continuar."</p>
    <a href="index.php?passo=3">Continuar</a>
</div>

<?php include('footer.php');?>