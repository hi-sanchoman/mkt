<template>
<div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto">

    <!-- Первый ряд: выбор даты и добавить сотрудника -->
    <div class="flex justify-start gap-5">

        <div class="mb-6 md:mb-0 flex justify-start">
            <div class="relative mr-4">
                <div class="flex space-x-2 items-center justify-center border p-2">
                    <button @click="prevMonth" class="hover:bg-white hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>
                    </button>
                    <span class="w-40 text-center">{{ getMonthName(salary_month) }} {{ salary_year }}</span>
                    <button @click="nextMonth" class="hover:bg-white hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-4"
            @click="$modal.show('addWorker')">
            Добавить сотрудника
        </button>
    </div>

    <!-- Таблица -->
    <div class="overflow-y-auto h-80">
        <table class="w-full whitespace-nowrap mt-5 tableizer-table mytable">
            <tr class="text-left  border-b border-gray-200">
                <th class="sticky top-0">Сотрудник</th>
                <th class="sticky top-0">Оклад</th>
                <th class="sticky top-0">Налог</th>
                <th class="sticky top-0">На руки</th>
                <th class="sticky top-0">Дни</th>
                <th class="sticky top-0">К оплате</th>
                <th class="sticky top-0">Начальное сальдо</th>
                <th class="sticky top-0" colspan="2">Конечное сальдо</th>
            </tr>

            <tr v-for="sal in mysalary" class="pt-2">
                <td>{{ sal.worker.name }} {{ sal.worker.surname }}</td>
                <td><input type="number" v-model="sal.worker.salary" name="" disabled></td>
                <td>{{ getNalog(sal.worker.salary).toFixed(0) }}</td>
                <td>{{(sal.worker.salary)-getNalog(sal.worker.salary).toFixed(0)}}</td>
                <td><input type="number" v-model="sal.days" name="" disabled></td>
                <td>
                    {{
                    (((sal.worker.salary)-getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0)
                    }}
                </td>
                <td><input type="number" v-model="sal.initial_saldo" name="" disabled></td>
                <td >{{ getPositiveEndSaldo(sal) }}</td>
                <td >{{ getNegativeEndSaldo(sal) }}</td>
            </tr>

            <tr class="text-left pt-5 border-b border-gray-200">
                <th>Итог</th>
                <th>{{ countOklad() }}</th>
                <th>{{ mysalary.reduce((acc, sal) => acc + parseInt(getNalog(sal.worker.salary).toFixed(0)),0) }}</th>
                <th>{{ mysalary.reduce((acc, sal) => acc + (sal.worker.salary)-parseInt(getNalog(sal.worker.salary).toFixed(0)),0) }}</th>
                <th></th>
                <th>
                    {{
                        mysalary.reduce((acc, sal) => acc + parseInt((((sal.worker.salary) - getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0)),0)
                    }}
                </th>
                <th>{{ countSaldo() }}</th>
                <th>{{ countEndSaldo() }}</th>
                <th>{{ countNegativeSaldo() }}</th>
            </tr>

            <tr v-for="(worker,key) in myworkers" class="pt-2">
                <td>{{ worker.name }} {{ worker.surname }}</td>
                <td><input type="number" v-model="worker.salary" name=""></td>
                <td>{{ getNalog(worker.salary).toFixed(0) }}</td>
                <td>{{ (worker.salary).toFixed(0) - getNalog(worker.salary).toFixed(0) }}</td>
                <td><input type="number" v-model="days[key]" name=""></td>
                <td>
                    {{
                        total_income[key] = (((worker.salary).toFixed(0) - getNalog(worker.salary).toFixed(0))/26*days[key]).toFixed(0)
                    }}
                </td>
                <td><input type="number" name="" v-model="worker.saldo" disabled></td>
                <td colspan="2">
                    <span v-if="days[key]">
                        {{
                            (((worker.salary).toFixed(0) - getNalog(worker.salary).toFixed(0))/26*days[key] - worker.saldo).toFixed(0)
                        }}
                    </span>
                </td>
            </tr>

        </table>
    </div>

    <!-- Последний ряд с кнопками Сохранить и Завершить месяц -->
    <div class="mt-10 w-32 flex justify-start gap-5">
        <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center"
            @click="saveSalary()">
            Сохранить
        </button>
        <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center"
            @click="endMonth()">
            Завершить месяц
        </button>
    </div>

    <!-- Зарплата: Добавление сотрудника  -->
    <modal name="addWorker">
        <form @submit.prevent="addWorker">
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                <text-input v-model="form.first_name" :error="form.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Имя" />
                <text-input v-model="form.last_name" :error="form.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Фамилия" />
                <text-input v-model="form.salary" :error="form.errors.salary" class="pr-6 pb-8 w-full lg:w-1/2" label="Оклад" />
            </div>
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                <loading-button :loading="form.processing" class="btn-indigo" type="submit">Добавить сотрудника</loading-button>
            </div>
        </form>
    </modal>

</div>
</template>
<script>
import SelectInput from '@/Shared/SelectInput'
import axios from 'axios'

export default {
    name: 'ContentSalary',
    components: {
        SelectInput,
    },
    props: {
        workers: Array,
        days: Array,
        dbMonth: Object
    },
    data() {
        return {
            salary_month: new Date().getMonth() + 1,
            salary_year: new Date().getFullYear(),
            myworkers: [],
            mysalary: [],
            salary:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            total_income:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            // date picker
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
            // add worker
            form: this.$inertia.form({
                first_name: null,
                last_name: null,
                salary: null
            }),
        }
    },
    created() {
        this.getSalaryMonth(this.salary_month, this.salary_year);
    },
    methods: {
        addWorker(){
            this.form.post(this.route('add-worker'));
            this.$modal.hide('addWorker');
        },

        getSalaryMonth(month, year) {
            axios.post('get-salary-month', {
                month: month,
                year: year
            }).then(response => {
                this.mysalary = response.data;
                let currentMonth = month - 1 == new Date().getMonth()
                let currentYear = year == new Date().getFullYear()

                this.myworkers = currentMonth && currentYear
                    ? this.workers
                    : []
            });
        },
        saveSalary() {
            if(!confirm("Сохранить зарплату?")) return;

            axios.post('save-salary',{
                workers: this.myworkers,
                days: this.days,
                total_incomes: this.total_income
            }).then(response => {

                this.mysalary = response.data.zarplata;
                this.myworkers = response.data.workers;

                alert(response.data.message);
                location.reload();
            });
        },

        endMonth() {
            if(this.dbMonth.month == new Date().getMonth() + 1) {
                alert('Месяц еще не закончен');
                return;
            }

            if(!confirm("Вы точно хотите завершить месяц?")) {
                return;
            }

            axios.get('end-month').then(response => {
                alert(response.data);
                location.reload();
            });
        },

        getNalog(salary) {
            var osms = salary * 0.02;
            var opv = salary * 0.1;
            var ipn = (salary - 42882 - opv - osms) * 0.1;

            return osms + opv + ipn;
        },

        getPositiveEndSaldo(sal) {
            var saldo = (((sal.worker.salary)-this.getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0) - sal.initial_saldo;
            if (saldo >= 0) return saldo;

            return 0;
        },

        getNegativeEndSaldo(sal) {
            var saldo = (((sal.worker.salary)-this.getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0) - sal.initial_saldo;

            if (saldo < 0) return saldo;

            return 0;
        },

        countOklad() {
            var oklad = 0;
            this.mysalary.forEach(function(r,a){
                oklad += parseInt(r.worker.salary);
            });
            return oklad;
        },

        countSaldo() {
            var saldo = 0;
            this.mysalary.forEach(function(r,a){
                saldo += parseInt(r.initial_saldo);
            });
            return saldo;
        },

        countEndSaldo() {
            var oklad = 0;
            this.mysalary.forEach(function(r,a){
                if(parseInt(r.total_income)-parseInt(r.initial_saldo) >= 0)
                    oklad += parseInt(r.total_income)-parseInt(r.initial_saldo);
            });
            return oklad;
        },

        countNegativeSaldo(){
            var oklad = 0;
            this.mysalary.forEach(function(r,a){
                if(parseInt(r.end_saldo) < 0)
                    oklad += parseInt(r.end_saldo);
            });
            return oklad;
        },

        // datepicker
        getMonthName(month) {
            return this.months.find(m => m.id == month).name;
        },
        nextMonth() {
            this.salary_month += 1

            if (this.salary_month > 12) {
                this.salary_month = 1;
                this.salary_year += 1;
            }

            this.getSalaryMonth(this.salary_month, this.salary_year)
        },
        prevMonth() {
            this.salary_month -= 1

            if (this.salary_month <= 0) {
                this.salary_month = 12;
                this.salary_year -= 1;
            }

            this.getSalaryMonth(this.salary_month, this.salary_year)
        }
    }
}
</script>
