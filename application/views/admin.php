<?php include ("admin-header.php");?>

<h1><?=$titulo;?></h1>
<h2><?=output_msg($type = null);?></h2>
<div id="tabs">
    <ul>
        <li><a href="#painel-1"><span>Pedidos</span></a></li>
        <li><a href="#painel-2"><span>Produtos Ativos</span></a></li>
        <li><a href="#painel-3"><span>Produtos Inativos</span></a></li>
        <li><a href="#painel-4"><span>Usuários</span></a></li>
        
    </ul>
    <div id="painel-1" class="gallery">
        <?=form_open("admin/liberarpedido", array('id' => 'form_obj1'));?>
        <input type="submit" id="aprovartodos" value="Liberar todos os selecionados" />
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="chkallrecents" id="chkallpedidos"
                               onchange="checarTodos(this, '.lista1')" /></th>
                    <th>N° Pedido</th><th>Usuário</th><th>Pedido em</th><th>Liberado em</th>
                    <th>Usar até</th><th>Baixados</th><th>Limite</th><th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pedidos as $pedido): ?>
                <tr class="lista1">
                    <td><input type="checkbox" name="edit[]" class="chkpedidos" value="<?=$pedido['id_pedido'];?>"
                               onchange="checarItem('chkpedidos','#painel-1','#chkallpedidos')" /></td>
                    <td>
                    <?php echo anchor('pedido/index/'.$pedido['id_pedido'],$pedido['id_pedido'],array('title'=>'Visualizar pedido'));?>                    
                    </td>
                    <td>
                    <?php echo anchor('usuario/index/'.$pedido['id_usuario'],$pedido['nome'],array('title'=>sprintf("Email: %s - Telefone: %s",$pedido['email'],$pedido['telefone'])));?>
                    </td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$pedido['pedido_em']); ?></td>
                    <td style='text-align: center'><?=is_date($pedido['liberado_em']) ? formataData('d/m/Y H:i', $pedido['liberado_em']) : '-' ;?></td>
                    <td style='text-align: center'><?=is_date($pedido['usar_ate']) ? formataData("d/m/Y", $pedido['usar_ate']) : 'Ilimitado' ;?></td>
                    <td><?php echo $pedido['downloads'];?></td>
                    <td><?php echo $pedido['limite'];?></td>
                    <td>
                    <?php echo anchor('admin/liberarpedido/'.$pedido['id_pedido'],'Liberar',array('title'=>'Liberar pedido', 'class'=>'liberar'));?>
                    </td>
                    <td>
                    <?php echo anchor('admin/removerpedido/'.$pedido['id_pedido'],'Remover',array('title'=>'Remover pedido', 'class'=>'remover'));?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" id="aprovartodos" value="Liberar todos os selecionados" />
        </form>
    </div>

    <div id="painel-2" class="gallery">
        <?=form_open("admin/desativarproduto", array('id' => 'form_obj2'));?>
        <input type="submit" id="aprovartodos" value="Desativar todos os selecionados" />
        <?php echo anchor('produto/novo', 'Adicionar novo produto', array('title'=> 'Adicionar novo produto'));?>
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="chkallrecents" id="chkallapproved"
                               onchange="checarTodos(this, '.lista2')" /></th><th>Imagem</th><th>Título</th>
                    <th>Atualizado em</th><th>Descrição</th><th colspan="3">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produtos_ativos as $product): ?>
                <tr class="lista2">
                    <td><input type="checkbox" name="edit[]" class="chkapproved" value="<?=$product['id_produto'];?>"
                               onchange="checarItem('chkapproved','#painel-2','#chkallapproved')" /></td>
                    <td>
                        <?php echo anchor('produto/editar/'.$product['id_produto'],
                        sprintf("<img src='%s' alt='%s' />",getThumbUrlById($product['id_produto']), $product['image']),
                        array('title'=>'Editar produto'));?>
                    </td>
                    <td style='text-align: center'><?=$product['nome']; ?></td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$product['atualizado']); ?></td>
                    <td><?=$product['descricao'] ?></td>
                    
                    <td><?php echo anchor('produto/editar/'.$product['id_produto'],'Editar',array('title'=>'Editar produto'));?></td>
                    <td><?php echo anchor('admin/desativarproduto/'.$product['id_produto'],'Desativar',array('title'=>'Desativar produto','class'=>'desativar'));?></td>
                    <td><?php echo anchor('admin/removerproduto/'.$product['id_produto'],'Remover',array('title'=>'Remover produto','class'=>'remover'));?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" id="aprovartodos" value="Desativar todos os selecionados" />
        <?php echo anchor('produto/novo', 'Adicionar novo produto', array('title'=> 'Adicionar novo produto'));?>
        </form>
    </div>

    <div id="painel-3" class="gallery">
        <?=form_open("admin/reativarproduto", array('id' => 'form_obj3'));?>
        <input type="submit" id="aprovartodos" value="Re-ativar todos os selecionados" />
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="chkallrecents" id="chkalldisabled"
                               onchange="checarTodos(this, '.lista3')" /></th><th>Imagem</th><th>Título</th>
                    <th>Atualizado em</th><th>Descrição</th><th colspan="3">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produtos_inativos as $product): ?>
                <tr class="lista3">
                    <td><input type="checkbox" name="edit[]" class="chkdisabled" value="<?=$product['id_produto'];?>"
                               onchange="checarItem('chkdisabled','#painel-3','#chkalldisabled')" /></td>
                    <td>
                        <?php echo anchor('produto/editar/'.$product['id_produto'],
                        sprintf("<img src='%s' alt='%s' />",getThumbUrlById($product['id_produto']), $product['image']),
                        array('title'=>'Editar produto'));?>
                    </td>
                    <td style='text-align: center'><?=$product['nome']; ?></td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$product['atualizado']); ?></td>
                    <td><?=$product['descricao'] ?></td>
                    
                    <td><?php echo anchor('produto/editar/'.$product['id_produto'],'Editar',array('title'=>'Editar produto'));?></td>
                    <td><?php echo anchor('admin/reativarproduto/'.$product['id_produto'],'Ativar',array('title'=>'Desativar produto','class'=>'desativar'));?></td>
                    <td><?php echo anchor('admin/removerproduto/'.$product['id_produto'],'Remover',array('title'=>'Remover produto','class'=>'remover'));?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" id="aprovartodos" value="Re-ativar todos os selecionados" />
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
                    <td>
                    <?php
                    echo anchor(
                        "usuario/index/".$usuario['id_usuario'],
                        $usuario['nome'],
                        array('title'=>sprintf("Email: %s - Telefone: %s",$usuario['email'],$usuario['telefone']))
                    ); 
                    ?>
                    </td>
                    <td><?=$usuario['email'] ?></td>
                    <td><?=$usuario['telefone'] ?></td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$usuario['cadastrado_em']); ?></td>
                    <td><?=$usuario['status'] ?></td>
                    <?php if($usuario['status']=='Ativo'):?>
                    <td>
                    <?php echo anchor('admin/bloquearuser/'.$usuario['id_usuario'],'Bloquear',array('title'=>'Bloquear usuário','class'=>'bloquear'));?>
                    </td>
                    <?php else:?>
                    <td>
                    <?php echo anchor('admin/ativaruser/'.$usuario['id_usuario'],'Ativar',array('title'=>'Ativar usuário','class'=>'ativar'));?>
                    </td>
                    <?php endif;?>
                    <td>
                    <?php echo anchor('admin/removeruser/'.$usuario['id_usuario'],'Remover',array('title'=>'Remover usuário','class'=>'remover'));?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include ("admin-footer.php");?>
