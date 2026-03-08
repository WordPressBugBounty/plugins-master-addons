(function(){
(function($) {
  $("body").on("click", ".jltma-popup .popup-dismiss", function(evt) {
    evt.preventDefault();
    $(this).closest(".jltma-popup").fadeOut(200);
  });
  $("body").on("click", ".jltma-upgrade-popup .popup-dismiss", function(evt) {
    evt.preventDefault();
    $(this).closest(".jltma-upgrade-popup").fadeOut(200);
  });
  $("body").on("click", ".jltma-pro-disabled", function(evt) {
    evt.preventDefault();
    $(".jltma-upgrade-popup").fadeIn(200);
  });
})(jQuery);
})();
