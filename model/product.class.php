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

    public function productIDs() {
      // Get all ID's from all products and returns it
      $db = new db();
      $sql = "SELECT idProduct FROM Product WHERE status=1";
      $input = array();

      return($db->readData($sql, $input));
    }

    public function countAllProducts() {
      // This function counts all products
      // And returns the number of products we have
      $db = new db();
      $sql = "SELECT idProduct FROM Product WHERE status=1";
      $input = array();

      return($db->countRows($sql, $input));
    }

    public function getProducts($page) {
      // This function gets all products for a page
      // And returns it
      $s = new Security();
      $page = $s->checkInput($page);

      $db = new db();
      $sql = "SELECT * FROM `Product` JOIN files_has_Product on files_has_Product.Product_idProduct=`idProduct` JOIN files ON files_has_Product.files_idfiles=files.idfiles WHERE status=1 LIMIT :page, 10";
      $input = array(
        "page" => $s->checkInput(intval($page) * 10)
      );
      // First number is how mutch we want to show
      // Seconds is where we start
      return($db->readData($sql, $input));
    }

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
