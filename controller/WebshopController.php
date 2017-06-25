<?php

  require_once 'model/product.class.php';
  require_once 'model/shoppingcard.class.php';
  require_once 'model/productview.class.php';
  require_once 'model/shoppingcardview.class.php';
  require_once 'model/Customer.class.php';
  require_once 'model/security.class.php';
  require_once 'model/order.class.php';
  require_once 'model/mail.class.php';
  require_once 'model/payment.class.php';
  require_once 'model/user.class.php';
  require_once 'config-webshop.php';
  require_once 'model/Translate.class.php';

  class WebshopController {
    // Webshop controller
    private $product;
    private $shoppingcard;
    private $customer;
    private $order;
    private $mail;
    private $payment;
    private $user;

    function __construct() {
      $this->product = new Product();
      $this->shoppingcard = new Shoppingcard();
      $this->customer = new Customer();
      $this->order = new Order();
      $this->mail = new Mail();
      $this->payment = new Payment();
      $this->user = new User();
    }

    public function handleRequest() {
      $op = ISSET($_REQUEST['op'])?$_REQUEST['op']:NULL;

      try {
        if (!$op || $op == 'home') {
          $this->displayNewestProducts();
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

        else if ($op == 'createOrder') {
          // Gets the form that the customer needs to fill in
          if (!empty($this->shoppingcard->getProductIDs())) {
            // To check if we have products
            // We don't want a user here if he don't have any products
            include 'view/header.php';
            include 'view/createCustomer.php';
            include 'view/footer.php';
          }
          else {
            // If someone has got here without any products in the shoppingcard
            include 'view/header.php';
            include 'view/no-products.html';
            include 'view/footer.php';
          }
        }

        else if ($op == 'Betalen') {
          // We save the shoppingcard to the database
          // And save the product price of every product
          // Than we redirect the client to the payment provider

          if (!empty($this->shoppingcard->getProductIDs())) {
            // To check if we have any products in the shoppingcard to paywith
            if (!empty($_POST)) {
              // To check if a user has got here by the post of the form
              $orderID = $this->createOrder();
              $this->order->generateMailToCustomerAboutOrderConfirmation($orderID);
              $this->shoppingcard->clearShoppingcard();

              $this->payment->startPayment($orderID);
            }

            else {
              // If someone has got here without the use of the form
              include 'view/header.php';
              include 'view/no-products.html';
              include 'view/footer.php';
            }
          }
          else {

            // If someone has got here without any products in his shoppingcard
            include 'view/header.php';
            include 'view/no-products.html';
            include 'view/footer.php';
          }
        }

        else if ($op == 'paymentResponse') {
          $this->payment->handelsPaymentResult($_POST['id']);
        }

        else if ($op == 'displayOrder') {
          $this->displayOrder();
        }


        else if ($op == 'loginForm') {
          include 'view/admin/header.html';
          include 'view/admin/loginForm.html';
        }

        else if ($op == 'login') {
          $mail = ISSET($_POST['login_mail'])?$_POST['login_mail']: NULL;
          $password = ISSET($_POST['login_password'])?$_POST['login_password']: NULL;

          $login = $this->user->userLogin($mail, $password, "?op=dashboard");
          if (!$login) {
            include 'view/admin/header.html';
              include 'view/admin/loginForm.html';
            include 'view/admin/badLogin.html';
          }
        }
        else if ($op == 'logout') {
          $this->user->userLogout("?op=home");
        }

        else if ($op == 'dashboard') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
              $AllProducts = $this->product->getAllProducts();
              include 'view/admin/header.html';
              if (!empty($AllProducts)) {
                  include 'view/admin/crud-dashboard.php';
              }
              else {
                include 'view/admin/crud-dashboard.php';
                include 'view/no-products.html';
              }
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }

        else if ($op == 'addProductForm') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
              include 'view/admin/header.html';
              include 'view/admin/addProduct.html';
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }

        else if ($op == 'productToevoegen') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $this->product->createProduct($_REQUEST);
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }

        else if ($op == 'updateProductForm') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $productDetails = $this->product->productDetails($_REQUEST['productID']);
            if (!empty($productDetails)) {
              include 'view/admin/header.html';
              include 'view/admin/updateProductForm.php';
            }
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }

        else if ($op == 'updateProduct') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $this->product->updateProduct($_REQUEST);
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }

        else if ($op == 'adminDeleteProduct') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $this->product->deleteProduct($_REQUEST['productID']);
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }
        else if ($op == 'contact') {
          include 'view/header.php';
            include 'view/contact.php';
          include 'view/footer.php';
        }

      } catch (Exception $e) {
        $this->showError("Application error", $e->getMessage());
      }
    }

    /**
     * Displays the error
     */
    private function showError($message) {
      echo "<h1>" . $message . "</h1>";
    }

    public function displayProducts() {
      // Displays all products
      $pageNumer = ISSET($_REQUEST['pageNumer'])? $_REQUEST['pageNumer']: 0;
      $pageLimit = 12;

      $products = $this->product->getProducts($pageNumer, $pageLimit);

      include 'view/header.php';
      if (!empty($products)) {
        // If we have products
        include 'view/products.php';
        $productPagenering = $this->generatePagenering($pageNumer);
      }
      else {
        // We have products
        include 'view/no-products.html';
      }
      include 'view/footer.php';
    }

    public function displayNewestProducts() {
      // Displays the newest products
      $newestProducts = $this->product->getNewestProducts();

      include 'view/header.php';
      if (!empty($newestProducts)) {
        include 'view/newproducts.php';
      }
      else {
        include 'view/no-products.html';
      }

      include 'view/footer.php';

    }

    public function generatePagenering($pageNumer) {
      // Generates pagenering
      $productsTotal = $this->product->countAllProducts();

      if ($productsTotal == 0) {
        include 'view/no-products.html';
      }
      else {
        $pages = ceil($productsTotal / 10);
        include 'view/pagenering.php';
      }
    }

    public function showProductDetails() {
      $productDetails = $this->product->productDetails($_REQUEST['productID']);

      include 'view/header.php';
      if (!empty($productDetails)) {
        include 'view/details.php';
      }
      else {
        include 'view/no-products.html';
      }
      include 'view/footer.php';
    }

    public function displayContact() {
      include 'view/contact.php';
    }

    public function showShoppingCard() {
      $shoppingcard = '';
      $view = new ShoppingcardView();
      $shoppingcardArray = $this->shoppingcard->get();

      $teller = 0;
      if (!empty($shoppingcardArray)) {
        foreach ($shoppingcardArray as $key) {
          $product_details[] = $this->product->productDetails($key['productID']);

          $product_details_price[]['productTotal'] = number_format($this->shoppingcard->productTotalPriceInShoppingCard($key['productID']), 2);

          $product_details_aantal[]['aantal'] = $view->generateOptionNumbers($key['productID'] ,$shoppingcardArray[$key['productID']]['amount']);


            $BTWPrice = $this->shoppingcard->calculateBTW();
            $BTWPrice = number_format($BTWPrice, 2);
            $BTWPrice = str_replace('.', 'dot', $BTWPrice);
            $BTWPrice = str_replace(',', 'comma', $BTWPrice);
            $BTWPrice = str_replace('dot', ',', $BTWPrice);
            $BTWPrice = str_replace('comma', '.', $BTWPrice);
            // Scanning for all dots and comma's
            // After we did that we convert that
            // To make sure that it is done correctly

            $priceWithoutBTW = $this->shoppingcard->calculatePriceWithoutBTW();
            $priceWithoutBTW = number_format($priceWithoutBTW, 2);
            $priceWithoutBTW = str_replace('.', 'dot', $priceWithoutBTW);
            $priceWithoutBTW = str_replace(',', 'comma', $priceWithoutBTW);
            $priceWithoutBTW = str_replace('dot', ',', $priceWithoutBTW);
            $priceWithoutBTW = str_replace('comma', '.', $priceWithoutBTW);

            $totalPrice = $this->shoppingcard->calculateTotalPriceShoppingcard();
            $totalPrice = number_format($totalPrice, 2);
            $totalPrice = str_replace('.', 'dot', $totalPrice);
            $totalPrice = str_replace(',', 'comma', $totalPrice);
            $totalPrice = str_replace('dot', ',', $totalPrice);
            $totalPrice = str_replace('comma', '.', $totalPrice);

          $teller++;
        }
      }
      include 'view/header.php';

      if (!empty($shoppingcardArray)) {
        include 'view/shoppingcard.php';
      }
      else {
        include 'view/emptyShoppingcard.php';
      }
      include 'view/footer.php';
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

    public function createOrder() {
      // Creates the order
      // And puts all order items in the database
      $s = new Security();

      $this->customer->firstname = $s->checkInput($_REQUEST['customer_firstname']);
      $this->customer->tussenvoegsel = $s->checkInput($_REQUEST['customer_tussenvoegsel']);
      $this->customer->lastname = $s->checkInput($_REQUEST['customer_lastname']);
      $this->customer->email = $s->checkInput($_REQUEST['customer_email']);
      $this->customer->street = $s->checkInput($_REQUEST['customer_street']);
      $this->customer->housenumber = $s->checkInput($_REQUEST['customer_houseNumber']);
      $this->customer->addon = $s->checkInput($_REQUEST['customer_addon']);
      $this->customer->zipcode = $s->checkInput($_REQUEST['customer_zipCode']);

      $orderID = $this->customer->saveCustomerToDB();
      $orderCreate = $this->order->createOrder($orderID);
      return($orderID);
    }

    public function displayOrder() {
      $Translate = new Translate();

      $orderID = ISSET($_REQUEST['orderID'])?$_REQUEST['orderID']: NULL;

      $order = $this->order->getOrder($orderID);
      if (!empty($order)) {
        foreach ($order as $key) {
          $betaal_status = $Translate->translateEngToNL($key['betaal_status']);
        }
        $orderItems = $this->order->getOrderItemsForHtmlGenerator($orderID);
        include 'view/header.php';
        include 'view/display-a-order.php';
        include 'view/footer.php';
      }
      else {
        include 'view/header.php';
        include 'view/footer.php';
      }
    }
}
?>
