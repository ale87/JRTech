<?php
require('ProcessaRelatorio.php');
?>
<html>
<body>
<form action='' method='post'>
<input type='date' id='data_inicial' name='data_inicial' required> 
<input type='date' id='data_final' name='data_final' required>
<input type='submit' id='submit' name='submit' value='Gerar Arquivo CSV'>
</form>
<div><a href='Index.php' style='text-decoration: none;'>Voltar</a></div>
</body>
</html>