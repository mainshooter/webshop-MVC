<?php

  require_once 'model/product.class.php';
  require_once 'model/shoppingcard.class.php';
  require_once 'model/productview.class.php';

  class WebshopController {
    // Webshop controller
    private $product;
    private $shoppingcard;

    function __construct() {
      $this->product = new product();
      $this->shoppingcard = new shoppingcard();
    }

    public function handleRequest() {
      $op = ISSET($_REQUEST['op'])?$_REQUEST['op']:NULL;

      try {
        if (!$op || $op == 'home') {
          $products = $this->product->getProducts('0');
          $productview = new Productview();
          $productview = $productview->createProductsView($products);
          include 'view/products.php';
        }
        else if ($op == 'details') {
          $productDetails = $this->product->details($_REQUEST['productID']);
          $productview = new Productview();
          $productDetails = $productview->createProductDetails($productDetails);
          include 'view/details.php';
        }

      } catch (Exception $e) {
        $this->showError("Application error", $e->getMessage());
      }

    }
  }
?>
