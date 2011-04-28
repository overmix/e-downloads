<?php include ("header.php");?>

<h2><?=output_msg($type = null);?></h2>

<fieldset>
    <legend><?=$titulo;?></legend>
    <ul>
    <?php foreach ($downloads as $product): ?>
        <li>
            <?php 
            echo anchor('downloads/get/'.$product['id_produto'],
            sprintf("<img src='%s' alt='%s' />",getThumbUrlById($product['id_produto']), $product['image']),
            array('title'=>$product['nome'],'rel'=>"prettyPhoto"));
            ?>                                
            <p>Nome: <?=$product['nome']?></p>
            <p>Descrição: <?=getDescription($product['id_produto'])?></p>
            <p><?php echo anchor('downloads/get/'.$product['id_produto'], 'Download', array('title'=>'Efetuar download'));?></p>
        </li>
    <?php endforeach;?>
    </ul>
</fieldset>
<?php include ("footer.php");?>
  
