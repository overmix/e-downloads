<?php include ("header.php");?>
<?php echo $titulo;?>


<div id="galeria-home" class="bg">
    <h3>Todos os downloads</h3>

    <div class="products">
        <?php foreach($products as $product) { ?>
        <h2><?=$product['nome'];?></h2>
            <a href="<?php echo base_url() . 'produto/index/' . $product['id_produto'];?>" rel="prettyPhoto[galeria]">
                <img src="<?=getThumbUrlById($product['id_produto']);?>" alt="foto 1" title="<?=$product['nome']?>" /></a>
            <p><?=$product['preco']?></p>
            <p><?=getDescription($product['id_produto'], 40)?></p>
            <p><?php echo anchor('produto/index/' . $product['id_produto'], 'detalhes', array('title'=>'Lista de downloads'));?></p>
        <?php } ?>
    </div>

    

</div>

<script type="text/javascript" language="javascript" charset="utf-8">
<?//=$this->lightbox->start('products');?>
</script>

<?php include ("footer.php"); ?>