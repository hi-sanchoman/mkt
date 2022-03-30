<template>
    <div>
       Учет расходов
           <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto mt-4">
        <div class="grid grid-cols-2 gap-2">
                <select-input v-model="rashod_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                    <option v-for="month in months" :value="month.id">{{month.name}}</option>
                </select-input>

                <select-input v-model="rashod_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                    <option v-for="year in years" >{{year}}</option>
                </select-input>
                <button :class="priem_moloka ? 'bg-green-500 text-white font-bold py-2 px-4 rounded h-auto':'bg-blue-500 text-white font-bold py-2 px-4 rounded h-auto'" @click="showRashodReports(1)">Прием молока</button>
                    <button :class="return_expenses ? 'bg-green-500 text-white font-bold py-2 px-4 rounded h-auto':'bg-blue-500 text-white font-bold py-2 px-4 rounded h-auto'" @click="showRashodReports(2)">расходы с возвратом</button>
                    <button :class="overage_expenses ? 'bg-green-500 text-white font-bold py-2 px-4 rounded h-auto':'bg-blue-500 text-white font-bold py-2 px-4 rounded h-auto'" @click="showRashodReports(3)">общий расход</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap mt-5 tableizer-table">
                    <tr>
                        <th>
                           Дата 
                        </th>
                        <th>
                           сумма 
                        </th>

                        <th>
                           категория 
                        </th>

                        <th>
                           сотрудник 
                        </th>
                    </tr>
                    <tr v-for="item in mytotalreport" v-if="new Date(item.created_at).getMonth()+1 == rashod_month && new Date(item.created_at).getFullYear() == rashod_year">
                        <td>
                           {{moment(item.created_at).format("DD-MM-YYYY")}}
                        </td>
                        <td>
                           {{item.sum}} 
                        </td>

                        <td>
                           {{categories[item.category_id-1].name}}
                        </td>

                        <td>
                           {{item.user}} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                          Итог
                        </td>
                        <td>
                           {{mytotalreport.reduce((acc, item) => acc + item.sum,0) }}
                        </td>

                        <td>
                        </td>

                        <td>
                        </td>
                    </tr>
                </table>
            </div>
            <br><br>
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
import moment from "moment";

export default {
    metaInfo: {
        title: 'Учет'
    },
    
    layout: Layout,
    data() {
        return {
            rashod_index: 4,
            myindex: 0,
            moment: moment,
            mytotalreport:this.expenses,
            priem_moloka:false,
            return_expenses:false,
            overage_expenses:true,
            years:[2021,2020,2019,2018],
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
            rashod_month:new Date().getMonth()+1,
            rashod_year:new Date().getFullYear(),
        }
    },
    props: {
        categories: Array,
        expenses: Array
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
        rashod_index:function(val){

            if(val == 1){

                
                this.mytotalreport = [];
                this.expenses.forEach(element => {
                    if(element.category_id == 4){
                        this.mytotalreport.push(element);
                    }
                });
            }else if(val == 2){
                
                this.mytotalreport = [];
                this.expenses.forEach(element => {
                    if(element.category_id == 5){
                        this.mytotalreport.push(element);
                    }
                });
            } else if(val == 3){
                
                this.mytotalreport = [];
                this.expenses.forEach(element => {
                    if(element.category_id != 5 && element.category_id != 4)
                        this.mytotalreport.push(element);
                    
                });
            }
        },
    },
    computed: {

    },
    methods: {
        showRashodReports(index){
            if(index == 1){
                this.priem_moloka = true;
                this.return_expenses = false;
                this.overage_expenses = false;
                this.myindex = 4;
            }
            else if(index == 2){
                this.priem_moloka = false;
                this.return_expenses = true;
                this.overage_expenses = false;
                this.myindex = 5;
            }
            else{
                this.priem_moloka = false;
                this.return_expenses = false;
                this.overage_expenses = true;
                this.myindex = 0;
            }
                this.rashod_index = index;    
            
            
            //this.$modal.show('rashod_reports');
        },
    }
}
</script>