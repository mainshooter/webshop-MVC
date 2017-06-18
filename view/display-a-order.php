<?php
  include 'header.php';

  $orderDisplay = '<div class="col-2"></div>';
    $orderDisplay .= '<div class="col-10 order-display">';
    foreach ($order as $key) {
      $orderDisplay .= '
        <h2>Order: ' . $key['idOrder'] . '</h2>
        <br>
        <div>Beste ' . $key['klant_voornaam'] . ',</div><br />
        We hebben je order ontvangen.<br />

        De betaal status van je bestelling is: ' . $betaal_status . '
        <br />
        <br />
        De status van je order is: ' . $key['order_status'] . '
        <br>
        <br>
        <h2>Je hebt het volgende besteld</h2>
      ';
    }

    $orderDisplay .= '
      <table>
        <tr>
          <th>Productnaam</th>
          <th>Aantal</th>
          <th>Prijs</th>
          <th>Totaal</th>
        </tr>
    ';

    foreach ($orderItems as $key) {
      $orderDisplay .= '
      <tr>
        <td>' . $key['naam'] . '</td>
        <td>' . $key['aantal'] . '</td>
        <td>' . str_replace('.', ',', $key['prijs']) . '</td>
        <td>' . str_replace('.', ',', $key['aantal'] * $key['prijs']) . '</td>
      </tr>
      ';
    }

    $orderDisplay .= '</table>';

    $orderDisplay .= '<br><br><p>We wensen u alvast veel plezier met uw VR-bril,
      <br>
      <br>
      <br>
      Met vriendelijke groet,
      <br>
      <br>
      Multiversum
    </p>';

    $orderDisplay .= '</div>';
    $orderDisplay .= '<div class="col-2"></div>';

    echo $orderDisplay;
  include 'footer.php';
?>
