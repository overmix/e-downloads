<?php include ("header.php");?>

<h2><?=output_msg($type = null);?></h2>

<fieldset>
    <legend><?=$titulo;?></legend>
    <ul>
    <?php foreach ($downloads as $product): ?>
        <li>
            <a href="<?=base_url().'downloads/get/'.$product['id_produto'];?>" rel="prettyPhoto">
                <img src="<?=getThumbUrlById($product['id_produto']);?>" alt="foto 1" title="<?=$product['nome']?>" /></a>
            <p>Nome: <?=$product['nome']?></p>
            <p>Descrição: <?=getDescription($product['id_produto'])?></p>
            <p><?php echo anchor('downloads/get/'.$product['id_produto'], 'Download', array('title'=>'Efetuar download'));?></p>
        </li>
    <?php endforeach;?>
    </ul>
</fieldset>
<?php include ("footer.php");?>
  
