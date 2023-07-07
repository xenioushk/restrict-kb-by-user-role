;(function ($) {
  function bkbm_rkbur_installation_counter() {
    return $.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        action: "bkbm_rkbur_installation_counter", // this is the name of our WP AJAX function that we'll set up next
        product_id: BkbmRkburAdminData.product_id, // change the localization variable.
      },
      dataType: "JSON",
    })
  }

  if (typeof BkbmRkburAdminData.installation != "undefined" && BkbmRkburAdminData.installation != 1) {
    $.when(bkbm_rkbur_installation_counter()).done(function (response_data) {
      // console.log(response_data)
    })
  }
})(jQuery)
