<?php
  require_once 'model/shoppingcard.class.php';
  require_once 'model/product.class.php';
  require_once 'model/databasehandler.class.php';
  require_once 'model/mail.class.php';
  require_once 'model/Customer.class.php';
  require_once 'model/HtmlGenerator.class.php';

  class Order {
    var $shoppingcard;
    var $oderNumer;

    private $Customer;
    private $Mail;
    private $Product;
    private $HtmlGenerator;

    function __construct() {
      // Runs when class is created
      $this->Mail = new Mail();
      $this->Product = new Product();
      $this->Customer = new Customer();
      $this->HtmlGenerator = new HtmlGenerator();
    }

    public function createOrder($orderID) {
      // This function creates the order
      // Parameter is a string or number from the orderID
      // This function is used to save every product item a customer has ordered

      try {
        $shoppingcard = new shoppingcard();
        $product = new product();

        $shoppingcardProductIDs = $shoppingcard->getProductIDs();

        foreach ($shoppingcardProductIDs as $productID) {
          $productPrice = $product->productPrice($productID);
          $productAmountInShoppingcard = $shoppingcard->getProductAmount($productID);

          $db = new db();

          $sql = "INSERT INTO order_item (Order_idOrder, prijs, Product_idProduct, aantal) VALUES (
            :orderID,
            :price,
            :productID,
            :amount
          )";
          $input = array(
            "orderID" => $orderID,
            "price" => $productPrice,
            "productID" => $productID,
            "amount" => $productAmountInShoppingcard
          );

          $db->CreateData($sql , $input);
          return(1);
        }
      } catch (Exception $e) {
        return($e->getMessage());
      }
    }

    /**
     * Removes a order by the paymentID
     * We do that when they order has not been paid
     * @param  [INT] $paymentID [The ID from a payment]
     */
    public function removeOrder($paymentID) {
      $db = new db();
      $s = new Security();

      $sql = "DELETE FROM `Order` WHERE paymentID=:paymentID";
      $input = array(
        "paymentID" => $s->checkInput($paymentID)
      );
      $db->deleteData($sql, $input);
    }

    /**
     * Gets the name of the customer who ordered by orderID
     * @param  [INT] $orderID [ID of the order]
     * @return [string]          [full name of the customer]
     */
    public function getNameOfThePersonWhoOrder($orderID) {
      // Gets the first and lastname of the person who ordered and returns it as a string
      $db = new db();
      $s = new Security();

      $sql = "SELECT klant_voornaam, klant_achternaam FROM `Order` WHERE idOrder=:orderID";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );
      $result = $db->readData($sql, $input);

      foreach ($result as $key) {
        if (!ISSET($key['klant_tussenvoegsel'])) {
          // If the customer don't have a tussenvoegels we need to clear it
          // That it because other wise it will be saying NULL
          $key['klant_tussenvoegsel'] = '';
        }
        else {
          $key['klant_tussenvoegsel'] .= ' ';
        }
        return($key['klant_voornaam'] . ' ' . $key['klant_tussenvoegsel'] . $key['klant_achternaam']);
      }
    }

    /**
     * Get the email of a customer by the orderID
     * @param  [INT] $orderID [The ID of the order]
     * @return [string]          [customer email adress]
     */
    public function getEmailOfThePersonWhoOrder($orderID) {
      // Gets the email adress from a order by orderID
      // Returns it as a string
      $db = new db();
      $s = new Security();

      $sql = "SELECT klant_email FROM `Order` WHERE idOrder=:orderID";
      $input = array(
        "orderID" => $orderID
      );
      $result = $db->readData($sql, $input);
      foreach ($result as $key) {
        return($key['klant_email']);
      }
    }

    /**
     * Gets the content of a order By orderID
     * @param  [INT] $orderID [The ID of the order]
     * @return [array]          [Containing the result from the db]
     */
    public function getOrder($orderID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT * FROM `Order` WHERE idOrder=:orderID";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );
      $order = $db->readData($sql, $input);
      return($order);
    }

    /**
     * Gets all items that a customer has orderd
     * @param  [INT] $orderID [The ID of the order]
     * @return [assoc array] [With the result from the db]
     */
    public function getOrderItems($orderID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT * FROM order_item WHERE Order_idOrder=:orderID";
      $input = array(
        "orderID" => $orderID
      );

      return($db->readData($sql, $input));
    }

    /**
     * Gets the orderItems with the name of the product, amount and the price of the order
     * @param  [INT] $orderID [The ID of the Order]
     * @return [assoc array] [The result from the DB]
     */
    public function getOrderItemsForHtmlGenerator($orderID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT naam, aantal, order_item.prijs FROM order_item JOIN Product ON idProduct=Product_idProduct WHERE Order_idOrder=:orderID";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );

      return($db->readData($sql, $input));
    }

    /**
     * Gets the headers for the product naam, aantal and price
     * @param  [INT] $orderID [The ID of the order]
     * @return [assoc array] [Result from the db]
     */
    public function getHeadersForOrderItemsForHtmlGenerator($orderID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT naam, aantal, order_item.prijs FROM order_item JOIN Product ON idProduct=Product_idProduct WHERE Order_idOrder=:orderID LIMIT 1";
      $input = array(
        "orderID" => $s->checkInput($orderID)
      );

      return($db->readData($sql, $input));
    }

    /**
     * Gets the orderID by using the paymentID given by mollie
     * @param  [INT] $paymentID [The paymentID geven by mollie]
     * @return [INT] $orderID   [The ID of the order]
     */
    protected function getOrderID($paymentID) {
      $db = new db();
      $s = new Security();

      $sql = "SELECT idOrder FROM `Order` WHERE paymentID=:paymentID LIMIT 1";
      $input = array(
        "paymentID" => $paymentID
      );

      $result = $db->readData($sql, $input);

      foreach ($result as $key) {
        return($key['idOrder']);
      }
    }

    /**
     * Generates a mail for the customer that we have is order in our system
     * @param  [INT] $orderID [The ID of the Order]
     */
    public function generateMailToCustomerAboutOrderConfirmation($orderID) {
      $this->Mail->subject = "Bevestiging order: " . $orderID;
      $mailContent = "
        <div>Beste " . $this->getNameOfThePersonWhoOrder($orderID) . ",<br /></div>
        <div>We hebben uw order in behandeling genomen.</div>
      ";

      $orderList = $this->getOrderItems($orderID);
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
          <td>' . $productNaam = $this->Product->getProductName($key['Product_idProduct']) . '</td>
          <td>' . $key['aantal'] . '</td>
          <td>' . str_replace('.', ',', $key['prijs']) . '</td>
          <td>' . str_replace('.', ',', $key['aantal'] * $key['prijs']) . '</td>
        </tr>
        ';
      }
      $mailContent .= '</table>';
      $this->Mail->messageInHTML = $mailContent;
      $this->Mail->adressName = $this->getNameOfThePersonWhoOrder($orderID);
      $this->Mail->adress = $this->getEmailOfThePersonWhoOrder($orderID);

      $this->Mail->sendMail();
    }

    /**
     * Sends a mail to the owner to start makeing a product ready
     * @param  [INT] $orderID [The orderID]
     */
    public function sendOwnerMailToReadAOrder($orderID) {
      $Mail = new Mail();
      $customer = new Customer();
      $HtmlGenerator = new HtmlGenerator();

      $headers = $this->getHeadersForOrderItemsForHtmlGenerator($orderID);
      $orderItems = $this->getOrderItemsForHtmlGenerator($orderID);
      $customerInfo = $customer->getCustomerInfoByOrderID($orderID);

      $Mail->adress = "498883@edu.rocmn.nl";
      $Mail->adressName = "Multiversum Webshop";
      $Mail->subject = "Er is een nieuwe order: " . $orderID;

      $Mail->messageInHTML = '<p>Er is een nieuwe order binnen voor:</p>';

      $Mail->messageInHTML .= '<ul>';

        $Mail->messageInHTML .= $HtmlGenerator->generateUnorderList($customerInfo);

      $Mail->messageInHTML .= '</ul>';

      $Mail->messageInHTML .= $HtmlGenerator->generateOrderTable($headers, $orderItems);

      $Mail->sendMail();
    }



  }



?>
