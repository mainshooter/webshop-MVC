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

        else if ($op == 'contact') {
          $this->displayContact();
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
          $this->user->userLogin($_POST['login_mail'], $_POST['login_password'], "?op=dashboard");
        }

        else if ($op == 'dashboard') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
              $AllProducts = $this->product->getAllProducts();
              include 'view/admin/crud-dashboard.php';
          }
          else {
            echo "U bent niet ingelogd";
          }
        }

        else if ($op == 'addProductForm') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
              include 'view/admin/addProduct.html';
          }
          else {
            echo "No acces";
          }
        }

        else if ($op == 'productToevoegen') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $this->product->add($_REQUEST);
          }
          else {
            echo "No acces";
          }
        }

        else if ($op == 'updateProductForm') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $productDetails = $this->product->details($_REQUEST['productID']);
            include 'view/admin/updateProductForm.php';
          }
          else {
            echo "No acces";
          }
        }

        else if ($op == 'updateProduct') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $this->product->update($_REQUEST);
          }
          else {
            echo "No acces";
          }
        }

        else if ($op == 'adminDeleteProduct') {
          $this->user->setPageAcces(['admin']);
          if ($this->user->checkIfUserHasAcces()) {
            $this->product->delete($_REQUEST['productID']);
          }
          else {
            echo "No acces";
          }
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
      $productview = new Productview();
      $productview = $productview->createProductsView($products);

      include 'view/header.php';
      include 'view/products.php';
      $productPagenering = $this->generatePagenering();
      include 'view/footer.php';
    }

    public function displayNewestProducts() {
      // Displays the newest products
      $newestProducts = $this->product->getNewestProducts();
      $newestProductsView = new Productview();
      $newestProductsView = $newestProductsView->createNewProductsView($newestProducts);

      include 'view/newproducts.php';
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
      if (!empty($shoppingcardArray)) {
        foreach ($shoppingcardArray as $key) {
          // Loops trough every item of the shoppingcard
          $product_details = $this->product->details($key['productID']);
          // Get the details of a product
          $amount = str_replace(',', '.', $shoppingcardArray[$key['productID']]['amount']);
          // Get how mutch we have of one product
          $productTotal = $this->shoppingcard->productTotalPriceInShoppingCard($key['productID']);
          // Total cost of one product with multiple items
          $shoppingcardArray['productDetails'] = $this->product->details($key['productID']);

          $shoppingcard .= $view->displayShoppingCard($product_details, $amount, $productTotal);
          // Display
        }
        $BTWPrice = $this->shoppingcard->calculateBTW();
        $BTWPrice = str_replace('.', ',', $BTWPrice);

        $shoppingcard .= "<h2 class='col-10 right-text'>BTW: &euro;" . $BTWPrice . "</h2>";
        $priceWithoutBTW = $this->shoppingcard->calculatePriceWithoutBTW();
        $priceWithoutBTW = str_replace('.', ',', $priceWithoutBTW);

        $shoppingcard .= "<h2 class='col-10 right-text'>Exclusief BTW: &euro;" . $priceWithoutBTW . "</h2>";
        $totalPrice = $this->shoppingcard->calculateTotalPriceShoppingcard();
        $totalPrice = str_replace('.', ',', $totalPrice);

        $shoppingcard .= "<h2 class='col-10 right-text'>Totaal: &euro;" . $totalPrice . "</h2>";
        $shoppingcard .= "<div class='col-8'></div><a href='?op=createOrder'><button type='button' class='col-2'>Bestellen!</button></a>";
      }
      else {
        $shoppingcard .= "<center><h2 class='shoppingcard-message col-12'>Uw winkelmandje is leeg!</h2></center>";
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
