<?php include ("header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>

<ul>
<?php foreach ($downloads as $product): ?>
    <li>
        <a href="<?=getProductUrlById($product['id_produto']);?>" rel="prettyPhoto">
            <img src="<?=getThumbUrlById($product['id_produto']);?>" alt="foto 1" title="<?=$product['nome']?>" /></a>
        <p>Nome: <?=$product['nome']?></p>
        <p>Descrição: <?=getDescription($product['id_produto'])?></p>
        <p><?php echo anchor('download/get', 'Download', array('title'=>'Efetuar download'));?></p>
    </li>
<?php endforeach;?>
</ul>
<?php include ("footer.php");?>
  
