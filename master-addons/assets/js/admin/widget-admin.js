(function(){
;
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
    show: function(message, type = "success", duration = 3e3) {
      this.init();
      var toaster = $(`
                <div class="jltma-toaster ${type}">
                    <span class="jltma-toaster-icon ${type}-icon"></span>
                    <span class="jltma-toaster-content">${message}</span>
                    <button class="jltma-toaster-close"></button>
                    <div class="jltma-toaster-progress"></div>
                </div>
            `);
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
  const WidgetAdmin = {
    init: function() {
      this.bindEvents();
      this.setupNativeCPTList();
    },
    /**
     * Setup native CPT list table interception
     * Intercept WP's native "Add New" button and inject extra buttons
     */
    setupNativeCPTList: function() {
      var self = this;
      $(document).on("click", ".wrap .page-title-action:not(.jltma-cpt-btn)", function(e) {
        e.preventDefault();
        self.openModal(e);
      });
      var $addNewBtn = $(".wrap .page-title-action").first();
      if ($addNewBtn.length) {
        $addNewBtn.text("Add New Widget");
        if (!$(".jltma-import-widget").length) {
          $addNewBtn.after(
            '<a href="#" class="jltma-cpt-btn jltma-cpt-btn-secondary jltma-import-widget"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Import Widget</a><a href="https://master-addons.com/widget-builder/" target="_blank" class="jltma-cpt-btn jltma-cpt-btn-youtube"><svg width="16" height="16" viewBox="0 0 24 24" fill="#ff0000"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.546 12 3.546 12 3.546s-7.505 0-9.377.504A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.504 9.376.504 9.376.504s7.505 0 9.377-.504a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg> Video Tutorial</a>'
          );
        }
      }
    },
    bindEvents: function() {
      $(document).on("click", ".jltma-add-new-widget", this.openModal.bind(this));
      $(document).on("change", "#jltma_widget_category", this.handleCategoryChange.bind(this));
      $(document).on("click", ".jltma-cancel-inline-category", this.hideInlineCategoryAdd.bind(this));
      $(document).on("click", ".jltma-save-inline-category", this.saveInlineCategory.bind(this));
      $(document).on("click", ".jltma-pop-close, .close-btn, .jltma-modal-backdrop", this.closeModal.bind(this));
      $(document).on("click", ".jltma-import-widget", this.openImportModal.bind(this));
      $(document).on("click", ".jltma-import-close, .jltma-import-backdrop", this.closeImportModal.bind(this));
      $(document).on("change", "#jltma-import-file", this.handleFileSelect.bind(this));
      $(document).on("keydown", function(e) {
        if (e.key === "Escape" || e.keyCode === 27) {
          if ($("#jltma_import_widget_modal").is(":visible")) {
            $("#jltma_import_widget_modal").fadeOut(300);
            $("body").removeClass("jltma-modal-open");
            WidgetAdmin.resetImportModal();
          }
        }
      });
      $(document).on("dragover", "#jltma-dropzone", this.handleDragOver.bind(this));
      $(document).on("dragleave", "#jltma-dropzone", this.handleDragLeave.bind(this));
      $(document).on("drop", "#jltma-dropzone", this.handleDrop.bind(this));
      $(document).on("keypress", "#jltma_new_category_title", function(e) {
        if (e.which === 13) {
          e.preventDefault();
          $(".jltma-save-inline-category").trigger("click");
        }
      });
      $(document).on("keydown", "#jltma_new_category_title", function(e) {
        if (e.which === 27) {
          e.preventDefault();
          $(".jltma-cancel-inline-category").trigger("click");
        }
      });
      $(document).on("submit", "#jltma-widget-form", this.saveWidget.bind(this));
      $(document).on("click", ".jltma-save-widget", function(e) {
        e.preventDefault();
        $("#jltma-widget-form").trigger("submit");
      });
      $(document).on("click", ".jltma-delete-widget", this.deleteWidget.bind(this));
      $(document).on("click", ".jltma-copy-shortcode-widget", this.copyShortcode.bind(this));
      $(document).on("click", ".jltma-widget-edit-cond", this.openModalFromConditions.bind(this));
    },
    openModal: function(e) {
      e.preventDefault();
      var $target = $(e.currentTarget);
      var widgetId = $target.data("widget-id") || 0;
      var modal = $("#jltma_widget_builder_modal");
      modal.addClass("loading");
      modal.addClass("active");
      $("body").addClass("jltma-modal-open");
      if (widgetId > 0) {
        modal.find(".jltma-modal-title").text("Edit Widget");
        $("#jltma_widget_id").val(widgetId);
        this.loadWidgetData(widgetId);
      } else {
        modal.find(".jltma-modal-title").text("Create New Widget");
        this.resetForm();
        modal.removeClass("loading");
      }
      modal.find("form").attr("data-widget-id", widgetId);
    },
    closeModal: function(e) {
      if ($(e.target).hasClass("jltma-modal-backdrop") || $(e.target).hasClass("close-btn") || $(e.target).closest(".jltma-pop-close").length) {
        $("#jltma_widget_builder_modal").removeClass("active");
        $("#jltma_category_modal").removeClass("active");
        $("body").removeClass("jltma-modal-open");
        e.preventDefault();
      }
    },
    resetForm: function() {
      var form = $("#jltma-widget-form");
      form[0].reset();
      $("#jltma_widget_id").val("");
      var categorySelect = $("select#jltma_widget_category");
      var initialValue = categorySelect.val() || "general";
      categorySelect.data("previous-value", initialValue);
      $("#jltma-inline-category-add").hide();
      $("#jltma_new_category_title").val("");
    },
    loadWidgetData: function(widgetId) {
      var self = this;
      var modal = $("#jltma_widget_builder_modal");
      $.ajax({
        url: jltmaWidgetAdmin.ajax_url,
        type: "GET",
        data: {
          action: "jltma_widget_get_data",
          widget_id: widgetId,
          _nonce: jltmaWidgetAdmin.widget_nonce
        },
        success: function(response) {
          if (response.success) {
            $("#jltma_widget_id").val(widgetId);
            $("#jltma_widget_title").val(response.data.title);
            var categoryDropdown = $("select#jltma_widget_category");
            var categoryValue = response.data.category || "general";
            categoryDropdown.val(categoryValue);
            categoryDropdown.data("previous-value", categoryValue);
            $("#jltma-inline-category-add").hide();
            $("#jltma_new_category_title").val("");
          }
          modal.removeClass("loading");
        },
        error: function(xhr, status, error) {
          modal.removeClass("loading");
          JLTMA_Toaster.error(jltmaWidgetAdmin.strings.error);
        }
      });
    },
    handleCategoryChange: function(e) {
      var $select = $(e.currentTarget);
      var selectedValue = $select.val();
      if (selectedValue === "__add_new__") {
        var previousValue = $select.data("previous-value") || "general";
        $select.data("previous-value", previousValue);
        $("#jltma-inline-category-add").slideDown(200);
        $("#jltma_new_category_title").val("").focus();
      } else {
        $select.data("previous-value", selectedValue);
      }
    },
    showInlineCategoryAdd: function(e) {
      e.preventDefault();
      $("#jltma-inline-category-add").slideDown(200);
      $("#jltma_new_category_title").focus();
    },
    hideInlineCategoryAdd: function(e) {
      e.preventDefault();
      var categorySelect = $("select#jltma_widget_category");
      var previousValue = categorySelect.data("previous-value") || "general";
      $("#jltma-inline-category-add").slideUp(200);
      $("#jltma_new_category_title").val("");
      categorySelect.val(previousValue);
    },
    saveInlineCategory: function(e) {
      e.preventDefault();
      var title = $("#jltma_new_category_title").val().trim();
      var categorySelect = $("select#jltma_widget_category");
      var saveBtn = $(".jltma-save-inline-category");
      if (!title) {
        JLTMA_Toaster.error("Category title is required.");
        return;
      }
      var originalText = saveBtn.text();
      saveBtn.prop("disabled", true).text("Saving...");
      var slug = title.toLowerCase().replace(/[^a-z0-9\s-]/g, "").replace(/\s+/g, "-").replace(/-+/g, "-").trim();
      $.ajax({
        url: jltmaWidgetAdmin.admin_url + "admin-ajax.php?rest_route=/jltma/v1/categories",
        type: "POST",
        headers: {
          "X-WP-Nonce": jltmaWidgetAdmin.widget_nonce
        },
        contentType: "application/json",
        data: JSON.stringify({
          name: title,
          slug
        }),
        success: function(response) {
          if (response.success && response.data) {
            var newSlug = response.data.slug;
            var newTitle = response.data.title;
            if (categorySelect.find('option[value="' + newSlug + '"]').length === 0) {
              categorySelect.find('option[value="__add_new__"]').before(
                '<option value="' + newSlug + '">' + newTitle + "</option>"
              );
            }
            categorySelect.val(newSlug);
            categorySelect.data("previous-value", newSlug);
            $("#jltma-inline-category-add").slideUp(200);
            $("#jltma_new_category_title").val("");
            JLTMA_Toaster.success(jltmaWidgetAdmin.strings.category_added);
          } else {
            JLTMA_Toaster.error("Failed to create category. Please try again.");
            categorySelect.val(categorySelect.data("previous-value") || "general");
          }
          saveBtn.prop("disabled", false).text(originalText);
        },
        error: function(xhr, status, error) {
          JLTMA_Toaster.error("Failed to create category. Please try again.");
          categorySelect.val(categorySelect.data("previous-value") || "general");
          saveBtn.prop("disabled", false).text(originalText);
        }
      });
    },
    saveWidget: function(e) {
      e.preventDefault();
      var form = $("#jltma-widget-form");
      var submitBtn = $(".jltma-save-widget");
      var widgetId = $("#jltma_widget_id").val();
      var title = $("#jltma_widget_title").val().trim();
      var category = $("select#jltma_widget_category").val();
      if (!title) {
        JLTMA_Toaster.error(jltmaWidgetAdmin.strings.widget_title_required);
        return;
      }
      var originalText = submitBtn.text();
      submitBtn.prop("disabled", true).text(jltmaWidgetAdmin.strings.saving);
      $.ajax({
        url: jltmaWidgetAdmin.ajax_url,
        type: "POST",
        data: {
          action: "jltma_widget_save_data",
          widget_id: widgetId,
          widget_title: title,
          widget_category: category,
          _nonce: jltmaWidgetAdmin.widget_nonce
        },
        success: function(response) {
          if (response.success) {
            if (widgetId) {
              var categoryName = $("select#jltma_widget_category option:selected").text();
              $("#jltma_widget_builder_modal").removeClass("active");
              $("body").removeClass("jltma-modal-open");
              var $row = $("#post-" + widgetId);
              var $categoryColumn = $row.find("td.column-jltma_widget_category");
              $categoryColumn.html(
                '<div style="text-align: center;"><span class="jltma-widget-category">' + categoryName + '</span><br><a href="#" class="jltma-widget-edit-cond" id="' + widgetId + '" style="font-size: 12px; color: #2271b1; text-decoration: none;">Edit Conditions <span class="dashicons dashicons-edit" style="font-size: 12px; vertical-align: middle;"></span></a></div>'
              );
              JLTMA_Toaster.success(jltmaWidgetAdmin.strings.saved);
            } else {
              window.location.href = response.data.edit_url;
            }
          } else {
            JLTMA_Toaster.error(response.data.message || jltmaWidgetAdmin.strings.error);
            submitBtn.prop("disabled", false).text(originalText);
          }
        },
        error: function(xhr, status, error) {
          JLTMA_Toaster.error(jltmaWidgetAdmin.strings.error);
          submitBtn.prop("disabled", false).text(originalText);
        }
      });
    },
    deleteWidget: function(e) {
      e.preventDefault();
      var widgetId = $(e.currentTarget).data("widget-id");
      if (!confirm(jltmaWidgetAdmin.strings.confirm_delete)) {
        return;
      }
      $.ajax({
        url: jltmaWidgetAdmin.ajax_url,
        type: "POST",
        data: {
          action: "jltma_widget_delete",
          widget_id: widgetId,
          _nonce: jltmaWidgetAdmin.widget_nonce
        },
        success: function(response) {
          if (response.success) {
            location.reload();
          } else {
            JLTMA_Toaster.error(response.data.message || jltmaWidgetAdmin.strings.error);
          }
        },
        error: function() {
          JLTMA_Toaster.error(jltmaWidgetAdmin.strings.error);
        }
      });
    },
    copyShortcode: function(e) {
      e.preventDefault();
      var shortcode = $(e.currentTarget).data("shortcode");
      var button = $(e.currentTarget);
      var temp = $("<textarea>");
      $("body").append(temp);
      temp.val(shortcode).select();
      document.execCommand("copy");
      temp.remove();
      var originalHtml = button.html();
      button.html('<span class="dashicons dashicons-yes"></span> ' + jltmaWidgetAdmin.strings.copied);
      setTimeout(function() {
        button.html(originalHtml);
      }, 2e3);
    },
    openModalFromConditions: function(e) {
      e.preventDefault();
      var widgetId = $(e.currentTarget).attr("id");
      var modal = $("#jltma_widget_builder_modal");
      modal.addClass("loading");
      modal.addClass("active");
      $("body").addClass("jltma-modal-open");
      if (widgetId > 0) {
        modal.find(".jltma-modal-title").text("Edit Widget");
        $("#jltma_widget_id").val(widgetId);
        this.loadWidgetData(widgetId);
      }
      modal.find("form").attr("data-widget-id", widgetId);
    },
    // Import Widget Functions
    openImportModal: function(e) {
      e.preventDefault();
      $("#jltma_import_widget_modal").fadeIn(300);
      $("body").addClass("jltma-modal-open");
    },
    closeImportModal: function(e) {
      if ($(e.target).hasClass("jltma-import-backdrop") || $(e.target).hasClass("jltma-import-close") || $(e.target).closest(".jltma-import-close").length) {
        $("#jltma_import_widget_modal").fadeOut(300);
        $("body").removeClass("jltma-modal-open");
        this.resetImportModal();
        e.preventDefault();
      }
    },
    resetImportModal: function() {
      $("#jltma-dropzone").removeClass("dragging");
      $(".jltma-dropzone-content").show();
      $(".jltma-importing").hide();
      $("#jltma-import-file").val("");
    },
    handleDragOver: function(e) {
      e.preventDefault();
      e.stopPropagation();
      $("#jltma-dropzone").addClass("dragging");
    },
    handleDragLeave: function(e) {
      e.preventDefault();
      e.stopPropagation();
      $("#jltma-dropzone").removeClass("dragging");
    },
    handleDrop: function(e) {
      e.preventDefault();
      e.stopPropagation();
      $("#jltma-dropzone").removeClass("dragging");
      var files = e.originalEvent.dataTransfer.files;
      if (files.length > 0) {
        this.processImportFile(files[0]);
      }
    },
    handleFileSelect: function(e) {
      var file = e.target.files[0];
      if (file) {
        this.processImportFile(file);
      }
    },
    processImportFile: function(file) {
      var self = this;
      if (!file.name.endsWith(".json")) {
        JLTMA_Toaster.error("Please select a valid JSON file.", 5e3);
        return;
      }
      $(".jltma-dropzone-content").hide();
      $(".jltma-importing").show();
      var reader = new FileReader();
      reader.onload = function(event) {
        try {
          var jsonData = JSON.parse(event.target.result);
          var isValid = true;
          if (window.jltmaWidgetBuilder && window.jltmaWidgetBuilder.hooks) {
            isValid = window.jltmaWidgetBuilder.hooks.applyFilters(
              "jltma_cwb_import_validate",
              isValid,
              jsonData
            );
          }
          if (!jsonData.widget || !jsonData.version) {
            throw new Error("Invalid widget file format");
          }
          if (!isValid) {
            throw new Error("Import validation failed");
          }
          var importedWidget = jsonData.widget;
          if (window.jltmaWidgetBuilder && window.jltmaWidgetBuilder.hooks) {
            window.jltmaWidgetBuilder.hooks.doAction(
              "jltma_cwb_before_import",
              importedWidget
            );
          }
          var processedWidget = importedWidget;
          if (window.jltmaWidgetBuilder && window.jltmaWidgetBuilder.hooks) {
            processedWidget = window.jltmaWidgetBuilder.hooks.applyFilters(
              "jltma_cwb_import_data",
              importedWidget,
              jsonData
            );
          }
          self.createWidgetFromImport(processedWidget, jsonData);
        } catch (parseError) {
          console.error("Failed to parse widget file:", parseError);
          JLTMA_Toaster.error("Failed to import widget. Invalid JSON file format.", 5e3);
          self.resetImportModal();
        }
      };
      reader.onerror = function() {
        console.error("Failed to read file");
        JLTMA_Toaster.error("Failed to read file. Please try again.", 5e3);
        self.resetImportModal();
      };
      reader.readAsText(file);
    },
    createWidgetFromImport: function(widgetData, originalJsonData) {
      var self = this;
      var CUSTOM_CATEGORY_PREFIX = "jltma_cwb_custom_";
      var categorySlug = widgetData.category || "general";
      var isCustomCategory = widgetData.isCustomCategory || categorySlug.startsWith(CUSTOM_CATEGORY_PREFIX);
      var createWidget = function() {
        console.log("Creating widget via REST API with data:", widgetData);
        $.ajax({
          url: jltmaWidgetAdmin.rest_url + "/widgets",
          type: "POST",
          headers: {
            "X-WP-Nonce": jltmaWidgetAdmin.rest_nonce
          },
          contentType: "application/json",
          data: JSON.stringify(widgetData),
          success: function(response) {
            console.log("Widget created successfully:", response);
            if (window.jltmaWidgetBuilder && window.jltmaWidgetBuilder.hooks) {
              window.jltmaWidgetBuilder.hooks.doAction(
                "jltma_cwb_imported",
                widgetData,
                originalJsonData
              );
            }
            JLTMA_Toaster.success("Widget imported successfully! Redirecting...", 3e3);
            setTimeout(function() {
              $("#jltma_import_widget_modal").fadeOut(300);
              $("body").removeClass("jltma-modal-open");
              setTimeout(function() {
                var editUrl = jltmaWidgetAdmin.admin_url + "admin.php?page=jltma-widget-editor&widget_id=" + response.id;
                window.location.href = editUrl;
              }, 300);
            }, 1500);
          },
          error: function(xhr, status, error) {
            console.error("Failed to create widget:", error);
            console.error("XHR Response:", xhr);
            console.error("Status:", xhr.status);
            console.error("Response Text:", xhr.responseText);
            console.error("Request Data:", widgetData);
            var errorMessage = "Failed to import widget. Please try again.";
            try {
              var errorData = JSON.parse(xhr.responseText);
              if (errorData.message) {
                errorMessage = errorData.message;
              }
            } catch (e) {
            }
            JLTMA_Toaster.error(errorMessage + " Check console for details.", 5e3);
            self.resetImportModal();
          }
        });
      };
      if (isCustomCategory) {
        $.ajax({
          url: jltmaWidgetAdmin.rest_url + "/categories",
          type: "GET",
          headers: {
            "X-WP-Nonce": jltmaWidgetAdmin.rest_nonce
          },
          success: function(categories) {
            var categoryExists = categories.some(function(cat) {
              return cat.slug === categorySlug;
            });
            if (!categoryExists) {
              self.createCategoryIfNotExists(categorySlug, createWidget);
            } else {
              createWidget();
            }
          },
          error: function(xhr, status, error) {
            console.warn("Failed to check categories, proceeding with widget creation:", error);
            createWidget();
          }
        });
      } else {
        createWidget();
      }
    },
    createCategoryIfNotExists: function(categorySlug, callback) {
      var CUSTOM_CATEGORY_PREFIX = "jltma_cwb_custom_";
      var categoryName = categorySlug.replace(CUSTOM_CATEGORY_PREFIX, "").split("_").map(function(word) {
        return word.charAt(0).toUpperCase() + word.slice(1);
      }).join(" ");
      var shouldCreate = true;
      if (window.jltmaWidgetBuilder && window.jltmaWidgetBuilder.hooks) {
        shouldCreate = window.jltmaWidgetBuilder.hooks.applyFilters(
          "jltma_cwb_before_create_category",
          shouldCreate,
          categorySlug,
          categoryName
        );
      }
      if (!shouldCreate) {
        if (callback) callback();
        return;
      }
      $.ajax({
        url: jltmaWidgetAdmin.rest_url + "/categories",
        type: "POST",
        headers: {
          "X-WP-Nonce": jltmaWidgetAdmin.rest_nonce
        },
        contentType: "application/json",
        data: JSON.stringify({
          name: categoryName,
          slug: categorySlug,
          custom_prefix: CUSTOM_CATEGORY_PREFIX,
          auto_created: true
        }),
        success: function(result) {
          if (window.jltmaWidgetBuilder && window.jltmaWidgetBuilder.hooks) {
            window.jltmaWidgetBuilder.hooks.doAction(
              "jltma_cwb_category_created",
              result.data,
              categorySlug,
              categoryName
            );
          }
          if (callback) callback();
        },
        error: function(xhr, status, error) {
          console.error("Failed to create category:", categorySlug, error);
          JLTMA_Toaster.warning('Could not create category "' + categoryName + '". Widget will use "general" category.', 5e3);
          if (callback) callback();
        }
      });
    }
  };
  $(document).ready(function() {
    WidgetAdmin.init();
  });
})(jQuery);
})();
