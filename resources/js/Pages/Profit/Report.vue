<template>
<div class="flex flex-col h-full">
    <!-- <h3>Отчет сформирован, <download-excel
                          class="font-bold pt-5 text-left cursor-pointer"
                          :data="json_data1"
                          :fields="json_fields1"
                          worksheet="My Worksheet"
                          name="отчет.xls"
                        >
                          Скачать отчет 
                        </download-excel></h3> -->
</div>
</template>

<script>


import axios from 'axios'
import $ from 'jquery'
import Vue from "vue";
import JsonExcel from "vue-json-excel";

 
Vue.component("downloadExcel", JsonExcel);


export default {
    metaInfo: {
        title: 'Excel'
    },

    props: {
        report: Array
    },
    data(){
        return {
            json_fields1: 
            {
                "Касса" : "kassa",
                "Банк счет" : "bank_amount",
                "Морозильник" : "freezer",
                "склад" : "store",
                "Долги/Реализация" : "owesrealization",
                "Рабочие" : "workers",
                "конт. ст. активы" : "actives",
                "Тетрапак" : "tetrapack",
                "Диз. топливо" : "fuel",
                "Зарплата" : "salary",
            },
            json_data1: this.report,
        }
    },
    mounted(){

 
    },
    created() {

    },
    components: {
        
        JsonExcel
    },
    watch: {
        
    }
}
</script>