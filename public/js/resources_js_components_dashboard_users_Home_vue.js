"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_dashboard_users_Home_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/users/Home.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/users/Home.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
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
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "HomeUser",
  data: function data() {
    return {
      overlay: false,
      snackbar: {
        active: false,
        text: "Mensaje",
        timeout: 2000,
        color: ""
      }
    };
  },
  methods: {
    newUser: function newUser() {// Aqui va codigo para nuevo Usuario
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VSnackbar/VSnackbar.sass":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VSnackbar/VSnackbar.sass ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".theme--light.v-snack__wrapper {\n  color: rgba(0, 0, 0, 0.87);\n}\n\n.theme--dark.v-snack__wrapper {\n  color: #FFFFFF;\n}\n\n.v-sheet.v-snack__wrapper {\n  border-radius: 4px;\n}\n.v-sheet.v-snack__wrapper:not(.v-sheet--outlined) {\n  box-shadow: 0px 3px 5px -1px rgba(0, 0, 0, 0.2), 0px 6px 10px 0px rgba(0, 0, 0, 0.14), 0px 1px 18px 0px rgba(0, 0, 0, 0.12);\n}\n.v-sheet.v-snack__wrapper.v-sheet--shaped {\n  border-radius: 24px 4px;\n}\n\n.v-snack {\n  bottom: 0;\n  display: flex;\n  font-size: 0.875rem;\n  justify-content: center;\n  left: 0;\n  pointer-events: none;\n  right: 0;\n  top: 0;\n  width: 100%;\n}\n.v-snack:not(.v-snack--absolute) {\n  height: 100vh;\n  position: fixed;\n  z-index: 1000;\n}\n.v-snack:not(.v-snack--centered):not(.v-snack--top) {\n  align-items: flex-end;\n}\n.v-snack__wrapper {\n  align-items: center;\n  border-color: currentColor !important;\n  display: flex;\n  margin: 8px;\n  max-width: 672px;\n  min-height: 48px;\n  min-width: 344px;\n  padding: 0;\n  pointer-events: auto;\n  position: relative;\n  transition-duration: 0.15s;\n  transition-property: opacity, transform;\n  transition-timing-function: cubic-bezier(0, 0, 0.2, 1);\n  z-index: 1;\n}\n.v-snack__wrapper.theme--dark {\n  background-color: #333333;\n  color: rgba(255, 255, 255, 0.87);\n}\n.v-snack__content {\n  flex-grow: 1;\n  font-size: 0.875rem;\n  font-weight: 400;\n  letter-spacing: 0.0178571429em;\n  line-height: 1.25rem;\n  margin-right: auto;\n  padding: 14px 16px;\n  text-align: initial;\n}\n.v-snack__action {\n  align-items: center;\n  align-self: center;\n  display: flex;\n}\n.v-snack__action .v-ripple__container {\n  display: none;\n}\n.v-application--is-ltr .v-snack__action {\n  margin-right: 8px;\n}\n.v-application--is-rtl .v-snack__action {\n  margin-left: 8px;\n}\n.v-snack__action > .v-snack__btn.v-btn {\n  padding: 0 8px;\n}\n.v-snack__btn {\n  margin-left: 0;\n  margin-right: 0;\n  margin: 0;\n  min-width: auto;\n}\n.v-snack--absolute {\n  height: 100%;\n  position: absolute;\n  z-index: 1;\n}\n.v-snack--centered {\n  align-items: center;\n}\n.v-snack--left {\n  justify-content: flex-start;\n  right: auto;\n}\n.v-snack--multi-line .v-snack__wrapper {\n  min-height: 68px;\n}\n.v-snack--right {\n  justify-content: flex-end;\n  left: auto;\n}\n.v-snack:not(.v-snack--has-background) .v-snack__wrapper {\n  box-shadow: none;\n}\n.v-snack--bottom {\n  top: auto;\n}\n.v-snack--text .v-snack__wrapper:before {\n  background-color: currentColor;\n  border-radius: inherit;\n  bottom: 0;\n  content: \"\";\n  left: 0;\n  opacity: 0.12;\n  pointer-events: none;\n  position: absolute;\n  right: 0;\n  top: 0;\n  z-index: -1;\n}\n.v-snack--top {\n  align-items: flex-start;\n  bottom: auto;\n}\n.v-snack--vertical .v-snack__wrapper {\n  flex-direction: column;\n}\n.v-snack--vertical .v-snack__wrapper .v-snack__action {\n  align-self: flex-end;\n  margin-bottom: 8px;\n}\n\n.v-snack-transition-enter.v-snack__wrapper {\n  transform: scale(0.8);\n}\n.v-snack-transition-enter.v-snack__wrapper, .v-snack-transition-leave-to.v-snack__wrapper {\n  opacity: 0;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/vuetify/src/components/VSnackbar/VSnackbar.sass":
/*!**********************************************************************!*\
  !*** ./node_modules/vuetify/src/components/VSnackbar/VSnackbar.sass ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VSnackbar_sass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!../../../../postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!../../../../sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./VSnackbar.sass */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VSnackbar/VSnackbar.sass");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VSnackbar_sass__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VSnackbar_sass__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/dashboard/users/Home.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/dashboard/users/Home.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Home.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/users/Home.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/dashboard/users/Home.vue?vue&type=template&id=fddc5168&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/dashboard/users/Home.vue?vue&type=template&id=fddc5168& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_fddc5168___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_fddc5168___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_fddc5168___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Home.vue?vue&type=template&id=fddc5168& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/users/Home.vue?vue&type=template&id=fddc5168&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/users/Home.vue?vue&type=template&id=fddc5168&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/dashboard/users/Home.vue?vue&type=template&id=fddc5168& ***!
  \********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-main",
    [
      _c(
        "v-overlay",
        { attrs: { value: _vm.overlay } },
        [
          _c("v-progress-circular", {
            attrs: { indeterminate: "", size: "64" },
          }),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-snackbar",
        {
          attrs: {
            color: _vm.snackbar.color,
            timeout: _vm.snackbar.timeout,
            top: "",
            elevation: "24",
          },
          scopedSlots: _vm._u([
            {
              key: "action",
              fn: function (ref) {
                var attrs = ref.attrs
                return [
                  _c(
                    "v-btn",
                    _vm._b(
                      {
                        staticClass: "txt_white",
                        attrs: { icon: "" },
                        on: {
                          click: function ($event) {
                            _vm.snackbar.active = false
                          },
                        },
                      },
                      "v-btn",
                      attrs,
                      false
                    ),
                    [_c("v-icon", [_vm._v("close")])],
                    1
                  ),
                ]
              },
            },
          ]),
          model: {
            value: _vm.snackbar.active,
            callback: function ($$v) {
              _vm.$set(_vm.snackbar, "active", $$v)
            },
            expression: "snackbar.active",
          },
        },
        [_vm._v("\n        " + _vm._s(_vm.snackbar.text) + "\n        ")]
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "mx-2 my-2" },
        [
          _c("p", { staticClass: "text-h5 my-4 ml-2" }, [
            _vm._v("Usuarios / Docentes"),
          ]),
          _vm._v(" "),
          _c(
            "v-btn",
            {
              staticClass: "mr-10 my-2 new_btn txt_white",
              attrs: { large: "", color: "teal" },
              on: {
                click: function ($event) {
                  return _vm.newUser()
                },
              },
            },
            [
              _c("v-icon", { staticClass: "mr-2" }, [_vm._v("person_add")]),
              _vm._v("\n            Nuevo\n        "),
            ],
            1
          ),
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/dashboard/users/Home.vue":
/*!**********************************************************!*\
  !*** ./resources/js/components/dashboard/users/Home.vue ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Home_vue_vue_type_template_id_fddc5168___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Home.vue?vue&type=template&id=fddc5168& */ "./resources/js/components/dashboard/users/Home.vue?vue&type=template&id=fddc5168&");
/* harmony import */ var _Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Home.vue?vue&type=script&lang=js& */ "./resources/js/components/dashboard/users/Home.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
/* harmony import */ var _node_modules_vuetify_loader_lib_runtime_installComponents_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../node_modules/vuetify-loader/lib/runtime/installComponents.js */ "./node_modules/vuetify-loader/lib/runtime/installComponents.js");
/* harmony import */ var _node_modules_vuetify_loader_lib_runtime_installComponents_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vuetify_loader_lib_runtime_installComponents_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var vuetify_lib_components_VBtn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vuetify/lib/components/VBtn */ "./node_modules/vuetify/lib/components/VBtn/VBtn.js");
/* harmony import */ var vuetify_lib_components_VIcon__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vuetify/lib/components/VIcon */ "./node_modules/vuetify/lib/components/VIcon/VIcon.js");
/* harmony import */ var vuetify_lib_components_VMain__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! vuetify/lib/components/VMain */ "./node_modules/vuetify/lib/components/VMain/VMain.js");
/* harmony import */ var vuetify_lib_components_VOverlay__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! vuetify/lib/components/VOverlay */ "./node_modules/vuetify/lib/components/VOverlay/VOverlay.js");
/* harmony import */ var vuetify_lib_components_VProgressCircular__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! vuetify/lib/components/VProgressCircular */ "./node_modules/vuetify/lib/components/VProgressCircular/VProgressCircular.js");
/* harmony import */ var vuetify_lib_components_VSnackbar__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! vuetify/lib/components/VSnackbar */ "./node_modules/vuetify/lib/components/VSnackbar/VSnackbar.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Home_vue_vue_type_template_id_fddc5168___WEBPACK_IMPORTED_MODULE_0__.render,
  _Home_vue_vue_type_template_id_fddc5168___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* vuetify-loader */
;






_node_modules_vuetify_loader_lib_runtime_installComponents_js__WEBPACK_IMPORTED_MODULE_3___default()(component, {VBtn: vuetify_lib_components_VBtn__WEBPACK_IMPORTED_MODULE_4__["default"],VIcon: vuetify_lib_components_VIcon__WEBPACK_IMPORTED_MODULE_5__["default"],VMain: vuetify_lib_components_VMain__WEBPACK_IMPORTED_MODULE_6__["default"],VOverlay: vuetify_lib_components_VOverlay__WEBPACK_IMPORTED_MODULE_7__["default"],VProgressCircular: vuetify_lib_components_VProgressCircular__WEBPACK_IMPORTED_MODULE_8__["default"],VSnackbar: vuetify_lib_components_VSnackbar__WEBPACK_IMPORTED_MODULE_9__["default"]})


/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/dashboard/users/Home.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./node_modules/vuetify/lib/components/VSnackbar/VSnackbar.js":
/*!********************************************************************!*\
  !*** ./node_modules/vuetify/lib/components/VSnackbar/VSnackbar.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _src_components_VSnackbar_VSnackbar_sass__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../src/components/VSnackbar/VSnackbar.sass */ "./node_modules/vuetify/src/components/VSnackbar/VSnackbar.sass");
/* harmony import */ var _VSheet_VSheet__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../VSheet/VSheet */ "./node_modules/vuetify/lib/components/VSheet/VSheet.js");
/* harmony import */ var _mixins_colorable__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../mixins/colorable */ "./node_modules/vuetify/lib/mixins/colorable/index.js");
/* harmony import */ var _mixins_themeable__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../mixins/themeable */ "./node_modules/vuetify/lib/mixins/themeable/index.js");
/* harmony import */ var _mixins_toggleable__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../mixins/toggleable */ "./node_modules/vuetify/lib/mixins/toggleable/index.js");
/* harmony import */ var _mixins_positionable__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../mixins/positionable */ "./node_modules/vuetify/lib/mixins/positionable/index.js");
/* harmony import */ var _util_mixins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../util/mixins */ "./node_modules/vuetify/lib/util/mixins.js");
/* harmony import */ var _util_helpers__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../util/helpers */ "./node_modules/vuetify/lib/util/helpers.js");
/* harmony import */ var _util_console__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../util/console */ "./node_modules/vuetify/lib/util/console.js");
// Styles
 // Components

 // Mixins




 // Utilities




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_util_mixins__WEBPACK_IMPORTED_MODULE_1__["default"])(_VSheet_VSheet__WEBPACK_IMPORTED_MODULE_2__["default"], _mixins_colorable__WEBPACK_IMPORTED_MODULE_3__["default"], _mixins_toggleable__WEBPACK_IMPORTED_MODULE_4__["default"], (0,_mixins_positionable__WEBPACK_IMPORTED_MODULE_5__.factory)(['absolute', 'bottom', 'left', 'right', 'top'])
/* @vue/component */
).extend({
  name: 'v-snackbar',
  props: {
    app: Boolean,
    centered: Boolean,
    contentClass: {
      type: String,
      default: ''
    },
    multiLine: Boolean,
    text: Boolean,
    timeout: {
      type: [Number, String],
      default: 5000
    },
    transition: {
      type: [Boolean, String],
      default: 'v-snack-transition',
      validator: v => typeof v === 'string' || v === false
    },
    vertical: Boolean
  },
  data: () => ({
    activeTimeout: -1
  }),
  computed: {
    classes() {
      return {
        'v-snack--absolute': this.absolute,
        'v-snack--active': this.isActive,
        'v-snack--bottom': this.bottom || !this.top,
        'v-snack--centered': this.centered,
        'v-snack--has-background': this.hasBackground,
        'v-snack--left': this.left,
        'v-snack--multi-line': this.multiLine && !this.vertical,
        'v-snack--right': this.right,
        'v-snack--text': this.text,
        'v-snack--top': this.top,
        'v-snack--vertical': this.vertical
      };
    },

    // Text and outlined styles both
    // use transparent backgrounds
    hasBackground() {
      return !this.text && !this.outlined;
    },

    // Snackbar is dark by default
    // override themeable logic.
    isDark() {
      return this.hasBackground ? !this.light : _mixins_themeable__WEBPACK_IMPORTED_MODULE_6__["default"].options.computed.isDark.call(this);
    },

    styles() {
      if (this.absolute || !this.app) return {};
      const {
        bar,
        bottom,
        footer,
        insetFooter,
        left,
        right,
        top
      } = this.$vuetify.application;
      return {
        paddingBottom: (0,_util_helpers__WEBPACK_IMPORTED_MODULE_7__.convertToUnit)(bottom + footer + insetFooter),
        paddingLeft: (0,_util_helpers__WEBPACK_IMPORTED_MODULE_7__.convertToUnit)(left),
        paddingRight: (0,_util_helpers__WEBPACK_IMPORTED_MODULE_7__.convertToUnit)(right),
        paddingTop: (0,_util_helpers__WEBPACK_IMPORTED_MODULE_7__.convertToUnit)(bar + top)
      };
    }

  },
  watch: {
    isActive: 'setTimeout',
    timeout: 'setTimeout'
  },

  mounted() {
    if (this.isActive) this.setTimeout();
  },

  created() {
    /* istanbul ignore next */
    if (this.$attrs.hasOwnProperty('auto-height')) {
      (0,_util_console__WEBPACK_IMPORTED_MODULE_8__.removed)('auto-height', this);
    }
    /* istanbul ignore next */
    // eslint-disable-next-line eqeqeq


    if (this.timeout == 0) {
      (0,_util_console__WEBPACK_IMPORTED_MODULE_8__.deprecate)('timeout="0"', '-1', this);
    }
  },

  methods: {
    genActions() {
      return this.$createElement('div', {
        staticClass: 'v-snack__action '
      }, [(0,_util_helpers__WEBPACK_IMPORTED_MODULE_7__.getSlot)(this, 'action', {
        attrs: {
          class: 'v-snack__btn'
        }
      })]);
    },

    genContent() {
      return this.$createElement('div', {
        staticClass: 'v-snack__content',
        class: {
          [this.contentClass]: true
        },
        attrs: {
          role: 'status',
          'aria-live': 'polite'
        }
      }, [(0,_util_helpers__WEBPACK_IMPORTED_MODULE_7__.getSlot)(this)]);
    },

    genWrapper() {
      const setColor = this.hasBackground ? this.setBackgroundColor : this.setTextColor;
      const data = setColor(this.color, {
        staticClass: 'v-snack__wrapper',
        class: _VSheet_VSheet__WEBPACK_IMPORTED_MODULE_2__["default"].options.computed.classes.call(this),
        style: _VSheet_VSheet__WEBPACK_IMPORTED_MODULE_2__["default"].options.computed.styles.call(this),
        directives: [{
          name: 'show',
          value: this.isActive
        }],
        on: {
          pointerenter: () => window.clearTimeout(this.activeTimeout),
          pointerleave: this.setTimeout
        }
      });
      return this.$createElement('div', data, [this.genContent(), this.genActions()]);
    },

    genTransition() {
      return this.$createElement('transition', {
        props: {
          name: this.transition
        }
      }, [this.genWrapper()]);
    },

    setTimeout() {
      window.clearTimeout(this.activeTimeout);
      const timeout = Number(this.timeout);

      if (!this.isActive || // TODO: remove 0 in v3
      [0, -1].includes(timeout)) {
        return;
      }

      this.activeTimeout = window.setTimeout(() => {
        this.isActive = false;
      }, timeout);
    }

  },

  render(h) {
    return h('div', {
      staticClass: 'v-snack',
      class: this.classes,
      style: this.styles
    }, [this.transition !== false ? this.genTransition() : this.genWrapper()]);
  }

}));
//# sourceMappingURL=VSnackbar.js.map

/***/ })

}]);