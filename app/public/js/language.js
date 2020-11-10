(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/language"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/components/ConfirmationDialog.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/components/ConfirmationDialog.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['messages'],
  methods: {
    onConfirmation: function onConfirmation() {
      this.$emit("confirm");
    },
    conCancellation: function conCancellation() {
      this.$emit("cancel");
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/components/ConfirmationDialog.vue?vue&type=template&id=47fd360e&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/components/ConfirmationDialog.vue?vue&type=template&id=47fd360e& ***!
  \******************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "flash-message flash-message-active" }, [
    _c("div", { staticClass: "centralize-wrapper" }, [
      _c("div", { staticClass: "centralize-inner" }, [
        _c("div", { staticClass: "centralize-content flash-confirmation" }, [
          _c("div", { staticClass: "flash-removable" }, [
            _c(
              "button",
              {
                staticClass: "close flash-close",
                attrs: { type: "button", "aria-hidden": "true" },
                on: { click: _vm.conCancellation }
              },
              [_vm._v("×")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "flash-icon" }),
            _vm._v(" "),
            _c("p", [_vm._v(_vm._s(_vm.messages["title"]))]),
            _vm._v(" "),
            _c(
              "a",
              {
                staticClass: "hidden-flash-item btn btn-sm btn-info btn-flat",
                attrs: { href: "javascript:" },
                on: { click: _vm.onConfirmation }
              },
              [_vm._v(_vm._s(_vm.messages["confirmButtonText"]))]
            ),
            _vm._v(" "),
            _c(
              "a",
              {
                staticClass:
                  "hidden-flash-item btn btn-sm btn-warning btn-flat",
                attrs: { href: "javascript:" },
                on: { click: _vm.conCancellation }
              },
              [_vm._v(_vm._s(_vm.messages["cancelButtonText"]))]
            )
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./resources/components/ConfirmationDialog.vue":
/*!*****************************************************!*\
  !*** ./resources/components/ConfirmationDialog.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ConfirmationDialog_vue_vue_type_template_id_47fd360e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ConfirmationDialog.vue?vue&type=template&id=47fd360e& */ "./resources/components/ConfirmationDialog.vue?vue&type=template&id=47fd360e&");
/* harmony import */ var _ConfirmationDialog_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ConfirmationDialog.vue?vue&type=script&lang=js& */ "./resources/components/ConfirmationDialog.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ConfirmationDialog_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ConfirmationDialog_vue_vue_type_template_id_47fd360e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ConfirmationDialog_vue_vue_type_template_id_47fd360e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/components/ConfirmationDialog.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/components/ConfirmationDialog.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/components/ConfirmationDialog.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ConfirmationDialog_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../node_modules/babel-loader/lib??ref--4-0!../../node_modules/vue-loader/lib??vue-loader-options!./ConfirmationDialog.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/components/ConfirmationDialog.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ConfirmationDialog_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/components/ConfirmationDialog.vue?vue&type=template&id=47fd360e&":
/*!************************************************************************************!*\
  !*** ./resources/components/ConfirmationDialog.vue?vue&type=template&id=47fd360e& ***!
  \************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ConfirmationDialog_vue_vue_type_template_id_47fd360e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../node_modules/vue-loader/lib??vue-loader-options!./ConfirmationDialog.vue?vue&type=template&id=47fd360e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/components/ConfirmationDialog.vue?vue&type=template&id=47fd360e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ConfirmationDialog_vue_vue_type_template_id_47fd360e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ConfirmationDialog_vue_vue_type_template_id_47fd360e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/language.js":
/*!**********************************!*\
  !*** ./resources/js/language.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

Vue.component('confirmation-dialog', __webpack_require__(/*! ../components/ConfirmationDialog */ "./resources/components/ConfirmationDialog.vue")["default"]);
new Vue({
  el: '#app',
  data: {
    languages: data.languages,
    selectedLanguage: data.selectedLanguage,
    searchPhrase: '',
    selectedKey: null,
    translations: [],
    filteredTranslations: [],
    newKeyErrorMsg: '',
    newKeyValueErrorMsg: '',
    confirmDialog: false,
    addNewKey: false,
    newKey: '',
    newKeyValue: ''
  },
  methods: {
    highlight: function highlight(value) {
      if (!value) {
        return;
      }

      return value.replace(/:{1}[\w-]+/gi, function (match) {
        return '<mark>' + match + '</mark>';
      });
    },
    changeLanguage: function changeLanguage(language) {
      this.selectedLanguage = language;
      this.filteredTranslations = this.translations[language];
      this.searchPhrase = '';
      this.selectedKey = null;
      this.addNewKey = '';
    },
    save: function save() {
      var request = {
        translations: this.translations
      };
      axios.put(data.routes.update, request).then(function (response) {
        flashBox(response.data.type, response.data.message);
      });
    },
    saveNewKey: function saveNewKey() {
      var _this = this;

      if (!this.newKey.length) {
        this.newKeyErrorMsg = data.newKeyError;
        return;
      }

      this.newKeyErrorMsg = '';

      if (!this.newKeyValue.length) {
        this.newKeyValueErrorMsg = data.newKeyValueError;
        return;
      }

      this.newKeyValueErrorMsg = '';

      _.forEach(this.languages, function (language) {
        _this.translations[language][_this.newKey] = _this.newKeyValue;
      });

      this.save();
    },
    getTranslations: function getTranslations() {
      var _this2 = this;

      axios.get(data.routes.getTranslations).then(function (response) {
        _this2.translations = response.data;
        _this2.filteredTranslations = _this2.translations[_this2.selectedLanguage];
      });
    },
    changeTranslations: function changeTranslations(key, event) {
      this.translations[this.selectedLanguage][key] = event.target.value;
      this.filteredTranslations[key] = event.target.value;
    },
    searchTranslations: function searchTranslations() {
      var _this3 = this;

      var filteredTranslations = {};

      if (this.searchPhrase.length > 0) {
        _.forEach(this.filteredTranslations, function (value, translation) {
          if (translation && translation.toString().trim().toLowerCase().includes(_this3.searchPhrase.trim().toLowerCase())) {
            filteredTranslations[translation] = _this3.filteredTranslations[translation];
          }
        });

        this.filteredTranslations = filteredTranslations;
      } else {
        this.filteredTranslations = this.translations[this.selectedLanguage];
      }
    },
    sync: function sync() {
      var _this4 = this;

      axios.put(data.routes.sync).then(function (response) {
        flashBox(response.data.type, response.data.message);
        _this4.translations = response.data.translations;
        _this4.filteredTranslations = _this4.translations[_this4.selectedLanguage];
      });
    },
    add: function add() {
      this.addNewKey = true;
    },
    remove: function remove() {
      this.confirmDialog = true;
    },
    confirmDelete: function confirmDelete() {
      var _this5 = this;

      this.confirmDialog = false;

      _.forEach(this.languages, function (lang) {
        _this5.translations[lang] = _.omit(_this5.translations[lang], [_this5.selectedKey]);
      });

      this.filteredTranslations = this.translations[this.selectedLanguage];
      this.selectedKey = null;
      this.save();
    },
    cancelDelete: function cancelDelete() {
      this.confirmDialog = false;
    }
  },
  mounted: function mounted() {
    this.getTranslations();
  },
  filters: {
    uppercase: function uppercase(value) {
      if (!value) return '';
      value = value.toString();
      return value.toUpperCase();
    }
  }
});

/***/ }),

/***/ 2:
/*!****************************************!*\
  !*** multi ./resources/js/language.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/trademen/resources/js/language.js */"./resources/js/language.js");


/***/ })

},[[2,"/js/manifest"]]]);