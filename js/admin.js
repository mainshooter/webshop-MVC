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

ajax: function(url) {
  // AJAX SYNC GET REQUEST
  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", url, false);
  xhttp.send();

  return(xhttp.responseText);
}
