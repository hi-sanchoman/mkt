"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Profit_ContentPhysDebts_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Shared_SelectInput__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Shared/SelectInput */ "./resources/js/Shared/SelectInput.vue");
/* harmony import */ var _Shared_TextInput__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Shared/TextInput */ "./resources/js/Shared/TextInput.vue");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_3__);
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
  name: 'ContentPhysDebts',
  components: {
    SelectInput: _Shared_SelectInput__WEBPACK_IMPORTED_MODULE_0__["default"],
    TextInput: _Shared_TextInput__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  props: {
    other_debts: Array
  },
  data: function data() {
    return {
      other_debt_id: 0,
      other_debt_amount: 0,
      selected_other_debt: null,
      moment: (moment__WEBPACK_IMPORTED_MODULE_3___default()),
      debts: []
    };
  },
  created: function created() {
    var _this$other_debts;

    var arr = [];
    (_this$other_debts = this.other_debts) === null || _this$other_debts === void 0 ? void 0 : _this$other_debts.forEach(function (d) {
      var item = d;
      var paid = d.payments.reduce(function (carry, c) {
        return carry + c.amount;
      }, 0);
      item.paid = paid;
      item.debt_for_today = Number(d.debt) - Number(paid);
      console.log('A', item.debt_for_today);
      if (Number(item.debt_for_today) > 0) arr.push(item);
    });
    this.debts = arr;
  },
  watch: {},
  computed: {},
  methods: {
    formatNum: function formatNum(num, type) {
      return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    },
    onOtherDebtClicked: function onOtherDebtClicked(debt) {
      this.selected_other_debt = debt;
      this.$modal.show('other_debt_history');
    },
    payOtherDebt: function payOtherDebt() {
      var _this = this;

      var d = this.debts.find(function (el) {
        return el.id == _this.other_debt_id;
      });
      console.log(this.other_debt_amount > Number(d.debt_for_today));

      if (d && this.other_debt_amount > Number(d.debt_for_today)) {
        alert('Сумма не должна превышать размер долга');
        return;
      }

      axios__WEBPACK_IMPORTED_MODULE_2___default().post("pay-other-debt", {
        id: this.other_debt_id,
        amount: this.other_debt_amount
      }).then(function (response) {
        _this.$modal.hide('pay-other-debt');

        alert('долг оплачен!');
        location.reload();
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SelectInput.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SelectInput.vue?vue&type=script&lang=js& ***!
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      "default": function _default() {
        return "select-input-".concat(this._uid);
      }
    },
    value: [String, Number, Boolean],
    label: String,
    error: String
  },
  data: function data() {
    return {
      selected: this.value
    };
  },
  watch: {
    selected: function selected(_selected) {
      this.$emit('input', _selected);
    }
  },
  methods: {
    focus: function focus() {
      this.$refs.input.focus();
    },
    select: function select() {
      this.$refs.input.select();
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/TextInput.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/TextInput.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

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
        return "text-input-".concat(this._uid);
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
    label: String,
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
    },
    onEnter: function onEnter(e) {
      console.log('on enter...', e);
      var form = event.target.form;

      var index = _toConsumableArray(form).indexOf(event.target);

      var next_index = index + 1;
      form.elements[next_index].select();
      form.elements[next_index].focus();
      event.preventDefault();
    }
  }
});

/***/ }),

/***/ "./resources/js/Pages/Profit/ContentPhysDebts.vue":
/*!********************************************************!*\
  !*** ./resources/js/Pages/Profit/ContentPhysDebts.vue ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ContentPhysDebts_vue_vue_type_template_id_6dbee8c7___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ContentPhysDebts.vue?vue&type=template&id=6dbee8c7& */ "./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=template&id=6dbee8c7&");
/* harmony import */ var _ContentPhysDebts_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ContentPhysDebts.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ContentPhysDebts_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ContentPhysDebts_vue_vue_type_template_id_6dbee8c7___WEBPACK_IMPORTED_MODULE_0__.render,
  _ContentPhysDebts_vue_vue_type_template_id_6dbee8c7___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Profit/ContentPhysDebts.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Shared/SelectInput.vue":
/*!*********************************************!*\
  !*** ./resources/js/Shared/SelectInput.vue ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _SelectInput_vue_vue_type_template_id_de51c2fc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SelectInput.vue?vue&type=template&id=de51c2fc& */ "./resources/js/Shared/SelectInput.vue?vue&type=template&id=de51c2fc&");
/* harmony import */ var _SelectInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SelectInput.vue?vue&type=script&lang=js& */ "./resources/js/Shared/SelectInput.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SelectInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SelectInput_vue_vue_type_template_id_de51c2fc___WEBPACK_IMPORTED_MODULE_0__.render,
  _SelectInput_vue_vue_type_template_id_de51c2fc___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Shared/SelectInput.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Shared/TextInput.vue":
/*!*******************************************!*\
  !*** ./resources/js/Shared/TextInput.vue ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TextInput_vue_vue_type_template_id_b56b971e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TextInput.vue?vue&type=template&id=b56b971e&scoped=true& */ "./resources/js/Shared/TextInput.vue?vue&type=template&id=b56b971e&scoped=true&");
/* harmony import */ var _TextInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TextInput.vue?vue&type=script&lang=js& */ "./resources/js/Shared/TextInput.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _TextInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _TextInput_vue_vue_type_template_id_b56b971e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _TextInput_vue_vue_type_template_id_b56b971e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "b56b971e",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Shared/TextInput.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentPhysDebts_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ContentPhysDebts.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentPhysDebts_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Shared/SelectInput.vue?vue&type=script&lang=js&":
/*!**********************************************************************!*\
  !*** ./resources/js/Shared/SelectInput.vue?vue&type=script&lang=js& ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SelectInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SelectInput.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SelectInput.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SelectInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Shared/TextInput.vue?vue&type=script&lang=js&":
/*!********************************************************************!*\
  !*** ./resources/js/Shared/TextInput.vue?vue&type=script&lang=js& ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TextInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./TextInput.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/TextInput.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TextInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=template&id=6dbee8c7&":
/*!***************************************************************************************!*\
  !*** ./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=template&id=6dbee8c7& ***!
  \***************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentPhysDebts_vue_vue_type_template_id_6dbee8c7___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentPhysDebts_vue_vue_type_template_id_6dbee8c7___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentPhysDebts_vue_vue_type_template_id_6dbee8c7___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ContentPhysDebts.vue?vue&type=template&id=6dbee8c7& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=template&id=6dbee8c7&");


/***/ }),

/***/ "./resources/js/Shared/SelectInput.vue?vue&type=template&id=de51c2fc&":
/*!****************************************************************************!*\
  !*** ./resources/js/Shared/SelectInput.vue?vue&type=template&id=de51c2fc& ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SelectInput_vue_vue_type_template_id_de51c2fc___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SelectInput_vue_vue_type_template_id_de51c2fc___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SelectInput_vue_vue_type_template_id_de51c2fc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SelectInput.vue?vue&type=template&id=de51c2fc& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SelectInput.vue?vue&type=template&id=de51c2fc&");


/***/ }),

/***/ "./resources/js/Shared/TextInput.vue?vue&type=template&id=b56b971e&scoped=true&":
/*!**************************************************************************************!*\
  !*** ./resources/js/Shared/TextInput.vue?vue&type=template&id=b56b971e&scoped=true& ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TextInput_vue_vue_type_template_id_b56b971e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TextInput_vue_vue_type_template_id_b56b971e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TextInput_vue_vue_type_template_id_b56b971e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./TextInput.vue?vue&type=template&id=b56b971e&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/TextInput.vue?vue&type=template&id=b56b971e&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=template&id=6dbee8c7&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentPhysDebts.vue?vue&type=template&id=6dbee8c7& ***!
  \******************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "w-full bg-white rounded-2xl h-auto  overflow-y-auto " },
    [
      _c("div", { staticClass: "flex flex-col h-full" }, [
        _c(
          "div",
          {
            staticClass:
              "w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ",
          },
          [
            _c(
              "div",
              { staticClass: "flex justify-start gap-5 items-center" },
              [
                _c("h3", { staticClass: "font-bold" }, [_vm._v("Долги")]),
                _vm._v(" "),
                _c(
                  "button",
                  {
                    staticClass:
                      "bg-blue-500 text-white font-bold py-2 px-4 rounded h-8",
                    on: {
                      click: function ($event) {
                        return _vm.$modal.show("pay-other-debt")
                      },
                    },
                  },
                  [_vm._v("\n                    + Оплата\n                ")]
                ),
              ]
            ),
            _vm._v(" "),
            _c(
              "table",
              { staticClass: "w-full whitespace-nowrap mt-5" },
              [
                _vm._m(0),
                _vm._v(" "),
                _vm._l(_vm.debts, function (debt) {
                  return _c(
                    "tr",
                    { staticClass: "text-left border-b border-gray-200" },
                    [
                      _c(
                        "td",
                        {
                          staticClass: "cursor-pointer",
                          on: {
                            click: function ($event) {
                              return _vm.onOtherDebtClicked(debt)
                            },
                          },
                        },
                        [
                          _vm._v(
                            "\n                        " +
                              _vm._s(debt.fio) +
                              "\n                    "
                          ),
                        ]
                      ),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(_vm.formatNum(debt.debt)))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(_vm.formatNum(debt.paid)))]),
                      _vm._v(" "),
                      _c("td", [
                        _vm._v(_vm._s(_vm.formatNum(debt.debt_for_today))),
                      ]),
                    ]
                  )
                }),
              ],
              2
            ),
          ]
        ),
      ]),
      _vm._v(" "),
      _c("modal", { attrs: { name: "pay-other-debt" } }, [
        _c("div", { staticClass: "p-5" }, [
          _c(
            "div",
            { staticClass: "flex items-center" },
            [
              _c("label", { staticClass: "form-label font-medium w-2/12" }, [
                _vm._v("ФИО:"),
              ]),
              _vm._v(" "),
              _c(
                "select-input",
                {
                  staticClass: "pr-6 pb-8 w-10/12 flex",
                  model: {
                    value: _vm.other_debt_id,
                    callback: function ($$v) {
                      _vm.other_debt_id = $$v
                    },
                    expression: "other_debt_id",
                  },
                },
                _vm._l(_vm.debts, function (debt) {
                  return _c(
                    "option",
                    { key: debt.id, domProps: { value: debt.id } },
                    [_vm._v(_vm._s(debt.fio))]
                  )
                }),
                0
              ),
            ],
            1
          ),
          _vm._v(" "),
          _c("div", { staticClass: "flex mb-3" }, [
            _c("label", { staticClass: "form-label font-medium w-2/12" }, [
              _vm._v("Остаток долга:"),
            ]),
            _vm._v(" "),
            _c("span", { staticClass: "w-10/12" }, [
              _vm._v(
                _vm._s(
                  _vm.debts.find(function (el) {
                    return el.id == _vm.other_debt_id
                  })
                    ? _vm.debts.find(function (el) {
                        return el.id == _vm.other_debt_id
                      }).debt_for_today
                    : ""
                )
              ),
            ]),
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "flex" },
            [
              _c("label", { staticClass: "form-label font-medium w-2/12" }, [
                _vm._v("Сумма:"),
              ]),
              _vm._v(" "),
              _c("text-input", {
                staticClass: "pr-6 pb-8 w-10/12",
                model: {
                  value: _vm.other_debt_amount,
                  callback: function ($$v) {
                    _vm.other_debt_amount = $$v
                  },
                  expression: "other_debt_amount",
                },
              }),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "button",
            {
              staticClass: "bg-blue-500 text-white font-bold py-2 px-4 rounded",
              on: {
                click: function ($event) {
                  return _vm.payOtherDebt()
                },
              },
            },
            [_vm._v("\n                Оплатить долг\n            ")]
          ),
        ]),
      ]),
      _vm._v(" "),
      _vm.selected_other_debt
        ? _c("modal", { attrs: { name: "other_debt_history" } }, [
            _c("div", { staticClass: "px-6 py-6" }, [
              _vm._v("\n            История долга\n\n            "),
              _c(
                "table",
                { staticClass: "w-full whitespace-nowrap mt-5" },
                [
                  _c(
                    "tr",
                    { staticClass: "text-left  border-b border-gray-200" },
                    [
                      _c("th", [_vm._v("ФИО")]),
                      _vm._v(" "),
                      _c("th", [_vm._v("Оплачено")]),
                      _vm._v(" "),
                      _c("th", [_vm._v("Дата")]),
                    ]
                  ),
                  _vm._v(" "),
                  _vm._l(_vm.selected_other_debt.payments, function (payment) {
                    return _c(
                      "tr",
                      {
                        key: payment.id,
                        staticClass: "text-left border-b border-gray-200",
                      },
                      [
                        _c("td", [_vm._v(_vm._s(_vm.selected_other_debt.fio))]),
                        _vm._v(" "),
                        _c("td", [
                          _vm._v(_vm._s(_vm.formatNum(payment.amount))),
                        ]),
                        _vm._v(" "),
                        _c("td", [
                          _vm._v(
                            _vm._s(
                              _vm
                                .moment(new Date(payment.created_at))
                                .format("YYYY-MM-DD HH:mm")
                            )
                          ),
                        ]),
                      ]
                    )
                  }),
                ],
                2
              ),
            ]),
          ])
        : _vm._e(),
    ],
    1
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", { staticClass: "text-left  border-b border-gray-200" }, [
      _c("th", [_vm._v("ФИО")]),
      _vm._v(" "),
      _c("th", [_vm._v("Начальный долг")]),
      _vm._v(" "),
      _c("th", [_vm._v("Оплачено")]),
      _vm._v(" "),
      _c("th", [_vm._v("Сумма долга на сегодня")]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SelectInput.vue?vue&type=template&id=de51c2fc&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/SelectInput.vue?vue&type=template&id=de51c2fc& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
  return _c("div", [
    _vm.label
      ? _c(
          "label",
          { staticClass: "form-label font-bold", attrs: { for: _vm.id } },
          [_vm._v(_vm._s(_vm.label) + ":")]
        )
      : _vm._e(),
    _vm._v(" "),
    _c(
      "select",
      _vm._b(
        {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.selected,
              expression: "selected",
            },
          ],
          ref: "input",
          staticClass: "form-select",
          class: { error: _vm.error },
          attrs: { id: _vm.id },
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
              _vm.selected = $event.target.multiple
                ? $$selectedVal
                : $$selectedVal[0]
            },
          },
        },
        "select",
        _vm.$attrs,
        false
      ),
      [_vm._t("default")],
      2
    ),
    _vm._v(" "),
    _vm.error
      ? _c("div", { staticClass: "form-error" }, [_vm._v(_vm._s(_vm.error))])
      : _vm._e(),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/TextInput.vue?vue&type=template&id=b56b971e&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/TextInput.vue?vue&type=template&id=b56b971e&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************/
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
    _vm.label
      ? _c("div", { class: { "w-3/12": _vm.col } }, [
          _vm.label
            ? _c(
                "label",
                {
                  staticClass: "form-label font-medium",
                  attrs: { for: _vm.id },
                },
                [_vm._v(_vm._s(_vm.label) + ":")]
              )
            : _vm._e(),
        ])
      : _vm._e(),
    _vm._v(" "),
    _c("div", { class: _vm.label ? "w-9/12" : "w-full" }, [
      _c(
        "input",
        _vm._b(
          {
            ref: "input",
            staticClass: "w-full block  pb-1 border-b-2 border-gray-200",
            class: { error: _vm.error },
            attrs: { id: _vm.id, onclick: "select()", type: _vm.type },
            domProps: { value: _vm.value },
            on: {
              keyup: function ($event) {
                if (
                  !$event.type.indexOf("key") &&
                  _vm._k($event.keyCode, "enter", 13, $event.key, "Enter")
                ) {
                  return null
                }
                return _vm.onEnter.apply(null, arguments)
              },
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