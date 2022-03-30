<template>
    <div>
       Продажи
       <div class="mt-4">
           <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 sm:hidden">
        <div class="gap-5">
            <h3>Продажи за {{months[realizators_month-1].name}} {{realizators_year}} года</h3>  
            <select-input v-model="realizators_month" class="pr-6 pb-8 w-full lg:w-1/6 mt-3" label="Месяц" >
                <option v-for="month in months" :value="month.id">{{month.name}}</option>
            </select-input>

            <select-input v-model="realizators_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год">
                <option v-for="year in years" >{{year}}</option>
            </select-input> 
        </div>
        <br>
        <br>
        <table class="tableizer-table w-full">
            <thead>
                <tr class="tableizer-firstrow"><th>Наименование товара</th><th>количество</th><th>цена</th><th>Сумма</th></tr>
                <tr v-for="item in mysold1">
                    <td>{{item.assortment}}</td>
                    <td>{{item.sold_amount}}</td>
                    <td>{{item.price}}</td>
                    <td>{{item.sold_amount * item.price}}</td>
                </tr>
                <tr>
                    <td>Итого:</td>
                    <td>{{mysold1.reduce((acc, item) => acc + parseInt(item.sold_amount),0)}}</td>
                    <td></td>
                    <td>{{mysold1.reduce((acc, item) => acc + item.sold_amount * item.price,0)}}</td>
                </tr>
            </thead>
        </table>
    </div>
       </div>
    </div>
</template>
<script>
    
import Layout from '@/Shared/Layout'
import axios from 'axios'
import $ from 'jquery'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import SelectInput from '@/Shared/SelectInput'

export default {
    metaInfo: {
        title: 'Морозильник'
    },
    
    layout: Layout,
    data() {
        return {
            mysold1: this.sold1,
            years:[2021,2020,2019,2018],
            realizators_month: new Date().getMonth()+1,
            realizators_year: new Date().getFullYear(),
            months:[
                {id: 1, name: "Январь"},
                {id: 2, name: "Февраль"},
                {id: 3, name: "Март"},
                {id: 4, name: "Апрель"},
                {id: 5, name: "Май"},
                {id: 6, name: "Июнь"},
                {id: 7, name: "Июль"},
                {id: 8, name: "Август"},
                {id: 9, name: "Сентябрь"},
                {id: 10, name: "Октябрь"},
                {id: 11, name: "Ноябрь"},
                {id: 12, name: "Декабрь"},
            ],
        }
    },
    props: {
        sold1: Array
    },
    mounted(){

 
    },
    created() {


    },
    components: {
      Datepicker,
      SelectInput
    },
    watch: {
        realizators_month:function(val) {
                  this.realizators_month = val;
                  this.getSold1();
               },
        realizators_year:function(val) {
                  this.realizators_year = val;
                  this.getSold1();
               },
    },
    computed: {

    },
    methods: {
        getSold1(){
        axios.post('sold1',{month:this.realizators_month,year:this.realizators_year}).then(response => {
            this.mysold1 = response.data;
        })
       },
    }
}
</script>