<?php

  session_start();
  session_set_cookie_params(0);

  require_once 'controller/webshop.php';
  $controller = new webshopController();
  $controller->handleRequest();

?>
