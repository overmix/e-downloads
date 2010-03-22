<?php

// Implementa uma função que converte arquivos pdf em jpeg

function imagecreatefrompdf($pdf_file)
{
	$info = pathinfo($pdf_file);
	$filename = $info['filename'];
	$base_dir = $info['dirname']."/";
	
	$jpeg_file = $base_dir.$filename.".jpg";
	
	// comando para conversão de pdf para jpeg
	// necessita do imagemagick num servidor unix
	$command = "convert $pdf_file $jpeg_file";

	// executa o comando
	exec($command, $result);
	
	return imagecreatefromjpeg($jpeg_file);
}

?>