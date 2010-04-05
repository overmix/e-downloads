<?php include ("admin-header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>

<div id="pedido">
    <a href="<?=getProductUrlById($pedido['id_produto']);?>">
        <img src="<?=getThumbUrlById($pedido['id_produto']);?>" alt="foto 1" title="<?=$pedido['nome']?>" /></a>
    <p>Nome: <?=$pedido['nome']?></p>
    <p>Preço: <?=$pedido['preco']?></p>
    <p>Descrição: <?=getDescription($pedido['id_produto'])?></p>

        <?=form_open("pedido/editar", array('id' => 'form_obj'));?>
    <fieldset>
        <legend>Editar dados do pedido</legend>

        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido'];?>" />
            <ul>
                <li>Pedido em:
                    <strong class="txt"><?=$this->validation->pedido_em;?></strong></li>
                <li>Liberado em:
                    <strong class="txt"><?=$this->validation->liberado_em;?></strong>
					<?php if(!is_date($this->validation->liberado_em)): ?>
					<a href="<?php echo base_url();?>admin/liberarpedido/<?php echo $pedido['id_pedido'];?>" title=" ">Liberar agora</a>
					<?php endif ;?>
					</li>
                <li>Downloads efetuados:
                    <strong class="txt"><?=$this->validation->downloads;?></strong></li>
                <li>
                    Usar até:
                    <strong class="obj" style="display:none"><input type="text" name="usar_ate" value="<?=$this->validation->usar_ate;?>" /></strong>
                    <strong class="txt"><?=$this->validation->usar_ate;?></strong>
                    <a href="#" title=" " class="edit">editar</a><?=$this->validation->usar_ate_error; ?>
                </li>
                <li>
                    Limite de downloads:
                    <strong class="obj" style="display:none"><input type="text" name="limite" value="<?=$this->validation->limite;?>" /></strong>
                    <strong class="txt"><?=$this->validation->limite;?></strong>
                    <a href="#" title=" " class="edit">editar</a><?=$this->validation->limite_error; ?>
                </li>
            </ul>
        </fieldset>
        <div class="botoes">
            <?=anchor(base_url() . "admin", "Voltar para a administração", array('class'=>'btn'));?>
            <button type="submit" class="btn">Salvar alterações</button>
        </div>
    </form>
</div>

<?php include ("admin-footer.php");?>
  
