<?php 
require('ProcessaPagamento.php');
?>
<html>
<body>
<form action='' method='post' enctype="multipart/form-data">
<label>Selecione um arquivo de texto para baixa.</label>
<input type='file' id='arquivo' name='arquivo' accept='.txt'><br/><br/>
<input type='submit' id='submit' name='submit' value='Baixar Pagamento'><br/>
</form>
<div><a href='Index.php' style='text-decoration: none;'>Voltar</a></div>
</body>
</html>