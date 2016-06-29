(function($) {
$(document).on('click', '#wp-admin-bar-wpis li > a', function(e) {
  e.preventDefault();
  var instance = $(this).attr('href').substr(1);
  if (instance == 'exit') {
    document.cookie = "wpp_shadow=;path=/";
  } else {
    document.cookie = "wpp_shadow=" + instance + ";path=/";
  }
  location.reload();
});
})(jQuery);
