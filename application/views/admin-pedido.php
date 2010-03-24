<?php include ("admin-header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>

<div id="pedido">
    <a href="<?=getProductUrlById($pedido['id_produto']);?>">
        <img src="<?=getThumbUrlById($pedido['id_produto']);?>" alt="foto 1" title="<?=$pedido['nome']?>" /></a>
    <p>Nome: <?=$pedido['nome']?></p>
    <p>Preço: <?=$pedido['preco']?></p>
    <p>Descrição: <?=getDescription($pedido['id_produto'])?></p>

    <fieldset>
        <legend>Editar dados do pedido</legend>
        <?=form_open("pedido/editar", array('id' => 'form_obj'));?>
            <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido'];?>" />
            <ul>
                <li><span>Pedido em:</span>
                    <strong class="txt"><?=$this->validation->pedido_em;?></strong></li>
                <li><span>Liberado em:</span>
                    <strong class="txt"><?=$this->validation->liberado_em;?></strong></li>
                <li><span>Downloads efetuados:</span>
                    <strong class="txt"><?=$this->validation->downloads;?></strong></li>
                <li>
                    <span>Usar até:</span>
                    <strong class="obj" style="display:none"><input type="text" name="usar_ate" value="<?=$this->validation->usar_ate;?>" /></strong>
                    <strong class="txt"><?=$this->validation->usar_ate;?></strong>
                    <a href="#" title=" " class="edit">editar</a><?=$this->validation->usar_ate_error; ?>
                </li>
                <li>
                    <span>Limite de downloads:</span>
                    <strong class="obj" style="display:none"><input type="text" name="limite" value="<?=$this->validation->limite;?>" /></strong>
                    <strong class="txt"><?=$this->validation->limite;?></strong>
                    <a href="#" title=" " class="edit">editar</a><?=$this->validation->limite_error; ?>
                </li>
            </ul>
            <button type="submit" class="btn">Salvar alterações</button>
        </form>
    </fieldset>
    <p><?php echo anchor('admin/index', 'Voltar', array('title'=>'Voltar para a administração'));?>
     </p>
</div>

<?php include ("admin-footer.php");?>
  
