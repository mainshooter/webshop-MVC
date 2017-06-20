var shoppingcard;
var product;
var messagePrompt;

(function() {
  messagePrompt = {
    /**
     * Sets a title for the message prompt
     * @param  {[string]} title [The title for the message prompt]
     */
    setTitle: function(title) {
      document.getElementById('messagePrompt').getElementsByTagName('h3')[0].innerHTML = title;
    },
    /**
     * Set the message for the message prompt
     * @param  {[string]} message [The message for the message prompt]
     */
    setMessage: function(message) {
      document.getElementById('messagePrompt').getElementsByTagName('p')[0].innerHTML = message;
    },
    showPrompt: function() {
      var prompt = document.getElementById('messagePrompt');
      prompt.style.display = 'block';

      setTimeout(function() {
        prompt.style.opacity = '1';
      },500);
    },
    hidePrompt: function() {
      var prompt = document.getElementById('messagePrompt');
      prompt.style.opacity = '0';

      setTimeout(function() {
        prompt.style.display = 'none';
      },500);
    }
  }
})();

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
