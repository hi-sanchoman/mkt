"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Profit_ContentSalary_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentSalary.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentSalary.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Shared_SelectInput__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Shared/SelectInput */ "./resources/js/Shared/SelectInput.vue");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Shared_TextInput__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/Shared/TextInput */ "./resources/js/Shared/TextInput.vue");
/* harmony import */ var _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/Shared/LoadingButton */ "./resources/js/Shared/LoadingButton.vue");
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
  name: 'ContentSalary',
  components: {
    SelectInput: _Shared_SelectInput__WEBPACK_IMPORTED_MODULE_0__["default"],
    LoadingButton: _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_3__["default"],
    TextInput: _Shared_TextInput__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: {
    workers: Array,
    days: Array,
    dbMonth: Object
  },
  data: function data() {
    return {
      salary_month: new Date().getMonth() + 1,
      salary_year: new Date().getFullYear(),
      myworkers: [],
      mysalary: [],
      salary: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      total_income: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      // date picker
      months: [{
        id: 1,
        name: "Январь"
      }, {
        id: 2,
        name: "Февраль"
      }, {
        id: 3,
        name: "Март"
      }, {
        id: 4,
        name: "Апрель"
      }, {
        id: 5,
        name: "Май"
      }, {
        id: 6,
        name: "Июнь"
      }, {
        id: 7,
        name: "Июль"
      }, {
        id: 8,
        name: "Август"
      }, {
        id: 9,
        name: "Сентябрь"
      }, {
        id: 10,
        name: "Октябрь"
      }, {
        id: 11,
        name: "Ноябрь"
      }, {
        id: 12,
        name: "Декабрь"
      }],
      // add worker
      form: this.$inertia.form({
        first_name: null,
        last_name: null,
        salary: null
      })
    };
  },
  created: function created() {
    this.getSalaryMonth(this.salary_month, this.salary_year);
  },
  methods: {
    addWorker: function addWorker() {
      var _this = this;

      // this.form.post(this.route('add-worker'))
      axios__WEBPACK_IMPORTED_MODULE_1___default().post('add-worker', {
        first_name: this.form.first_name,
        last_name: this.form.last_name,
        salary: this.form.salary
      }).then(function (res) {
        alert('Сотрудник добавлен!');

        _this.$modal.hide('addWorker');

        _this.getSalaryMonth(_this.salary_month, _this.salary_year);
      })["catch"](function (e) {
        return alert(e);
      });
    },
    getSalaryMonth: function getSalaryMonth(month, year) {
      var _this2 = this;

      axios__WEBPACK_IMPORTED_MODULE_1___default().post('get-salary-month', {
        month: month,
        year: year
      }).then(function (response) {
        _this2.mysalary = response.data.salary;
        var currentMonth = month - 1 == new Date().getMonth();
        var currentYear = year == new Date().getFullYear();
        _this2.myworkers = [];

        if (currentMonth && currentYear) {
          _this2.myworkers = response.data.workers;
        }
      });
    },
    saveSalary: function saveSalary() {
      var _this3 = this;

      if (!confirm("Сохранить зарплату?")) return;
      axios__WEBPACK_IMPORTED_MODULE_1___default().post('save-salary', {
        workers: this.myworkers,
        days: this.days,
        total_incomes: this.total_income
      }).then(function (response) {
        _this3.mysalary = response.data.zarplata;
        _this3.myworkers = response.data.workers;
        alert(response.data.message);
      });
    },
    endMonth: function endMonth() {
      if (this.dbMonth.month == new Date().getMonth() + 1) {
        alert('Месяц еще не закончен');
        return;
      }

      if (!confirm("Вы точно хотите завершить месяц?")) {
        return;
      }

      axios__WEBPACK_IMPORTED_MODULE_1___default().get('end-month').then(function (response) {
        alert(response.data);
        location.reload();
      });
    },
    getNalog: function getNalog(salary) {
      var osms = salary * 0.02;
      var opv = salary * 0.1;
      var ipn = (salary - 42882 - opv - osms) * 0.1;
      return osms + opv + ipn;
    },
    getPositiveEndSaldo: function getPositiveEndSaldo(sal) {
      var saldo = ((sal.worker.salary - this.getNalog(sal.worker.salary).toFixed(0)) / 26 * sal.days).toFixed(0) - sal.initial_saldo;
      if (saldo >= 0) return saldo;
      return 0;
    },
    getNegativeEndSaldo: function getNegativeEndSaldo(sal) {
      var saldo = ((sal.worker.salary - this.getNalog(sal.worker.salary).toFixed(0)) / 26 * sal.days).toFixed(0) - sal.initial_saldo;
      if (saldo < 0) return saldo;
      return 0;
    },
    countOklad: function countOklad() {
      var oklad = 0;
      this.mysalary.forEach(function (r, a) {
        oklad += parseInt(r.worker.salary);
      });
      return oklad;
    },
    countSaldo: function countSaldo() {
      var saldo = 0;
      this.mysalary.forEach(function (r, a) {
        saldo += parseInt(r.initial_saldo);
      });
      return saldo;
    },
    countEndSaldo: function countEndSaldo() {
      var oklad = 0;
      this.mysalary.forEach(function (r, a) {
        if (parseInt(r.total_income) - parseInt(r.initial_saldo) >= 0) oklad += parseInt(r.total_income) - parseInt(r.initial_saldo);
      });
      return oklad;
    },
    countNegativeSaldo: function countNegativeSaldo() {
      var oklad = 0;
      this.mysalary.forEach(function (r, a) {
        if (parseInt(r.end_saldo) < 0) oklad += parseInt(r.end_saldo);
      });
      return oklad;
    },
    // datepicker
    getMonthName: function getMonthName(month) {
      return this.months.find(function (m) {
        return m.id == month;
      }).name;
    },
    nextMonth: function nextMonth() {
      this.salary_month += 1;

      if (this.salary_month > 12) {
        this.salary_month = 1;
        this.salary_year += 1;
      }

      this.getSalaryMonth(this.salary_month, this.salary_year);
    },
    prevMonth: function prevMonth() {
      this.salary_month -= 1;

      if (this.salary_month <= 0) {
        this.salary_month = 12;
        this.salary_year -= 1;
      }

      this.getSalaryMonth(this.salary_month, this.salary_year);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    loading: Boolean
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

/***/ "./resources/js/Pages/Profit/ContentSalary.vue":
/*!*****************************************************!*\
  !*** ./resources/js/Pages/Profit/ContentSalary.vue ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ContentSalary_vue_vue_type_template_id_3d131021___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ContentSalary.vue?vue&type=template&id=3d131021& */ "./resources/js/Pages/Profit/ContentSalary.vue?vue&type=template&id=3d131021&");
/* harmony import */ var _ContentSalary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ContentSalary.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Profit/ContentSalary.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ContentSalary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ContentSalary_vue_vue_type_template_id_3d131021___WEBPACK_IMPORTED_MODULE_0__.render,
  _ContentSalary_vue_vue_type_template_id_3d131021___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Profit/ContentSalary.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Shared/LoadingButton.vue":
/*!***********************************************!*\
  !*** ./resources/js/Shared/LoadingButton.vue ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=template&id=5f30d6e2& */ "./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2&");
/* harmony import */ var _LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=script&lang=js& */ "./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__.render,
  _LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Shared/LoadingButton.vue"
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

/***/ "./resources/js/Pages/Profit/ContentSalary.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/Pages/Profit/ContentSalary.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentSalary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ContentSalary.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentSalary.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentSalary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoadingButton.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

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

/***/ "./resources/js/Pages/Profit/ContentSalary.vue?vue&type=template&id=3d131021&":
/*!************************************************************************************!*\
  !*** ./resources/js/Pages/Profit/ContentSalary.vue?vue&type=template&id=3d131021& ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentSalary_vue_vue_type_template_id_3d131021___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentSalary_vue_vue_type_template_id_3d131021___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ContentSalary_vue_vue_type_template_id_3d131021___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ContentSalary.vue?vue&type=template&id=3d131021& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentSalary.vue?vue&type=template&id=3d131021&");


/***/ }),

/***/ "./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2&":
/*!******************************************************************************!*\
  !*** ./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2& ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LoadingButton.vue?vue&type=template&id=5f30d6e2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2&");


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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentSalary.vue?vue&type=template&id=3d131021&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Profit/ContentSalary.vue?vue&type=template&id=3d131021& ***!
  \***************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto" },
    [
      _c("div", { staticClass: "flex justify-start gap-5 mb-4" }, [
        _c("div", { staticClass: "mb-6 md:mb-0 flex justify-start" }, [
          _c("div", { staticClass: "relative mr-4" }, [
            _c(
              "div",
              {
                staticClass:
                  "flex space-x-2 items-center justify-center border p-2",
              },
              [
                _c(
                  "button",
                  {
                    staticClass: "hover:bg-white hover:text-black",
                    on: { click: _vm.prevMonth },
                  },
                  [
                    _c(
                      "svg",
                      {
                        staticClass: "w-6 h-6",
                        attrs: {
                          xmlns: "http://www.w3.org/2000/svg",
                          fill: "none",
                          viewBox: "0 0 24 24",
                          "stroke-width": "1.5",
                          stroke: "currentColor",
                        },
                      },
                      [
                        _c("path", {
                          attrs: {
                            "stroke-linecap": "round",
                            "stroke-linejoin": "round",
                            d: "M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18",
                          },
                        }),
                      ]
                    ),
                  ]
                ),
                _vm._v(" "),
                _c("span", { staticClass: "w-40 text-center" }, [
                  _vm._v(
                    _vm._s(_vm.getMonthName(_vm.salary_month)) +
                      " " +
                      _vm._s(_vm.salary_year)
                  ),
                ]),
                _vm._v(" "),
                _c(
                  "button",
                  {
                    staticClass: "hover:bg-white hover:text-black",
                    on: { click: _vm.nextMonth },
                  },
                  [
                    _c(
                      "svg",
                      {
                        staticClass: "w-6 h-6",
                        attrs: {
                          xmlns: "http://www.w3.org/2000/svg",
                          fill: "none",
                          viewBox: "0 0 24 24",
                          "stroke-width": "1.5",
                          stroke: "currentColor",
                        },
                      },
                      [
                        _c("path", {
                          attrs: {
                            "stroke-linecap": "round",
                            "stroke-linejoin": "round",
                            d: "M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3",
                          },
                        }),
                      ]
                    ),
                  ]
                ),
              ]
            ),
          ]),
        ]),
        _vm._v(" "),
        _c(
          "button",
          {
            staticClass:
              "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4",
            on: {
              click: function ($event) {
                return _vm.$modal.show("addWorker")
              },
            },
          },
          [_vm._v("\n            Добавить сотрудника\n        ")]
        ),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "overflow-y-auto h-80" }, [
        _c(
          "table",
          { staticClass: "w-full whitespace-nowrap  tableizer-table mytable" },
          [
            _vm._m(0),
            _vm._v(" "),
            _vm._l(_vm.mysalary, function (sal) {
              return _c("tr", { staticClass: "pt-2" }, [
                _c("td", [
                  _vm._v(
                    _vm._s(sal.worker.name) + " " + _vm._s(sal.worker.surname)
                  ),
                ]),
                _vm._v(" "),
                _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: sal.worker.salary,
                        expression: "sal.worker.salary",
                      },
                    ],
                    attrs: { type: "number", name: "", disabled: "" },
                    domProps: { value: sal.worker.salary },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(sal.worker, "salary", $event.target.value)
                      },
                    },
                  }),
                ]),
                _vm._v(" "),
                _c("td", [
                  _vm._v(_vm._s(_vm.getNalog(sal.worker.salary).toFixed(0))),
                ]),
                _vm._v(" "),
                _c("td", [
                  _vm._v(
                    _vm._s(
                      sal.worker.salary -
                        _vm.getNalog(sal.worker.salary).toFixed(0)
                    )
                  ),
                ]),
                _vm._v(" "),
                _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: sal.days,
                        expression: "sal.days",
                      },
                    ],
                    attrs: { type: "number", name: "", disabled: "" },
                    domProps: { value: sal.days },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(sal, "days", $event.target.value)
                      },
                    },
                  }),
                ]),
                _vm._v(" "),
                _c("td", [
                  _vm._v(
                    "\n                    " +
                      _vm._s(
                        (
                          ((sal.worker.salary -
                            _vm.getNalog(sal.worker.salary).toFixed(0)) /
                            26) *
                          sal.days
                        ).toFixed(0)
                      ) +
                      "\n                "
                  ),
                ]),
                _vm._v(" "),
                _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: sal.initial_saldo,
                        expression: "sal.initial_saldo",
                      },
                    ],
                    attrs: { type: "number", name: "", disabled: "" },
                    domProps: { value: sal.initial_saldo },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(sal, "initial_saldo", $event.target.value)
                      },
                    },
                  }),
                ]),
                _vm._v(" "),
                _c("td", [_vm._v(_vm._s(_vm.getPositiveEndSaldo(sal)))]),
                _vm._v(" "),
                _c("td", [_vm._v(_vm._s(_vm.getNegativeEndSaldo(sal)))]),
              ])
            }),
            _vm._v(" "),
            _c(
              "tr",
              { staticClass: "text-left pt-5 border-b border-gray-200" },
              [
                _c("th", [_vm._v("Итог")]),
                _vm._v(" "),
                _c("th", [_vm._v(_vm._s(_vm.countOklad()))]),
                _vm._v(" "),
                _c("th", [
                  _vm._v(
                    _vm._s(
                      _vm.mysalary.reduce(function (acc, sal) {
                        return (
                          acc +
                          parseInt(_vm.getNalog(sal.worker.salary).toFixed(0))
                        )
                      }, 0)
                    )
                  ),
                ]),
                _vm._v(" "),
                _c("th", [
                  _vm._v(
                    _vm._s(
                      _vm.mysalary.reduce(function (acc, sal) {
                        return (
                          acc +
                          sal.worker.salary -
                          parseInt(_vm.getNalog(sal.worker.salary).toFixed(0))
                        )
                      }, 0)
                    )
                  ),
                ]),
                _vm._v(" "),
                _c("th"),
                _vm._v(" "),
                _c("th", [
                  _vm._v(
                    "\n                    " +
                      _vm._s(
                        _vm.mysalary.reduce(function (acc, sal) {
                          return (
                            acc +
                            parseInt(
                              (
                                ((sal.worker.salary -
                                  _vm.getNalog(sal.worker.salary).toFixed(0)) /
                                  26) *
                                sal.days
                              ).toFixed(0)
                            )
                          )
                        }, 0)
                      ) +
                      "\n                "
                  ),
                ]),
                _vm._v(" "),
                _c("th", [_vm._v(_vm._s(_vm.countSaldo()))]),
                _vm._v(" "),
                _c("th", [_vm._v(_vm._s(_vm.countEndSaldo()))]),
                _vm._v(" "),
                _c("th", [_vm._v(_vm._s(_vm.countNegativeSaldo()))]),
              ]
            ),
            _vm._v(" "),
            _vm._l(_vm.myworkers, function (worker, key) {
              return _c("tr", { staticClass: "pt-2" }, [
                _c("td", [
                  _vm._v(_vm._s(worker.name) + " " + _vm._s(worker.surname)),
                ]),
                _vm._v(" "),
                _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: worker.salary,
                        expression: "worker.salary",
                      },
                    ],
                    attrs: { type: "number", name: "" },
                    domProps: { value: worker.salary },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(worker, "salary", $event.target.value)
                      },
                    },
                  }),
                ]),
                _vm._v(" "),
                _c("td", [
                  _vm._v(_vm._s(_vm.getNalog(worker.salary).toFixed(0))),
                ]),
                _vm._v(" "),
                _c("td", [
                  _vm._v(
                    _vm._s(
                      worker.salary.toFixed(0) -
                        _vm.getNalog(worker.salary).toFixed(0)
                    )
                  ),
                ]),
                _vm._v(" "),
                _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.days[key],
                        expression: "days[key]",
                      },
                    ],
                    attrs: { type: "number", name: "" },
                    domProps: { value: _vm.days[key] },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.days, key, $event.target.value)
                      },
                    },
                  }),
                ]),
                _vm._v(" "),
                _c("td", [
                  _vm._v(
                    "\n                    " +
                      _vm._s(
                        (_vm.total_income[key] = (
                          ((worker.salary.toFixed(0) -
                            _vm.getNalog(worker.salary).toFixed(0)) /
                            26) *
                          _vm.days[key]
                        ).toFixed(0))
                      ) +
                      "\n                "
                  ),
                ]),
                _vm._v(" "),
                _c("td", [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: worker.saldo,
                        expression: "worker.saldo",
                      },
                    ],
                    attrs: { type: "number", name: "", disabled: "" },
                    domProps: { value: worker.saldo },
                    on: {
                      input: function ($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(worker, "saldo", $event.target.value)
                      },
                    },
                  }),
                ]),
                _vm._v(" "),
                _c("td", { attrs: { colspan: "2" } }, [
                  _vm.days[key]
                    ? _c("span", [
                        _vm._v(
                          "\n                        " +
                            _vm._s(
                              (
                                ((worker.salary.toFixed(0) -
                                  _vm.getNalog(worker.salary).toFixed(0)) /
                                  26) *
                                  _vm.days[key] -
                                worker.saldo
                              ).toFixed(0)
                            ) +
                            "\n                    "
                        ),
                      ])
                    : _vm._e(),
                ]),
              ])
            }),
          ],
          2
        ),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "mt-10 w-32 flex justify-start gap-5" }, [
        _c(
          "button",
          {
            staticClass:
              "bg-blue-500 text-white font-bold py-2 px-4 rounded text-center",
            on: {
              click: function ($event) {
                return _vm.saveSalary()
              },
            },
          },
          [_vm._v("\n            Сохранить\n        ")]
        ),
        _vm._v(" "),
        _c(
          "button",
          {
            staticClass:
              "bg-blue-500 text-white font-bold py-2 px-4 rounded text-center",
            on: {
              click: function ($event) {
                return _vm.endMonth()
              },
            },
          },
          [_vm._v("\n            Завершить месяц\n        ")]
        ),
      ]),
      _vm._v(" "),
      _c("modal", { attrs: { name: "addWorker" } }, [
        _c(
          "form",
          {
            on: {
              submit: function ($event) {
                $event.preventDefault()
                return _vm.addWorker.apply(null, arguments)
              },
            },
          },
          [
            _c(
              "div",
              { staticClass: "p-8 -mr-6 -mb-8 flex flex-wrap" },
              [
                _c("text-input", {
                  staticClass: "pr-6 pb-8 w-full lg:w-1/2",
                  attrs: { error: _vm.form.errors.first_name, label: "Имя" },
                  model: {
                    value: _vm.form.first_name,
                    callback: function ($$v) {
                      _vm.$set(_vm.form, "first_name", $$v)
                    },
                    expression: "form.first_name",
                  },
                }),
                _vm._v(" "),
                _c("text-input", {
                  staticClass: "pr-6 pb-8 w-full lg:w-1/2",
                  attrs: { error: _vm.form.errors.last_name, label: "Фамилия" },
                  model: {
                    value: _vm.form.last_name,
                    callback: function ($$v) {
                      _vm.$set(_vm.form, "last_name", $$v)
                    },
                    expression: "form.last_name",
                  },
                }),
                _vm._v(" "),
                _c("text-input", {
                  staticClass: "pr-6 pb-8 w-full lg:w-1/2",
                  attrs: { error: _vm.form.errors.salary, label: "Оклад" },
                  model: {
                    value: _vm.form.salary,
                    callback: function ($$v) {
                      _vm.$set(_vm.form, "salary", $$v)
                    },
                    expression: "form.salary",
                  },
                }),
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass:
                  "px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center",
              },
              [
                _c(
                  "loading-button",
                  {
                    staticClass: "btn-indigo",
                    attrs: { loading: _vm.form.processing, type: "submit" },
                  },
                  [_vm._v("Добавить сотрудника")]
                ),
              ],
              1
            ),
          ]
        ),
      ]),
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
      _c("th", { staticClass: "sticky top-0" }, [_vm._v("Сотрудник")]),
      _vm._v(" "),
      _c("th", { staticClass: "sticky top-0" }, [_vm._v("Оклад")]),
      _vm._v(" "),
      _c("th", { staticClass: "sticky top-0" }, [_vm._v("Налог")]),
      _vm._v(" "),
      _c("th", { staticClass: "sticky top-0" }, [_vm._v("На руки")]),
      _vm._v(" "),
      _c("th", { staticClass: "sticky top-0" }, [_vm._v("Дни")]),
      _vm._v(" "),
      _c("th", { staticClass: "sticky top-0" }, [_vm._v("К оплате")]),
      _vm._v(" "),
      _c("th", { staticClass: "sticky top-0" }, [_vm._v("Начальное сальдо")]),
      _vm._v(" "),
      _c("th", { staticClass: "sticky top-0", attrs: { colspan: "2" } }, [
        _vm._v("Конечное сальдо"),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2& ***!
  \*********************************************************************************************************************************************************************************************************************/
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
    "button",
    { staticClass: "flex items-center", attrs: { disabled: _vm.loading } },
    [
      _vm.loading ? _c("div", { staticClass: "btn-spinner mr-2" }) : _vm._e(),
      _vm._v(" "),
      _vm._t("default"),
    ],
    2
  )
}
var staticRenderFns = []
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