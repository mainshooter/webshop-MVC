var file;
var product;
(function() {
  file = {
    delete: function(fileID) {
      // Deletes a file after confermation
      var confirm = confirm('Weet u dit zeker?');
      if (confirm == true) {
        ajax("/admin/crud.php?product=deleteImage&fileID=" + fileID + "");
      }
    },
  }
})();

(function() {
  product = {
    delete: function(productID) {
      var confirmation = confirm('Weet u dit zeker?');
      if (confirmation == true) {
        console.log(ajax("?op=adminDeleteProduct&productID=" + productID));
        window.location.href = "?op=dashboard";
      }
    }
  }
})();

function ajax(url) {
  // AJAX SYNC GET REQUEST
  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", url, true);
  xhttp.send();

  return(xhttp.responseText);
}
