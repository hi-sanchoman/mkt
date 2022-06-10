"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[1318],{56540:(e,t,n)=>{n.d(t,{Z:()=>d});"undefined"!=typeof globalThis?globalThis:"undefined"!=typeof window?window:void 0!==n.g?n.g:"undefined"!=typeof self&&self;var o,r,a=(o=function(e,t){e.exports=function e(t,n,o){var r,a,s=window,i="application/octet-stream",l=o||i,d=t,c=!n&&!o&&d,u=document.createElement("a"),f=function(e){return String(e)},p=s.Blob||s.MozBlob||s.WebKitBlob||f,h=n||"download";if(p=p.call?p.bind(s):Blob,"true"===String(this)&&(l=(d=[d,l])[0],d=d[1]),c&&c.length<2048&&(h=c.split("/").pop().split("?")[0],u.href=c,-1!==u.href.indexOf(c))){var m=new XMLHttpRequest;return m.open("GET",c,!0),m.responseType="blob",m.onload=function(t){e(t.target.response,h,i)},setTimeout((function(){m.send()}),0),m}if(/^data:([\w+-]+\/[\w+.-]+)?[,;]/.test(d)){if(!(d.length>2096103.424&&p!==f))return navigator.msSaveBlob?navigator.msSaveBlob(g(d),h):v(d);l=(d=g(d)).type||i}else if(/([\x80-\xff])/.test(d)){for(var y=0,b=new Uint8Array(d.length),x=b.length;y<x;++y)b[y]=d.charCodeAt(y);d=new p([b],{type:l})}function g(e){for(var t=e.split(/[:;,]/),n=t[1],o=("base64"==t[2]?atob:decodeURIComponent)(t.pop()),r=o.length,a=0,s=new Uint8Array(r);a<r;++a)s[a]=o.charCodeAt(a);return new p([s],{type:n})}function v(e,t){if("download"in u)return u.href=e,u.setAttribute("download",h),u.className="download-js-link",u.innerHTML="downloading...",u.style.display="none",document.body.appendChild(u),setTimeout((function(){u.click(),document.body.removeChild(u),!0===t&&setTimeout((function(){s.URL.revokeObjectURL(u.href)}),250)}),66),!0;if(/(Version)\/(\d+)\.(\d+)(?:\.(\d+))?.*Safari\//.test(navigator.userAgent))return/^data:/.test(e)&&(e="data:"+e.replace(/^data:([\w\/\-\+]+)/,i)),window.open(e)||confirm("Displaying New Document\n\nUse Save As... to download, then click back to return to this page.")&&(location.href=e),!0;var n=document.createElement("iframe");document.body.appendChild(n),!t&&/^data:/.test(e)&&(e="data:"+e.replace(/^data:([\w\/\-\+]+)/,i)),n.src=e,setTimeout((function(){document.body.removeChild(n)}),333)}if(r=d instanceof p?d:new p([d],{type:l}),navigator.msSaveBlob)return navigator.msSaveBlob(r,h);if(s.URL)v(s.URL.createObjectURL(r),!0);else{if("string"==typeof r||r.constructor===f)try{return v("data:"+l+";base64,"+s.btoa(r))}catch(e){return v("data:"+l+","+encodeURIComponent(r))}(a=new FileReader).onload=function(e){v(this.result)},a.readAsDataURL(r)}return!0}},o(r={exports:{}},r.exports),r.exports);var s=function(e,t,n,o,r,a,s,i,l,d){"boolean"!=typeof s&&(l=i,i=s,s=!1);var c,u="function"==typeof n?n.options:n;if(e&&e.render&&(u.render=e.render,u.staticRenderFns=e.staticRenderFns,u._compiled=!0,r&&(u.functional=!0)),o&&(u._scopeId=o),a?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),t&&t.call(this,l(e)),e&&e._registeredComponents&&e._registeredComponents.add(a)},u._ssrRegister=c):t&&(c=s?function(){t.call(this,d(this.$root.$options.shadowRoot))}:function(e){t.call(this,i(e))}),c)if(u.functional){var f=u.render;u.render=function(e,t){return c.call(t),f(e,t)}}else{var p=u.beforeCreate;u.beforeCreate=p?[].concat(p,c):[c]}return n};const i={props:{type:{type:String,default:"xls"},data:{type:Array,required:!1,default:null},fields:{type:Object,default:()=>null},exportFields:{type:Object,default:()=>null},defaultValue:{type:String,required:!1,default:""},header:{default:null},footer:{default:null},name:{type:String,default:"data.xls"},fetch:{type:Function},meta:{type:Array,default:()=>[]},worksheet:{type:String,default:"Sheet1"},beforeGenerate:{type:Function},beforeFinish:{type:Function},escapeCsv:{type:Boolean,default:!0},stringifyLongNum:{type:Boolean,default:!1}},computed:{idName:()=>"export_"+(new Date).getTime(),downloadFields(){return this.fields?this.fields:this.exportFields?this.exportFields:void 0}},methods:{async generate(){"function"==typeof this.beforeGenerate&&await this.beforeGenerate();let e=this.data;if("function"!=typeof this.fetch&&e||(e=await this.fetch()),!e||!e.length)return;let t=this.getProcessedJson(e,this.downloadFields);return"html"===this.type?this.export(this.jsonToXLS(t),this.name.replace(".xls",".html"),"text/html"):"csv"===this.type?this.export(this.jsonToCSV(t),this.name.replace(".xls",".csv"),"application/csv"):this.export(this.jsonToXLS(t),this.name,"application/vnd.ms-excel")},export:async function(e,t,n){let o=this.base64ToBlob(e,n);"function"==typeof this.beforeFinish&&await this.beforeFinish(),a(o,t,n)},jsonToXLS(e){let t="<thead>";const n=Object.keys(e[0]).length;let o=this;const r=this.header||this.$attrs.title;r&&(t+=this.parseExtraData(r,'<tr><th colspan="'+n+'">${data}</th></tr>')),t+="<tr>";for(let n in e[0])t+="<th>"+n+"</th>";return t+="</tr>",t+="</thead>",t+="<tbody>",e.map((function(e,n){t+="<tr>";for(let n in e)t+="<td>"+o.preprocessLongNum(o.valueReformattedForMultilines(e[n]))+"</td>";t+="</tr>"})),t+="</tbody>",null!=this.footer&&(t+="<tfoot>",t+=this.parseExtraData(this.footer,'<tr><td colspan="'+n+'">${data}</td></tr>'),t+="</tfoot>"),'<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta name=ProgId content=Excel.Sheet> <meta name=Generator content="Microsoft Excel 11"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">\x3c!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>${worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--\x3e<style>br {mso-data-placement: same-cell;}</style></head><body><table>${table}</table></body></html>'.replace("${table}",t).replace("${worksheet}",this.worksheet)},jsonToCSV(e){let t=this;var n=[];const o=this.header||this.$attrs.title;o&&n.push(this.parseExtraData(o,"${data}\r\n"));for(let t in e[0])n.push(t),n.push(",");return n.pop(),n.push("\r\n"),e.map((function(e){for(let o in e){let r=e[o]+"";t.escapeCsv&&(r='="'+r+'"',r.match(/[,"\n]/)&&(r='"'+r.replace(/\"/g,'""')+'"')),n.push(r),n.push(",")}n.pop(),n.push("\r\n")})),null!=this.footer&&n.push(this.parseExtraData(this.footer,"${data}\r\n")),n.join("")},getProcessedJson(e,t){let n=this.getKeys(e,t),o=[],r=this;return e.map((function(e,t){let a={};for(let t in n){let o=n[t];a[t]=r.getValue(o,e)}o.push(a)})),o},getKeys(e,t){if(t)return t;let n={};for(let t in e[0])n[t]=t;return n},parseExtraData(e,t){let n="";if(Array.isArray(e))for(var o=0;o<e.length;o++)e[o]&&(n+=t.replace("${data}",e[o]));else n+=t.replace("${data}",e);return n},getValue(e,t){const n="object"!=typeof e?e:e.field;let o="string"!=typeof n?[]:n.split("."),r=this.defaultValue;return r=n?o.length>1?this.getValueFromNestedItem(t,o):this.parseValue(t[n]):t,e.hasOwnProperty("callback")&&(r=this.getValueFromCallback(r,e.callback)),r},valueReformattedForMultilines:e=>"string"==typeof e?e.replace(/\n/gi,"<br/>"):e,preprocessLongNum(e){if(this.stringifyLongNum){if(String(e).startsWith("0x"))return e;if(!isNaN(e)&&""!=e&&(e>99999999999||e<1e-13))return'="'+e+'"'}return e},getValueFromNestedItem(e,t){let n=e;for(let e of t)n&&(n=n[e]);return this.parseValue(n)},getValueFromCallback(e,t){if("function"!=typeof t)return this.defaultValue;const n=t(e);return this.parseValue(n)},parseValue(e){return e||0===e||"boolean"==typeof e?e:this.defaultValue},base64ToBlob(e,t){let n=window.btoa(window.unescape(encodeURIComponent(e))),o=atob(n),r=o.length,a=new Uint8ClampedArray(r);for(;r--;)a[r]=o.charCodeAt(r);return new Blob([a],{type:t})}}};var l=function(){var e=this,t=e.$createElement;return(e._self._c||t)("div",{attrs:{id:e.idName},on:{click:e.generate}},[e._t("default",[e._v(" Download "+e._s(e.name)+" ")])],2)};l._withStripped=!0;const d=s({render:l,staticRenderFns:[]},undefined,i,undefined,!1,undefined,void 0,void 0)},11318:(e,t,n)=>{n.r(t),n.d(t,{default:()=>s});n(9669);var o=n(20144),r=n(56540);o.default.component("downloadExcel",r.Z);const a={metaInfo:{title:"Excel"},props:{report:Array},data:function(){return{json_fields1:{Продукт:"product",Заявка:"order_amount",Отпущено:"amount",Возврат:"returned",Брак:"defect","Брак на сумму":"defect_sum",Продано:"sold",Цена:"price",Сумма:"sum"},json_data1:this.report}},mounted:function(){},created:function(){},components:{JsonExcel:r.Z},watch:{}};const s=(0,n(51900).Z)(a,(function(){var e=this.$createElement;return(this._self._c||e)("div",{staticClass:"flex flex-col h-full"})}),[],!1,null,null,null).exports},51900:(e,t,n)=>{function o(e,t,n,o,r,a,s,i){var l,d="function"==typeof e?e.options:e;if(t&&(d.render=t,d.staticRenderFns=n,d._compiled=!0),o&&(d.functional=!0),a&&(d._scopeId="data-v-"+a),s?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),r&&r.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},d._ssrRegister=l):r&&(l=i?function(){r.call(this,(d.functional?this.parent:this).$root.$options.shadowRoot)}:r),l)if(d.functional){d._injectStyles=l;var c=d.render;d.render=function(e,t){return l.call(t),c(e,t)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,l):[l]}return{exports:e,options:d}}n.d(t,{Z:()=>o})}}]);
//# sourceMappingURL=1318.js.map?id=972f2dd485c10b3e