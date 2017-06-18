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
          include 'view/createCustomer.php';
        }

        else if ($op == 'betalen') {
          // We save the shoppingcard to the database
          // And save the product price of every product
          // Than we redirect the client to the payment provider

          $orderID = $this->createOrder();
          $this->order->generateMailToCustomerAboutOrderConfirmation($orderID);
          $this->shoppingcard->clearShoppingcard();

          $this->payment->startPayment($orderID);
        }

        else if ($op == 'paymentResponse') {
          $this->payment->handelsPaymentResult($_POST['id']);
        }

        else if ($op == 'displayOrder') {
          $this->displayOrder();
        }

        else if ($op == 'productAdminList') {
          // This op generates the crud list for a product
          $this->productListForAdmin();
        }

        else if ($op == 'updateProduct') {
          $this->product->update($_REQUEST);
        }

        else if ($op == 'loginForm') {
          include 'view/admin/header.html';
          include 'view/admin/loginForm.html';
        }

        else if ($op == 'login') {
          $login = $this->user->userLogin($_POST['login_mail'], $_POST['login_password'], "?op=dashboard");
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
              include 'view/admin/crud-dashboard.php';
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
            $this->product->add($_REQUEST);
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }

        else if ($op == 'updateProductForm') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $productDetails = $this->product->details($_REQUEST['productID']);
            include 'view/admin/header.html';
            include 'view/admin/updateProductForm.php';
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }

        else if ($op == 'updateProduct') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $this->product->update($_REQUEST);
          }
          else {
            include 'view/admin/header.html';
            include 'view/admin/no-acces.html';
          }
        }

        else if ($op == 'adminDeleteProduct') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $this->product->delete($_REQUEST['productID']);
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
      $pageLimit = 10;

      $products = $this->product->getProducts($pageNumer, $pageLimit);
      // $productview = new Productview();
      // $productview = $productview->createProductsView($products);

      include 'view/header.php';
      include 'view/products.php';
      $productPagenering = $this->generatePagenering();
      include 'view/footer.php';
    }

    public function displayNewestProducts() {
      // Displays the newest products
      $newestProducts = $this->product->getNewestProducts();

      include 'view/header.php';
      include 'view/newproducts.php';
      include 'view/footer.php';

    }

    public function generatePagenering() {
      // Generates pagenering
      $productsTotal = $this->product->countAllProducts();

      $pages = ceil($productsTotal / 10);
      include 'view/pagenering.php';
    }

    public function showProductDetails() {
      $productDetails = $this->product->details($_REQUEST['productID']);
      $productview = new Productview();
      $productDetails = $productview->createProductDetails($productDetails);
      include 'view/details.php';
    }

    public function displayContact() {
      include 'view/contact.php';
    }

    public function showShoppingCard() {
      $shoppingcard = '';
      $view = new ShoppingcardView();
      $shoppingcardArray = $this->shoppingcard->get();

      $teller = 0;
      foreach ($shoppingcardArray as $key) {
        $product_details[] = $this->product->details($key['productID']);

        $product_details_price[]['productTotal'] = $this->shoppingcard->productTotalPriceInShoppingCard($key['productID']);

        $product_details_aantal[]['aantal'] = $view->generateOptionNumbers($key['productID'] ,$shoppingcardArray[$key['productID']]['amount']);


          $BTWPrice = $this->shoppingcard->calculateBTW();
          $BTWPrice = str_replace('.', ',', $BTWPrice);

          $priceWithoutBTW = $this->shoppingcard->calculatePriceWithoutBTW();
          $priceWithoutBTW = str_replace('.', ',', $priceWithoutBTW);

          $totalPrice = $this->shoppingcard->calculateTotalPriceShoppingcard();
          $totalPrice = str_replace('.', ',', $totalPrice);

        $teller++;
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
      $this->customer->country = $s->checkInput($_REQUEST['customer_country']);

      $orderID = $this->customer->saveCustomerToDB();
      $orderCreate = $this->order->createOrder($orderID);
      return($orderID);
    }

    public function displayOrder() {
      $Translate = new Translate();

      $orderID = ISSET($_REQUEST['orderID'])?$_REQUEST['orderID']: NULL;

      $order = $this->order->getOrder($orderID);
      foreach ($order as $key) {
        $betaal_status = $Translate->translateEngToNL($key['betaal_status']);
      }
      $orderItems = $this->order->getOrderItemsForHtmlGenerator($orderID);

      include 'view/display-a-order.php';
    }

    public function adminDashboard() {

  }
}
?>
