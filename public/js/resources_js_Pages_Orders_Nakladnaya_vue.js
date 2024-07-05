"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Orders_Nakladnaya_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Shared_SearchInput__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Shared/SearchInput */ "./resources/js/Shared/SearchInput.vue");
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
  metaInfo: {
    title: 'Накладная'
  },
  props: {
    id: Number
  },
  components: {
    SearchInput: _Shared_SearchInput__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      nakladnaya: null,
      market: null,
      type: null,
      items: [],
      branches: [],
      loading: true,
      saving: false,
      poisk: '',
      // поиск магазина
      results: [],
      // результаты поиска магазинов
      searchingMarket: false // режим поиск магазина

    };
  },
  computed: {
    total: function total() {
      var sum = 0;
      this.items.forEach(function (i) {
        var s = +i.price * (+i.amount - +i.brak);
        sum += isNaN(s) ? 0 : +s;
      });
      return sum;
    }
  },
  watch: {
    poisk: function poisk() {
      if (this.poisk === '') {
        this.results = [];
        return;
      }

      this.updateResults();
    }
  },
  created: function created() {
    this.get();
  },
  methods: {
    updateResults: function updateResults() {
      var _this = this;

      if (this.poisk === '') {
        this.results = this.branches;
        return;
      }

      this.results = this.branches.filter(function (i) {
        return i.name.toLowerCase().includes(_this.poisk.toLowerCase());
      });
    },
    createMarket: function createMarket() {
      var _this2 = this;

      if (this.poisk === '') return;
      if (!confirm('Вы уверены, что хотите создать новый магазин?')) return;
      this.searchingMarket = false;
      this.loading = true;

      try {
        axios__WEBPACK_IMPORTED_MODULE_0___default().post("create-market", {
          name: this.poisk,
          user_id: this.nakladnaya.user_id
        }).then(function (response) {
          _this2.loading = false;
          _this2.market = response.data;
          _this2.poisk = '';
        });
      } catch (e) {
        this.loading = false;
        this.searchingMarket = true;
        alert('Ошибка создания магазина');
      }
    },
    get: function get() {
      var _this3 = this;

      try {
        axios__WEBPACK_IMPORTED_MODULE_0___default().get("nakladnaya/".concat(this.id)).then(function (response) {
          _this3.nakladnaya = response.data;
          _this3.type = response.data.type;
          _this3.market = response.data.market;
          _this3.branches = response.data.branches;
          _this3.items = _this3.parse(response.data.table);
          _this3.loading = false;

          _this3.updateResults();
        });
      } catch (e) {
        this.loading = false;
      }
    },
    parse: function parse(data) {
      var arr = [];
      data.forEach(function (i) {
        arr.push({
          index: i[0],
          name: i[1],
          amount: i[2],
          brak: i[3],
          price: i[4],
          sum: i[5],
          store_id: i[6]
        });
      });
      return arr;
    },
    update: function update() {
      var _this4 = this;

      try {
        var _this$market;

        this.saving = true;
        axios__WEBPACK_IMPORTED_MODULE_0___default().post("nakladnaya/update", {
          id: this.id,
          items: this.items,
          shop_id: (_this$market = this.market) === null || _this$market === void 0 ? void 0 : _this$market.id,
          type: this.type
        }).then(function (response) {
          alert('Накладная обновлена');
          _this4.loading = false;
          _this4.saving = false;
        })["catch"](function (e) {
          var _e$response, _e$response$data;

          console.log(e);
          _this4.loading = false;
          _this4.saving = false;
          alert(e ? e === null || e === void 0 ? void 0 : (_e$response = e.response) === null || _e$response === void 0 ? void 0 : (_e$response$data = _e$response.data) === null || _e$response$data === void 0 ? void 0 : _e$response$data.message : 'Ошибка обновления накладной');
        });
      } catch (e) {
        console.log(e);
        this.loading = false;
        this.saving = false;
        alert('Ошибка обновления накладной');
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SearchInput.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SearchInput.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      "default": function _default() {
        return "search-input-".concat(this._uid);
      }
    },
    type: {
      type: String,
      "default": 'text'
    },
    col: {
      type: Boolean,
      "default": true
    },
    value: String,
    placeholder: String,
    error: String
  },
  methods: {
    focus: function focus() {
      this.$refs.input.focus();
    },
    select: function select() {
      this.$refs.input.select();
    },
    setSelectionRange: function setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end);
    }
  }
});

/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.min-w-640px[data-v-774ba723] {\n    min-width: 640px;\n}\n.bottom[data-v-774ba723] {\n  justify-content: flex-end;\n  align-items: center;\n  padding-bottom: 150px;\n  background: rgba(255, 255, 255, 0.7);\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_style_index_0_id_774ba723_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_style_index_0_id_774ba723_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_style_index_0_id_774ba723_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/Pages/Orders/Nakladnaya.vue":
/*!**************************************************!*\
  !*** ./resources/js/Pages/Orders/Nakladnaya.vue ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Nakladnaya_vue_vue_type_template_id_774ba723_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Nakladnaya.vue?vue&type=template&id=774ba723&scoped=true& */ "./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=template&id=774ba723&scoped=true&");
/* harmony import */ var _Nakladnaya_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Nakladnaya.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=script&lang=js&");
/* harmony import */ var _Nakladnaya_vue_vue_type_style_index_0_id_774ba723_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css& */ "./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Nakladnaya_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Nakladnaya_vue_vue_type_template_id_774ba723_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _Nakladnaya_vue_vue_type_template_id_774ba723_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "774ba723",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Orders/Nakladnaya.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Shared/SearchInput.vue":
/*!*********************************************!*\
  !*** ./resources/js/Shared/SearchInput.vue ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _SearchInput_vue_vue_type_template_id_41c9ef56_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SearchInput.vue?vue&type=template&id=41c9ef56&scoped=true& */ "./resources/js/Shared/SearchInput.vue?vue&type=template&id=41c9ef56&scoped=true&");
/* harmony import */ var _SearchInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SearchInput.vue?vue&type=script&lang=js& */ "./resources/js/Shared/SearchInput.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SearchInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SearchInput_vue_vue_type_template_id_41c9ef56_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _SearchInput_vue_vue_type_template_id_41c9ef56_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "41c9ef56",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Shared/SearchInput.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** ./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Nakladnaya.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Shared/SearchInput.vue?vue&type=script&lang=js&":
/*!**********************************************************************!*\
  !*** ./resources/js/Shared/SearchInput.vue?vue&type=script&lang=js& ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SearchInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SearchInput.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SearchInput.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SearchInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css& ***!
  \***********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_style_index_0_id_774ba723_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=style&index=0&id=774ba723&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=template&id=774ba723&scoped=true&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=template&id=774ba723&scoped=true& ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_template_id_774ba723_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_template_id_774ba723_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Nakladnaya_vue_vue_type_template_id_774ba723_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Nakladnaya.vue?vue&type=template&id=774ba723&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=template&id=774ba723&scoped=true&");


/***/ }),

/***/ "./resources/js/Shared/SearchInput.vue?vue&type=template&id=41c9ef56&scoped=true&":
/*!****************************************************************************************!*\
  !*** ./resources/js/Shared/SearchInput.vue?vue&type=template&id=41c9ef56&scoped=true& ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SearchInput_vue_vue_type_template_id_41c9ef56_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SearchInput_vue_vue_type_template_id_41c9ef56_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SearchInput_vue_vue_type_template_id_41c9ef56_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SearchInput.vue?vue&type=template&id=41c9ef56&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SearchInput.vue?vue&type=template&id=41c9ef56&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=template&id=774ba723&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Orders/Nakladnaya.vue?vue&type=template&id=774ba723&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************/
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
    "div",
    { staticClass: "relative  min-w-500px max-sm:text-xs" },
    [
      _c("p", { staticClass: "text-lg font-bold mb-3 border-b pb-2" }, [
        _vm._v("Накладная №" + _vm._s(_vm.id)),
      ]),
      _vm._v(" "),
      _vm.searchingMarket
        ? [
            _c(
              "button",
              {
                staticClass: "btn btn-primary btn-sm mb-3",
                on: {
                  click: function ($event) {
                    _vm.searchingMarket = false
                    _vm.poisk = ""
                  },
                },
              },
              [_vm._v("\n      Вернуться в накладную\n    ")]
            ),
            _vm._v(" "),
            _c("search-input", {
              staticClass: "w-full mb-3",
              attrs: { placeholder: "Поиск по магазину" },
              model: {
                value: _vm.poisk,
                callback: function ($$v) {
                  _vm.poisk = $$v
                },
                expression: "poisk",
              },
            }),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "results" },
              [
                _vm.results.length === 0
                  ? _c(
                      "div",
                      { staticClass: "pb-6 flex gap-3 mb-6 items-center" },
                      [
                        _c("span", [_vm._v("Ничего не найдено")]),
                        _vm._v(" "),
                        _vm.results.length === 0
                          ? _c(
                              "div",
                              {
                                staticClass:
                                  "text-white px-2 bg-indigo-500 py-1 rounded inline-block",
                                on: { click: _vm.createMarket },
                              },
                              [_vm._v("Создать магазин")]
                            )
                          : _vm._e(),
                      ]
                    )
                  : _vm._e(),
                _vm._v(" "),
                _vm._l(_vm.results, function (result, i) {
                  return _c("div", { key: i }, [
                    _c(
                      "div",
                      { staticClass: "flex items-center justify-between mb-6" },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "font-bold hover:bg-gray-100 cursor-pointer px-2 py-1 rounded",
                            on: {
                              click: function ($event) {
                                _vm.market = result
                                _vm.searchingMarket = false
                              },
                            },
                          },
                          [
                            _vm._v(
                              "\n            " +
                                _vm._s(result.name) +
                                "\n          "
                            ),
                          ]
                        ),
                      ]
                    ),
                  ])
                }),
              ],
              2
            ),
          ]
        : _vm.nakladnaya
        ? [
            _c("div", { staticClass: "flex gap-3 mb-3" }, [
              _c("p", { staticClass: "font-bold w-1/4" }, [_vm._v("Тип:")]),
              _vm._v(" "),
              _c(
                "select",
                {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.type,
                      expression: "type",
                    },
                  ],
                  staticClass: "border-b-2",
                  attrs: { label: "опция", placeholder: "Тип накладной" },
                  on: {
                    change: function ($event) {
                      var $$selectedVal = Array.prototype.filter
                        .call($event.target.options, function (o) {
                          return o.selected
                        })
                        .map(function (o) {
                          var val = "_value" in o ? o._value : o.value
                          return val
                        })
                      _vm.type = $event.target.multiple
                        ? $$selectedVal
                        : $$selectedVal[0]
                    },
                  },
                },
                [
                  _c("option", { attrs: { value: "1" } }, [
                    _vm._v("Консегнация для МКТ"),
                  ]),
                  _vm._v(" "),
                  _c("option", { attrs: { value: "2" } }, [
                    _vm._v("Консегнация для себя"),
                  ]),
                  _vm._v(" "),
                  _c("option", { attrs: { value: "3" } }, [
                    _vm._v("Оплата наличными"),
                  ]),
                  _vm._v(" "),
                  _c("option", { attrs: { value: "9" } }, [_vm._v("Возврат")]),
                ]
              ),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "flex gap-3 mb-3 items-center" }, [
              _c("p", { staticClass: "font-bold w-1/4" }, [_vm._v("Магазин:")]),
              _vm._v(" "),
              _vm.market
                ? _c("span", { staticClass: "font-bold" }, [
                    _vm._v(_vm._s(_vm.market.name)),
                  ])
                : _vm._e(),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass:
                    "bg-indigo-500 px-2 py-1 rounded ml-1 text-white",
                  on: {
                    click: function ($event) {
                      _vm.searchingMarket = true
                    },
                  },
                },
                [_vm._v("Поменять")]
              ),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "flex gap-3 mb-3" }, [
              _c("p", { staticClass: "font-bold w-1/4" }, [_vm._v("Сумма:")]),
              _vm._v(" "),
              _c("span", { staticClass: "font-normal" }, [
                _vm._v(_vm._s(_vm.nakladnaya.sum)),
              ]),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "flex gap-3 mb-3" }, [
              _c("p", { staticClass: "font-bold w-1/4" }, [
                _vm._v("Реализатор:"),
              ]),
              _vm._v(" "),
              _c("span", { staticClass: "font-normal" }, [
                _vm._v(_vm._s(_vm.nakladnaya.realizator)),
              ]),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "flex gap-3 mb-3" }, [
              _c("p", { staticClass: "font-bold w-1/4" }, [_vm._v("Дата:")]),
              _vm._v(" "),
              _c("span", { staticClass: "font-normal" }, [
                _vm._v(_vm._s(_vm.nakladnaya.date)),
              ]),
            ]),
            _vm._v(" "),
            _c(
              "table",
              { staticClass: "w-full mb-6 min-w-640px" },
              [
                _c(
                  "tr",
                  { staticClass: "text-left" },
                  _vm._l(_vm.nakladnaya.headers, function (header, h) {
                    return _c(
                      "th",
                      { key: "header" + h, staticClass: "text-left pb-3 py-1" },
                      [_vm._v("\n          " + _vm._s(header) + "\n        ")]
                    )
                  }),
                  0
                ),
                _vm._v(" "),
                _vm._l(_vm.items, function (row, r) {
                  return _c("tr", { key: "row" + r, staticClass: "border" }, [
                    _c("td", { staticClass: "pl-1" }, [
                      _vm._v(_vm._s(row.index)),
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "py-1" }, [
                      _vm._v(_vm._s(row.name)),
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "py-1" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: row.amount,
                            expression: "row.amount",
                          },
                        ],
                        staticClass: "w-8",
                        attrs: { type: "number" },
                        domProps: { value: row.amount },
                        on: {
                          input: function ($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.$set(row, "amount", $event.target.value)
                          },
                        },
                      }),
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "py-1" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: row.brak,
                            expression: "row.brak",
                          },
                        ],
                        staticClass: "w-8",
                        attrs: { type: "number" },
                        domProps: { value: row.brak },
                        on: {
                          input: function ($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.$set(row, "brak", $event.target.value)
                          },
                        },
                      }),
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "py-1" }, [
                      _vm._v(_vm._s(row.price)),
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "py-1" }, [
                      _vm._v(_vm._s(+row.price * (+row.amount - +row.brak))),
                    ]),
                  ])
                }),
                _vm._v(" "),
                _c("tr", [
                  _c("td", { staticClass: "pl-1" }),
                  _vm._v(" "),
                  _c("td", { staticClass: "py-1" }),
                  _vm._v(" "),
                  _c("td", { staticClass: "py-1" }),
                  _vm._v(" "),
                  _c("td", { staticClass: "py-1" }),
                  _vm._v(" "),
                  _c("td", { staticClass: "py-1" }, [_vm._v("ИТОГ")]),
                  _vm._v(" "),
                  _c("td", { staticClass: "py-1" }, [
                    _vm._v(_vm._s(_vm.total)),
                  ]),
                ]),
              ],
              2
            ),
          ]
        : _vm.loading
        ? [_vm._m(0)]
        : _vm._e(),
      _vm._v(" "),
      _vm.saving
        ? _c(
            "div",
            {
              staticClass:
                "flex flex-col p-4 absolute top-0 left-0 justify-center w-full h-full z-10 bg-white bottom",
            },
            [
              _c("img", {
                staticClass: "w-8 h-8 bg-white",
                attrs: { src: "/img/loading.gif", alt: "" },
              }),
              _vm._v(" "),
              _c("p", { staticClass: "mt-4 text-center" }, [
                _vm._v("Подождите, накладная сохраняется..."),
              ]),
            ]
          )
        : _vm._e(),
    ],
    2
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "flex flex-col items-center p-4" }, [
      _c("img", {
        staticClass: "w-8 h-8 bg-white",
        attrs: { src: "/img/loading.gif", alt: "" },
      }),
      _vm._v(" "),
      _c("p", { staticClass: "mt-4 text-center" }, [
        _vm._v("Подождите, накладная загружается..."),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SearchInput.vue?vue&type=template&id=41c9ef56&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SearchInput.vue?vue&type=template&id=41c9ef56&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "flex", class: { "flex-col": !_vm.col } }, [
    _c("div", { class: { "w-1/12s": _vm.col } }, [
      _c("img", {
        staticClass: "h-6 ml-auto mr-4",
        attrs: { src: "/img/search.png" },
      }),
    ]),
    _vm._v(" "),
    _c("div", { class: { "w-11/12": _vm.col } }, [
      _c(
        "input",
        _vm._b(
          {
            ref: "input",
            staticClass: "w-full block  pb-1 border-b-2 border-gray-200",
            class: { error: _vm.error },
            attrs: { id: _vm.id, placeholder: _vm.placeholder, type: _vm.type },
            domProps: { value: _vm.value },
            on: {
              input: function ($event) {
                return _vm.$emit("input", $event.target.value)
              },
            },
          },
          "input",
          _vm.$attrs,
          false
        )
      ),
      _vm._v(" "),
      _c("div", { staticClass: "w-full mt-1" }, [
        _vm.error
          ? _c("div", { staticClass: "form-error" }, [
              _vm._v(_vm._s(_vm.error)),
            ])
          : _vm._e(),
      ]),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
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
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
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


/***/ })

}]);