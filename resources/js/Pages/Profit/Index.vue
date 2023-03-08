<template>
<div class="flex flex-col h-full">

    <!-- Кнопки в первом ряду -->
    <div class="panel flex justify-start gap-5 mb-4">
        <button v-if="userIsNot([ACCOUNTANT])"
            :class="kassa ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
            @click="showKassa()">
            Касса
        </button>

        <button v-if="userIsNot([ACCOUNTANT])"
            :class="salary ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
            @click="showSalary()">
            Зарплата
        </button>

        <button :class="dolgi ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
            @click="showOwes()">
            Долги клиентов
        </button>

        <button :class="dolgi_other ?'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
            @click="showOwesOther()">
            Долги (физ)
        </button>
    </div>

    <!-- Вкладка: Касса  -->
    <div v-if="kassa" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">

        <div class="flex justify-between mb-4 items-center">
            <div class="flex">
                <div>
                    от
                    <datepicker
                        v-model="from"
                        type="date"
                        placeholder=""
                        :show-time-header = "time">
                    </datepicker>
                </div>
                <div>
                    до
                    <datepicker
                        v-model="to"
                        type="date"
                        placeholder=""
                        :show-time-header = "time">
                    </datepicker>
                </div>
            </div>

            <p @click="showAddOstatok()" class="text-center">
                <b>Начальный остаток:</b> {{ formatNum(parseInt(myostatok)) }}
            </p>
        </div>

        <div class="grid grid-cols-2">

            <!-- Левая колонка: Приходы -->
            <div class="border-r-2 mr-5 pr-5 section-income">
                <div class="flex justify-start items-center gap-5">
                    <h3 class="font-bold">Приход</h3>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded text-sm" @click="showPrihod()">
                      + Добавить
                    </button>
                </div>
                <br>

                <search-input v-model="income_sotrudnik" class="pr-6 pb-8 w-full lg:w-2/2" />

                <table class="w-full whitespace-nowrap mt-5">

                    <!-- ЧТО ЭТААААААА -->
                    <tr v-for="income in myincomes"
                        class="text-left  border-b border-gray-200"
                        v-if="
                            income.user.toLowerCase().includes(income_sotrudnik.toLowerCase())
                            && new Date(income.created_at) >= new Date(from)
                            && new Date(income.created_at) <= new Date(to).setDate(new Date(to).getDate()+2)
                        "
                        :title="income.description"
                    >
                        <td>{{new Date(income.created_at).toISOString().split('T')[0]}}</td>
                        <td>{{income.user}}</td>
                        <td>{{formatNum(income.sum.toFixed(0))}}</td>
                    </tr>
                </table>
                <br>
                <div><b>Итого:</b> {{ formatNum(kassaTotalIncome) }}</div>
            </div>

            <!-- Правая колонка: Расходы -->
            <div class="section-expense">
                <div class="flex justify-start items-center gap-5">
                    <h3 class="font-bold">Расход</h3>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded text-sm" @click="showRashod()">
                      + Добавить
                    </button>
                </div>
                <br>
                <search-input v-model="rashod_sotrudnik" class="pr-6 pb-8 w-full lg:w-2/2" />
                <table class="w-full whitespace-nowrap mt-5">

                    <!-- ЧТО ЭТААААААА -->
                    <tr v-for="expense in myexpenses" class="text-left  border-b border-gray-200"
                        v-if="(
                            expense.user.toLowerCase().includes(rashod_sotrudnik.toLowerCase())
                            || categories[expense.category_id-1].name.toLowerCase().includes(rashod_sotrudnik.toLowerCase()))
                            && new Date(expense.created_at) >= new Date(from)
                            && new Date(expense.created_at) <= new Date(to).setDate(new Date(to).getDate()+2
                        )"
                        :title="expense.description"
                    >
                        <td>{{new Date(expense.created_at).toISOString().split('T')[0]}}</td>
                        <td>{{formatNum(expense.sum)}}</td>
                         <td>{{categories[expense.category_id-1].name}}</td>
                        <td v-if="expense.user">{{expense.user}}</td>
                    </tr>
                </table>
                <br>
                <div class="flex justify-start gap-5">
                    <p><b>Итого:</b> {{ formatNum(kassaTotalExpense) }}</p>
                </div>
            </div>
        </div>
        <br>
        <div class="font-bold rounded text-center w-full">
            Остаток: {{ sumOstatok }}
        </div>
    </div>

    <!-- Вкладка: Зарплата  -->
    <content-salary v-if="salary"
        :workers="workers"
        :days="days"
        :dbMonth="month1"
    ></content-salary>

    <!-- Вкладка: Долги клиентов  -->
    <content-client-debts v-if="dolgi"
        :markets="markets"
        :branches="branches"
    ></content-client-debts>

    <!-- Вкладка: Долги (физ) -->
    <content-phys-debts v-if="dolgi_other"
        :other_debts="db_other_debts"
    ></content-phys-debts>

    <!-- Касса: добавление прихода -->
    <modal name="prihod">
        <form onsubmit="return false;">
        <div class="p-5">
            <text-input v-model="income_sum" class="pr-6 pb-8 w-full lg:w-1/2" label="Сумма" />
            <text-input  v-model="income_user" list="workers" class="pr-6 pb-8 w-full lg:w-1/2" label="Сотрудник" />
            <datalist id="workers">
                <option v-for="user in users">{{user.first_name}} {{user.last_name}}</option>
            </datalist>

            <text-input  v-model="income_description" class="pr-6 pb-8 w-full lg:w-1/2" label="Описание" />
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addIncome()">сохранить</button>
        </div>
        </form>
    </modal>

    <!-- Касса: добавление расхода -->
    <modal name="rashod">
        <form onsubmit="return false;">
        <div class="p-5">
            <select-input v-model="rashod_category" class="pr-6 pb-8 w-full lg:w-1/2" label="Категория">
                <option v-for="category in categories" :value="category.id">{{category.name}}</option>
            </select-input>

            <select-input v-if="rashod_category == 21" v-model="rashod_other_debt_id" class="pr-6 pb-8 w-1/2" label="ФИО">
                <option v-for="debt in db_other_debts" :key="debt.id" :value="debt.id">{{ debt.fio }}</option>
            </select-input>
            <text-input v-if="rashod_category == 21" v-model="rashod_other_debt_fio" class="pr-6 pb-8 w-full lg:w-1/2" label="ФИО (новый)" />

            <text-input v-if="rashod_category !== 21" list="workers" v-model="rashod_user" class="pr-6 pb-8 w-full lg:w-1/2" label="Сотрудник" />
            <datalist id="workers">
                <option v-for="user in work_users">{{user.first_name}} {{user.last_name}}</option>
            </datalist>

            <span class="flex pb-8" v-if="rashod_category == 1 && rashod_salary_to_pay">Зарплата к оплате: {{ formatNum(rashod_salary_to_pay) }} тг</span>

            <text-input v-if="sum_rashod" v-model="rashod_sum" class="pr-6 pb-8 w-full lg:w-1/2" label="Сумма" />


            <text-input  v-model="rashod_description" class="pr-6 pb-8 w-full lg:w-1/2" label="Описание" />
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addExpense()">сохранить</button>
        </div>
        </form>
    </modal>

    <!-- Касса: добавление начального остатка -->
    <modal name="ostatok">
        <div class="p-6">
            <p>Введите остаток</p>
            <br>
            <number-input v-model="ostatok" :error="form.errors.salary" class="pr-6 pb-8 w-full lg:w-1/2" label="Остаток" />
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addOstatok()">Сохранить</button>
        </div>
    </modal>

</div>
</template>

<script>
import Layout from '@/Shared/Layout'
import NumberInput from '@/Shared/NumberInput'
import TextInput from '@/Shared/TextInput'
import SearchInput from '@/Shared/SearchInput'
import SelectInput from '@/Shared/SelectInput'
import axios from 'axios'
import moment from "moment";
import LoadingButton from '@/Shared/LoadingButton'
import ContentSalary from '@/Pages/Profit/ContentSalary'
import ContentClientDebts from '@/Pages/Profit/ContentClientDebts'
import ContentPhysDebts from '@/Pages/Profit/ContentPhysDebts'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'

export default {
    metaInfo: {
        title: 'Зарплата / Расходы / Долги'
    },
    layout: Layout,
    components: {
        NumberInput,
        SelectInput,
        TextInput,
        SearchInput,
        LoadingButton,
        Datepicker,
        ContentSalary,
        ContentClientDebts,
        ContentPhysDebts
    },
    props: {
        owes2: Array,
        owes1: Array,
        owerealiztor: Array,
        oweshop1: Array,
        oweother: Array,
        categories: Array,
        incomes: Array,
        expenses: Array,
        milk_expenses: Array,
        users: Array,
        zarplata: Array,
        workers: Array,
        days: Array,
        saldo: Array,
        income: Number,
        left: Number,
        ostatok1: Number,
        month1: Object,
        markets: Array,
        branches: Array,
        db_other_debts: Array,
    },
    data() {
        return {
            myowes1: this.owes1,
            myostatok: this.ostatok1,
            ostatok: 0,
            priem_moloka:false,
            return_expenses:false,
            overage_expenses:true,
            mytotalreport: this.expenses,
            moment: moment,
            form: this.$inertia.form({}),
            totalreport:
            {
                kassa: 0,
                bank_amount: 0,
                freezer: 0,
                store: 0,
                owesrealization: 0,
                workers: 0,
                actives: 0,
                tetrapack: 0,
                fuel: 0,
                salary: 0
            },
            oweshop: this.oweshop1,
            magazine_dolg: null,
            dolgi_magazin: 0,
            time: true,
            from: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
            to: new Date(),
            salary:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            days1:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            income1:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            OSMS:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            IPN:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            OPV:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            total_income:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            initial_saldo:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            end_saldo: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            work_users: this.users,
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
            rashod_other_debt_id: null,
            rashod_other_debt_fio: null,
            rashod_salary_to_pay: null,
            myexpenses: this.expenses,
            myincomes: this.incomes,
            kassa: true,
            dolgi: false,
            dolgi_other: false,
            worker: '',
            worker_salary: 0,
            mysalary: this.zarplata,
            export_zarplate: this.export_zarplate,
            owes_other: false,
            salary: false,
            salary: false,
            rashod_index: 4,
            report_save: true,
            sum_rashod: true,
            myindex: 0,
            company_naks: [],
            market_branches: [],
        }
    },
    computed: {
        sumOstatok() {
            let sum = 0;
            sum += this.myincomes.reduce((acc, item) => acc + parseInt(item.sum), 0)
            sum += this.myostatok;
            sum -= this.myexpenses.reduce((acc, item) => acc + parseInt(item.sum), 0)
            return this.formatNum(sum)
        },
        kassaTotalIncome() {
            return this.myincomes.reduce((acc, item) => acc + parseInt(item.sum), 0)
        },
        kassaTotalExpense() {
            return this.myexpenses.reduce((acc, item) => acc + parseInt(item.sum), 0)
        }
    },
    created() {
        if(this.$page.props.auth.user.position_id == 6){
            this.kassa = false;
            this.dolgi = true;
            this.dolgi_other = false;
            this.salary = false;
        }
    },
    watch: {
        rashod_index:function(val){
            if(val == 1) {

                this.mytotalreport = this.milk_expenses;

            } else if(val == 2) {

                this.mytotalreport = [];
                this.expenses.forEach(element => {
                    if(element.category_id == 5){
                        this.mytotalreport.push(element);
                    }
                });
            } else if(val == 3) {

                this.mytotalreport = [];
                this.expenses.forEach(element => {
                    if(element.category_id != 5 && element.category_id != 4)
                        this.mytotalreport.push(element);

                });
            }
        },
        rashod_category: function(val){
            if(val == 4){
                this.sum_rashod = true;
                axios.get('get-work-users').then(response => {
                    this.work_users = response.data;
                });
            }else if(val == 2){
                this.sum_rashod = true;
                axios.get('get-workers').then(response => {
                    this.work_users = response.data;
                });
            }
            else if(val == 1){
                axios.get('get-workers').then(response => {
                    this.work_users = response.data;
                });
                this.rashod_sum = 0;
            }
            else{
                this.sum_rashod = true;
                this.work_users = this.users;
            }
        },
        rashod_user: function (val) {
            if (!val) {
                this.rashod_salary_to_pay = null;
                return;
            }

            axios.post('get-salary-to-pay', {worker: val}).then(response => {
                this.rashod_salary_to_pay = response.data;
            });
        }
    },
    methods: {
        changeButton() {
            this.report_save = true;
            axios.post('update-totalreport', this.totalreport);
            this.totalreport =
            {
                kassa: null,
                bank_amount: null,
                freezer: null,
                store: null,
                owesrealization: null,
                workers: null,
                actives: null,
                tetrapack: null,
                fuel: null,
                salary: null
            };
            alert('отчет обновлен');
        },
        updateTotalReport(report){
            this.report_save = false;
            this.totalreport = report;
        },
        saveTotalReport(){
            if(confirm("Сохранить отчет?")){
                axios.post('save-total-report', this.totalreport);
                alert('отчет сохранен');
            }
        },

        formatNum(num, type) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
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

        showSalary(){
            this.kassa = false;
            this.dolgi = false;
            this.dolgi_other = false;
            this.salary = true;
        },
        showKassa(){
            this.kassa = true;
            this.dolgi = false;
            this.dolgi_other = false;
            this.salary = false;
        },
        showOwes(){
            this.kassa = false;
            this.dolgi = true;
            this.dolgi_other = false;
            this.salary = false;
        },
        showOwesOther(){
            this.kassa = false;
            this.dolgi = false;
            this.dolgi_other = true;
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
            this.rashod_sum = null;
            this.rashod_category = null;
            this.rashod_user = null;

            this.$modal.show('rashod');
        },
        showPrihod(){
            this.income_sum = null;
            this.income_user = null;
            this.income_description = null;
            this.$modal.show('prihod');
        },
        addIncome(){
            axios.post('send-income',{
                sum: this.income_sum,
                description: this.income_description,
                user: this.income_user
            }).then(response => {
                this.myincomes.unshift(response.data);
                if(response.data.error){
                        alert(response.data.error);
                }else{
                    alert("приход добавлен");
                    location.reload();
                }
            });
            this.$modal.hide('prihod');
        },
        addExpense() {

            if(!confirm("Сохранить расход?")){
                return;
            }

            axios.post('send-expense',{
                sum: this.rashod_sum,
                category: this.rashod_category,
                description: this.rashod_description,
                other_debt_id: this.rashod_other_debt_id,
                other_debt_fio: this.rashod_other_debt_fio,
                user: this.rashod_user
            }).then(response => {
                if(response.data.error) {
                    alert(response.data.error);
                } else{
                    this.mysalary = response.data.zarplata;
                    this.myworkers = response.data.workers;
                    this.myexpenses.unshift(response.data.expense);

                    alert("расход добавлен");

                    location.reload();
                }
            });

            this.$modal.hide('rashod');
        },



        showAddOstatok(){
            this.$modal.show('ostatok');
        },
        addOstatok(){
            this.$modal.hide('ostatok');
            axios.post('add-ostatok',{ostatok: this.ostatok}).then(response => {
                alert(response.data.message);
                this.myostatok = response.data.ostatok;
            })
        },

        addTotalIncome(key,income){
            this.total_income[key] = income;
        },
        pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        },



    }
}
</script>
