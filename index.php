<!--

  Project: Multiversum
  Authors: Mark Salet, Peter Romijn

 -->
<?php
  ini_set('post_max_size', '64M');
  ini_set('upload_max_filesize', '64M');

  session_start();
  session_set_cookie_params(0);

  require_once 'controller/WebshopController.php';

  $controller = new WebshopController();
  $controller->handleRequest();
?>
