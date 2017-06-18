<!--

  Project: Multiversum
  Authors: Mark Salet, Peter Romijn

 -->
<?php
  session_start();
  session_set_cookie_params(0);

  require_once 'controller/WebshopController.php';

  $controller = new WebshopController();
  $controller->handleRequest();
?>
