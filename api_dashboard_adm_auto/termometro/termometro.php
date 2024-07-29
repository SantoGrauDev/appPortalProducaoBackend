<?php

// previne o cache
header ("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-Disposition:inline; filename=\"termometro.png\"");

// função para facilitar a criação das cores (cor no formato #rrggbb)
function rgb($i,$c){
return imagecolorallocate($i,hexdec("$c[1]$c[2]"),hexdec("$c[3]$c[4]"),hexdec("$c[5]$c[6]"));
}

// carrega as imagens
$fundo=imagecreatefrompng("img/fundo.png"); // não esquecer de verificar o nome do arquivo
$vidro=imagecreatefrompng("img/vidro.png"); // não esquecer de verificar o nome do arquivo
// cria a cor do texto e da linha
$pr=rgb($fundo,"000000"); // preto
// cria a cor da barra
$cor=rgb($fundo,"#D71E1E"); 
// configuração
$larg=array(8,35); // tamanho da linha que cruza a barra (somente as coordenadas horizontais)
$linha=11; // espessura da linha
$bmin=210; // posicao minima na barra (coordenada vertical)
$bmax=20;  // posição maxima na barra (coordenada vertical)
$pos_barra=22; // distancia horizontal da barra (coordenada horizontal)
$escalamax=100; // valor maximo da escala

// obtenção dos valores a serem medidos
$val=64; // valor exemplo

// calculo da posição
$delta= (float)($bmin/$escalamax);
$pos  = (float)($val*$delta);
$pos  = (float)(($bmin-$pos)+$bmax) ;


// agora é a parte do desenho
// ajusta a espessura da linha
imagesetthickness($fundo,$linha); 
// desenha a "bolinha" no fundo do termometro
imagefilledellipse($fundo,$pos_barra,$bmin+8,23,25,$cor);

// desenha a barra do termometro
imageline($fundo,$pos_barra,$bmin,$pos_barra,$pos,$cor);

// escreve o valor sobre a linha
imagestring($fundo, 5, $larg[1]+5, $pos-8, $val, $pr);
// desenha a linha na escala
imagesetthickness($fundo,1); // restaura a espessura da linha
imageline($fundo,$larg[0],$pos,$larg[1],$pos,$pr);

// insere a imagem do vidro
imagecopyresampled($fundo,$vidro,0,0,0,0,80,250,80,250);

// gera o cabeçalho de imagem
header("Content-type: image/png");
// exibe a imagem
imagepng($fundo);
imagedestroy($fundo);
imagedestroy($vidro);
// fim :)
 
?> 