<?php include ("header.php");?>

<h2><?=output_msg($type = null);?></h2>

<fieldset>
    <legend><?=$titulo;?></legend>
    <a href="<?=getProductUrlById($product['id_produto']);?>" rel="prettyPhoto">
        <img src="<?=getThumbUrlById($product['id_produto']);?>" alt="foto 1" title="<?=$product['nome']?>" /></a>
    <p>Nome: <?=$product['nome']?></p>
    <p>Preço: <?=$product['preco']?></p>
    <p>Descrição: <?=getDescription($product['id_produto'])?></p>
    <p><?php echo anchor('pagamento/index/'.$product['id_produto'], 'Comprar', array('title'=>'Efetuar pagamento'));?></p>
</fieldset>

<?php include ("footer.php");?>
  
