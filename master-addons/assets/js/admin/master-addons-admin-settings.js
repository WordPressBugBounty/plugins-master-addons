(function(){
(function($) {
  "use strict";
  var JLTMA_Toaster = {
    container: null,
    init: function() {
      if (!this.container) {
        this.container = $('<div class="jltma-toaster-container"></div>');
        $("body").append(this.container);
      }
    },
    show: function(message, type, duration) {
      type = type || "success";
      duration = duration === void 0 ? 3e3 : duration;
      this.init();
      var toaster = $('<div class="jltma-toaster ' + type + '"><span class="jltma-toaster-icon ' + type + '-icon"></span><span class="jltma-toaster-content">' + message + '</span><button class="jltma-toaster-close"></button><div class="jltma-toaster-progress"></div></div>');
      this.container.append(toaster);
      toaster.find(".jltma-toaster-close").on("click", function() {
        JLTMA_Toaster.dismiss(toaster);
      });
      if (duration > 0) {
        setTimeout(function() {
          JLTMA_Toaster.dismiss(toaster);
        }, duration);
      }
      return toaster;
    },
    dismiss: function(toaster) {
      toaster.addClass("jltma-toaster-exit");
      setTimeout(function() {
        toaster.remove();
      }, 300);
    },
    success: function(message, duration) {
      return this.show(message, "success", duration);
    },
    error: function(message, duration) {
      return this.show(message, "error", duration);
    },
    warning: function(message, duration) {
      return this.show(message, "warning", duration);
    },
    info: function(message, duration) {
      return this.show(message, "info", duration);
    }
  };
  jQuery(document).ready(function($2) {
    "use strict";
    var saveHeaderAction = $2(".jltma-tab-dashboard-header-wrapper .jltma-tab-element-save-setting");
    $2(".jltma-master-addons-features-list input").on("click", function() {
      saveHeaderAction.addClass("jltma-addons-save-now");
      saveHeaderAction.removeAttr("disabled").css("cursor", "pointer");
    });
    $2("#jltma-api-forms-settings input, #jltma-addons-white-label-settings input").on("keyup", function() {
      saveHeaderAction.addClass("jltma-addons-save-now");
      saveHeaderAction.removeAttr("disabled").css("cursor", "pointer");
    });
    $2('#jltma-addons-white-label-settings input[type="checkbox"]').on("change", function() {
      saveHeaderAction.addClass("jltma-addons-save-now");
      saveHeaderAction.removeAttr("disabled").css("cursor", "pointer");
    });
    $2("#jltma-addons-elements .jltma-addons-enable-all, a.jltma-wl-plugin-logo, a.jltma-remove-button").on("click", function(e) {
      e.preventDefault();
      $2("#jltma-addons-elements .jltma-master-addons_feature-switchbox input:enabled").each(function(i) {
        $2(this).prop("checked", true).change();
      });
      saveHeaderAction.addClass("jltma-addons-save-now").removeAttr("disabled").css("cursor", "pointer");
    });
    $2("#jltma-addons-elements .jltma-addons-disable-all").on("click", function(e) {
      e.preventDefault();
      $2("#jltma-addons-elements .jltma-master-addons_feature-switchbox input:enabled").each(function(i) {
        $2(this).prop("checked", false).change();
      });
      saveHeaderAction.addClass("jltma-addons-save-now").removeAttr("disabled").css("cursor", "pointer");
    });
    $2("#jltma-addons-extensions .jltma-addons-enable-all").on("click", function(e) {
      e.preventDefault();
      $2("#jltma-addons-extensions .jltma-master-addons_feature-switchbox input:enabled").each(function(i) {
        $2(this).prop("checked", true).change();
      });
      saveHeaderAction.addClass("jltma-addons-save-now").removeAttr("disabled").css("cursor", "pointer");
    });
    $2("#jltma-addons-extensions .jltma-addons-disable-all").on("click", function(e) {
      e.preventDefault();
      $2("#jltma-addons-extensions .jltma-master-addons_feature-switchbox input:enabled").each(function(i) {
        $2(this).prop("checked", false).change();
      });
      saveHeaderAction.addClass("jltma-addons-save-now").removeAttr("disabled").css("cursor", "pointer");
    });
    $2("#jltma-master-addons-icons .jltma-addons-enable-all").on("click", function(e) {
      e.preventDefault();
      $2("#jltma-master-addons-icons .jltma-master-addons_feature-switchbox input:enabled").each(function(i) {
        $2(this).prop("checked", true).change();
      });
      saveHeaderAction.addClass("jltma-addons-save-now").removeAttr("disabled").css("cursor", "pointer");
    });
    $2("#jltma-master-addons-icons .jltma-addons-disable-all").on("click", function(e) {
      e.preventDefault();
      $2("#jltma-master-addons-icons .jltma-master-addons_feature-switchbox input:enabled").each(function(i) {
        $2(this).prop("checked", false).change();
      });
      saveHeaderAction.addClass("jltma-addons-save-now").removeAttr("disabled").css("cursor", "pointer");
    });
    $2(".master-addons-posts a.rsswidget").attr("target", "_blank");
    $2("jltma-master-addons-tabs-navbar a:not(.jltma-upgrade-pro)").on("click", function(event) {
      event.preventDefault();
      var context = $2(this).closest("jltma-master-addons-tabs-navbar").parent();
      var url = $2(this).attr("href"), target = $2(this).attr("target");
      if (target == "_blank") {
        window.open(url, target);
      } else {
        $2("jltma-master-addons-tabs-navbar li", context).removeClass("jltma-admin-tab-active");
        $2(this).closest("li").addClass("jltma-admin-tab-active");
        $2(".jltma-master-addons-tab-panel", context).hide();
        $2($2(this).attr("href"), context).show();
      }
    });
    $2("jltma-master-addons-tabs-navbar").each(function() {
      if ($2(".jltma-admin-tab-active", this).length)
        $2(".jltma-admin-tab-active", this).click();
      else
        $2("a", this).first().click();
    });
    $2(".jltma-upgrade-pro").not(".elementor-pro-conflict").on("click", function(event) {
      event.preventDefault();
      event.stopPropagation();
      var $popup = $2("#jltma-popup");
      if ($popup.length) {
        $popup.fadeIn(300);
      }
    });
    $2(document).on("click", ".jltma-popup-overlay, .popup-dismiss", function() {
      $2("#jltma-popup").fadeOut(300);
    });
    $2(document).on("keyup", function(e) {
      if (e.key === "Escape") {
        $2("#jltma-popup").fadeOut(300);
      }
    });
    $2(".elementor-pro-conflict:parent").on("click", function(event) {
      event.preventDefault();
      var extensionKey = $2(this).find('input[type="checkbox"]').attr("id");
      var message = "This feature is disabled because Elementor Pro is active.";
      if (extensionKey === "dynamic-tags") {
        message = "This feature is disabled because Elementor Pro is active. Elementor Pro already provides Dynamic Tags functionality.";
      } else if (extensionKey === "custom-css") {
        message = "This feature is disabled because Elementor Pro is active. Elementor Pro already provides Custom CSS functionality.";
      }
      swal({
        title: "Feature Disabled",
        text: message,
        type: "info",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Understood"
      });
    });
    $2("body").on("click", ".jltma-wl-plugin-logo", function(e) {
      e.preventDefault();
      var button = $2(this), custom_uploader = wp.media({
        title: "Insert image",
        library: {
          // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
          type: "image"
        },
        button: {
          text: "Use this image"
          // button label text
        },
        multiple: false
      }).on("select", function() {
        var attachment = custom_uploader.state().get("selection").first().toJSON();
        button.html('<img src="' + attachment.url + '">').next().show();
        $2(".jltma-whl-selected-image").val(attachment.id);
      }).open();
    });
    $2("body").on("click", ".jltma-remove-button", function(e) {
      e.preventDefault();
      var button = $2(this);
      button.next().val("");
      button.hide().prev().html('<i class="dashicons dashicons-cloud-upload"></i> <span>Upload image</span>');
    });
    var purchaseCompleted = function(response) {
      var trial = response.purchase.trial_ends !== null, total = trial ? 0 : response.purchase.initial_amount.toString(), productName = "Product Name", storeUrl = "https://master-addons.com", storeName = "Master Addons";
      if (typeof fbq !== "undefined") {
        fbq("track", "Purchase", { currency: "USD", value: response.purchase.initial_amount });
      }
      if (typeof ga !== "undefined") {
        ga("send", "event", "plugin", "purchase", productName, response.purchase.initial_amount.toString());
        ga("require", "ecommerce");
        ga("ecommerce:addTransaction", {
          "id": response.purchase.id.toString(),
          // Transaction ID. Required.
          "affiliation": storeName,
          // Affiliation or store name.
          "revenue": total,
          // Grand Total.
          "shipping": "0",
          // Shipping.
          "tax": "0"
          // Tax.
        });
        ga("ecommerce:addItem", {
          "id": response.purchase.id.toString(),
          // Transaction ID. Required.
          "name": productName,
          // Product name. Required.
          "sku": response.purchase.plan_id.toString(),
          // SKU/code.
          "category": "Plugin",
          // Category or variation.
          "price": response.purchase.initial_amount.toString(),
          // Unit price.
          "quantity": "1"
          // Quantity.
        });
        ga("ecommerce:send");
        ga("send", {
          hitType: "pageview",
          page: "/purchase-completed/",
          location: storeUrl + "/purchase-completed/"
        });
      }
    };
    $2(".jltma-tab-element-save-setting").on("click", function(e) {
      e.preventDefault();
      let $this = $2(this);
      if ($2(this).hasClass("jltma-addons-save-now")) {
        const ajaxPromises = [];
        ajaxPromises.push(
          $2.ajax({
            url: JLTMA_OPTIONS.ajaxurl,
            type: "post",
            data: {
              action: "jltma_save_elements_settings",
              security: JLTMA_OPTIONS.ajax_nonce,
              fields: $2("#jltma-addons-tab-settings").serialize()
            }
          })
        );
        ajaxPromises.push(
          $2.ajax({
            url: JLTMA_OPTIONS.ajaxurl,
            type: "post",
            data: {
              action: "master_addons_save_extensions_settings",
              security: JLTMA_OPTIONS.ajax_extensions_nonce,
              fields: $2("#jltma-addons-extensions-settings").serialize()
            }
          })
        );
        ajaxPromises.push(
          $2.ajax({
            url: JLTMA_OPTIONS.ajaxurl,
            type: "post",
            data: {
              action: "jltma_save_api_settings",
              security: JLTMA_OPTIONS.ajax_api_nonce,
              fields: $2("#jltma-api-forms-settings").serializeArray()
            }
          })
        );
        ajaxPromises.push(
          $2.ajax({
            url: JLTMA_OPTIONS.ajaxurl,
            type: "post",
            data: {
              action: "jltma_save_icons_library_settings",
              security: JLTMA_OPTIONS.ajax_icons_library_nonce,
              fields: $2("#jltma-master-addons-icons-settings").serialize()
            }
          })
        );
        if ("valid" === $2(this).data("lic")) {
          ajaxPromises.push(
            $2.ajax({
              url: JLTMA_OPTIONS.ajaxurl,
              type: "post",
              data: {
                action: "jltma_save_white_label_settings",
                security: JLTMA_OPTIONS.ajax_nonce,
                fields: $2("form#jltma-addons-white-label-settings").serialize()
              }
            })
          );
        }
        Promise.all(ajaxPromises).then(function(responses) {
          $this.html("Save Settings");
          saveHeaderAction.removeClass("jltma-addons-save-now");
          JLTMA_Toaster.success("Settings saved successfully!");
        }).catch(function(error) {
          $this.html("Save Settings");
          saveHeaderAction.removeClass("jltma-addons-save-now");
          JLTMA_Toaster.error("Failed to save settings. Please try again.");
        });
      } else {
        $2(this).attr("disabled", "true").css("cursor", "not-allowed");
      }
    });
    $2("select.master-addons-rollback-select").on("change", function() {
      var $this = $2(this), $rollbackButton = $this.next(".jltma-rollback-button"), placeholderText = $rollbackButton.data("placeholder-text"), placeholderUrl = $rollbackButton.data("placeholder-url");
      $rollbackButton.html(placeholderText.replace("{VERSION}", $this.val()));
      $rollbackButton.attr("href", placeholderUrl.replace("VERSION", $this.val()));
    }).trigger("change");
    $2(".jltma-rollback-button").on("click", function(event) {
      event.preventDefault();
      var $this = $2(this), dialogsManager = new DialogsManager.Instance();
      dialogsManager.createWidget("confirm", {
        headerMessage: JLTMA_OPTIONS.rollback.rollback_to_previous_version,
        message: JLTMA_OPTIONS.rollback.rollback_confirm,
        strings: {
          cancel: JLTMA_OPTIONS.rollback.cancel,
          confirm: JLTMA_OPTIONS.rollback.yes
        },
        onConfirm: function() {
          $this.addClass("loading");
          location.href = $this.attr("href");
        }
      }).show();
    });
    (function(n) {
      n.fn.copiq = function(e) {
        var t = n.extend({
          parent: "body",
          content: "",
          onSuccess: function() {
          },
          onError: function() {
          }
        }, e);
        return this.each(function() {
          var e2 = n(this);
          e2.on("click", function() {
            var n2 = e2.parents(t.parent).find(t.content);
            var o = document.createRange();
            var c = window.getSelection();
            o.selectNodeContents(n2[0]);
            c.removeAllRanges();
            c.addRange(o);
            try {
              var r = document.execCommand("copy");
              var a = r ? "onSuccess" : "onError";
              t[a](e2, n2, c.toString());
            } catch (i) {
            }
            c.removeAllRanges();
          });
        });
      };
    })(jQuery);
    $2(".jltma-copy-btn").copiq({
      parent: ".copy-section",
      content: ".api-element-inner",
      onSuccess: function($element, source, selection) {
        $2("span", $element).text($element.attr("data-text-copied"));
        setTimeout(function() {
          $2("span", $element).text($element.attr("data-text"));
        }, 2e3);
      }
    });
  });
})(jQuery);
})();
