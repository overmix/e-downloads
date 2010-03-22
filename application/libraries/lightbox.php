<?php
/**
 * Description of lightbox
 *
 * @author ldmotta
 */
class LightBox {

    function start($target='simpleModal') {
        $output = "
		$(function(){
			$('.$target a[rel*=prettyPhoto]').prettyPhoto({
				animationSpeed: 'normal',       /* fast/slow/normal */
				padding: 40,                    /* padding for each side of the picture */
				opacity: 0.7,                   /* Value betwee 0 and 1 */
				showTitle: true,                /* true/false */
				allowresize: true,              /* true/false */
				counter_separator_label: '/',   /* The separator for the gallery counter 1 'of' 2 */
				theme: 'dark_rounded',           /* light_rounded / dark_rounded / light_square / dark_square */
				hideflash: true,                /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
				modal: false,                   /* If set to true, only the close button will close the window */
				changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
				callback: function(){}          /* Called when prettyPhoto is closed */
			});
		});";
        return $output;
    }

}
?>
