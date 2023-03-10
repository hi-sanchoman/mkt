"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4494],{24494:(t,e,r)=>{r.r(e),r.d(e,{default:()=>c});var n=r(66186),o=r(45168),s=r(9669),a=r.n(s),l=r(30381),i=r.n(l);const u={name:"ContentPhysDebts",components:{SelectInput:n.Z,TextInput:o.Z},props:{other_debts:Array},data:function(){return{other_debt_id:0,other_debt_amount:0,selected_other_debt:null,moment:i()}},created:function(){},watch:{},computed:{},methods:{formatNum:function(t,e){return t.toString().replace(/\B(?=(\d{3})+(?!\d))/g," ")},onOtherDebtClicked:function(t){this.selected_other_debt=t,this.$modal.show("other_debt_history")},payOtherDebt:function(){var t=this;a().post("pay-other-debt",{id:this.other_debt_id,amount:this.other_debt_amount}).then((function(e){t.$modal.hide("pay-other-debt"),alert("долг оплачен!"),location.reload()}))}}};const c=(0,r(51900).Z)(u,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"w-full bg-white rounded-2xl h-auto  overflow-y-auto "},[r("div",{staticClass:"flex flex-col h-full"},[r("div",{staticClass:"w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto "},[r("div",{staticClass:"flex justify-start gap-5 items-center"},[r("h3",{staticClass:"font-bold"},[t._v("Долги")]),t._v(" "),r("button",{staticClass:"bg-blue-500 text-white font-bold py-2 px-4 rounded h-8",on:{click:function(e){return t.$modal.show("pay-other-debt")}}},[t._v("\r\n                    + Оплата\r\n                ")])]),t._v(" "),r("table",{staticClass:"w-full whitespace-nowrap mt-5"},[t._m(0),t._v(" "),t._l(t.other_debts,(function(e){return r("tr",{staticClass:"text-left border-b border-gray-200"},[r("td",{staticClass:"cursor-pointer",on:{click:function(r){return t.onOtherDebtClicked(e)}}},[t._v("\r\n                        "+t._s(e.fio)+"\r\n                    ")]),t._v(" "),r("td",[t._v(t._s(t.formatNum(e.debt)))]),t._v(" "),r("td",[t._v(t._s(t.formatNum(e.payments.reduce((function(t,e){return t+e.amount}),0))))]),t._v(" "),r("td",[t._v(t._s(t.formatNum(e.debt-e.payments.reduce((function(t,e){return t+e.amount}),0))))])])}))],2)])]),t._v(" "),r("modal",{attrs:{name:"pay-other-debt"}},[r("div",{staticClass:"p-5"},[r("select-input",{staticClass:"pr-6 pb-8 w-full lg:w-1/1",attrs:{label:"ФИО"},model:{value:t.other_debt_id,callback:function(e){t.other_debt_id=e},expression:"other_debt_id"}},t._l(t.other_debts,(function(e){return r("option",{key:e.id,domProps:{value:e.id}},[t._v(t._s(e.fio))])})),0),t._v(" "),r("text-input",{staticClass:"pr-6 pb-8 w-full lg:w-1/1",attrs:{label:"Сумма"},model:{value:t.other_debt_amount,callback:function(e){t.other_debt_amount=e},expression:"other_debt_amount"}}),t._v(" "),r("button",{staticClass:"bg-blue-500 text-white font-bold py-2 px-4 rounded",on:{click:function(e){return t.payOtherDebt()}}},[t._v("\r\n                Оплатить долг\r\n            ")])],1)]),t._v(" "),t.selected_other_debt?r("modal",{attrs:{name:"other_debt_history"}},[r("div",{staticClass:"px-6 py-6"},[t._v("\r\n            История долга\r\n\r\n            "),r("table",{staticClass:"w-full whitespace-nowrap mt-5"},[r("tr",{staticClass:"text-left  border-b border-gray-200"},[r("th",[t._v("ФИО")]),t._v(" "),r("th",[t._v("Оплачено")]),t._v(" "),r("th",[t._v("Дата")])]),t._v(" "),t._l(t.selected_other_debt.payments,(function(e){return r("tr",{key:e.id,staticClass:"text-left border-b border-gray-200"},[r("td",[t._v(t._s(t.selected_other_debt.fio))]),t._v(" "),r("td",[t._v(t._s(t.formatNum(e.amount)))]),t._v(" "),r("td",[t._v(t._s(t.moment(new Date(e.created_at)).format("YYYY-MM-DD HH:mm")))])])}))],2)])]):t._e()],1)}),[function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("tr",{staticClass:"text-left  border-b border-gray-200"},[r("th",[t._v("ФИО")]),t._v(" "),r("th",[t._v("Начальный долг")]),t._v(" "),r("th",[t._v("Оплачено")]),t._v(" "),r("th",[t._v("Сумма долга на сегодня")])])}],!1,null,null,null).exports},66186:(t,e,r)=>{r.d(e,{Z:()=>o});const n={inheritAttrs:!1,props:{id:{type:String,default:function(){return"select-input-".concat(this._uid)}},value:[String,Number,Boolean],label:String,error:String},data:function(){return{selected:this.value}},watch:{selected:function(t){this.$emit("input",t)}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()}}};const o=(0,r(51900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[t.label?r("label",{staticClass:"form-label",attrs:{for:t.id}},[t._v(t._s(t.label)+":")]):t._e(),t._v(" "),r("select",t._b({directives:[{name:"model",rawName:"v-model",value:t.selected,expression:"selected"}],ref:"input",staticClass:"form-select",class:{error:t.error},attrs:{id:t.id},on:{change:function(e){var r=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.selected=e.target.multiple?r:r[0]}}},"select",t.$attrs,!1),[t._t("default")],2),t._v(" "),t.error?r("div",{staticClass:"form-error"},[t._v(t._s(t.error))]):t._e()])}),[],!1,null,null,null).exports},45168:(t,e,r)=>{r.d(e,{Z:()=>a});function n(t){return function(t){if(Array.isArray(t))return o(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||function(t,e){if(!t)return;if("string"==typeof t)return o(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);"Object"===r&&t.constructor&&(r=t.constructor.name);if("Map"===r||"Set"===r)return Array.from(t);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return o(t,e)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function o(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}const s={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},col:{type:Boolean,default:!0},value:String,label:String,error:String},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)},onEnter:function(t){console.log("on enter...",t);var e=event.target.form,r=n(e).indexOf(event.target)+1;e.elements[r].select(),e.elements[r].focus(),event.preventDefault()}}};const a=(0,r(51900).Z)(s,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"flex",class:{"flex-col":!t.col}},[r("div",{class:{"w-3/12":t.col}},[t.label?r("label",{staticClass:"form-label font-medium",attrs:{for:t.id}},[t._v(t._s(t.label)+":")]):t._e()]),t._v(" "),r("div",{class:{"w-9/12":t.col}},[r("input",t._b({ref:"input",staticClass:"w-full block  pb-1 border-b-2 border-gray-200",class:{error:t.error},attrs:{id:t.id,onclick:"select()",type:t.type},domProps:{value:t.value},on:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.onEnter.apply(null,arguments)},input:function(e){return t.$emit("input",e.target.value)}}},"input",t.$attrs,!1)),t._v(" "),r("div",{staticClass:"w-full mt-1"},[t.error?r("div",{staticClass:"form-error"},[t._v(t._s(t.error))]):t._e()])])])}),[],!1,null,"6ecb489a",null).exports},51900:(t,e,r)=>{function n(t,e,r,n,o,s,a,l){var i,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=r,u._compiled=!0),n&&(u.functional=!0),s&&(u._scopeId="data-v-"+s),a?(i=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},u._ssrRegister=i):o&&(i=l?function(){o.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:o),i)if(u.functional){u._injectStyles=i;var c=u.render;u.render=function(t,e){return i.call(e),c(t,e)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,i):[i]}return{exports:t,options:u}}r.d(e,{Z:()=>n})}}]);
//# sourceMappingURL=4494.js.map?id=bc5d91912cc41373