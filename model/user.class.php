<?php
require_once 'databasehandler.class.php';
require_once 'security.class.php';

 class User {
   private $mail;
   private $password;
   private $loginToken;

   function __construct() {
     $this->loginToken = 'h79vr29hu3pqhf-249pgae';
   }

   /**
    * User login handler
    * @param  [string] $userInputMail     [The mail adress that the user filled in]
    * @param  [string] $userInputPassword [The password the user filled in]
    */
   public function userLogin($userInputMail, $userInputPassword) {
     if ($this->checkIfEmailExists($userInputMail)) {

       $orginalHashedPassword = $this->getOrginalPassword($userInputMail);

       if ($this->validatePassword($userInputPassword, $orginalHashedPassword)) {
         $this->setLoginToken();
       }
     }
   }

   /**
    * Sets the login token when the client has succesfully logged in
    */
   private function setLoginToken() {
     $_SESSION['loginToken'] = $this->loginToken;
   }

   /**
    * Checks if a email exists in the db
    * @param  [stirng] $userMailInput [The input from the user that contains the mail adress]
    * @return [boolean]                [If the mail exists in the db]
    */
   private function checkIfEmailExists($userMailInput) {
     $db = new db();
     $s = new Security();

     $sql = "SELECT email FROM user WHERE email=:mail";
     $input = array(
       "mail" => $s->checkInput($userMailInput)
     );
     $result = $db->countRows($sql, $input);

     if ($result > 0) {
       return(true);
     }
     else if ($result == 0) {
       return(false);
     }
   }

   /**
    * Validate a if the password that was filled in was correct
    * @param  [string] $userInputPassword [The password that the client filled in]
    * @param  [hashed string] $hashedPassword    [The hashed password from the db]
    * @return [boolean]      [If the passwords are the same]
    */
   private function validatePassword($userInputPassword, $hashedPassword) {
     $s = new Security();

     if (password_verify($userInputPassword, $hashedPassword)) {
       return(true);
     }
     else {
       return(false);
     }
   }

   /**
    * Gets the orginal hashed password from the database
    * @param  [stirng] $userMail [The mail of the user]
    * @return [string]           [The hashed password from the db]
    */
   private function getOrginalPassword($userMail) {
     $db = new db();
     $s = new Security();

     $sql = "SELECT wachtwoord FROM user WHERE email=:mail";
     $input = array(
       "mail" => $s->checkInput($userMail)
     );
     $result = $db->readData($sql, $input);

     foreach ($result as $key) {
       return($key['wachtwoord']);
     }
   }

   public function registerNewUser($newEmail, $newPassword) {
     $db = new db();
     $s = new Security();

     $password = $this->generateHashPassword($s->checkInput($newPassword));

     $sql = "INSERT INTO `user`(email`, `wachtwoord`) VALUES (:mail, :password)";
     $input = array(
       "mail" => $s->checkInput($newEmail),
       "password" => $s->checkInput($password)
     );

     $db->createData($sql, $input);
   }

   /**
    * Generates a hashed password
    * @param  [string] $password [The incomeping unhashed password]
    * @return [string hashed] [The new password]
    */
   private function generateHashPassword($password) {
     $s = new Security();
     $password = $this->checkInput($password);

     $password = password_hash($password, PASSWORD_DEFAULT);

     return($password);
   }


   /**
    * Checks the login token for a user
    * @return [boolean] [Returns if the login token is the same]
    */
   private function checkLoginToken() {
     // Checks if the user has the same login token
     // Returns true or false
     if (ISSET($_SESSION['logintoken'])) {
       if ($_SESSION['logintoken'] === $this->loginToken) {
         return(true);
       }
       else {
         return(false);
       }
     }
     else {
       return(false);
     }
   }

   public function isLogedIn() {
     return($this->checkLoginToken());
   }
 }


?>
