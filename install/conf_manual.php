<?php include('header.php') ;?>

<h1><?php echo $title;?></h1>

<div>
    <p><?php echo $title_msg;?></p>
    
    <div class="error message"><?php echo $config_msg; ?></div>
    
    <textarea cols="80" rows="14"><?php echo $content_config;?></textarea>
    <p>Depois que você fizer isso clique em "Continuar."</p>
    <a href="<?php echo $redirect; ?>">Continuar</a>
</div>

<?php include('footer.php');?>
