<?php

  require_once 'databasehandler.class.php';
  require_once 'security.class.php';
  require_once 'productview.class.php';

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
    public function add($newProductArray) {
      // Add a product to database
      // Parameter is send as a array
      $s = new Security();
      $db = new db();
      $sql = "INSERT INTO `Product`(`naam`, `prijs`, `beschrijving`, `EAN`, `Categorie_idCategorie`) VALUES (:naam, :prijs, :beschrijving, :EAN,:Categorie_idCategorie)";
      $input = array(
        // "Fabrikant_idFabrikant" => $s->checkInput($newProductArray['fabrikantID']),
        "naam" => $s->checkInput($newProductArray['naam']),
        "prijs" => $s->checkInput($newProductArray['prijs']),
        "beschrijving" => $s->checkInput($newProductArray['beschrijving']),
        "Categorie_idCategorie" => $s->checkInput($newProductArray['catagorieID']),
        "EAN" => $s->checkInput($newProductArray['EAN'])
      );
      return($db->CreateData($sql, $input));
    }

    /**
     * Delete a product by change there enable status to 0
     * @param  [INT] $productID [The ID of the product]
     * @return [string]            [The result from the db handler]
     */
    public function delete($productID) {
      // Removes product
      $s = new Security();
      $db = new db();
      $filehandler = new filehandler();

      $sql = "SELECT * FROM files JOIN files_has_Product on files.idfiles=files_has_Product.idfiles_has_Product WHERE Product_idProduct=:productID";
      $input = array(
        "productID" => $productID
      );
      $result = $db->readData($sql, $input);

      $fileID = '';
      foreach ($result as $key) {
        $fileID = $key['idfiles'];
        $filehandler->fileName = $key['filenaam'];
        $filehandler->filePath = '../file/uploads/';
        $filehandler->deleteFile();
      }
      $sql = "DELETE FROM files WHERE idfiles=:fileID";
      $input = array(
        "fileID" => $fileID
      );
      $db->DeleteData($sql, $input);

      $sql = "DELETE FROM `files_has_Product` WHERE `Product_idProduct`=:productID";
      $input = array(
        "productID" => $s->checkInput($productID)
      );
      $db->DeleteData($sql, $input);

      $sql = "DELETE FROM `Product` WHERE idProduct=:productID";
      $input = array(
        "productID" => $s->checkInput($productID)
      );
      // echo $db->DeleteData($sql, $input);
      return($db->DeleteData($sql, $input));
    }

    /**
     * Updates a product
     * @param  [array] $updateProductArray [The array that contains the new values for a product]
     * @return [string]                     [The result from the db handler]
     */
    public function update($updateProductArray) {
      // Update product
      // expexts an array with the values
      $s = new Security();
      $db = new db();
      $sql = "UPDATE `Product` SET `naam`=:naam,`prijs`=:prijs,`beschrijving`=:beschrijving, EAN=:EAN WHERE idProduct=:productID";
      $input = array(
        "naam" => $s->checkInput($updateProductArray['naam']),
        "prijs" => $s->checkInput($updateProductArray['prijs']),
        "beschrijving" => $s->checkInput($updateProductArray['beschrijving']),
        "EAN" => $s->checkInput($updateProductArray['EAN']),
        "productID" => $s->checkInput($updateProductArray['productID'])
      );
      return($db->UpdateData($sql, $input));
    }

    /**
     * Get the details for a product by a orderID
     * @param  [INT] $id [productID]
     * @return [array]     [array with the result from the database]
     */
    public function details($id) {
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
      $sql = "SELECT * FROM `Product` JOIN files_has_Product on files_has_Product.Product_idProduct=`idProduct` JOIN files ON files_has_Product.files_idfiles=files.idfiles WHERE status=1 LIMIT :page," . $limit;
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
        return(intval($row['prijs']));
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

    public function getCatagories() {
      // This function get all the catagories and returns it as a array
      $db = new db();
      $sql = "SELECT * FROM catagories";
      $input = array();

      return($db->readData($sql, $input));
    }

    public function getProductsFromCatagories() {
      // This function gets all products form a catagorie and returns it as a array
    }
  }


?>
