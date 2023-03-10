"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6313],{86313:(t,e,a)=>{a.r(e),a.d(e,{default:()=>d});var r=a(66186),n=a(9669),s=a.n(n),o=a(45168),l=a(10103);const i={name:"ContentSalary",components:{SelectInput:r.Z,LoadingButton:l.Z,TextInput:o.Z},props:{workers:Array,days:Array,dbMonth:Object},data:function(){return{salary_month:(new Date).getMonth()+1,salary_year:(new Date).getFullYear(),myworkers:[],mysalary:[],salary:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],total_income:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],months:[{id:1,name:"Январь"},{id:2,name:"Февраль"},{id:3,name:"Март"},{id:4,name:"Апрель"},{id:5,name:"Май"},{id:6,name:"Июнь"},{id:7,name:"Июль"},{id:8,name:"Август"},{id:9,name:"Сентябрь"},{id:10,name:"Октябрь"},{id:11,name:"Ноябрь"},{id:12,name:"Декабрь"}],form:this.$inertia.form({first_name:null,last_name:null,salary:null})}},created:function(){this.getSalaryMonth(this.salary_month,this.salary_year)},methods:{addWorker:function(){var t=this;s().post("add-worker",{first_name:this.form.first_name,last_name:this.form.last_name,salary:this.form.salary}).then((function(e){alert("Сотрудник добавлен!"),t.$modal.hide("addWorker"),t.getSalaryMonth(t.salary_month,t.salary_year)})).catch((function(t){return alert(t)}))},getSalaryMonth:function(t,e){var a=this;s().post("get-salary-month",{month:t,year:e}).then((function(r){a.mysalary=r.data.salary;var n=t-1==(new Date).getMonth(),s=e==(new Date).getFullYear();a.myworkers=[],n&&s&&(a.myworkers=r.data.workers)}))},saveSalary:function(){var t=this;confirm("Сохранить зарплату?")&&s().post("save-salary",{workers:this.myworkers,days:this.days,total_incomes:this.total_income}).then((function(e){t.mysalary=e.data.zarplata,t.myworkers=e.data.workers,alert(e.data.message)}))},endMonth:function(){this.dbMonth.month!=(new Date).getMonth()+1?confirm("Вы точно хотите завершить месяц?")&&s().get("end-month").then((function(t){alert(t.data),location.reload()})):alert("Месяц еще не закончен")},getNalog:function(t){var e=.02*t,a=.1*t;return e+a+.1*(t-42882-a-e)},getPositiveEndSaldo:function(t){var e=((t.worker.salary-this.getNalog(t.worker.salary).toFixed(0))/26*t.days).toFixed(0)-t.initial_saldo;return e>=0?e:0},getNegativeEndSaldo:function(t){var e=((t.worker.salary-this.getNalog(t.worker.salary).toFixed(0))/26*t.days).toFixed(0)-t.initial_saldo;return e<0?e:0},countOklad:function(){var t=0;return this.mysalary.forEach((function(e,a){t+=parseInt(e.worker.salary)})),t},countSaldo:function(){var t=0;return this.mysalary.forEach((function(e,a){t+=parseInt(e.initial_saldo)})),t},countEndSaldo:function(){var t=0;return this.mysalary.forEach((function(e,a){parseInt(e.total_income)-parseInt(e.initial_saldo)>=0&&(t+=parseInt(e.total_income)-parseInt(e.initial_saldo))})),t},countNegativeSaldo:function(){var t=0;return this.mysalary.forEach((function(e,a){parseInt(e.end_saldo)<0&&(t+=parseInt(e.end_saldo))})),t},getMonthName:function(t){return this.months.find((function(e){return e.id==t})).name},nextMonth:function(){this.salary_month+=1,this.salary_month>12&&(this.salary_month=1,this.salary_year+=1),this.getSalaryMonth(this.salary_month,this.salary_year)},prevMonth:function(){this.salary_month-=1,this.salary_month<=0&&(this.salary_month=12,this.salary_year-=1),this.getSalaryMonth(this.salary_month,this.salary_year)}}};const d=(0,a(51900).Z)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto"},[a("div",{staticClass:"flex justify-start gap-5 mb-4"},[a("div",{staticClass:"mb-6 md:mb-0 flex justify-start"},[a("div",{staticClass:"relative mr-4"},[a("div",{staticClass:"flex space-x-2 items-center justify-center border p-2"},[a("button",{staticClass:"hover:bg-white hover:text-black",on:{click:t.prevMonth}},[a("svg",{staticClass:"w-6 h-6",attrs:{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"}},[a("path",{attrs:{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18"}})])]),t._v(" "),a("span",{staticClass:"w-40 text-center"},[t._v(t._s(t.getMonthName(t.salary_month))+" "+t._s(t.salary_year))]),t._v(" "),a("button",{staticClass:"hover:bg-white hover:text-black",on:{click:t.nextMonth}},[a("svg",{staticClass:"w-6 h-6",attrs:{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"}},[a("path",{attrs:{"stroke-linecap":"round","stroke-linejoin":"round",d:"M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"}})])])])])]),t._v(" "),a("button",{staticClass:"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4",on:{click:function(e){return t.$modal.show("addWorker")}}},[t._v("\r\n            Добавить сотрудника\r\n        ")])]),t._v(" "),a("div",{staticClass:"overflow-y-auto h-80"},[a("table",{staticClass:"w-full whitespace-nowrap  tableizer-table mytable"},[t._m(0),t._v(" "),t._l(t.mysalary,(function(e){return a("tr",{staticClass:"pt-2"},[a("td",[t._v(t._s(e.worker.name)+" "+t._s(e.worker.surname))]),t._v(" "),a("td",[a("input",{directives:[{name:"model",rawName:"v-model",value:e.worker.salary,expression:"sal.worker.salary"}],attrs:{type:"number",name:"",disabled:""},domProps:{value:e.worker.salary},on:{input:function(a){a.target.composing||t.$set(e.worker,"salary",a.target.value)}}})]),t._v(" "),a("td",[t._v(t._s(t.getNalog(e.worker.salary).toFixed(0)))]),t._v(" "),a("td",[t._v(t._s(e.worker.salary-t.getNalog(e.worker.salary).toFixed(0)))]),t._v(" "),a("td",[a("input",{directives:[{name:"model",rawName:"v-model",value:e.days,expression:"sal.days"}],attrs:{type:"number",name:"",disabled:""},domProps:{value:e.days},on:{input:function(a){a.target.composing||t.$set(e,"days",a.target.value)}}})]),t._v(" "),a("td",[t._v("\r\n                    "+t._s(((e.worker.salary-t.getNalog(e.worker.salary).toFixed(0))/26*e.days).toFixed(0))+"\r\n                ")]),t._v(" "),a("td",[a("input",{directives:[{name:"model",rawName:"v-model",value:e.initial_saldo,expression:"sal.initial_saldo"}],attrs:{type:"number",name:"",disabled:""},domProps:{value:e.initial_saldo},on:{input:function(a){a.target.composing||t.$set(e,"initial_saldo",a.target.value)}}})]),t._v(" "),a("td",[t._v(t._s(t.getPositiveEndSaldo(e)))]),t._v(" "),a("td",[t._v(t._s(t.getNegativeEndSaldo(e)))])])})),t._v(" "),a("tr",{staticClass:"text-left pt-5 border-b border-gray-200"},[a("th",[t._v("Итог")]),t._v(" "),a("th",[t._v(t._s(t.countOklad()))]),t._v(" "),a("th",[t._v(t._s(t.mysalary.reduce((function(e,a){return e+parseInt(t.getNalog(a.worker.salary).toFixed(0))}),0)))]),t._v(" "),a("th",[t._v(t._s(t.mysalary.reduce((function(e,a){return e+a.worker.salary-parseInt(t.getNalog(a.worker.salary).toFixed(0))}),0)))]),t._v(" "),a("th"),t._v(" "),a("th",[t._v("\r\n                    "+t._s(t.mysalary.reduce((function(e,a){return e+parseInt(((a.worker.salary-t.getNalog(a.worker.salary).toFixed(0))/26*a.days).toFixed(0))}),0))+"\r\n                ")]),t._v(" "),a("th",[t._v(t._s(t.countSaldo()))]),t._v(" "),a("th",[t._v(t._s(t.countEndSaldo()))]),t._v(" "),a("th",[t._v(t._s(t.countNegativeSaldo()))])]),t._v(" "),t._l(t.myworkers,(function(e,r){return a("tr",{staticClass:"pt-2"},[a("td",[t._v(t._s(e.name)+" "+t._s(e.surname))]),t._v(" "),a("td",[a("input",{directives:[{name:"model",rawName:"v-model",value:e.salary,expression:"worker.salary"}],attrs:{type:"number",name:""},domProps:{value:e.salary},on:{input:function(a){a.target.composing||t.$set(e,"salary",a.target.value)}}})]),t._v(" "),a("td",[t._v(t._s(t.getNalog(e.salary).toFixed(0)))]),t._v(" "),a("td",[t._v(t._s(e.salary.toFixed(0)-t.getNalog(e.salary).toFixed(0)))]),t._v(" "),a("td",[a("input",{directives:[{name:"model",rawName:"v-model",value:t.days[r],expression:"days[key]"}],attrs:{type:"number",name:""},domProps:{value:t.days[r]},on:{input:function(e){e.target.composing||t.$set(t.days,r,e.target.value)}}})]),t._v(" "),a("td",[t._v("\r\n                    "+t._s(t.total_income[r]=((e.salary.toFixed(0)-t.getNalog(e.salary).toFixed(0))/26*t.days[r]).toFixed(0))+"\r\n                ")]),t._v(" "),a("td",[a("input",{directives:[{name:"model",rawName:"v-model",value:e.saldo,expression:"worker.saldo"}],attrs:{type:"number",name:"",disabled:""},domProps:{value:e.saldo},on:{input:function(a){a.target.composing||t.$set(e,"saldo",a.target.value)}}})]),t._v(" "),a("td",{attrs:{colspan:"2"}},[t.days[r]?a("span",[t._v("\r\n                        "+t._s(((e.salary.toFixed(0)-t.getNalog(e.salary).toFixed(0))/26*t.days[r]-e.saldo).toFixed(0))+"\r\n                    ")]):t._e()])])}))],2)]),t._v(" "),a("div",{staticClass:"mt-10 w-32 flex justify-start gap-5"},[a("button",{staticClass:"bg-blue-500 text-white font-bold py-2 px-4 rounded text-center",on:{click:function(e){return t.saveSalary()}}},[t._v("\r\n            Сохранить\r\n        ")]),t._v(" "),a("button",{staticClass:"bg-blue-500 text-white font-bold py-2 px-4 rounded text-center",on:{click:function(e){return t.endMonth()}}},[t._v("\r\n            Завершить месяц\r\n        ")])]),t._v(" "),a("modal",{attrs:{name:"addWorker"}},[a("form",{on:{submit:function(e){return e.preventDefault(),t.addWorker.apply(null,arguments)}}},[a("div",{staticClass:"p-8 -mr-6 -mb-8 flex flex-wrap"},[a("text-input",{staticClass:"pr-6 pb-8 w-full lg:w-1/2",attrs:{error:t.form.errors.first_name,label:"Имя"},model:{value:t.form.first_name,callback:function(e){t.$set(t.form,"first_name",e)},expression:"form.first_name"}}),t._v(" "),a("text-input",{staticClass:"pr-6 pb-8 w-full lg:w-1/2",attrs:{error:t.form.errors.last_name,label:"Фамилия"},model:{value:t.form.last_name,callback:function(e){t.$set(t.form,"last_name",e)},expression:"form.last_name"}}),t._v(" "),a("text-input",{staticClass:"pr-6 pb-8 w-full lg:w-1/2",attrs:{error:t.form.errors.salary,label:"Оклад"},model:{value:t.form.salary,callback:function(e){t.$set(t.form,"salary",e)},expression:"form.salary"}})],1),t._v(" "),a("div",{staticClass:"px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center"},[a("loading-button",{staticClass:"btn-indigo",attrs:{loading:t.form.processing,type:"submit"}},[t._v("Добавить сотрудника")])],1)])])],1)}),[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("tr",{staticClass:"text-left  border-b border-gray-200"},[a("th",{staticClass:"sticky top-0"},[t._v("Сотрудник")]),t._v(" "),a("th",{staticClass:"sticky top-0"},[t._v("Оклад")]),t._v(" "),a("th",{staticClass:"sticky top-0"},[t._v("Налог")]),t._v(" "),a("th",{staticClass:"sticky top-0"},[t._v("На руки")]),t._v(" "),a("th",{staticClass:"sticky top-0"},[t._v("Дни")]),t._v(" "),a("th",{staticClass:"sticky top-0"},[t._v("К оплате")]),t._v(" "),a("th",{staticClass:"sticky top-0"},[t._v("Начальное сальдо")]),t._v(" "),a("th",{staticClass:"sticky top-0",attrs:{colspan:"2"}},[t._v("Конечное сальдо")])])}],!1,null,null,null).exports},10103:(t,e,a)=>{a.d(e,{Z:()=>n});const r={props:{loading:Boolean}};const n=(0,a(51900).Z)(r,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("button",{staticClass:"flex items-center",attrs:{disabled:t.loading}},[t.loading?a("div",{staticClass:"btn-spinner mr-2"}):t._e(),t._v(" "),t._t("default")],2)}),[],!1,null,null,null).exports},66186:(t,e,a)=>{a.d(e,{Z:()=>n});const r={inheritAttrs:!1,props:{id:{type:String,default:function(){return"select-input-".concat(this._uid)}},value:[String,Number,Boolean],label:String,error:String},data:function(){return{selected:this.value}},watch:{selected:function(t){this.$emit("input",t)}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()}}};const n=(0,a(51900).Z)(r,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[t.label?a("label",{staticClass:"form-label",attrs:{for:t.id}},[t._v(t._s(t.label)+":")]):t._e(),t._v(" "),a("select",t._b({directives:[{name:"model",rawName:"v-model",value:t.selected,expression:"selected"}],ref:"input",staticClass:"form-select",class:{error:t.error},attrs:{id:t.id},on:{change:function(e){var a=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.selected=e.target.multiple?a:a[0]}}},"select",t.$attrs,!1),[t._t("default")],2),t._v(" "),t.error?a("div",{staticClass:"form-error"},[t._v(t._s(t.error))]):t._e()])}),[],!1,null,null,null).exports},45168:(t,e,a)=>{a.d(e,{Z:()=>o});function r(t){return function(t){if(Array.isArray(t))return n(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||function(t,e){if(!t)return;if("string"==typeof t)return n(t,e);var a=Object.prototype.toString.call(t).slice(8,-1);"Object"===a&&t.constructor&&(a=t.constructor.name);if("Map"===a||"Set"===a)return Array.from(t);if("Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a))return n(t,e)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function n(t,e){(null==e||e>t.length)&&(e=t.length);for(var a=0,r=new Array(e);a<e;a++)r[a]=t[a];return r}const s={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},col:{type:Boolean,default:!0},value:String,label:String,error:String},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)},onEnter:function(t){console.log("on enter...",t);var e=event.target.form,a=r(e).indexOf(event.target)+1;e.elements[a].select(),e.elements[a].focus(),event.preventDefault()}}};const o=(0,a(51900).Z)(s,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"flex",class:{"flex-col":!t.col}},[a("div",{class:{"w-3/12":t.col}},[t.label?a("label",{staticClass:"form-label font-medium",attrs:{for:t.id}},[t._v(t._s(t.label)+":")]):t._e()]),t._v(" "),a("div",{class:{"w-9/12":t.col}},[a("input",t._b({ref:"input",staticClass:"w-full block  pb-1 border-b-2 border-gray-200",class:{error:t.error},attrs:{id:t.id,onclick:"select()",type:t.type},domProps:{value:t.value},on:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.onEnter.apply(null,arguments)},input:function(e){return t.$emit("input",e.target.value)}}},"input",t.$attrs,!1)),t._v(" "),a("div",{staticClass:"w-full mt-1"},[t.error?a("div",{staticClass:"form-error"},[t._v(t._s(t.error))]):t._e()])])])}),[],!1,null,"6ecb489a",null).exports},51900:(t,e,a)=>{function r(t,e,a,r,n,s,o,l){var i,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=a,d._compiled=!0),r&&(d.functional=!0),s&&(d._scopeId="data-v-"+s),o?(i=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),n&&n.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(o)},d._ssrRegister=i):n&&(i=l?function(){n.call(this,(d.functional?this.parent:this).$root.$options.shadowRoot)}:n),i)if(d.functional){d._injectStyles=i;var c=d.render;d.render=function(t,e){return i.call(e),c(t,e)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,i):[i]}return{exports:t,options:d}}a.d(e,{Z:()=>r})}}]);
//# sourceMappingURL=6313.js.map?id=b65b2c9adaecf987