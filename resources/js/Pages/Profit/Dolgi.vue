<template>
<div class="flex flex-col h-full">

    
    <div class="w-full bg-white rounded-2xl  h-auto p-2 overflow-y-auto ">
        <div class="grid grid-cols-2 gap-2">
            <h3>Долги</h3>
            <button class="text-sm bg-blue-500 text-white font-bold py-2 px-4 rounded text-center h-8" @click="showRealizators()">Реализаторы</button>
            <button class="text-sm bg-blue-500 text-white font-bold py-2 px-4 rounded text-center h-8" @click="showMagazines()">Магазины</button>
            <button class="text-sm bg-blue-500 text-white font-bold py-2 px-4 rounded text-center h-8" @click="showOther()">Другое</button>

            <select-input v-model="dolgi_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                <option v-for="month in months" :value="month.id">{{month.name}}</option>
            </select-input>

            <select-input v-model="dolgi_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                <option v-for="year in years" >{{year}}</option>
            </select-input>
        </div>
        
        <div class="overflow-x-auto w-full">
            <table v-if="realizators" class="tableizer-table w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    
                    <th>Реализатор</th>
                    <th>Сумма долга на сегодня</th>      
                    <th>Оплачено</th>         
                </tr>
                <tr v-for="owes in owerealizator" v-if="new Date(owes.created_at).getMonth() >= new Date(dolgi_month)" class="text-left  border-b border-gray-200">
                    <td>{{owes.realizator.first_name}}</td>
                    <td>{{owes.amount}}</td>
                    <td><input type="checkbox" name=""></td>
                </tr>
            </table>


            <table v-if="magazines" class="tableizer-table w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    
                    <th>Магазин</th>
                    <th>Сумма долга на сегодня</th> 
                    <th>Оплачено</th>               
                </tr>
                <tr v-for="owe in owes1" v-if="new Date(owe.created_at).getMonth() >= new Date(dolgi_month)" class="text-left  border-b border-gray-200">
                    <td>{{owe.magazine.name}}</td>
                    <td>{{owe.remains}}</td>
                    <td><input type="checkbox" name=""></td>
                </tr>
            </table>



            <table v-if="other" class="tableizer-table w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    
                    <th>Сотрудник</th>
                    <th>Сумма долга на сегодня</th>      
                    <th>Оплачено</th>          
                </tr>
                <tr v-for="owe in oweother" v-if="new Date(owe.created_at).getMonth() >= dolgi_month" class="text-left  border-b border-gray-200">
                    <td>{{owe.name}}</td>
                    <td>{{owe.amount}}</td>
                    <td><input type="checkbox" name=""></td>
                </tr>
            </table>
        </div>
          <div class="mt-10 flex justify-start gap-5">
   
        </div>
    </div>

</div>
</template>

<script>

import Layout from '@/Shared/Layout'
import NumberInput from '@/Shared/NumberInput'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import axios from 'axios'
import $ from 'jquery'
import Vue from "vue";
import JsonExcel from "vue-json-excel";

import LoadingButton from '@/Shared/LoadingButton'
 
Vue.component("downloadExcel", JsonExcel);


export default {
    metaInfo: {
        title: 'Storage'
    },
    
    layout: Layout,

    props: {
        owes1: Array,
        owerealizator: Array,
        oweshop: Array,
        oweother: Array,
        categories: Array,
        incomes: Array,
        expenses: Array,
        users: Array,
        zarplata: Array,
        workers: Array,
        days: Array,
        saldo: Array,
        income: Number
    },
    data(){
        return {
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
            dolgi_month: new Date().getMonth(),
            dolgi_year: new Date().getFullYear(),
            work_users: this.users,
            //create worker form
            form: this.$inertia.form({
                first_name: null,
                last_name: null,
                salary: null
            }),
            //end worker form
            //excell
            json_fields1: 
            {
                "Реализаторы" : "realizator",
                "Сумма долга на сегодня" : "amount"
            },
            json_data1: this.owerealizator ,

            json_fields2: 
            {
                "Магазин" : "shop",
                "Сумма долга на сегодня" : "amount"
            },
            json_data2: this.oweshop ,


            json_fields3: 
            {
                "Другое" : "name",
                "Сумма долга на сегодня" : "amount"
            },
            json_data3: this.oweother ,


            json_fields: 
            {
                     "Сотрудник": "worker.name",
                     "Оклад": "worker.salary",
                     "Отработано дней": "days",
                     "Всего начислено" : "income",
                     "Взносы ОСМС": "OSMS",
                     "ИПН": "IPN",
                     "ОПВ": "OPV",
                     "Всего к оплате":"total_income",
                     "Начальное сальдо":"initial_saldo",
                     "Конечное сальдо":"end_saldo",
                     "Рспись":""
            },
            json_data: this.zarplata ,//this.realization_report,
            json_meta: [
                {
                    key : 'charset',
                    value : 'utf-8'
                }
            ],
            //end excel
            myworkers: this.workers,
            mydays: this.days,
            mysaldo: this.saldo,
            mysalary: this.zarplata,
            income_user: null,
            rashod_user: null,
            income_sotrudnik: "",
            rashod_sotrudnik: "",
            income_sum: null,
            income_description: null,
            rashod_sum: null,
            rashod_category: null,
            rashod_description: null,
            myexpenses: this.expenses,
            myincomes: this.incomes,
            kassa: true,
            dolgi: false,
            worker: '',
            worker_salary: 0,
            mysalary: this.zarplata,
            magazines: false,
            realizators: true,
            other: false
        }
    },
    mounted(){

 
    },
    created() {


    },
    components: {
        NumberInput,
        SelectInput,
        TextInput,
        JsonExcel,
        LoadingButton
    },
    watch: {
        rashod_category:function(val){
            if(val == 4){
                axios.get('get-work-users').then(response => {
                    this.work_users = response.data;
                });
            }else{
                this.work_users = this.users;
            }
        }
    },
    computed: {

    },
    methods: {
        addList(){
            axios.get('get-work-users').then(response => {
                this.work_users = response.data;
            });
        },
        hozrashod(){
            alert("not ready yet!");
        },
        vozvrat(){
            alert("not ready yet!");
        },
        others(){
            alert("not ready yet!");
        },
        moloko(){
            alert("not ready yet!");
        },

        addWorker(){
            this.form.post(this.route('add-worker'));
            this.$modal.hide('addWorker');
        },
        showAddWorkerForm(){
            this.$modal.show('addWorker');
        },
        countOklad(){
            var oklad = 0;
            this.mysalary.forEach(function(r,a){
                oklad += parseInt(r.worker.salary);
            });
            return oklad;
        },
        countIncome(){
            var income = 0;
            this.mysalary.forEach(function(r,a){
                income += parseInt(r.income);
            });
            return income;
        },
        countOSMS(){
            var osms = 0;
            this.mysalary.forEach(function(r,a){
                osms += parseInt(r.OSMS);
            });
            return osms;
        },
        countIPN(){
            var IPN = 0;
            this.mysalary.forEach(function(r,a){
                IPN += parseInt(r.IPN);
            });
            return IPN;
        },
        countOPV(){
            var OPV = 0;
            this.mysalary.forEach(function(r,a){
                OPV += parseInt(r.OPV);
            });
            return OPV;
        },
        countTotalIncome(){
            var total = 0;
            this.mysalary.forEach(function(r,a){
                total += parseInt(r.total_income);
            });
            return total;
        },
        countSaldo(){
            var saldo = 0;
            this.mysalary.forEach(function(r,a){
                saldo += parseInt(r.initial_saldo);
            });
            return saldo;
        },
        countEndSaldo(){
            var oklad = 0;
            this.mysalary.forEach(function(r,a){
                oklad += parseInt(r.end_saldo);
            });
            return oklad;
        },
        giveSalary(id, key){
            axios.post('give-salary',{worker: id, days: this.mydays[id-1], saldo: this.mysaldo[id-1]}).then(response => {
                this.myworkers.splice(key,1);
            });
        },
        showSalaryForm(){
            this.$modal.show('salaryForm');
        },
        showRealizators(){
            this.magazines = false,
            this.realizators = true,
            this.other = false
        },

        showMagazines(){
            this.magazines = true,
            this.realizators = false,
            this.other = false
        },


        showOther(){
            this.magazines = false,
            this.realizators = false,
            this.other = true
        },
        getIncomesTotal(){
            var total = 0;
            for (let i = 0; i < this.incomes.length; i++) {
                total += parseInt(this.incomes[i].sum);
            }
            return total;
        },
        getExpensesTotal(){
            var total = 0;
            for (let i = 0; i < this.expenses.length; i++) {
                total += parseInt(this.expenses[i].sum);
            }
            return total;
        },
        showRashod(){
            this.$modal.show('rashod');
        },
        showPrihod(){
            this.$modal.show('prihod');
        },
        addIncome(){
            axios.post('send-income',{
                sum: this.income_sum,
                description: this.income_description,
                user: this.income_user
            }).then(response => {
                this.myincomes.push(response.data);
            });
            this.$modal.hide('prihod');
        },
        addExpense(){
            axios.post('send-expense',{
                sum: this.rashod_sum,
                category: this.rashod_category,
                description: this.rashod_description,
                user: this.rashod_user
            }).then(response => {
                this.myexpenses.push(response.data);
            });
            this.$modal.hide('rashod');

        }
    }
}
</script>