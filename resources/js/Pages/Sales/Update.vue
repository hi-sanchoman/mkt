<template>
    <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
    	
 	
    	<table class="tableizer-table w-full">
    		<tr>
    			<td>
    				Саидакбар
    			</td>
    			<td>
    				
    			</td>
    			<td colspan="2">
    				Дата
    			</td>
    			<td colspan="4">
    				16.09.2021 13:51
    			</td>
    			<td>
    			
    			</td>
    			
    		</tr>

    		<tr class="pt-2">
    			<th>Наименование товара</th>
    			<th>Заявка</th>
    			<th>Отпущено</th>
    			<th>Возврат</th>
    			<th>Обмен/Брак</th>
    			<th>Брак на сумму</th>
    			<th>Продано</th>
    			<th>Цена</th>
    			<th>Сумма</th>
    		</tr>
    		<tr>
    			<td>накладное на возврат</td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td>итог</td>
    		</tr>
    		<tr>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td>итог</td>
    			<td>11625</td>
    			<td></td>
    			<td></td>
    			<td colspan="2" class="text-right">673 708</td>
    		</tr>
    		<tr>
    			<td colspan="8"></td>
    			<td>сумма реализации</td>
    			<td>299745</td>
    		</tr>
    		<tr>
    			<td colspan="4"></td>
    			<td colspan="4">Накладные под реализации</td>
    			<td>Продажа на нал</td>
    			<td>373 963</td>
    		</tr>
    	</table>

    	<div class="row">
        <div class="col-4 flex gap-5 mt-5">
            <div>
                <h6>Накладные под реализации</h6>
                <div class="flex gap-3 mt-2" v-for="col in columns">
                    
                    <div>
                        <select class="block appearance-none mt-2 w-48 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" v-model="col.magazine">
                            <option v-for="item in mymagazines" :value="item">{{item.name}}</option>
                        </select>
                    </div>
                    <div><input class="block appearance-none mt-2 w-48 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" type="number" name="amount" v-model="col.amount"></div>
                </div>
                <button class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">добавить магазин</button>
            </div>
            <div>
                <div>
                    <table class="border-2 m-2">
                        <tr class="p-2">
                            <th class="text-left">Сумма реализации</th>
                            <td class="text-center">0</td>
                        </tr>
                        <tr class="p-2">
                            <th class="text-left">Продажа на нал.</th>
                            <td class="text-center"><input type="number" name=""></td>

                        </tr>
                        <tr class="p-2">
                            <th class="text-left">Мажит</th>
                            <td class="text-center"><input type="number" name=""></td>
                        </tr>
                        <tr class="p-2">
                            <th class="text-left">Сордор</th>
                            <td class="text-center"><input type="number" name=""></td>
                        </tr>
                        <tr class="p-2">
                            <th class="text-left">за услугу 10%</th>
                            <td class="text-center">0</td>
                        </tr>
                        <tr class="p-2">
                            <th class="text-left">К оплате</th>
                            <td class="text-center">0</td>
                        </tr>
                    </table>

                </div>
            </div>

        </div> 
    </div>
    </div>
</template>

<script>

import Layout from '@/Shared/Layout'
import axios from 'axios'
import $ from 'jquery'
import Vue from "vue";
import JsonExcel from "vue-json-excel";
import moment from "moment";
 
Vue.component("downloadExcel", JsonExcel);


export default {
    metaInfo: {
        title: 'Update'
    },
    data(){
        return{
            moment: moment,
            columns:this.mycolumns,
            mymagazines: [],
            magazines: [0,0,0,0,0,0],
            myrealizators: null,
            myrealizators_total: null,
            myreport: this.report1,
            sales: true,
            report: false,
            alert: this.realization_count,
            realizator_order: [],
            realizator: this.realizators[0],
             json_fields: 
                {
                     "Товар": "assortment.type",
                     "Заявка": "order_amount",
                     "Отпущено": "amount",
                     "Возврат" : "returned",
                     "Обмен/Брак": "defect",
                     "Брак на сумму": "defect_sum",
                     "Продано": "sold"
                },
            json_data: this.report1,//this.realization_report,
            json_meta: [
                {
                    key : 'charset',
                    value : 'utf-8'
                }
            ],
            myrealizations: this.realizations,
            total: 0,
            reserve: 0,
            real: false,
            myorder:[],
            form:this.$inertia.form({
                realizator: null,
                summ: null,
                defect: null,
            }),
        }
    },
    layout: Layout,

    props: {
     mycolumns: Array,
     report1: Array
    
    },
    mounted(){

 
    },
    created() {
        
    },
    components: {
        JsonExcel
    },
    watch: {

    },
    computed: {

    },
    methods: {
        addColumn(){
            this.columns.push({magazine: null, amount: null, pivot: null});
        },
        saveRealization(){
            axios.post('save-realization',{
                realization: this.realizator.realization[this.realizator.realization.length-1],
                realizator_income: this.getRealizationSum()/10,
                bill: this.getRealizationSum(),
                income: this.getRealizationSum()-this.getRealizationSum()/10,
                columns: this.columns
            }).then(response => {
                alert(response.data.message);
                this.columns = response.data.columns;
            });
        },
        saveConfirmRealization(){
            axios.post('confirm-realization',{
                realization: this.realizator.realization[0],
                realizator_income: this.getRealizationSum()/10,
                bill: this.getRealizationSum(),
                income: this.getRealizationSum()-this.getRealizationSum()/10,
                columns: this.columns
            }).then(response => {
                alert(response);
            });
        },
        getRealizationSum(){
            var total = 0;
            this.magazines.forEach(element => {
                if(element != 0)
                    total = total+parseInt(element);
            });
            return total;
        },
        totalSum(){
            var total = 0;
            this.myreport.forEach(element => {
                total += element.order_amount*element.assortment.price;
            });
            return total;
        },
        totalBrak(){
            var total = 0;
            this.myreport.forEach(element => {
                total += element.returned*element.assortment.price;
            });
            return total;
        },
        addTotal(number,amount,assortment){
            this.total += number;
            if(amount>number){
                this.reserve += amount-number;
                //axios.post('add-reserve',{ assortment: assortment, amount: this.reserve});
            }
            return number;
        },
        getTotal(){
            var mytotal = this.total;
            this.total = 0;

            return mytotal;
        },
        getReserve(){

            var myreserve = this.reserve;
            this.reserve = 0;

            return myreserve;  
        },
        showOrder(id){
            axios.post('sales/order',{id : id}).then(response => {

                this.myorder = response.data.order;
            });
            this.$modal.show('order');
        },
    }
}
</script>