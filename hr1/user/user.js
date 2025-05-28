document.addEventListener('DOMContentLoaded', function() {
  var alertBox = document.getElementById('alertBox');

  if (alertBox) {
    setTimeout(function() {
      var alertInstance = bootstrap.Alert.getOrCreateInstance(alertBox);
      if (alertInstance) {
        alertInstance.close();
      }

      if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('registered');
        url.searchParams.delete('registration_error');
        url.searchParams.delete('msg');
        window.history.replaceState({path: url.toString()}, '', url.toString());
      }
    }, 5000);
  }
});
