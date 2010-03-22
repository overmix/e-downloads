<?php include ("admin-header.php");?>

<h1><?=$titulo;?></h1>

<div id="tabs">
    <ul>
        <li><a href="#imagens-1"><span>Downloads</span></a></li>
    </ul>
    <div id="imagens-1" class="gallery">
        <?=form_open("admin/aprovar", array('id' => 'form_obj1'));?>
        <input type="submit" id="aprovartodos" value="Aprovar todos os selecionados" />
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="chkallrecents" id="chkallrecents" /></th><th>Imagem</th><th>Título</th>
                    <th>Atualizado em</th><th>Descrição</th><th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($downloads as $item): ?>
                <tr class="listimg1">
                    <td><input type="checkbox" name="edit[]" class="imgitem" value="<?=$item['id_produto'];?>" /></td>
                    <td>
                        <a href="<?=getMediaUrlById($item['id_produto']);?>" rel="prettyPhoto[downloads]">
                            <img src="<?=getThumbUrlById($item['id_produto']);?>" alt="<?=$item['image'];?>" />
                        </a>
                    </td>
                    <td><?=$item['nome'] ?></td>
                    <td><?=$item['image'] ?></td>
                    <td style='text-align: center'><?=$item['media_category'] ?></td>
                    <td style='text-align: center'><?=formataData("d/m/Y H:i",$item['atualizado']); ?></td>
                    <td><a href="admin/aprovar/<?=$item['id_produto'];?>" class="aprovar">Aprovar</a></td>
                    <td><a href="admin/remover/<?=$item['id_produto'];?>" class="remover">Remover</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" value="Aprovar todos os selecionados" />
        </form>
    </div>
    <?php /*
    <div id="lembrates-2" class="gallery">
        <?=form_open("admin/make_xls", array('id' => 'form_obj2'));?>
        <input type="submit" id="imprimirtodos" value="Gerar tabela com todos os selecionados" />
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="chkallemails" id="chkallemails" /></th>
                    <th>Lista de emails</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($emails_naoenviados as $item):
                $status = $item['status']==0?'Não enviado':'Enviado';
                ?>
                <tr class="listemail">
                    <td><input type="checkbox" name="edit[]" class="emailitem" value="<?=$item['lembrete_id'];?>" /></td>
                    <td><?=$item['email'];?></td>
                    <td><?=$status;?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <input type="submit" id="imprimirtodos" value="Gerar tabela com todos os selecionados" />
    </div>
    */ ?>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
<?=$this->lightbox->start('gallery');?>
</script>
<?php include ("admin-footer.php");?>