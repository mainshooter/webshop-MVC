<?php

  class HtmlGenerator {

    /**
     * Prepares for the generate of the HTML selectBOS
     * @param  [string] $type [The colom we want to display]
     * @param  [INT] $selectedContactID [The selected contact]
     * @return [string HTML] $selectBox [generated selectBOX]
     */
    public function prepareGenerateSelectBox($type, $selectedContactID) {
      $ContactsService = new ContactsService();
      $contacts = $ContactsService->readContacts('');

      $columNames = ['contactID', $type, $selectedContactID];

        $class = 'class="inputAjax"';

      $selectBox = $this->createSelectBox($contacts, $columNames, $class);
      return($selectBox);
    }

    /**
     * Create selectbox with the posibility to highlight a select option when the
     * columNames[0] == $highlateID/columNames[2]
     * @param  [assoc array] $arr [the result from the db]
     * @param  [array] $columNames [All the columNames includeing the highlatedID]
     * @param  [string] $JSevent  [A string with the event we want to use in AJAX]
     * @return [string] [With the HTML selectBox]
     */
    public function createSelectBox($arr, $columNames, $class) {
      $highlateID = ISSET($columNames[2])?$columNames[2]: NULL;
      $JSevent = ISSET($JSevent)?$JSevent: NULL;

      $selectBox = "<select " . $class . ">";
      foreach ($arr as $key => $value) {
        if ($value[$columNames[0]] == $highlateID) {
          $selectBox .= '<option value="' . $value[$columNames[0]] . '" selected>' . $value[$columNames[1]] . '</option>';
        }

        else {
          $selectBox .= '<option value="' . $value[$columNames[0]] . '">' . $value[$columNames[1]] . '</option>';
        }
    }
    $selectBox .= "</select>";
    return($selectBox);
  }

  /**
   * Generates the li items for a ul
   * @param  [assoc array] $arr [The result from the DB with the keys and values]
   * @return [string / html]      [generated <li>s]
   */
  public function generateUnorderList($arr) {
    $unOrderList = '';

    foreach ($arr as $row) {
      foreach ($row as $key => $value) {
        $unOrderList .= '<li>' . $key . ': ' . $value . '</li>';
      }
    }

    return($unOrderList);
  }

  /**
   * This function generates The HTML output for a order
   * @param  [assoc array] $headers [The name of all rows]
   * @param [assoc array] $orderItems [All order items]
   * @return [string HTML]          [The generated html output]
   */
  public function generateOrderTable($headers, $orderItems) {
    $html = '<table>';
    foreach ($headers as $row) {
      $html .= '<tr>';
      foreach ($row as $key => $value) {
        $html .= '<th>' . $key . '</th>';
      }
      $html .= '</tr>';
    }

    foreach ($orderItems as $row) {
      $html .= '<tr>';
      foreach ($row as $key => $value) {
        $html .= '<td>' . str_replace('.', ',', $value) . '</td>';
      }
      $html .= '</tr>';
    }

    $html .= '</table>';
    return($html);
  }
}

?>
