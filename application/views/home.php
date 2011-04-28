<?php include ("header.php");?>

<fieldset>
    <legend>Todos os downloads</legend>
    <?php foreach($products as $product) { ?>
        <h2><?=$product['nome'];?></h2>
        <?php 
            echo anchor(
                'produto/index/'.$product['id_produto'],
                sprintf("<img src='%s' alt='Imagem' title='%s' />",getThumbUrlById($product['id_produto']),$product['nome']),
                array('title'=>'Lista de downloads')
           );
        ?>        
        <p><?=$product['preco']?></p>
        <p><?=getDescription($product['id_produto'], 40)?></p>
        <p><?php echo anchor('produto/index/' . $product['id_produto'], 'detalhes', array('title'=>'Lista de downloads'));?></p>
    <?php } ?>
</fieldset>

<script type="text/javascript" language="javascript" charset="utf-8">
<?//=$this->lightbox->start('products');?>
</script>

<?php include ("footer.php"); ?>


