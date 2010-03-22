$(function(){
    $('#form_obj1').submit(function(){
        if(!$('#imagens-1').find('input[class=imgitem]').is(":checked"))
        {
            alert("Selecione pelo menos uma imagem/vídeo para aprovar.");
            return false;
        }
    });

    $('#chkallrecents').change(function() {
        $('.listimg1').find('input[type=checkbox]').each(function(e){
            $(this).attr('checked', $('#chkallrecents').is(':checked'));
        })
    })

    $('.imgitem').change(function() {
        var todosMarcados = true;
        $('#imagens-1').find('input[class=imgitem]').each(function(e){
            if(!$(this).is(':checked')) todosMarcados = false
        })
        $('#chkallrecents').attr('checked',todosMarcados);
    })

    // ------------------------------------------------------------------
    $('#form_obj2').submit(function(){
        if(!$('#lembrates-1').find('input[class=emailitem]').is(":checked"))
        {
            alert("Selecione pelo menos um email para imprimir.");
            return false;
        }
    });

    $('#chkallemails').change(function() {
        $('.listemail').find('input[type=checkbox]').each(function(e){
            $(this).attr('checked', $('#chkallemails').is(':checked'));
        })
    })

    $('.emailitem').change(function() {
        var todosMarcados = true;
        $('#lembrates-1').find('input[class=emailitem]').each(function(e){
            if(!$(this).is(':checked')) todosMarcados = false
        })
        $('#chkallemails').attr('checked',todosMarcados);
    })
    // ------------------------------------------------------------------

    $('#chkallaproved').change(function() {
        $('.listimg2').find('input[type=checkbox]').each(function(e){
            $(this).attr('checked', $('#chkallaproved').is(':checked'));
        })
    })

    $('#upload_enviar').click(function(){
        $('#enviando').show();
    })

	$('.remover').click(function(){
		if(!confirm("Comfirma a exclusão deste item?")) {
            return false;
        }
	});

    // Limpa todos os dados do formulário
	$('#resetar').click(function(){
		$('#form_obj').find('input, textarea').each(function(e){
			$(this).val('')
		})
		return false;
	});

    jQuery('#lembrete').focus(function(){
        $(this).val('');
    });
    jQuery('#lembrete').blur(function(){
        if(!$(this).val()) $(this).val('e-mail');
    });
    jQuery('.edit').click(function(){
        if($(this).attr('class') == 'edit'){
            $(this).parent().find('.txt').hide();
            $(this).parent().find('.obj').show();
            $(this).attr('class', 'canceledit');
        }else{
            $(this).parent().find('.obj').hide();
            $(this).parent().find('.txt').show();
            $(this).parent().find('input').val($(this).parent().find('.txt').html())
            $(this).attr('class', 'edit')
        }
        return false;
    });

    jQuery('#pr_foto').click(function(){
        var r = ($('#profile_img a[@rel*=lightbox]').length < 3);
        if (!r) {
            alert("O limite máximo é de 3 fotos");
        }
        return r;
    });

    jQuery('#pr_video').click(function(){
        var r = ($('#profile_vid a[@rel*=vidbox]').length < 1);
        if (!r) {
            alert("O limite máximo é de apenas 1 vídeo");
        }
        return r;
    });

    jQuery('#up_foto').click(function(){
        return verificaUpload("upload/index/1");
		$('#youtub').hide();
    });

    jQuery('#up_video').click(function(){
        return verificaUpload("upload/index/2");
		$('#youtub').show();
    });
/*
    jQuery('.rating').rating({
        showCancel: false
        }).change(function(){
            var r = prompt('Digite um email válido.', '');
            if(r!=null){
                if(!vEmail(r)){
                    alert('Digite um email válido!');
                    refaz_rating(this);
                }else{
                    var url = $(this).parents('form').attr('action');
                    var id = $(this).parents('form').attr('alt');
                    jQuery.post(url+'envia_email', {email: r, voto: $(this).val(), img_id: id}, function(data){
                        if(data){
                            alert('Um email está sendo enviado para o endereço informado,\nacesse-o e clique no link de confirmação de voto.\nObrigado!')
                        }
                        location = url;
                    });
                }
            }else{
                refaz_rating(this)
            }
        });
       // $('button').hide();
    
    jQuery('.link_votar').change(function(){
        var r = prompt('Digite um email válido.', '');
        if(r!=null){
            if(!vEmail(r)){
                alert('Digite um email válido!');
            }else{
                jQuery.post($().this_url()+"envia_email", { email: r, votos: this.value, pontos: this });
                alert('Um email está sendo enviado para o endereço informado,\nacesse-o e clique no link de confirmação de voto.\nObrigado!')
            }
        }
        return false;
    });
    */
});

/**
* Refaz o rating e desabilita caso necessário
* @params object obj Objeto rating clicado
* @params bool disable True para desabilitar o objeto rating, False ou undefined para deixar habilitado
* @return void
*/
function refaz_rating (obj)
{
    $(obj).next().remove();
    $(obj).show().rating({showCancel:false});
}

/**
* Verifica se digitou um email válido
* @params string t Email de destino
*/
function vEmail(t){
    return t.match(/^\w[\w\.\+-]+@\w[\w\.\+-]+\.\w\w+$/);
}


/**
 * veriricaUpload
 */
function verificaUpload(href) {
    jQuery.post(href);
    return false;
}

function mudatipo(url) {
    window.location = url;
}

$(function(){
    $('.vTel').keypress(function(e){
        if(e.charCode!=0) mascaraTel(this)
    });
});

function mascaraTel(objeto){
    if(objeto.value.length == 0)
        objeto.value = '(' + objeto.value;

    if(objeto.value.length == 3)
        objeto.value = objeto.value + ')';

    if(objeto.value.length == 8)
        objeto.value = objeto.value + '-';
}


jQuery.fn.extend({
    this_url: function() {
        var doc_location = document.location.href;
        var url_strip = new RegExp("http:\/\/.*[^index]\/");
        var base_url = url_strip.exec(doc_location);
        return base_url;
    }
});

/*
$(document).ready(function(){	
	$("#slider").easySlider({
		auto: true, 
		continuous: true,
		controlsShow:false,
		speed:1000,
		pause:4000
	});
});
*/