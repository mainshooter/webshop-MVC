var shoppingcard;
var product;
(function() {
  shoppingcard = {
    add: function(productID) {
      console.log("RUN");
      var result = shoppingcard.ajax("?op=shoppingcardAdd&productID=" + productID + "&amount=1");
      console.log(result);
      shoppingcard.count();
    },
  count: function() {
    // This function gets all products from the shoppingcard counts the row - php
    // Displays it
    var result = shoppingcard.ajax("?op=shoppingcardCounter");
    document.getElementById('shoppingcardCount').innerHTML = result;
    // $('shoppingcardCount').innerHTML = result.responseText;
  },
  remove: function(productID) {
    // Removes a item from the shoppingcard
    shoppingcard.ajax("?op=shoppingcardDelete&productID=" + productID + "");
    shoppingcard.display();
    shoppingcard.count();
  },
  update: function(productID, amount) {
    // Update the amount of one product in the shoppingcard
    shoppingcard.ajax("?op=shoppingcardUpdate&productID=" + productID + "&amount=" + amount + "");
    shoppingcard.display();
    shoppingcard.count();
  },
  display: function() {
    // Displays the shoppingcard after a update
    var result = shoppingcard.ajax("?op=shoppingcardShow");
    document.body.innerHTML = result;
  },
  goTo: function() {
    window.location.replace('?op=shoppingcardShow');
  },
  ajax: function(url) {
    // AJAX SYNC GET REQUEST
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", url, false);
    xhttp.send();

    return(xhttp.responseText);
  }
}
})();
(function() {
  title = {

  }
})();
