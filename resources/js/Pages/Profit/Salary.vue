<template>
<div class="flex flex-col h-full">

    <div  class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class=" gap-5 sm:flex justify-start">
            <h3>Зарплата</h3>
            <select-input v-model="salary_month1" class="pr-6 pb-8 w-full lg:w-1/6 mt-4" label="Месяц">
                <option v-for="month in months" :value="month.id">{{month.name}}</option>
            </select-input>

            <select-input v-model="salary_year1" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                <option v-for="year in years" >{{year}}</option>
            </select-input>
        </div>
        <!--<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showSalaryForm()">выдать зарплату</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showAddWorkerForm()">добавить сотрудника</button>-->
        <div class="overflow-y-auto h-80">
            <table class="w-full whitespace-nowrap mt-5 tableizer-table">
                <tr class="text-left  border-b border-gray-200">
                    <th>Сотрудник</th>
                    <th>Оклад</th>
                    <th>Отработано дней</th>
                    <th>Всего начислено</th>
                    <th>Взносы ОСМС</th>
                    <th>ИПН</th>
                    <th>ОПВ</th>
                    <th>Всего к оплате</th>
                    <th>Начальное сальдо</th>
                    <th>Конечное сальдо</th>
                </tr>
                <tr v-for="sal in mysalary" class="pt-2">
                    <td>{{sal.worker.name}}&nbsp;{{sal.worker.surname}}</td>
                    <td><input type="number" v-model="sal.worker.salary" name=""></td>
                    <td><input type="number" v-model="sal.days" name=""></td>
                    <td><input type="number" v-model="sal.income" name=""></td>
                    <td><input type="number" v-model="sal.OSMS" name=""></td>
                    <td><input type="number" v-model="sal.IPN" name=""></td>
                    <td><input type="number" v-model="sal.OPV" name=""></td>
                    <td><input type="number" v-model="sal.total_income" name=""></td>
                    <td><input type="number" v-model="sal.initial_saldo" name=""></td>
                    <td><input type="number" v-model="sal.end_saldo" name=""></td>
                </tr>
                <tr class="text-left pt-5 border-b border-gray-200">
                    <th>Итог</th>
                    <th>{{countOklad()}}</th>
                    <th></th>
                    <th>{{countIncome()}}</th>
                    <th>{{countOSMS()}}</th>
                    <th>{{countIPN()}}</th>
                    <th>{{countOPV()}}</th>
                    <th>{{countTotalIncome()}}</th>
                    <th>{{countSaldo()}}</th>
                    <th>{{countEndSaldo()}}</th>
                </tr>
                <tr v-for="worker in myworkers" class="pt-2">
                    <td>{{worker.name}}&nbsp;{{worker.surname}}</td>
                    <td><input type="number" v-model="worker.salary" name=""></td>
                    <td><input type="number" name=""></td>
                    <td><input type="number" name=""></td>
                    <td><input type="number" name=""></td>
                    <td><input type="number" name=""></td>
                    <td><input type="number" name=""></td>
                    <td><input type="number" name=""></td>
                    <td><input type="number" name=""></td>
                    <td><input type="number" name=""></td>
                </tr>
                
            </table>
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
        owerealiztor: Array,
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
            salary_month: new Date().getMonth()+1,
            salary_year: new Date().getFullYear(),
            salary_month1: new Date().getMonth()+1,
            salary_year1: new Date().getFullYear(),
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
            owes: true,
            salary: false,
            reports: false,
            salary: false
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
        },
       salary_month1:function(val){
            axios.post('get-salary-month',{month:val,year:this.salary_year1}).then(response => {
                this.mysalary = response.data;
                if(this.salary_month1 - 1 == new Date().getMonth() && this.salary_year1 == new Date().getFullYear()){
                    this.myworkers = this.workers;
                }
                else{
                    this.myworkers = [];
                }
            });
        },
        salary_year1:function(val){
            axios.post('get-salary-month',{month:this.salary_month1,year:val}).then(response => {
                this.mysalary = response.data;
                 if(this.salary_month1 - 1 == new Date().getMonth() && this.salary_year1 == new Date().getFullYear()){
                    this.myworkers = this.workers;
                }
                else{
                    this.myworkers = [];
                }
            });
        },
        salary_month:function(val){
            axios.post('get-owes-month',{month:val,year:this.salary_year}).then(response => {
                this.myowes1 = response.data;
              
            });
        },
        salary_year:function(val){
            axios.post('get-owes-month',{month:this.salary_month,year:val}).then(response => {
                this.myowes1 = response.data;
            });
        },
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
        showSalary(){
            this.kassa = false;
            this.dolgi = false;
            this.reports = false;
            this.salary = true;
        },
        showReports(){
            this.kassa = false;
            this.dolgi = false;
            this.reports = true;
            this.salary = false;
        },
        showKassa(){
            this.kassa = true;
            this.dolgi = false;
            this.reports = false;
            this.salary = false;
        },
        showOwes(){
            this.kassa = false;
            this.dolgi = true;
            this.reports = false;
            this.salary = false;
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