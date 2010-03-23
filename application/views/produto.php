<?php include ("header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>

<div id="produto">
    <a href="<?=getProductUrlById($product['id_produto']);?>" rel="prettyPhoto">
        <img src="<?=getThumbUrlById($product['id_produto']);?>" alt="foto 1" title="<?=$product['nome']?>" /></a>
    <p>Nome: <?=$product['nome']?></p>
    <p>Preço: <?=$product['preco']?></p>
    <p>Descrição: <?=getDescription($product['id_produto'])?></p>
    <p><?php echo anchor('pagamento/index/'.$product['id_produto'], 'Comprar', array('title'=>'Efetuar pagamento'));?>
     </p>
</div>

<?php include ("footer.php");?>
  
