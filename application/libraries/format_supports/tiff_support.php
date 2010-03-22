<?php

// Implementa uma função que converte arquivos tiff em jpeg

function imagecreatefromtiff($tiff_file)
{
	$info = pathinfo($tiff_file);
	$filename = $info['filename'];
	$base_dir = $info['dirname']."/";
	
	$jpeg_file = $base_dir.$filename.".jpg";
	
	// comando para conversão de tiff para jpeg
	// necessita do imagemagick num servidor unix
	$command = "convert $tiff_file $jpeg_file";

	// executa o comando
	exec($command, $result);
	
	return imagecreatefromjpeg($jpeg_file);
}

?>