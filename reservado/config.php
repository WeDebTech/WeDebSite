<?php
/* Credenciais da base de dados. Assumindo que se está
a correr o servidor MySQL com definições predefinidas
(user 'root' sem password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'atwsite');

function openConnection(){
  //Tentar conectar-se à base de dados MySQL
  $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  mysqli_query($link, "SET NAMES 'utf8'");

  //Verificar conexão
  if($link == false){
    die("ERRO: Não foi possivel connectar. " . mysqli_connect_error());
  }

  return $link;
}
?>
