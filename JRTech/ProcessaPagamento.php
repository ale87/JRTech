<?php 
require('Conexao.php');

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
		echo "<script>alert('Este arquivo já foi baixado anteriormente.')</script>";
		return;
	}

	if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio)){
		$lerArquivo = fopen($diretorio, 'r');
		
		while(!feof($lerArquivo)){
			$li[] = substr(fgets($lerArquivo, 50), 38, 5);
			$lf[] = substr(fgets($lerArquivo, 400), 4, 5);
		}

		fclose($lerArquivo);
		unset($li[0], $li[sizeof($li)], $li[sizeof($li)]);
		unset($lf[0], $lf[sizeof($lf)], $lf[sizeof($lf)]);
		$query = null;
		$query_ini = null;
		$query_fim = null;
		$x = 0;
		$y = 0;

		foreach($li as $lin){
			$x++;

			if(intval($lin)>0 && $lin == $lf[$x]){
				$query = "select conta_receber_id as [conta] from contas_receber_pagtos
				          where conta_receber_id = $lin";
				$consulta = sqlsrv_query($conexao, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				$contador = sqlsrv_num_rows($consulta);

				if($contador==false){
					$query_ini = "select vencimento as [venc], valor as [val] from 
					             contas_receber where id = $lin";
					$consulta = sqlsrv_query($conexao, $query_ini);

					while($linha = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)){
						$query_fim .= "update contas_receber set quitado=1 where id=$lin;
					         	      insert into contas_receber_pagtos(conta_receber_id, 
					              	  pagto_data, pagto_valor, pagto_tipo, conta_corrente_id,
					           	      cheque_id, alteracao_usu_id, alteracao_data)
					                  values($lin, " .$linha['venc']->format('d/m/Y'). ","
					                 .$linha['val']. ", 5, 2, NULL, 3, GETDATE());";
					}
				}

				else{
					$y++;					
				}
			}
			
			else{
				unlink($diretorio);
				echo "<script>alert('Não foi possível baixar os pagamentos completamente! Verifique o formato do arquivo e a conexão com o banco de dados.')</script>";
				return;
			}
		}
		
		if(sqlsrv_query($conexao, $query_fim) && $y!=$x){
			echo "<script>alert('Arquivo baixado com sucesso!')</script>";
		}

		else if($y==$x){
			unlink($diretorio);
			echo "<script>alert('Todos os pagamentos deste arquivo já estavam baixados.')</script>";
		}
	
		else{
			unlink($diretorio);		
			echo "<script>alert('Não foi possível ler o arquivo! Verifique se o mesmo encontra-se disponível para leitura.')</script>";
		}
	}
}