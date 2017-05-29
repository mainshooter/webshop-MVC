<?php

  class ShoppingcardView {
    public function displayShoppingCard($result, $amount, $productTotal) {
      $view = '';
      foreach ($result as $key) {
        $view .= '
          <div class="col-2">&nbsp;</div>
          <table class="col-8">
            <tr class="product winkelmandje-height-center">
              <td class="col-1"><img class="col-12" src="' . $key['pad'] . $key['filenaam'] . '"></td>
              <td class="col-5"><h2 class="left-text"><a href="?op=details&productID=' . $key['idProduct'] . '">' . $key['naam'] . '</a></h2></td>
              <td class="col-1">&euro;' . str_replace('.', ',', $key['prijs']) . '</td>
              <td class="col-1 right-text">Aantal: </td>
              <td class="col-1">'; $view .= $this->generateOptionNumbers($key['idProduct'], $amount); $view .='</td>
              <td class="col-1"><i class="fa fa-trash-o" aria-hidden="true" style="margin-top: 0.5em;" onclick="shoppingcard.remove(' . $key['idProduct'] . ')"></i></td>
              <td class="col-2">Totaal: &euro;' . $productTotal . '</td>
            </tr>
          </table>
          <div class="col-2">&nbsp;</div>
          <div class="col-12"></div>
        ';
      }
      return($view);
    }

    public function generateOptionNumbers($productID, $amount) {
      // Generates a select input field.
      // When the option is the same to the number we got for the option input
      // We set that as selected
      $selectField = '<select onchange="shoppingcard.update(\'' . $productID . '\', this.value);" class="col-12">';
      for ($i=0; $i < 10; $i++) {
        if ($amount == $i) {
          $selectField .= '<option value="' . $i .'" selected>' . $i . '</option>';
        }
        else {
          $selectField .= '<option value="' . $i .'">' . $i . '</option>';
        }
      }
      $selectField .= '</select>';
      return($selectField);
    }
  }

?>