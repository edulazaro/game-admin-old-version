(function ($) {
  $(function () {
    $('.gameadmin-input-color').wpColorPicker();
  });
}(jQuery));

jQuery(document).ready( function() { jQuery('.postbox h3').prepend('<a class="togbox">+</a> '); jQuery('.postbox h3').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); }); });