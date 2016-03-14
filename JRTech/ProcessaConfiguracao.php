<?php
$diretorio = $_POST['diretorio'];

if(!isset($_COOKIE['diretorio'])){
	
	setcookie('diretorio', $diretorio, time() + 3600 * 24 * 30 * 12 * 5);
}

else{
	
	$_COOKIE['diretorio'] = $diretorio;
}

header('Location: Configuracao.php');
?>