<template>
<div class="flex flex-col h-full">
    <h3>Отчет сформирован, <download-excel
                          class="font-bold pt-5 text-left cursor-pointer"
                          :data="json_data1"
                          :fields="json_fields1"
                          worksheet="My Worksheet"
                          name="отчет.xls"
                        >
                          Скачать отчет 
                        </download-excel></h3>
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
        conversions: Array,
        price: Array,
        oiltotal: Number,
        excelKg: Array,
        excelGotov: Array,
    },
    data(){
        return {
            json_fields1: 
                {
                      "#": "id",
                      "Поступления молока": "assortment",
                      "количество": "kg",
                      "На сумму": "store",
                      "": "defect",
                      "": "price",
                      "": "total_price"
                },
            json_data1: [
            {
                id: 1,
                assortment: "Физ вес",
                kg: this.conversions[0].kg,
                store: this.conversions[0].kg*this.price[0].price
            },
            {
                id: 2,
                assortment: "Масло 72,5%",
                kg: this.oiltotal,
                store: this.oiltotal*850
            },
            
            {
                id: 5,
                assortment: "Итого",
                kg: "",
                store: this.oiltotal*850+this.conversions[0].kg*this.price[0].price
            },
            {
                id: "",
                assortment: "",
                kg: "",
                store: ""
            },
            {
                id: "№",
                assortment: "Ассортимент",
                kg: "из кг Молоко",
                store: "Выход товара",
               
            },
            {
                id: "1",
                assortment: "Молоко 2,5%",
                kg:this.excelKg[0],
                store: this.excelGotov[0],
                price: 200
               
            },
            {
                id: "2",
                assortment: "Кефир 2,5%",
                kg:this.excelKg[1],
                store: this.excelGotov[1],
                defect: "брак 800",
                price: 200
               
            },
            {
                id: "3",
                assortment: "Ряженка 4%",
                kg:this.excelKg[2],
                store: this.excelGotov[2],
                price: 200
               
            },
            {
                id: "4",
                assortment: "Брынза",
                kg:this.excelKg[3],
                store: this.excelGotov[3],
                price: 200
               
            },
            {
                id: "5",
                assortment: "Сметана 20%",
                kg:this.excelKg[4],
                store: this.excelGotov[4],
                price: 200
               
            },
            {
                id: "6",
                assortment: "Сметана 15%",
                kg:this.excelKg[5],
                store: this.excelGotov[5],
                price: 200
               
            },
            {
                id: "7",
                assortment: "Сливки на масло",
                kg:this.excelKg[6],
                store: this.excelGotov[6],
               
            },
            {
                id: "8",
                assortment: "Чечил",
                kg:this.excelKg[7],
                store: this.excelGotov[7],
                price: 200
               
            },
            {
                id: "9",
                assortment: "Обрат на творог",
                kg: this.excelKg[8],
                store: this.excelGotov[8],
                price: 200
               
            },
            {
                id: "10",
                assortment: "Масло сливочное 72,5%",
                kg: this.excelKg[9],
                store: this.excelGotov[9],
                price: 200
               
            },
            {
                id: "11",
                assortment: "Масло топленое",
                kg:this.excelKg[10],
                store: this.excelGotov[10],
                price: 200
               
            },
            {
                id: "12",
                assortment: "Курт",
                kg: this.excelKg[11],
                store: this.excelGotov[11],
               
            },
            {
                id: "13",
                assortment: "Брак",
                kg: this.excelKg[12],
                store: this.excelGotov[12],
                price: 200
               
            },
            {
                id: "14",
                assortment: "Белок",
                kg: this.excelKg[13],
                store: this.excelGotov[13],
                price: 200
               
            },
            ],
            json_meta: [
                {
                    key : 'charset',
                    value : 'utf-8'
                }
            ],
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