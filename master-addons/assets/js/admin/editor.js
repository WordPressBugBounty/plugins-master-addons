(function(){
!(function(e) {
  "use strict";
  var t, a, o, n, i = window.MasterAddonsData || {};
  a = {
    ModalLayoutView: null,
    ModalHeaderView: null,
    ModalHeaderInsertButton: null,
    ModalLoadingView: null,
    ModalBodyView: null,
    ModalErrorView: null,
    LibraryCollection: null,
    KeywordsModel: null,
    ModalCollectionView: null,
    ModalTabsCollection: null,
    ModalTabsCollectionView: null,
    FiltersCollectionView: null,
    FiltersItemView: null,
    ModalTabsItemView: null,
    ModalTemplateItemView: null,
    ModalInsertTemplateBehavior: null,
    ModalTemplateModel: null,
    CategoriesCollection: null,
    ModalPreviewView: null,
    ModalHeaderBack: null,
    ModalHeaderLogo: null,
    MasterProButton: null,
    KeywordsView: null,
    TabModel: null,
    CategoryModel: null,
    init: function() {
      var a2 = this;
      a2.ModalTemplateModel = Backbone.Model.extend({
        defaults: {
          template_id: 0,
          name: "",
          title: "",
          thumbnail: "",
          preview: "",
          source: "",
          categories: [],
          keywords: [],
          liveUrl: "",
          package: ""
        }
      }), a2.ModalHeaderView = Marionette.LayoutView.extend({
        id: "ma-el-template-modal-header",
        template: "#views-ma-el-template-modal-header",
        ui: {
          closeModal: "#ma-el-template-modal-header-close-modal"
        },
        events: {
          "click @ui.closeModal": "onCloseModalClick"
        },
        regions: {
          headerLogo: "#ma-el-template-modal-header-logo-area",
          headerTabs: "#ma-el-template-modal-header-tabs",
          headerActions: "#ma-el-template-modal-header-actions"
        },
        onCloseModalClick: function() {
          e("body").removeClass("elementor-editor-preview").addClass("elementor-editor-active");
          t.closeModal();
        }
      }), a2.TabModel = Backbone.Model.extend({
        defaults: {
          slug: "",
          title: ""
        }
      }), a2.LibraryCollection = Backbone.Collection.extend({
        model: a2.ModalTemplateModel
      }), a2.ModalTabsCollection = Backbone.Collection.extend({
        model: a2.TabModel
      }), a2.CategoryModel = Backbone.Model.extend({
        defaults: {
          slug: "",
          title: ""
        }
      }), a2.KeywordsModel = Backbone.Model.extend({
        defaults: {
          keywords: {}
        }
      }), a2.CategoriesCollection = Backbone.Collection.extend({
        model: a2.CategoryModel
      }), a2.KeywordsView = Marionette.ItemView.extend({
        id: "elementor-template-library-filter",
        template: "#views-ma-el-template-modal-keywords",
        onRender: function() {
          var self = this;
          setTimeout(function() {
            jQuery(".ma-el-keywords-filter-action").off("click").on("click", function(e2) {
              e2.preventDefault();
              e2.stopPropagation();
              jQuery(".ma-el-keywords-filters-container").toggleClass("active");
            });
            jQuery(".ma-el-keywords-filter-item").off("click").on("click", function(e2) {
              e2.preventDefault();
              var keywordValue = jQuery(this).data("keyword");
              var keywordTitle = jQuery(this).text();
              t.setFilter("keyword", keywordValue);
              jQuery("#ma-el-keywords-selected-filter").text(keywordTitle);
              jQuery(".ma-el-keywords-filters-container").removeClass("active");
            });
            jQuery(document).off("click.keywordsDropdown").on("click.keywordsDropdown", function(e2) {
              if (!jQuery(e2.target).closest(".ma-el-keywords-filters-wrap").length) {
                jQuery(".ma-el-keywords-filters-container").removeClass("active");
              }
            });
          }, 100);
        }
      }), a2.ModalPreviewView = Marionette.ItemView.extend({
        template: "#views-ma-el-template-modal-preview",
        id: "ma-el-item-preview-wrap",
        ui: {
          iframe: "iframe",
          notice: ".ma-el-item-notice"
        },
        onRender: function() {
          if (null !== this.getOption("notice") && this.getOption("notice").length) {
            var e2 = "";
            -1 !== this.getOption("notice").indexOf("facebook") ? e2 += "<p>Please login with your Facebook account in order to get your Facebook Reviews.</p>" : -1 !== this.getOption("notice").indexOf("google") ? e2 += "<p>You need to add your Google API key from Dashboard -> Master Addons for Elementor -> Google Maps</p>" : -1 !== this.getOption("notice").indexOf("form") && (e2 += "<p>You need to have <a href='https://wordpress.org/plugins/contact-form-7/' target='_blank'>Contact Form 7 plugin</a> installed and active.</p>"), this.ui.notice.html("<div><p><strong>Important!</strong></p>" + e2 + "</div>");
          }
          this.ui.iframe.attr("src", this.getOption("url"));
        }
      }), a2.ModalHeaderBack = Marionette.ItemView.extend({
        template: "#views-ma-el-template-modal-header-back",
        id: "ma-el-template-modal-header-back",
        ui: {
          button: "button"
        },
        events: {
          "click @ui.button": "onBackClick"
        },
        onBackClick: function() {
          t.setPreview("back");
        }
      }), a2.ModalHeaderLogo = Marionette.ItemView.extend({
        template: "#views-ma-el-template-modal-header-logo",
        id: "ma-el-template-modal-header-logo"
      }), a2.ModalBodyView = Marionette.LayoutView.extend({
        template: "#views-ma-el-template-modal-content",
        id: "ma-el-template-library-content",
        className: function() {
          return "library-tab-" + t.getTab();
        },
        regions: {
          contentTemplates: ".ma-el-templates-list",
          contentFilters: ".ma-el-filters-list",
          contentKeywords: ".ma-el-keywords-list"
        },
        onRender: function() {
          var self = this;
          setTimeout(function() {
            jQuery("#ma-el-template-search-input").off("input").on("input", function() {
              var searchTerm = jQuery(this).val().toLowerCase();
              t.setFilter("search", searchTerm);
            });
          }, 100);
        }
      }), a2.LibraryLoadingView = Marionette.ItemView.extend({
        id: "ma-el-modal-template-library-loading",
        template: "#views-ma-el-template-modal-loading"
      }), a2.LibraryErrorView = Marionette.ItemView.extend({
        id: "ma-el-modal-template-error",
        template: "#views-ma-el-template-modal-error"
      }), a2.ModalInsertTemplateBehavior = Marionette.Behavior.extend({
        ui: {
          insertButton: ".ma-el-template-insert"
        },
        events: {
          "click @ui.insertButton": "onInsertButtonClick"
        },
        onInsertButtonClick: function() {
          var a3 = this.view.model, o2 = a3.attributes.dependencies, n2 = a3.attributes.pro, l = Object.keys(o2).length, r = {};
          if (t.layout.showLoadingView(), 0 < l)
            for (var s in o2) e.ajax({
              url: ajaxurl,
              type: "post",
              dataType: "json",
              data: {
                action: "jltma_inner_template",
                security: MasterAddonsData.insert_template_nonce,
                template: o2[s],
                tab: t.getTab()
              }
            });
          "valid" !== i.license.status && n2 ? t.layout.showLicenseError() : elementor.templates.requestTemplateContent(a3.get("source"), a3.get("template_id"), {
            data: {
              tab: t.getTab(),
              page_settings: false
            },
            success: function(e2) {
              e2.license ? (console.log("%c !", "color: #7a7a7a; background-color: #eee;"), t.closeModal(), elementor.channels.data.trigger("$e.run( 'document/import' )", a3), null !== t.atIndex && (r.at = t.atIndex), elementor.config.version < "3.0.0" ? elementor.sections.currentView.addChildModel(e2.content, r) : elementor.previewView.addChildModel(e2.content, r), elementor.channels.data.trigger("template:after:insert", a3), t.atIndex = null) : t.layout.showLicenseError();
              jQuery("body").removeClass("elementor-editor-preview").addClass("elementor-editor-active");
            },
            error: function(e2) {
              console.log(e2);
            }
          });
        }
      }), a2.ModalHeaderInsertButton = Marionette.ItemView.extend({
        template: "#views-ma-el-template-modal-insert-button",
        id: "jltma-template-modal-insert-wrapper",
        className: "elementor-template-library-template-action jltma-modal-template-header-item",
        behaviors: {
          insertTemplate: {
            behaviorClass: a2.ModalInsertTemplateBehavior
          }
        }
      }), a2.MasterProButton = Marionette.ItemView.extend({
        template: "#views-ma-el-template-pro-button",
        id: "ma-el-modal-template-pro-button"
      }), a2.ModalTemplateItemView = Marionette.ItemView.extend({
        template: "#views-ma-el-template-modal-item",
        className: function() {
          var e2 = " ma-el-modal-template-has-url", t2 = "";
          return "" === this.model.get("preview") && (e2 = " ma-el-modal-template-no-url"), this.model.get("pro") && "valid" != i.license.status && (t2 = " ma-el-modal-template-pro"), "elementor-template-library-template elementor-template-library-template-remote" + e2 + t2;
        },
        ui: function() {
          return {
            previewButton: ".elementor-template-library-template-preview"
          };
        },
        events: function() {
          return {
            "click @ui.previewButton": "onPreviewButtonClick"
          };
        },
        onPreviewButtonClick: function() {
          "" !== this.model.get("url") && t.setPreview(this.model);
        },
        behaviors: {
          insertTemplate: {
            behaviorClass: a2.ModalInsertTemplateBehavior
          }
        }
      }), a2.FiltersItemView = Marionette.ItemView.extend({
        template: "#views-ma-el-template-modal-filters-item",
        tagName: "li",
        className: "ma-el-modal-template-filter-item",
        attributes: function() {
          return {
            "data-filter": this.model.get("slug")
          };
        },
        events: function() {
          return {
            "click": "onFilterClick"
          };
        },
        onFilterClick: function(e2) {
          var filterValue = this.model.get("slug");
          var filterTitle = this.model.get("title");
          jQuery(".ma-el-library-keywords").val(""), t.setFilter("category", filterValue), t.setFilter("keyword", "");
          jQuery("#ma-el-modal-selected-filter").text(filterTitle);
          jQuery(".ma-el-modal-filters-container").removeClass("active");
        }
      }), a2.ModalTabsItemView = Marionette.ItemView.extend({
        template: "#views-ma-el-template-modal-tabs-item",
        className: function() {
          return "elementor-template-library-menu-item";
        },
        ui: function() {
          return {
            tabsLabels: "label",
            tabsInput: "input"
          };
        },
        events: function() {
          return {
            "click @ui.tabsLabels": "onTabClick"
          };
        },
        onRender: function() {
          this.model.get("slug") === t.getTab() && this.ui.tabsInput.attr("checked", "checked");
        },
        onTabClick: function(e2) {
          var a3 = jQuery(e2.target);
          t.setTab(a3.val()), t.setFilter("keyword", "");
        }
      }), a2.FiltersCollectionView = Marionette.CompositeView.extend({
        id: "ma-el-modal-template-library-filters",
        template: "#views-ma-el-template-modal-filters",
        childViewContainer: "#ma-el-modal-filters-container ul",
        getChildView: function(e2) {
          return a2.FiltersItemView;
        },
        onRender: function() {
          var self = this;
          setTimeout(function() {
            jQuery(".ma-el-modal-filter-action").off("click").on("click", function(e2) {
              e2.preventDefault();
              e2.stopPropagation();
              jQuery(".ma-el-modal-filters-container").toggleClass("active");
            });
            jQuery(document).off("click.filterDropdown").on("click.filterDropdown", function(e2) {
              if (!jQuery(e2.target).closest(".ma-el-modal-filters-wrap").length) {
                jQuery(".ma-el-modal-filters-container").removeClass("active");
              }
            });
          }, 100);
        }
      }), a2.ModalTabsCollectionView = Marionette.CompositeView.extend({
        template: "#views-ma-el-template-modal-tabs",
        childViewContainer: "#views-ma-el-template-modal-tabs-items",
        initialize: function() {
          this.listenTo(t.channels.layout, "tamplate:cloned", this._renderChildren);
        },
        getChildView: function(e2) {
          return a2.ModalTabsItemView;
        }
      }), a2.ModalCollectionView = Marionette.CompositeView.extend({
        template: "#views-ma-el-template-modal-templates",
        id: "ma-el-modal-template-library-templates",
        childViewContainer: "#ma-el-modal-templates-container",
        initialize: function() {
          this.listenTo(t.channels.templates, "filter:change", this._renderChildren);
        },
        filter: function(e2) {
          var a3 = t.getFilter("category"), o2 = t.getFilter("keyword"), s = t.getFilter("search");
          var matchesFilters = !a3 && !o2 || (o2 && !a3 ? _.contains(e2.get("keywords"), o2) : a3 && !o2 ? _.contains(e2.get("categories"), a3) : _.contains(e2.get("categories"), a3) && _.contains(e2.get("keywords"), o2));
          if (s && s.length > 0) {
            var title = (e2.get("title") || e2.get("name") || "").toLowerCase();
            var matchesSearch = title.indexOf(s) !== -1;
            return matchesFilters && matchesSearch;
          }
          return matchesFilters;
        },
        getChildView: function(e2) {
          return a2.ModalTemplateItemView;
        },
        onRenderCollection: function() {
          var self = this;
          var e2 = this.$childViewContainer, n2 = t.getTab();
          console.log("Skipping Marionette masonry initialization - Macy handles layout");
          return;
        }
      }), a2.ModalLoadingView = Marionette.ItemView.extend({
        id: "ma-el-modal-loading",
        template: "#views-ma-el-template-modal-loading"
      }), a2.ModalErrorView = Marionette.ItemView.extend({
        id: "ma-el-modal-loading",
        template: "#views-ma-el-template-modal-error"
      }), a2.ModalLayoutView = Marionette.LayoutView.extend({
        el: "#ma-el-modal-template",
        regions: i.modalRegions,
        initialize: function() {
          this.getRegion("modalHeader").show(new a2.ModalHeaderView()), this.listenTo(t.channels.tabs, "filter:change", this.switchTabs), this.listenTo(t.channels.layout, "preview:change", this.switchPreview);
        },
        switchTabs: function() {
          this.showLoadingView(), t.setFilter("keyword", ""), t.requestTemplates(t.getTab());
        },
        switchPreview: function() {
          var e2 = this.getHeaderView(), o2 = t.getPreview(), n2 = t.getFilter("category"), i2 = t.getFilter("keyword");
          return "back" === o2 ? (e2.headerLogo.show(new a2.ModalHeaderLogo()), e2.headerTabs.show(new a2.ModalTabsCollectionView({
            collection: t.collections.tabs
          })), e2.headerActions.empty(), t.setTab(t.getTab()), "" != n2 && (t.setFilter("category", n2), jQuery("#ma-el-modal-filters-container").find("input[value='" + n2 + "']").prop("checked", true)), void ("" != i2 && t.setFilter("keyword", i2))) : "initial" === o2 ? (e2.headerActions.empty(), void e2.headerLogo.show(new a2.ModalHeaderLogo())) : (this.getRegion("modalContent").show(new a2.ModalPreviewView({
            preview: o2.get("preview"),
            url: o2.get("url"),
            notice: o2.get("notice")
          })), e2.headerLogo.empty(), e2.headerTabs.show(new a2.ModalHeaderBack()), void e2.headerActions.show(new a2.ModalHeaderInsertButton({
            model: o2
          })));
        },
        getHeaderView: function() {
          return this.getRegion("modalHeader").currentView;
        },
        getContentView: function() {
          return this.getRegion("modalContent").currentView;
        },
        showLoadingView: function() {
          this.modalContent.show(new a2.ModalLoadingView());
        },
        showLicenseError: function() {
          this.modalContent.show(new a2.ModalErrorView());
        },
        showTemplatesView: function(e2, o2, n2) {
          this.getRegion("modalContent").show(new a2.ModalBodyView());
          var i2 = this.getContentView(), l = this.getHeaderView(), r = new a2.KeywordsModel({
            keywords: n2
          });
          t.collections.tabs = new a2.ModalTabsCollection(t.getTabs()), l.headerTabs.show(new a2.ModalTabsCollectionView({
            collection: t.collections.tabs
          })), i2.contentTemplates.show(new a2.ModalCollectionView({
            collection: e2
          })), i2.contentFilters.show(new a2.FiltersCollectionView({
            collection: o2
          })), i2.contentKeywords.show(new a2.KeywordsView({
            model: r
          }));
        }
      });
    },
    masonry: {
      instance: null,
      init: function(t2) {
        console.log("Marionette masonry.init() called but disabled - Macy from assets.php handles layout");
        return;
      },
      fallbackMasonry: function(t2) {
        console.log("Hello");
        var self = this;
        self.settings = e.extend(self.getDefaultSettings(), t2);
        self.elements = self.getDefaultElements();
        self.runFallback();
      },
      getDefaultSettings: function() {
        return {
          container: null,
          items: null,
          columnsCount: 3,
          verticalSpaceBetween: 30
        };
      },
      getDefaultElements: function() {
        return {
          $container: jQuery(this.settings.container),
          $items: jQuery(this.settings.items)
        };
      },
      runFallback: function() {
        var e2 = [], t2 = this.elements.$container.position().top, a2 = this.settings, o2 = a2.columnsCount;
        t2 += parseInt(this.elements.$container.css("margin-top"), 10);
        this.elements.$container.height("");
        this.elements.$items.each(function(n2) {
          var i2 = Math.floor(n2 / o2), l = n2 % o2, r = jQuery(this), s = r.position(), d = r[0].getBoundingClientRect().height + a2.verticalSpaceBetween;
          if (i2) {
            var m = s.top - t2 - e2[l];
            m -= parseInt(r.css("margin-top"), 10);
            m *= -1;
            r.css("margin-top", m + "px");
            e2[l] += d;
          } else {
            e2.push(d);
          }
        });
        this.elements.$container.height(Math.max.apply(Math, e2));
      }
    }
  }, t = {
    modal: !(n = {
      getDataToSave: function(e2) {
        return e2.id = window.elementor.config.post_id, e2;
      },
      init: function() {
        window.elementor.settings.master_template && (window.elementor.settings.master_template.getDataToSave = this.getDataToSave), window.elementor.settings.master_page && (window.elementor.settings.master_page.getDataToSave = this.getDataToSave, window.elementor.settings.master_page.changeCallbacks = {
          custom_header: function() {
            this.save(function() {
              elementor.reloadPreview(), elementor.once("preview:loaded", function() {
                elementor.getPanelView().setPage("master_page_settings");
              });
            });
          },
          custom_footer: function() {
            this.save(function() {
              elementor.reloadPreview(), elementor.once("preview:loaded", function() {
                elementor.getPanelView().setPage("master_page_settings");
              });
            });
          }
        });
      }
    }),
    layout: !(o = {
      MasterSearchView: null,
      init: function() {
        this.MasterSearchView = window.elementor.modules.controls.BaseData.extend({
          onReady: function() {
            var t2 = this.model.attributes.action, a2 = this.model.attributes.query_params;
            this.ui.select.find("option").each(function(t3, a3) {
              e(this).attr("selected", true);
            }), this.ui.select.select2({
              ajax: {
                url: function() {
                  var o2 = "";
                  return 0 < a2.length && e.each(a2, function(e2, t3) {
                    window.elementor.settings.page.model.attributes[t3] && (o2 += "&" + t3 + "=" + window.elementor.settings.page.model.attributes[t3]);
                  }), ajaxurl + "?action=" + t2 + o2;
                },
                dataType: "json"
              },
              placeholder: "Please enter 3 or more characters",
              minimumInputLength: 3
            });
          },
          onBeforeDestroy: function() {
            this.ui.select.data("select2") && this.ui.select.select2("destroy"), this.$el.remove();
          }
        }), window.elementor.addControlView("master_search", this.MasterSearchView);
      }
    }),
    collections: {},
    tabs: {},
    defaultTab: "",
    channels: {},
    atIndex: null,
    init: function() {
      window.elementor.on("preview:loaded", window._.bind(t.onPreviewLoaded, t)), a.init(), o.init(), n.init();
    },
    onPreviewLoaded: function() {
      let e2 = setInterval(() => {
        window.elementor.$previewContents.find(".elementor-add-new-section").length && (this.initMasterTempsButton(), clearInterval(e2));
      }, 100);
      window.elementor.$previewContents.on("click.addMasterTemplate", ".ma-el-add-section-btn", _.bind(function() {
        this.toggleEditorMode();
        this.showTemplatesModal();
      }, this)), this.channels = {
        templates: Backbone.Radio.channel("MASTER_EDITOR:templates"),
        tabs: Backbone.Radio.channel("MASTER_EDITOR:tabs"),
        layout: Backbone.Radio.channel("MASTER_EDITOR:layout")
      }, this.tabs = i.tabs, this.defaultTab = i.defaultTab;
    },
    initMasterTempsButton: function() {
      var a2 = window.elementor.$previewContents.find(".elementor-add-new-section"), o2 = '<button type="button" class="elementor-add-section-area-button ma-el-add-section-btn" title="Master Addons Templates" aria-label="Master Addons Templates"><div class="jltma-editor-icon"></div></button>';
      a2.length && i.MasterAddonsEditorBtn && a2.each(function() {
        var $section = e(this);
        if (!$section.find(".ma-el-add-section-btn").length) {
          var $dragTitle = $section.find(".elementor-add-section-drag-title");
          if ($dragTitle.length) {
            e(o2).insertBefore($dragTitle);
          } else {
            $section.append(e(o2));
          }
        }
      });
      window.elementor.$previewContents.on("click.addMasterTemplate", ".elementor-editor-section-settings .elementor-editor-element-add", function() {
        t.toggleEditorMode();
        var a3 = e(this).closest(".elementor-top-section"), n2 = a3.data("model-cid");
        elementor.config.version < "3.0.0" ? window.elementor.sections.currentView.collection.length && e.each(window.elementor.sections.currentView.collection.models, function(e2, a4) {
          n2 === a4.cid && (t.atIndex = e2);
        }) : elementor.previewView.collection.length && e.each(elementor.previewView.collection.models, function(e2, a4) {
          n2 === a4.cid && (t.atIndex = e2);
        });
        if (i.MasterAddonsEditorBtn) {
          var $addSection = a3.prev(".elementor-add-section").find(".elementor-add-new-section");
          if (!$addSection.find(".ma-el-add-section-btn").length) {
            var $dragTitle = $addSection.find(".elementor-add-section-drag-title");
            if ($dragTitle.length) {
              e(o2).insertBefore($dragTitle);
            } else {
              $addSection.append(e(o2));
            }
          }
        }
      });
    },
    getFilter: function(e2) {
      return this.channels.templates.request("filter:" + e2);
    },
    setFilter: function(e2, t2) {
      this.channels.templates.reply("filter:" + e2, t2), this.channels.templates.trigger("filter:change");
    },
    getTab: function() {
      return this.channels.tabs.request("filter:tabs");
    },
    setTab: function(e2, t2) {
      this.channels.tabs.reply("filter:tabs", e2), t2 || this.channels.tabs.trigger("filter:change");
    },
    getTabs: function() {
      var e2 = [];
      return _.each(this.tabs, function(t2, a2) {
        e2.push({
          slug: a2,
          title: t2.title
        });
      }), e2;
    },
    getPreview: function(e2) {
      return this.channels.layout.request("preview");
    },
    setPreview: function(e2, t2) {
      this.channels.layout.reply("preview", e2), t2 || this.channels.layout.trigger("preview:change");
    },
    getKeywords: function() {
      return _.each(this.keywords, function(e2, t2) {
        tabs.push({
          slug: t2,
          title: e2
        });
      }), [];
    },
    showTemplatesModal: function() {
      jQuery("body").addClass("master-addons-template-popup-loaded");
      var modal = this.getModal();
      modal.show();
      setTimeout(function() {
        e(".dialog-widget-content").off("click");
        e(".dialog-lightbox-widget").off("click");
        e(".dialog-lightbox-widget-content").off("click");
        e(".dialog-widget-content, .dialog-lightbox-widget").on("click", function(event) {
          if (event.target === this) {
            event.stopPropagation();
            event.preventDefault();
            return false;
          }
        });
      }, 100);
      this.layout || (this.layout = new a.ModalLayoutView(), this.layout.showLoadingView()), this.setTab(this.defaultTab, true), this.requestTemplates(this.defaultTab), this.setPreview("initial");
    },
    requestTemplates: function(t2) {
      var o2 = this, n2 = o2.tabs[t2];
      o2.setFilter("category", false), n2.data.templates && n2.data.categories ? o2.layout.showTemplatesView(n2.data.templates, n2.data.categories, n2.data.keywords) : e.ajax({
        url: ajaxurl,
        type: "get",
        dataType: "json",
        data: {
          action: "jltma_get_templates",
          security: MasterAddonsData.get_templates_nonce,
          tab: t2,
          page: 1
        },
        success: function(e2) {
          var n3 = new a.LibraryCollection(e2.data.templates), i2 = new a.CategoriesCollection(e2.data.categories);
          o2.tabs[t2].data = {
            templates: n3,
            categories: i2,
            keywords: e2.data.keywords,
            pagination: e2.data.pagination || null
          }, o2.layout.showTemplatesView(n3, i2, e2.data.keywords);
        }
      });
    },
    loadMoreTemplates: function() {
      var o2 = this, tab = o2.getTab(), tabData = o2.tabs[tab].data;
      if (!tabData || !tabData.pagination || !tabData.pagination.has_more || o2._loadingMore) return;
      o2._loadingMore = true;
      var nextPage = tabData.pagination.current_page + 1;
      var $loading = e(".ma-el-template-loading-more");
      if ($loading.length) $loading.show();
      e.ajax({
        url: ajaxurl,
        type: "get",
        dataType: "json",
        data: {
          action: "jltma_get_templates",
          security: MasterAddonsData.get_templates_nonce,
          tab,
          page: nextPage
        },
        success: function(response) {
          if (response.data && response.data.templates && response.data.templates.length > 0) {
            tabData.templates.add(response.data.templates);
            tabData.pagination = response.data.pagination;
          }
          o2._loadingMore = false;
          if ($loading.length) $loading.hide();
        },
        error: function() {
          o2._loadingMore = false;
          if ($loading.length) $loading.hide();
        }
      });
    },
    closeModal: function() {
      this.getModal().hide();
      jQuery("body").removeClass("master-addons-template-popup-loaded");
    },
    getModal: function() {
      return this.modal || (this.modal = elementor.dialogsManager.createWidget("lightbox", {
        id: "ma-el-modal-template",
        className: "elementor-templates-modal",
        closeButton: false,
        closeOnOutsideClick: false,
        closeOnEscKey: false,
        hide: {
          onOutsideClick: false,
          onEscKeyPress: false
        }
      })), this.modal;
    },
    editorCheck: function() {
      return e("body").hasClass("elementor-editor-active") ? true : false;
    },
    toggleEditorMode: function() {
      var body = e("body");
      body.removeClass("elementor-editor-active").addClass("elementor-editor-preview");
      body.removeClass("master-addons-template-popup-loaded");
    }
  }, e(window).on("elementor:init", t.init);
  window.MasterAddonsEditor = t;
  e(document).on("click", "#ma-el-template-cache-refresh", function(event) {
    event.preventDefault();
    var $button = e(this);
    var $icon = $button.find("i");
    $button.addClass("updating");
    $icon.removeClass("eicon-sync").addClass("eicon-loading eicon-animation-spin");
    e.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        action: "jltma_refresh_templates_cache",
        _wpnonce: MasterAddonsData.refresh_cache_nonce
      },
      success: function(response) {
        if (response.success) {
          console.log("Template cache refreshed successfully");
          var $cacheStatus = e("#ma-el-template-cache-status");
          if ($cacheStatus.length && response.data.total_templates) {
            var newCount = response.data.total_templates;
            $cacheStatus.find(".cache-count").text(newCount);
            $cacheStatus.attr("title", "Cache Status: " + newCount + " templates cached");
            if (newCount > 0) {
              $cacheStatus.show();
            } else {
              $cacheStatus.hide();
            }
          }
          $button.removeClass("updating");
          $icon.removeClass("eicon-loading eicon-animation-spin").addClass("eicon-sync");
          setTimeout(function() {
            if (t && t.getTab) {
              var currentTab = t.getTab();
              if (t.tabs && t.tabs[currentTab]) {
                t.tabs[currentTab].data = null;
              }
              t.requestTemplates(currentTab);
            }
          }, 500);
          $button.attr("title", "Cache refreshed successfully!");
          setTimeout(function() {
            $button.attr("title", "Refresh Cache");
          }, 3e3);
        } else {
          console.error("Failed to refresh template cache:", response.data ? response.data.message : "Unknown error");
          $button.removeClass("updating");
          $icon.removeClass("eicon-loading eicon-animation-spin").addClass("eicon-sync");
        }
      },
      error: function(xhr, status, error) {
        console.error("AJAX error refreshing template cache:", error);
        $button.removeClass("updating");
        $icon.removeClass("eicon-loading eicon-animation-spin").addClass("eicon-sync");
      }
    });
  });
})(jQuery);
})();
