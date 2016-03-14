<?php 
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['submit'])){
	if(!file_exists('files') && !is_dir('files')){
		mkdir('files', 0777);
	}
	
	if(!is_writable('files')){
		chmod('files', 0777);
	}
	
	$data = new DateTime();
	$data = date_format($data, 'dmYHis');
	$arquivo = $data . '.csv';
	$dt_ini = $_POST['data_inicial']; //Y-m-d
	$dt_fim = $_POST['data_final']; //Y-m-d
	
	// substituir o $val por query de banco
	
	$val = null;
	$val .= "Joao;1234;6789;\n";
	$cria = fopen('files/' . $arquivo, 'w+');
	
	if(fwrite($cria, $val)){
		echo "<script>alert('Arquivo criado com sucesso!')</script>";
	}
	
	else{
		echo "<script>alert('Arquivo não pôde ser criado! Verifique a conexão com o seu banco de dados.')</script>";
	}
	
	fclose($cria);
}
?>