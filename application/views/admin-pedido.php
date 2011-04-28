<?php include ("admin-header.php");?>

<h1><?=$titulo;?></h1>

<h2><?=output_msg($type = null);?></h2>

<div id="pedido">
    <?php echo anchor(getProductUrlById($pedido['id_produto']),
    sprintf("<img src='%s' alt='%s' />",getThumbUrlById($pedido['id_produto']), $pedido['nome']),
    array('title'=>$pedido['nome']));?>    
    
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
					<?php echo anchor("admin/liberarpedido/".$pedido['id_pedido'],"Liberar agora", array('title'=>'Liberar agora'))?>
					<?php endif ;?>
					</li>
                <li>Downloads efetuados:
                    <strong class="txt"><?=$this->validation->downloads;?></strong></li>
                <li>
                    Usar até:
                    <strong class="obj" style="display:none"><input type="text" name="usar_ate" value="<?=$this->validation->usar_ate;?>" /></strong>
                    <strong class="txt"><?=$this->validation->usar_ate;?></strong>
                    <?php echo anchor("#","editar",array('title'=>'Editar','class'=>'edit'))?><?=$this->validation->usar_ate_error; ?>
                </li>
                <li>
                    Limite de downloads:
                    <strong class="obj" style="display:none"><input type="text" name="limite" value="<?=$this->validation->limite;?>" /></strong>
                    <strong class="txt"><?=$this->validation->limite;?></strong>
                    <?php echo anchor("#","editar",array('title'=>'Editar','class'=>'edit'))?><?=$this->validation->limite_error; ?>
                </li>
            </ul>
        </fieldset>
        <div class="botoes">
            <?php echo anchor('admin','Voltar para a administração',array('title'=>'Voltar para a administração','class'=>'btn'));?>
            <button type="submit" class="btn">Salvar alterações</button>
        </div>
    </form>
</div>

<?php include ("admin-footer.php");?>
  
