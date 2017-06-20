<?php

  require_once 'databasehandler.class.php';
  require_once 'security.class.php';
  require_once 'productview.class.php';
  require_once 'filehandler.class.php';

  class Product {
    var $id;
    var $name;
    var $discription;
    var $price;
    // Properties

    /**
     * [add description]
     * @param [array] $newProductArray [contains the new values for a row in the product table]
     * @return [type] $productID [Returns the productID of the creates row]
     */
    public function createProduct($newProductArray) {
      // Add a product to database
      // Parameter is send as a array
      $FileHandler = new Filehandler();
      $s = new Security();
      $db = new db();

      $FileHandler->fileName = $_FILES['file_upload']['name'];
      $FileHandler->filePath = 'file/uploads/';
      if ($FileHandler->checkFileExists() == false) {
        $sql = "INSERT INTO `Product`(`naam`, `prijs`, `beschrijving`, `EAN`, `Categorie_idCategorie`, `status`) VALUES (:naam, :prijs, :beschrijving, :EAN,:Categorie_idCategorie, '1')";
        $input = array(
          // "Fabrikant_idFabrikant" => $s->checkInput($newProductArray['fabrikantID']),
          "naam" => $s->checkInput($newProductArray['productName']),
          "prijs" => $s->checkInput($newProductArray['productPrice']),
          "beschrijving" => $s->checkInput($newProductArray['discription']),
          "Categorie_idCategorie" => $s->checkInput($newProductArray['catagorie']),
          "EAN" => $s->checkInput($newProductArray['ean-code']),
        );
        $newProductID = $db->CreateData($sql, $input);

        $FileHandler->uploadFile();
        $fileID = $FileHandler->saveFileLocation($_FILES['file_upload']['name'], 'file/uploads/');

        $this->linkProductToFile($newProductID, $fileID);
      }
      else {
        return("We know this file <br />" . $_FILES['file_upload']['name']);
      }
      header("Location: ?op=dashboard");
    }

    /**
     * Delete a product by change there enable status to 0
     * @param  [INT] $productID [The ID of the product]
     * @return [string]            [The result from the db handler]
     */
    public function deleteProduct($productID) {
      // Removes product
      $s = new Security();
      $db = new db();

      $sql = "UPDATE Product SET Product.status=0 WHERE idProduct=:productID";
      $input = array(
        "productID" => $s->checkInput($productID)
      );
      $result = $db->readData($sql, $input);
      header("Location: ?op=dashboard");
    }

    /**
     * Updates a product and the image from a product
     * @param  [array] $updateProductArray [The array that contains the new values for a product]
     * @return [string]                     [The result from the db handler]
     */
    public function updateProduct($updateProductArray) {
      // Update product
      // expexts an array with the values
      $s = new Security();
      $db = new db();
      $Filehandler = new filehandler();

      $sql = "UPDATE `Product` SET `naam`=:naam,`prijs`=:prijs,`beschrijving`=:beschrijving, EAN=:EAN WHERE idProduct=:productID";
      $input = array(
        "naam" => $s->checkInput($updateProductArray['product_name']),
        "prijs" => $s->checkInput($updateProductArray['product_price']),
        "beschrijving" => $s->checkInput($updateProductArray['product_description']),
        "EAN" => $s->checkInput($updateProductArray['EAN']),
        "productID" => $s->checkInput($updateProductArray['productID'])
      );
      $result = $db->UpdateData($sql, $input);

      if (ISSET($_FILES['file_upload']['name'])) {
        // If there is a file upload
        if ($_FILES['file_upload']['name'] > '') {
          $product_has_file = $this->checkForProductPhoto($_REQUEST['productID']);
          // We check if we had a file
          // If there is we first need to delete the image
          // And then upload the new one
            if ($product_has_file >= 1) {
              $pictureID = $this->getProductPictureID($_REQUEST['productID']);
              $fileName = $this->getProductPictureFileName($pictureID);
              // Get the id from the picture and the filename

              $Filehandler->filePath = "file/uploads/";
              $Filehandler->deleteFileDatabase($pictureID);

              $Filehandler->fileName = $_FILES['file_upload']['name'];
              $Filehandler->filePath = 'file/uploads/';
              $Filehandler->uploadFile();
              // Uploads the file

              $Filehandler->filePath = "file/uploads/";
              $fileID = $Filehandler->saveFileLocation($_FILES['file_upload']['name'], 'file/uploads/');
              $this->linkProductToFile($_REQUEST['productID'], $fileID);
            }
            else {
              // There wasn't a picture for this product
              // So we only need to upload one
              $Filehandler->fileName = $_FILES['file_upload']['name'];
              $Filehandler->filePath = 'file/uploads/';
              if ($Filehandler->checkFileExists() == false) {
                $Filehandler->uploadFile();
                $fileID = $Filehandler->saveFileLocation($_FILES['file_upload']['name'], 'file/uploads/');

                $this->linkProductToFile($updateProductArray['productID'], $fileID);
              }
              else {
                return("File exists");
              }
            }
          }
      }
      header("Location: ?op=dashboard");
    }

    /**
     * Get the details for a product by a orderID
     * @param  [INT] $id [productID]
     * @return [array]     [array with the result from the database]
     */
    public function productDetails($id) {
      // This function gets the detailed page
      $s = new Security();
      $page = $s->checkInput($id);

      $db = new db();
      $sql = "SELECT * FROM `Product` JOIN files_has_Product on files_has_Product.Product_idProduct=`idProduct` JOIN files ON files_has_Product.files_idfiles=files.idfiles WHERE idProduct=:productID AND status=1";
      $input = array(
        "productID" => $s->checkInput($id)
      );
      return($db->readData($sql, $input));
    }

    /**
     * Gets all active products from the db
     * @return [assoc array] [The result from the db]
     */
    public function getAllProducts() {
      $Db = new db();

      $sql = "SELECT * FROM `Product` JOIN files_has_Product on files_has_Product.Product_idProduct=`idProduct` JOIN files ON files_has_Product.files_idfiles=files.idfiles WHERE status=1";
      $input = array();

      return($Db->readData($sql, $input));
    }

    /**
     * Get the productSpecifications for a product
     * @param  [INT] $productID [The ID for the product to get all productSpecs]
     * @return [array]            [DB result with the productSpecifications]
     */
    public function productSpec($productID) {
      // This function get the products specs from a product
      // It expexts as a parameter a productID
      // Returns array
      $db = new db();
      $s = new Security();

      $sql = "SELECT * FROM Specificatie WHERE Product_idProduct=:productID AND status=1";
      $input = array(
        "productID" => $s->checkInput($productID)
      );
      return($db->readData($sql, $input));
    }

    /**
     * Get all productIDs from the product table
     * @return [array] [All productIDs]
     */
    public function productIDs() {
      // Get all ID's from all products and returns it
      $db = new db();
      $sql = "SELECT idProduct FROM Product WHERE status=1";
      $input = array();

      return($db->readData($sql, $input));
    }

    /**
     * Counts howmany products there are with a status 1 / enables
     * @return [number] [how many rows there are]
     */
    public function countAllProducts() {
      // This function counts all products
      // And returns the number of products we have
      $db = new db();
      $sql = "SELECT idProduct FROM Product WHERE status=1";
      $input = array();

      return($db->countRows($sql, $input));
    }


    public function getProducts($page, $limit) {
    /**
     * Get all the products with details for a page
     * @param  [number] $page [the page we want to see]
     * @return [array]       [result from the database with the products]
     */
      $s = new Security();
      $page = $s->checkInput($page);
      $limit = $s->checkInput($limit);

      $db = new db();
      $sql = "SELECT * FROM `Product` JOIN files_has_Product on files_has_Product.Product_idProduct=`idProduct` JOIN files ON files_has_Product.files_idfiles=files.idfiles WHERE status=1 ORDER BY idProduct DESC LIMIT :page," . $limit;
      $input = array(
        "page" => $s->checkInput(intval($page) * $limit)
      );
      // First number is how mutch we want to show
      // Seconds is where we start
      return($db->readData($sql, $input));
    }

    public function getNewestProducts() {
      // This function gets all products for a page
      // And returns it
      $db = new db();
      $sql = "SELECT * FROM `Product` JOIN files_has_Product on files_has_Product.Product_idProduct=`idProduct` JOIN files ON files_has_Product.files_idfiles=files.idfiles WHERE status=1 ORDER BY idProduct DESC LIMIT 2";
      $input = array();
      // First number is how mutch we want to show
      // Seconds is where we start
      return($db->readData($sql, $input));
    }


    /**
     * Gets a product price for one product
     * @param  [INT] $productID [the product we need a price from]
     * @return [decimal]            [contains the price for one product]
     */

    public function productPrice($productID) {
      // Gets the price of one product
      // And returns it as a number or string
      $db = new db();
      $s = new Security();

      $sql = "SELECT prijs FROM Product WHERE idProduct=:productID LIMIT 1";
      $input = array(
        "productID" => $s->checkInput($productID)
      );
      $result = $db->readData($sql, $input);

      foreach ($result as $row) {
        return(floatval($row['prijs']));
      }
    }

    /**
     * Gets the name for one product by the productID
     * @param  [INT] $productID [the id for one product]
     * @return [string]            [The name of a product]
     */
    public function getProductName($productID) {
      // Get the product name by the productID
      // Returns the product name as a string
      $db = new db();
      $s = new Security();

      $sql = "SELECT naam FROM Product WHERE idProduct=:productID";
      $input = array(
        "productID" => $s->checkInput($productID)
      );
      $result = $db->readData($sql, $input);
      foreach ($result as $key) {
        return($key['naam']);
      }
    }

    /**
     * Checks if a product has a picture
     * @param  [INT] $productID [The ID for the product]
     * @return [number]            [Returns how many rows]
     */
    public function checkForProductPhoto($productID) {
      // This function checks if the db contains a picture for a product
      // Returns 1 or higer if a product has a picture
      // Returns 0 when it didn't found anything
      $db = new db();
      $s = new Security();

      $sql = "SELECT * FROM files_has_Product WHERE Product_idProduct=:productID";
      $input = array(
        "productID" => $s->checkInput($productID)
      );
      $rowCount = $db->countRows($sql, $input);
      return($rowCount);
    }

    /**
     * get all picutres from a product by id and returns it
     * @param  [INT] $productID [The ID of the product]
     * @return [INT]            [the file of the ID]
     */
    public function getProductPictureID($productID) {
      // This function get the id for the picture file
      // Returns the fileID as string or number
      $s = new Security();
      $db = new db();

      $sql = "SELECT files_idfiles FROM files_has_Product WHERE Product_idProduct=:productID";
      $input = array(
        "productID" => $s->checkInput($productID)
      );
      $fileID = $db->readData($sql, $input);

      foreach ($fileID as $key) {
        return($key['files_idfiles']);
      }
    }

    /**
     * Gets the fileName by a productID
     * @param  [INT] $fileID [The ID of the product]
     * @return [string]         [filename]
     */
    public function getProductPictureFileName($fileID) {
      // Gets the picture file name
      // And returns it as a string
      $db = new db();
      $s = new Security();

      $sql = "SELECT filenaam FROM files WHERE idfiles=:fileID";
      $input = array(
        "fileID" => $s->checkInput($fileID)
      );
      $fileNameArray = $db->readData($sql, $input);
      foreach ($fileNameArray as $key) {
        return($key['filenaam']);
      }
    }

    /**
     * Links a file with a product
     * @param  [INT] $productID [The ID of the product we are need to link]
     * @param  [INT] $fileID    [The ID of the file]
     */
    public function linkProductToFile($productID, $fileID) {
      // This function links a product to a file
      $db = new db();
      $s = new Security();

      $sql = "INSERT INTO files_has_Product (files_idfiles, Product_idProduct) VALUES (:fileID, :productID)";
      $input = array(
        "fileID" => $s->checkInput($fileID),
        "productID" => $s->checkInput($productID)
      );
      $db->CreateData($sql, $input);
    }

    /**
     * Gets all catagories from the db
     * @return [assoc array] [The result from the db]
     */
    public function getCatagories() {
      // This function get all the catagories and returns it as a array
      $db = new db();
      $sql = "SELECT * FROM catagories";
      $input = array();

      return($db->readData($sql, $input));
    }
  }


?>
