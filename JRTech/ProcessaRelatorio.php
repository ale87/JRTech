<?php 
require('Conexao.php');
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['submit'])){
	if(!file_exists('files') || !is_dir('files')){
		mkdir('files', 0777);
	}
	
	if(!is_writable('files')){
		chmod('files', 0777);
	}
	
	$dt_ini = date('d-m-y', strtotime($_POST['data_inicial']));
	$dt_fim = date('d-m-y', strtotime($_POST['data_final']));

	if($dt_fim < $dt_ini){
		echo "<script>alert('Data Inicial não pode ser maior do que a Data Final!')</script>";
		return;
	}
	
	$arquivo = $dt_ini . ' ate ' . $dt_fim . '.csv';
	$diretorio = 'files/' . $arquivo;

	$valor = 'nome;cpf;endereco;bairro;cidade;uf;cep;email;celular;vencimento;'
			 . 'nosso numero;numero contrato;valor;estado;agencia;conta;cod banco;' 
			 . 'cod beneficiario;carteira;observacoes' . "\r\n";

	$query = "select p.nome as [nome], p.cpf as [cpf], p.endereco as [end],
			p.bairro as [bai], p.cidade as [cid], p.uf as [uf], p.cep as [cep], 
			p.email as [email], p.celular as [cel], c.vencimento as [venc], 
			c.id as [id], p.n_identificador as [ide], c.valor as [val], 
			p.estado as [est] 
			from pessoas p inner join contas_receber c on (p.id = c.pessoa_id) 
			where c.quitado = 1 and (c.vencimento between '" .$dt_ini. "' and '" .$dt_fim. "')
			and (p.estado <> 3) order by c.vencimento";
	
	$consulta = sqlsrv_query($conexao, $query);

	if($consulta == false) {
		echo "<script>alert('Verifique a conexão com seu banco de dados!')</script>";
		return;
	}

	while($linha = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
		$valor.= $linha['nome']. ';'
				.$linha['cpf']. ';'
				.$linha['end']. ';'
				.$linha['bai']. ';'
				.$linha['cid']. ';'
				.$linha['uf']. ';'
				.$linha['cep']. ';'
				.$linha['email']. ';'
				.strval($linha['cel']). ';'
				.date_format($linha['venc'], 'd-m-Y'). ';'
				.$linha['id']. ';'
				.$linha['ide']. ';'
				.number_format($linha['val'], 2, ',', '.'). ';'
				.$linha['est']. ';'
				.'3553;'
				.'13000623-3;'
				.'33;'
				.'3628612;'
				.'102;'
				.'APOS O VENCIMENTO MULTA DE 2% AO MES + MORA DE R$ 0,10 AO DIA'
				."\r\n";
	}
	
	if(file_exists($diretorio)){
		echo "<script>alert('Arquivo de mesmo período já existente na pasta.')</script>";
	}

	else{
		$csv = fopen($diretorio, 'w+');
		fwrite($csv, $valor);
		echo "<script>alert('Arquivo criado com sucesso!')</script>";
		fclose($csv);
	}	

	sqlsrv_close($conexao);
}