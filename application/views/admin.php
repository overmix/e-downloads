<?php include ("admin-header.php");?>

<h1><?=$titulo;?></h1>

<div id="tabs">
    <ul>
        <li><a href="#painel-1"><span>Pedidos</span></a></li>
        <li><a href="#painel-2"><span>Produtos Ativos</span></a></li>
        <li><a href="#painel-3"><span>Produtos Inativos</span></a></li>
        <li><a href="#painel-4"><span>Usuários</span></a></li>
        
    </ul>
    <div id="painel-1" class="gallery">
        <?=form_open("admin/liberar", array('id' => 'form_obj1'));?>
        <input type="submit" id="aprovartodos" value="Liberar todos os selecionados" />
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="chkallrecents" id="chkallrecents" /></th>
                    <th>N° Pedido</th><th>Usuário</th><th>Pedido em</th><th>Liberado em</th>
                    <th>Usar até</th><th>Baixados</th><th>Limite</th><th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pedidos as $pedido): ?>
                <tr class="listimg1">
                    <td><input type="checkbox" name="edit[]" class="imgitem" value="<?=$pedido['id_pedido'];?>" /></td>
                    <td><a href="<?php echo base_url();?>pedido/index/<?php echo $pedido['id_pedido'];?>" title="Visualizar pedido"><?php echo $pedido['id_pedido'];?></a></td>
                    <td><a href="<?php echo base_url();?>usuario/index/<?php echo $pedido['id_usuario'];?>" title="Email: <?php echo $pedido['email'];?> - Telefone: <?php echo $pedido['telefone'];?>"><?php echo $pedido['nome'];?></a></td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$pedido['pedido_em']); ?></td>
                    <td style='text-align: center'><?=is_date($pedido['liberado_em']) ? formataData('d/m/Y H:i', $pedido['liberado_em']) : '-' ;?></td>
                    <td style='text-align: center'><?=is_date($pedido['usar_ate']) ? formataData("d/m/Y", $pedido['usar_ate']) : 'Ilimitado' ;?></td>
                    <td><?php echo $pedido['downloads'];?></td>
                    <td><?php echo $pedido['limite'];?></td>
                    <td><a href="<?php echo base_url();?>admin/liberarpedido/<?=$pedido['id_pedido'];?>" class="liberar">Liberar</a></td>
                    <td><a href="<?php echo base_url();?>admin/removerpedido/<?=$pedido['id_pedido'];?>" class="remover">Remover</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" value="Aprovar todos os selecionados" />
        </form>
    </div>
    <div id="painel-2" class="gallery">
        <?=form_open("admin/desativar", array('id' => 'form_obj1'));?>
        <input type="submit" id="aprovartodos" value="Desativar todos os selecionados" />
        <?php echo anchor('produto/novo', 'Adicionar novo produto', array('title'=> 'Adicionar novo produto'));?>
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="chkallrecents" id="chkallrecents" /></th><th>Imagem</th><th>Título</th>
                    <th>Atualizado em</th><th>Descrição</th><th colspan="3">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produtos_ativos as $product): ?>
                <tr class="listimg1">
                    <td><input type="checkbox" name="edit[]" class="imgitem" value="<?=$product['id_produto'];?>" /></td>
                    <td>
                        <a href="<?php echo base_url();?>produto/editar/<?php echo $product['id_produto'];?>">
                            <img src="<?=getThumbUrlById($product['id_produto']);?>" alt="<?=$product['image'];?>" />
                        </a>
                    </td>
                    <td style='text-align: center'><?=$product['nome']; ?></td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$product['atualizado']); ?></td>
                    <td><?=$product['descricao'] ?></td>
                    <td><a href="<?php echo base_url();?>produto/editar/<?php echo $product['id_produto'];?>">Editar</a></td>
                    <td><a href="admin/desativarproduto/<?=$product['id_produto'];?>" class="desativar">Desativar</a></td>
                    <td><a href="admin/removerproduto/<?=$product['id_produto'];?>" class="remover">Remover</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" value="Aprovar todos os selecionados" />
        <?php echo anchor('produto/novo', 'Adicionar novo produto', array('title'=> 'Adicionar novo produto'));?>
        </form>
    </div>
    <div id="painel-3" class="gallery">
        <?=form_open("admin/reativar", array('id' => 'form_obj1'));?>
        <input type="submit" id="aprovartodos" value="Re-ativar todos os selecionados" />
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="chkallrecents" id="chkallrecents" /></th><th>Imagem</th><th>Título</th>
                    <th>Atualizado em</th><th>Descrição</th><th colspan="3">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produtos_inativos as $product): ?>
                <tr class="listimg1">
                    <td><input type="checkbox" name="edit[]" class="imgitem" value="<?=$product['id_produto'];?>" /></td>
                    <td>
                        <a href="<?=getThumbUrlById($product['id_produto']);?>">
                            <img src="<?=getThumbUrlById($product['id_produto']);?>" alt="<?=$product['image'];?>" />
                        </a>
                    </td>
                    <td style='text-align: center'><?=$product['nome']; ?></td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$product['atualizado']); ?></td>
                    <td><?=$product['descricao'] ?></td>
                    <td><a href="<?php echo base_url();?>produto/editar/<?php echo $product['id_produto'];?>">Editar</a></td>
                    <td><a href="admin/reativarproduto/<?=$product['id_produto'];?>" class="reativar">Ativar</a></td>
                    <td><a href="admin/removerproduto/<?=$product['id_produto'];?>" class="remover">Remover</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" value="Aprovar todos os selecionados" />
        </form>
    </div>
    <div id="painel-4" class="gallery">
        <table>
            <thead>
                <tr>
                    <th>Nome</th><th>Email</th><th>Telefone</th><th>Cadastrado em</th><th>Status</th><th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($usuarios as $usuario): ?>
                <tr class="listimg1">
                    <td><a href="<?php echo base_url();?>usuario/index/<?php echo $usuario['id_usuario'];?>" title="Email: <?php echo $usuario['email'];?> - Telefone: <?php echo $usuario['telefone'];?>"><?php echo $usuario['nome'];?></a></td>
                    <td><?=$usuario['email'] ?></td>
                    <td><?=$usuario['telefone'] ?></td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$usuario['cadastrado_em']); ?></td>
                    <td><?=$usuario['status'] ?></td>
                    <?php if($usuario['status']=='Ativo'):?>
                    <td><a href="admin/bloquearuser/<?=$usuario['id_usuario'];?>" class="bloquear">Bloquear</a></td>
                    <?php else:?>
                    <td><a href="admin/ativaruser/<?=$usuario['id_usuario'];?>" class="ativar">Ativar</a></td>
                    <?php endif;?>
                    <td><a href="admin/removeruser/<?=$usuario['id_usuario'];?>" class="remover">Remover</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include ("admin-footer.php");?>