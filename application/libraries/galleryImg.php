<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  ?>
<?php
//------------------------------------------------------------------------------
//	m2brgallery.php
//	versão: 1.3 - PHP5
//	Autor: Guilherme Rambo (Baseada na classe m2brimagem de Davi Tavares Ferreira e Paulo Coutinho)
//	http://www.m2brnet.com
//  http://www.daviferreira.com
//  http://www.prsolucoes.com
//  http://www.screencaster.com.br
//	Última modificação: 13/10/2009 10:32
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// classe imagem

class galleryImg {

	// arquivos
	private $origem, $img;	
	// dimensões
	private $largura, $altura, $nova_largura, $nova_altura;
	// dados do arquivo
	private $extensao, $tamanho, $arquivo, $diretorio;
	// cor de fundo para preenchimento
	private $rgb;
	// mensagem de erro
	private $erro;
	
	// construtor
	public function __construct($origem='') {
		
		$this->origem			= $origem;
		$this->img				= '';
		$this->largura			= 0;
		$this->altura			= 0;
		$this->nova_largura		= 0;
		$this->nova_altura		= 0;
		$this->extensao			= '';
		$this->tamanho			= '';
		$this->arquivo			= '';
		$this->diretorio		= '';
		$this->rgb				= array(0, 0, 0);
		
		$this->dados();
		
	} // fim construtor
	
	public function __tostring()
	{
		return $this->origem;
	}
	
	public function getSizes()
	{
		return array($this->largura, $this->altura);
	}
	
	// retorna dados da imagem
	public function dados() {
		
		// mensagem padrão, sem erro
		$this->erro = 'OK';
		
		// verifica se imagem existe
		if (!is_file($this->origem)) {
	   		$this->erro = 'Erro: Arquivo de imagem não encontrado!';
		} else {
			// dados do arquivo
			$this->dadosArquivo();
			
			// verifica se é imagem
			if (!$this->eImagem()) {
				$this->erro = 'Erro: Arquivo '.$this->origem.' não é uma imagem!';
			} else {
				// pega dimensões
				$this->dimensoes();
				
				// cria imagem para php
				$this->criaImagem();			
			}
		}
		
		return true;
	} // fim dados
	
	// retorna msg de erro ou OK
	function valida() {
		return $this->erro;
	} // fim valida
	
	// carrega imagem (nova imagem, fora do construtor)
	public function carrega($origem='') {
		$this->origem			= $origem;
		$this->dados();
		return true;
	} // fim carrega

//------------------------------------------------------------------------------
// dados da imagem

	// seta as dimensóes do arquivo
	private function dimensoes() {
		$tamanho_original 	= getimagesize($this->origem);
		$this->largura 	 	= $tamanho_original[0];
		$this->altura	 	= $tamanho_original[1];
		return true;
	} // fim dimensoes
	
	// seta dados do arquivo
	private function dadosArquivo() {
		// imagem de origem
		$pathinfo = pathinfo($this->origem);
		$this->extensao 	= strtolower($pathinfo['extension']);
		$this->arquivo		= $pathinfo['basename'];
		$this->diretorio	= $pathinfo['dirname'];
		$this->tamanho		= filesize($this->origem);
		return true;
	} // fim dadosArquivo
	
	// verifica se é imagem
	private function eImagem() {
		$extensoes = array('jpg','jpeg','gif','bmp','png','psd','tiff');
		if (!in_array($this->extensao, $extensoes))
			return false;
		else
			return true;
	} // fim validaImagem	
	
//------------------------------------------------------------------------------
// manipulação da imagem	

	// cria imagem para manipulaçao com o GD
	private function criaImagem() {
		switch($this->extensao) {
			case 'gif':
				$this->img	= imagecreatefromgif($this->origem);
				break;
			case 'jpg':
				$this->img	= imagecreatefromjpeg($this->origem);
				break;
			case 'jpeg':
				$this->img	= imagecreatefromjpeg($this->origem);
				break;
			case 'png':
				$this->img	= imagecreatefrompng($this->origem);
				break;
			case 'bmp':
				// requer util.inc.php
				$this->img	= imagecreatefrombmp($this->origem);
				break;
			case 'psd':
				// requer psd_support.php
				include_once("format_supports/psd_support.php");
				$this->img = imagecreatefrompsd($this->origem);
				break;
			case 'tiff':
				// requer tiff_support.php
				include_once("format_supports/tiff_support.php");
				$this->img = imagecreatefromtiff($this->origem);
				break;
			case 'pdf':
				// requer pdf_support.php
				include_once("format_supports/pdf_support.php");
				$this->img = imagecreatefrompdf($this->origem);
				break;
		}
		return true;
	} // fim criaImagem

//------------------------------------------------------------------------------
// funções para redimensionamento
	
	// redimensiona imagem
	public function redimensiona($nova_largura=0, $nova_altura=0, $tipo='', $rgb='') {
	
		// seta variáveis passadas via parâmetro
		$this->nova_largura		= $nova_largura;
		$this->nova_altura		= $nova_altura;
		$this->rgb				= $rgb;
		
		// define se só passou nova largura ou altura
		if (!$this->nova_largura && !$this->nova_altura) {
			return false;
		// só passou altura
		} elseif (!$this->nova_largura) {
			$this->nova_largura = $this->largura/($this->altura/$this->nova_altura);
		// só passou largura
		} elseif (!$this->nova_altura) {
			$this->nova_altura = $this->altura/($this->largura/$this->nova_largura);
		}
		
		// redimensiona de acordo com tipo
		if ($tipo == 'crop') {
			$this->img = $this->resizeCrop();
		} elseif ($tipo == 'fill') {
			$this->img = $this->resizeFill();
		} else {
			$this->img = $this->resize();
		}
		
		
		return true;
	
	} // fim redimensiona
	
	// redimensiona proporcionalmente
	// novas altura ou largura serão modificadas
	private function resize() {
		// proporção
		// largura > altura
		if ($this->largura > $this->altura) {
			$r_largura 	= $this->nova_largura;
			$r_altura	= round($this->altura / ($this->largura/$this->nova_largura));
		// largura <= altura
		} elseif ($this->largura <= $this->altura) {
			$r_altura 	= $this->nova_altura;
			$r_largura	= round($this->largura / ($this->altura/$this->nova_altura));
		}
		
		// cria imagem de destino temporária
		$imgtemp	= imagecreatetruecolor($r_largura, $r_altura);
		
		imagecopyresampled($imgtemp, $this->img, 0, 0, 0, 0, $r_largura, $r_altura, $this->largura, $this->altura);
		return $imgtemp;
	} // fim resize()
	
	// redimensiona imagem sem cropar, proporcionalmente, 
	// preenchendo espaço vazio com cor rgb especificada
	private function resizeFill() {
		// cria imagem de destino temporária
		$imgtemp	= imagecreatetruecolor($this->nova_largura, $this->nova_altura);
		
		// adiciona cor de fundo à nova imagem
		$corfundo = imagecolorallocate($imgtemp, $this->rgb[0], $this->rgb[1], $this->rgb[2]);
		imagefill($imgtemp, 0, 0, $corfundo);
		
		// salva variáveis para centralização
		$dif_y = $this->nova_altura;
		$dif_x = $this->nova_largura;
		
		// verifica altura e largura
		if ($this->largura > $this->altura) {
			$this->nova_altura	= (($this->altura * $this->nova_largura)/$this->largura);
		} elseif ($this->largura <= $this->altura) {
			$this->nova_largura	= (($this->largura * $this->nova_altura)/$this->altura);
		}  // fim do if verifica altura largura
		
		// copia com o novo tamanho, centralizando
		$dif_x = ($dif_x-$this->nova_largura)/2;
		$dif_y = ($dif_y-$this->nova_altura)/2;
		imagecopyresampled($imgtemp, $this->img, $dif_x, $dif_y, 0, 0, $this->nova_largura, $this->nova_altura, $this->largura, $this->altura);
		return $imgtemp;
	} // fim resizeFill()
	
	// redimensiona imagem, cropando para encaixar no novo tamanho,
	// sem sobras
	// baseado no script original de Noah Winecoff
	// http://www.findmotive.com/2006/12/13/php-crop-image/
	private function resizeCrop() {
		// cria imagem de destino temporária
		$imgtemp	= imagecreatetruecolor($this->nova_largura, $this->nova_altura);
	
		// média altura/largura
		$hm	= $this->altura/$this->nova_altura;
		$wm	= $this->largura/$this->nova_largura;
		
		// 50% para cálculo do crop
		$h_height = $this->nova_altura/2;
		$h_width  = $this->nova_largura/2;
		
		// largura > altura
		if ($wm > $hm) {
			$adjusted_width = $this->largura / $hm;
        	$half_width = $adjusted_width / 2;
        	$int_width = $half_width - $h_width;
        	imagecopyresampled($imgtemp, $this->img, -$int_width, 0, 0, 0, $adjusted_width, $this->nova_altura, $this->largura, $this->altura);
		// largura <= altura
		} elseif (($wm <= $hm)) {
			$adjusted_height = $this->altura / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;
			imagecopyresampled($imgtemp, $this->img, 0, -$int_height, 0, 0, $this->nova_largura, $adjusted_height, $this->largura, $this->altura);
		} 
		return $imgtemp;
	} // fim resizeCrop

//------------------------------------------------------------------------------
// flipa imagem
// baseada na função original de relsqui
// http://www.php.net/manual/en/ref.image.php#62029

	public function flip($tipo='h') {
		$w = imagesx($this->img);
		$h = imagesy($this->img);
		
		$imgtemp = imagecreatetruecolor($w, $h);
		
		// vertical
		if ($tipo == 'v') {
			for ($y = 0; $y < $h; $y++) {
				imagecopy($imgtemp, $this->img, 0, $y, 0, $h - $y - 1, $w, 1);
			}
		}
		
		// horizontal
		if ($tipo == 'h') {
			for ($x = 0; $x < $w; $x++) {
				imagecopy($imgtemp, $this->img, $x, 0, $w - $x - 1, 0, 1, $h);
			}
		}
		
		$this->img = $imgtemp;
		
		return true;
	} // fim flip

//------------------------------------------------------------------------------
// gira imagem

	public function girar($graus,$rgb) {
		$corfundo	= imagecolorallocate($this->img, $rgb[0], $rgb[1], $rgb[2]);
		$this->img	= imagerotate($this->img,$graus,$corfundo);
		return true;   
	} // fim girar
	
//------------------------------------------------------------------------------
// marcas d'água
	
	// adiciona texto à imagem
	public function legenda($texto,$tamanho=10,$x=0,$y=0,$rgb,$truetype=false,$fonte='') {     
		$cortexto = imagecolorallocate($this->img, $rgb[0], $rgb[1], $rgb[2]);
		
		// truetype ou fonte do sistema?
		if ($truetype == true) {
			imagettftext($this->img, $tamanho, 0, $x, $y, $cortexto, $fonte, $texto);
		} else {
			imagestring($this->img, $tamanho, $x, $y, $texto, $cortexto);
		}
		
		return true;
	} // fim legenda

	// adiciona imagem de marca d'água
	public function marca($imagem,$x=0,$y=0,$alfa=100) {
		// cria imagem temporária para merge
		if ($imagem) {
			$pathinfo = pathinfo($imagem);
			switch(strtolower($pathinfo['extension'])) {
				case('jpg'):
					$marcadagua = imagecreatefromjpeg($imagem);
					break;
				case('jpeg'):
					$marcadagua = imagecreatefromjpeg($imagem);
					break;
				case('png'):
					$marcadagua = imagecreatefrompng($imagem);
					break;
				case('gif'):
					$marcadagua = imagecreatefromgif($imagem);
					break;
				case('bpm'):
					$marcadagua = imagecreatefrombmp($imagem);
					break;
				default:
					return false;
			}	
		} else {
			return false;
		}
		// dimensões
		$marca_w	= imagesx($marcadagua);
		$marca_h	= imagesy($marcadagua);
		// retorna imagens com merge
		imagealphablending($marcadagua, true);
		imagecopy($this->img,$marcadagua,$x,$y,0,0,$marca_w,$marca_h);
		return true;
	} // fim marca


//------------------------------------------------------------------------------
// gera imagem de saída

	// retorna saída de acordo com tipo definido
	// browser ou arquivo
	public function grava($destino='', $salvar=false, $qualidade=90) {
		
		// dados do arquivo de destino	
		if ($destino) {	
			$pathinfo = pathinfo($destino);
			$extensao_destino = strtolower($pathinfo['extension']);
		}
		
		// valida extensão de destino
		if (!isset($extensao_destino)) {
			$extensao_destino = "jpg";
		} else {
			$extensoes = array('jpg','jpeg','gif','bmp','png');
			if (!in_array($extensao_destino, $extensoes))
				return false;
		}
		
		if ($extensao_destino == 'jpg' || $extensao_destino == 'jpeg' || $extensao_destino == 'bmp') {			
			if ($salvar == true && $destino) {
				imagejpeg($this->img,$destino,$qualidade);
			} else {
				header("Content-type: image/jpeg");
				imagejpeg($this->img);
				imagedestroy($this->img);
				exit;
			}
		} elseif ($extensao_destino == 'png') {
			if ($salvar == true && $destino) {
				imagepng($this->img,$destino);
			} else {
				header("Content-type: image/png");
				imagepng($this->img);
				imagedestroy($this->img);
				exit;
			}
		} elseif ($extensao_destino == 'gif') {
			if ($salvar == true && $destino) {
				imagegif($this->img,$destino);
			} else {
				header("Content-type: image/gif");
				imagegif($this->img);
				imagedestroy($this->img);
				exit;
			}
		}
	} // fim grava
	
	function fix_png_alpha($color=false)
	{
		if(!$color) $color = array(255,255,255);
		
		imagealphablending($this->img, true);

		$new_img = imagecreatetruecolor($this->largura, $this->altura);
		$theColor = imagecolorallocate($new_img,$color[0],$color[1],$color[2]);
		imagefill($new_img,0,0,$theColor);

		imagecopy($new_img,$this->img,0,0,0,0,$this->largura,$this->altura);
		
		$this->img = $new_img;
	}

//------------------------------------------------------------------------------
// fim da classe    
}

//------------------------------------------------------------------------------
// suporte para a manipulação de arquivos BMP

/*********************************************/
/* Function: ImageCreateFromBMP              */
/* Author:   DHKold                          */
/* Contact:  admin@dhkold.com                */
/* Date:     The 15th of June 2005           */
/* Version:  2.0B                            */
/*********************************************/

function imagecreatefrombmp($filename) {
 //Ouverture du fichier en mode binaire
   if (! $f1 = fopen($filename,"rb")) return FALSE;

 //1 : Chargement des ent?tes FICHIER
   $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
   if ($FILE['file_type'] != 19778) return FALSE;

 //2 : Chargement des ent?tes BMP
   $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
				 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
				 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
   $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
   if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
   $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
   $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
   $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] = 4-(4*$BMP['decal']);
   if ($BMP['decal'] == 4) $BMP['decal'] = 0;

 //3 : Chargement des couleurs de la palette
   $PALETTE = array();
   if ($BMP['colors'] < 16777216)
   {
	$PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
   }

 //4 : Cr?ation de l'image
   $IMG = fread($f1,$BMP['size_bitmap']);
   $VIDE = chr(0);

   $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
   $P = 0;
   $Y = $BMP['height']-1;
   while ($Y >= 0)
   {
	$X=0;
	while ($X < $BMP['width'])
	{
	 if ($BMP['bits_per_pixel'] == 24)
		$COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
	 elseif ($BMP['bits_per_pixel'] == 16)
	 { 
		$COLOR = unpack("n",substr($IMG,$P,2));
		$COLOR[1] = $PALETTE[$COLOR[1]+1];
	 }
	 elseif ($BMP['bits_per_pixel'] == 8)
	 { 
		$COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
		$COLOR[1] = $PALETTE[$COLOR[1]+1];
	 }
	 elseif ($BMP['bits_per_pixel'] == 4)
	 {
		$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
		if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
		$COLOR[1] = $PALETTE[$COLOR[1]+1];
	 }
	 elseif ($BMP['bits_per_pixel'] == 1)
	 {
		$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
		if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
		elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
		elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
		elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
		elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
		elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
		elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
		elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
		$COLOR[1] = $PALETTE[$COLOR[1]+1];
	 }
	 else
		return FALSE;
	 imagesetpixel($res,$X,$Y,$COLOR[1]);
	 $X++;
	 $P += $BMP['bytes_per_pixel'];
	}
	$Y--;
	$P+=$BMP['decal'];
   }

 //Fermeture du fichier
   fclose($f1);

 return $res;
 
} // fim function image from BMP