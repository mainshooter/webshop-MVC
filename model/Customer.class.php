<?php
  require_once 'databasehandler.class.php';

  class Customer {
    var $firstname;
    var $tussenvoegsel;
    var $lastname;
    var $email;
    var $street;
    var $housenumber;
    var $addon;
    var $zipcode;
    var $country;

    /**
     * [Saves a customer to the database]
     * @return [int] [Returns the orderID for the created customer]
     */
    public function saveCustomerToDB() {
      $db = new db();

      $sql = "INSERT INTO `Order` (
        `klant_voornaam`,
        `klant_achternaam`,
        `klant_tussenvoegsel`,
        `klant_straat`,
        `klant_huisnummer`,
        `klant_postcode`,
        `klant_email`,
        `klant_huisnummertoevoegingen`
      ) VALUES (
        :klant_voornaam,
        :klant_achternaam,
        :klant_tussenvoegsel,
        :klant_straat,
        :klant_huisnummer,
        :klant_postcode,
        :klant_email,
        :klant_huisnummertoevoegingen
      )";

      $input = array(
        "klant_voornaam" => $this->firstname,
        "klant_achternaam" => $this->lastname,
        "klant_tussenvoegsel" => $this->tussenvoegsel,
        "klant_straat" => $this->street,
        "klant_huisnummer" => $this->housenumber,
        "klant_postcode" => $this->zipcode,
        "klant_email" => $this->email,
        "klant_huisnummertoevoegingen" => $this->addon
      );

      return($db->CreateData($sql, $input));
    }

  }


?>
