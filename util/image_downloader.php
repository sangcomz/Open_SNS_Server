<?php 
header("Content-Type: text/html; charset=utf8");


		
		
		$url = "http://sangcomz.xyz/puzzler/account/profile_image/testimage.png";
		$includes_dir = $_SERVER['DOCUMENT_ROOT'].'/testimage.png';
		//$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/image.png","wb");
		//fwrite($fp, copy($url, $includes_dir));
		//fclose($fp);
		echo $im = imagecreatefrompng("http://sangcomz.xyz/puzzler/account/profile_image/testimage.png");
		
		
		//header('Content-Type: image/png');
		//imagepng($im);
		//imagedestroy($im);


//echo file_exists(ROOTPATH.'/Texts/MyInfo.txt');

//file_put_contents($includes_dir, fopen("http://sangcomz.xyz/puzzler/account/profile_image/testimage.png", 'r'));

$image_Url=file_get_contents($url);

$file_destino_path=$includes_dir;

file_put_contents($file_destino_path, $image_Url)
		
?>