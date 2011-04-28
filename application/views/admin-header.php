<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="pt-BR">
    <head>
        <title><?=$page_title;?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="<?=base_url();?>skin/css/style.css" type="text/css" media="screen" charset="utf-8" />
        <link rel="stylesheet" href="<?=base_url();?>skin/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />

        <script type="text/javascript" src="<?=base_url();?>skin/js/jquery-1.3.2.min.js"></script>
        <link rel="stylesheet" href="<?=base_url();?>skin/css/ui.all.css" type="text/css" />
        <script type="text/javascript" src="<?=base_url();?>skin/js/ui.core.js"></script>
        <script type="text/javascript" src="<?=base_url();?>skin/js/ui.tabs.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#tabs").tabs();
                //Rezebraizim
                $("table tr:nth-child(even)").addClass("cor-sim");
            });
        </script>

        <script type="text/javascript" charset="utf-8" src="<?=base_url();?>skin/js/jquery.prettyPhoto.js"></script>
        <script type="text/javascript" charset="utf-8" src="<?=base_url();?>skin/js/functions.js"></script>
        <style type="text/css">
            .ui-tabs .ui-tabs-hide {
                display: none;
            }
            table thead tr {background-color: #CCCCCC;}
            .cor-sim {background-color:  #E6E6E6;}
            .gallery table {width: 100%}
            .usuarios table tr td {padding: 5px 5px 5px; text-align: center}
            .usuarios table {width: 100%}
        </style>

    </head>
    <body>
        <ul>
            <li><?php echo anchor('home', 'Ir para o site', array('title'=>'1º Concurso de foto e vídeo - Olhares sobre a água e o clima'));?></li>
            <li><?php echo anchor('admin', 'Gerenciar');?></li>
            <li><?php echo anchor('profile', 'Meu perfil', array('title'=>'Acesse seu perfil')); ?></li>
            <li><?php echo anchor('inicio/sair', 'Sair');?></li>
        </ul>

        <?=output_msg($type = null);?>

        <div id="container" class="center">
