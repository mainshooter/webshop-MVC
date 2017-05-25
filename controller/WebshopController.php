<?php

  require_once 'model/product.class.php';
  require_once 'model/shoppingcard.class.php';
  require_once 'model/productview.class.php';
  require_once 'model/Shoppingcardview.php';

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
        else if ($op == 'shoppingcardAdd') {
          // Add product from shoppingcard
          if ($this->shoppingcard->checkIfIdExists($_REQUEST['productID']) == false) {
            // It isn't existsing in the shoppingcard
            $this->shoppingcard->add($_REQUEST['productID'], $_REQUEST['amount']);
          }
          else if ($this->shoppingcard->checkIfIdExists($_REQUEST['productID']) == true) {
            // Exists in the shoppingcard
            $amount = $this->shoppingcard->getProductAmount($_REQUEST['productID']);
            // echo $amount;
            $amount = $amount + 1;
            $this->shoppingcard->add($_REQUEST['productID'], $amount);
          }
        }
        else if ($op == 'shoppingcardShow') {
          $shoppingcard = '';
          $view = new ShoppingcardView();
          $shoppingcardArray = $this->shoppingcard->get();
          if (!empty($shoppingcardArray)) {
            foreach ($shoppingcardArray as $key) {
              // Loops trough every item of the shoppingcard
              $product_details = $this->product->details($key['productID']);
              // Get the details of a product
              $amount = $shoppingcardArray[$key['productID']]['amount'];
              // Get how mutch we have of one product
              $productTotal = $this->shoppingcard->productTotalPriceInShoppingCard($key['productID']);
              // Total cost of one product with multiple items
              $shoppingcardArray['productDetails'] = $this->product->details($key['productID']);

              $shoppingcard .= $view->displayShoppingCard($product_details, $amount, $productTotal);
              // Display
            }
            $BTWPrice = $this->shoppingcard->calculateBTW();
            $shoppingcard .= "<h2 class='col-10 right-text'>BTW: &euro;" . $BTWPrice . "</h2>";
            $priceWithoutBTW = $this->shoppingcard->calculatePriceWithoutBTW();
            $shoppingcard .= "<h2 class='col-10 right-text'>Exclusief BTW: &euro;" . $priceWithoutBTW . "</h2>";
            $totalPrice = $this->shoppingcard->calculateTotalPriceShoppingcard();
            $shoppingcard .= "<h2 class='col-10 right-text'>Totaal: &euro;" . $totalPrice . "</h2>";
          }
          else {
            $shoppingcard .= "<h2 class='col-12 center'>Uw winkelmandje is leeg!</h2>";
          }

          include 'view/shoppingcard.php';
        }
        else if ($op == 'shoppingcardCounter') {
          $shoppingcardTotal = $this->shoppingcard->count();
          echo $shoppingcardTotal;
        }

      } catch (Exception $e) {
        $this->showError("Application error", $e->getMessage());
      }

    }
  }
?>
