<?php 

if(isset($_POST['submit'])){
	if(!file_exists('logs') && !is_dir('logs')){
		mkdir('logs', 0777);
	}
	
	if(!is_writable('logs')){
		chmod('logs', 0777);
	}
	
	$arquivo = $_FILES['arquivo']['name'];
	$diretorio = 'logs/'.$arquivo;
	
	if(file_exists($diretorio) && is_readable($diretorio)){
		echo "<script>alert('Este arquivo j� foi baixado anteriormente!')</script>";
		return;
	}

	if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio)) {
		$lerArquivo = fopen($diretorio, 'r');
		
		while(!feof($lerArquivo)){
			$linha[] = substr(fgets($lerArquivo), 53, 6);
		}
		
		$tam = sizeof($linha);		
		unset($linha[0], $linha[sizeof($linha)], $linha[sizeof($linha)]);
		fclose($lerArquivo);
		
		// banco
		
		echo "<script>alert('Pagamento baixado com sucesso!')</script>";
	}
	
	else{
		
		echo "<script>alert('N�o foi poss�vel ler o arquivo! Verifique se o mesmo existe e encontra-se dispon�vel para leitura.')</script>";
	}
}
?>