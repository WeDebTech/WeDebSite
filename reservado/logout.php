<?php
  //Iniciar a sessão
  session_start();

  //Fechar todas as variáveis
  $_SESSION = array();

  //Destruir a sessão
  session_destroy();

  //Redirecionar para a página de Login
  header("location: login.php");
  exit;
?>
