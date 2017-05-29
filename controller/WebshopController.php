<?php

  require_once 'model/product.class.php';
  require_once 'model/shoppingcard.class.php';
  require_once 'model/productview.class.php';
  require_once 'model/Shoppingcardview.php';
  require_once 'model/Customer.class.php';
  require_once 'model/security.class.php';
  require_once 'model/Order.php';
  require_once 'model/mail.class.php';

  class WebshopController {
    // Webshop controller
    private $product;
    private $shoppingcard;
    private $customer;
    private $order;
    private $mail;

    function __construct() {
      $this->product = new Product();
      $this->shoppingcard = new Shoppingcard();
      $this->customer = new Customer();
      $this->order = new Order();
      $this->mail = new Mail();
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

        else if ($op == 'createOrder') {
          // Gets the form that the customer needs to fill in
          include 'view/createCustomer.php';
        }

        else if ($op == 'betalen') {
          // We save the shoppingcard to the database
          // And save the product price of every product
          // Than we redirect the client to the payment provider

          $orderID =  $this->createOrder();
          $this->createConfirmationMailForOrder($orderID);
          $this->shoppingcard->clear();
        }

        else if ($op = 'productAdminList') {

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
        $shoppingcard .= "<button type='button' class='col-2'><a href='?op=createOrder'>Bestellen!</button>";
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

    public function createConfirmationMailForOrder($orderID) {
      $this->mail->subject = "Bevestiging order: " . $orderID;
      $mailContent = "
        <div>Beste " . $this->order->getNameOfThePersonWhoOrder($orderID) . ",</div>
        <div>We hebben uw order in behandeling genomen.</div>
      ";

      $orderList = $this->order->getOrderItems($orderID);
      $mailContent .= '<table>';
      $mailContent .= "
        <tr>
          <th>Product</th>
          <th>Hoeveelheid</th>
          <th>Prijs</th>
          <th>Totaal</th>
        </tr>
      ";
      foreach ($orderList as $key) {

        $mailContent .= '
        <tr>
          <td>' . $productNaam = $this->product->getProductName($key['Product_idProduct']) . '</td>
          <td>' . $key['aantal'] . '</td>
          <td>' . $key['prijs'] . '</td>
          <td>' . $key['aantal'] * $key['prijs'] . '</td>
        </tr>
        ';
      }
      $mailContent .= '</table>';
      $this->mail->messageInHTML = $mailContent;
      $this->mail->adressName = $this->order->getNameOfThePersonWhoOrder($orderID);
      $this->mail->adress = $this->order->getEmailOfThePersonWhoOrder($orderID);

      $this->mail->sendMail();
    }
  }
?>
