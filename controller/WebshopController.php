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
          $this->displayProducts();
        }

        else if ($op == 'page') {
          $this->displayProducts();
        }

        else if ($op == 'details') {
          $this->showProductDetails();
        }

        else if ($op == 'shoppingcardAdd') {
          $this->addProductToShoppingcard();
        }

        else if ($op == 'shoppingcardShow') {
          $this->showShoppingCard();
        }

        else if ($op == 'shoppingcardUpdate') {
          $this->shoppingcard->update($_REQUEST['productID'], $_REQUEST['amount']);
        }

        else if ($op == 'shoppingcardDelete') {
          $this->shoppingcard->delete($_REQUEST['productID']);
        }

        else if ($op == 'shoppingcardCounter') {
          $shoppingcardTotal = $this->shoppingcard->count();
          echo $shoppingcardTotal;
        }

      } catch (Exception $e) {
        $this->showError("Application error", $e->getMessage());
      }
    }

    public function displayProducts() {
      $pageNumer = ISSET($_REQUEST['pageNumer'])? $_REQUEST['pageNumer']: 0;
      $products = $this->product->getProducts($pageNumer);
      $productview = new Productview();
      $productview = $productview->createProductsView($products);

      $productPagenering = $this->generatePagenering();

      include 'view/products.php';
    }

    public function generatePagenering() {
      // Generates pagenering
      $productsTotal = $this->product->countAllProducts();

      $pages = ceil($productsTotal / 10);
      // echo $pages;
      $list = '';
      $list .= '<ul class="col-12">';
      for ($i=0; $i < $pages; $i++) {
        $list .= '<li><a href="?pageNumer=' . $i . '">' . $i . '</a></li>';
      }
      $list .= '</ul>';
      return($list);
    }

    public function showProductDetails() {
      $productDetails = $this->product->details($_REQUEST['productID']);
      $productview = new Productview();
      $productDetails = $productview->createProductDetails($productDetails);
      include 'view/details.php';
    }

    public function showShoppingCard() {
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

    public function addProductToShoppingcard() {
      // Add product from shoppingcard
      $productID = ISSET($_REQUEST['productID'])?$_REQUEST['productID']: NULL;
      $productAmount = ISSET($_REQUEST['amount'])?$_REQUEST['amount']: NULL;

      if ($this->shoppingcard->checkIfIdExists($productID) == false) {
        // It isn't existsing in the shoppingcard
        $this->shoppingcard->add($productID, $productAmount);
      }
      else if ($this->shoppingcard->checkIfIdExists($productID) == true) {
        // Exists in the shoppingcard
        $amount = $this->shoppingcard->getProductAmount($productID);
        // echo $amount;
        $amount = $amount + 1;
        $this->shoppingcard->add($productID, $amount);
      }
    }
  }
?>
