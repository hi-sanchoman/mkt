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
        <br>
        <center><p @click="showAddOstatok()">Начальный остаток: {{formatNum(parseInt(myostatok)) }}</p></center>
        <br>
        <div class="grid grid-cols-2 ">



            <div class="border-r-2 mr-5 pr-5">
                <div class="flex justify-start gap-5">
                    <h3>Приход</h3>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showPrihod()">
                      Добавить
                    </button>
                </div>
                <br>

                <search-input v-model="income_sotrudnik" class="pr-6 pb-8 w-full lg:w-2/2" />

                <table class="w-full whitespace-nowrap mt-5">
                    <tr v-for="income in myincomes" class="text-left  border-b border-gray-200" v-if="income.user.toLowerCase().includes(income_sotrudnik.toLowerCase()) && new Date(income.created_at) >= new Date(from) && new Date(income.created_at) <= new Date(to).setDate(new Date(to).getDate()+2)" :title="income.description">
                        <td>{{new Date(income.created_at).toISOString().split('T')[0]}}</td>
                        <td>{{income.user}}</td>
                        <td>{{formatNum(income.sum.toFixed(0))}}</td>

                    </tr>
                </table>
                <br>
                <div>Итого: {{formatNum(myincomes.reduce((acc, item) => acc + parseInt(item.sum),0))}}</div>
            </div>
            <div>
                <div class="flex justify-start gap-5">
                    <h3>Расход</h3>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showRashod()">
                      Расход
                    </button>
                </div>
                <br>
                <search-input v-model="rashod_sotrudnik" class="pr-6 pb-8 w-full lg:w-2/2" />
                <table class="w-full whitespace-nowrap mt-5">
                    <tr v-for="expense in myexpenses" class="text-left  border-b border-gray-200" v-if="(expense.user.toLowerCase().includes(rashod_sotrudnik.toLowerCase()) || categories[expense.category_id-1].name.toLowerCase().includes(rashod_sotrudnik.toLowerCase()))  && new Date(expense.created_at) >= new Date(from) && new Date(expense.created_at) <= new Date(to).setDate(new Date(to).getDate()+2)" :title="expense.description">
                        <td>{{new Date(expense.created_at).toISOString().split('T')[0]}}</td>
                        <td>{{formatNum(expense.sum)}}</td>
                         <td>{{categories[expense.category_id-1].name}}</td>
                        <td v-if="expense.user">{{expense.user}}</td>

                    </tr>
                </table>
                <br>
                <div class="flex justify-start gap-5">
                    <p>Итого: {{formatNum(myexpenses.reduce((acc, item) => acc + parseInt(item.sum),0))}}</p>

                </div>
            </div>
        </div>
        <br>
        <div class="font-bold rounded text-center w-full">Остаток: {{formatNum((myincomes.reduce((acc, item) => acc + parseInt(item.sum),0)+myostatok)-(myexpenses.reduce((acc, item) => acc + parseInt(item.sum),0)))}}</div>
    </div>

    <!-- Вкладка: Зарплата -->
    <div v-if="salary" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class="flex justify-start gap-5">
            <!-- <h3>Зарплата</h3> -->
            <select-input v-model="salary_month1" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                <option v-for="month in months" :value="month.id">{{month.name}}</option>
            </select-input>

            <select-input v-model="salary_year1" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                <option v-for="year in years" >{{year}}</option>
            </select-input>
        </div>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showAddWorkerForm()">добавить сотрудника</button>-->
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
                    <td>{{sal.worker.name}}&nbsp;{{sal.worker.surname}}</td>
                    <td><input type="number" v-model="sal.worker.salary" name="" disabled></td>
                    <td>{{getNalog(sal.worker.salary).toFixed(0)}}</td>

                    <td>{{(sal.worker.salary)-getNalog(sal.worker.salary).toFixed(0)}}</td>

                    <td><input type="number" v-model="sal.days" name="" disabled></td>

                    <td>{{(((sal.worker.salary)-getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0)}}</td>

                    <td><input type="number" v-model="sal.initial_saldo" name="" disabled></td>
                    <td >{{ getPositiveEndSaldo(sal) }}</td>
                    <td >{{ getNegativeEndSaldo(sal) }}</td>
                </tr>


                <tr class="text-left pt-5 border-b border-gray-200">
                    <th>Итог</th>
                    <th>{{countOklad()}}</th>
                    <th>{{mysalary.reduce((acc, sal) => acc + parseInt(getNalog(sal.worker.salary).toFixed(0)),0)}}</th>
                    <th>{{mysalary.reduce((acc, sal) => acc + (sal.worker.salary)-parseInt(getNalog(sal.worker.salary).toFixed(0)),0)}}</th>
                    <th></th>
                    <th>{{mysalary.reduce((acc, sal) => acc + parseInt((((sal.worker.salary)-getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0)),0)}}</th>
                    <th>{{countSaldo()}}</th>
                    <th>{{countEndSaldo()}}</th>
                    <th>{{countNegativeSaldo()}}</th>

                </tr>
                <!--
                <tr v-for="sal in mysalary" class="pt-2">
                    <td>{{sal.worker.name}}&nbsp;{{sal.worker.surname}}</td>
                    <td><input type="number" v-model="sal.worker.salary" name="" disabled></td>
                    <td><input type="number" v-model="sal.days" name="" disabled></td>
                    <td><input type="number" v-model="sal.income" name="" disabled></td>
                    <td><input type="number" v-model="sal.OSMS" name="" disabled></td>
                    <td><input type="number" v-model="sal.IPN" name="" disabled></td>
                    <td><input type="number" v-model="sal.OPV" name="" disabled></td>
                    <td><!--<input type="number" v-model="sal.total_income" name="">{{sal.total_income-sal.initial_saldo}}</td>
                    <td><input type="number" v-model="sal.initial_saldo" name="" disabled></td>
                    <td><input type="number" v-model="sal.end_saldo" name="" disabled></td>
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
                </tr>-->

                <tr v-for="(worker,key) in myworkers" class="pt-2">
                    <td>{{worker.name}}&nbsp;{{worker.surname}}</td>
                    <td><input type="number" v-model="worker.salary" name=""></td>
                    <td>{{getNalog(worker.salary).toFixed(0)}}</td>

                    <td>{{(worker.salary).toFixed(0) - getNalog(worker.salary).toFixed(0)}}</td>
                    <td><input type="number" v-model="days[key]" onclick="select()" name=""></td>
                    <td>{{total_income[key] = (((worker.salary).toFixed(0) - getNalog(worker.salary).toFixed(0))/26*days[key]).toFixed(0)}}</td>
                    <!--<td><input type="number" v-model="OSMS[key]" name="">{{(worker.salary/26*days[key]*0.02).toFixed(0)}}</td>
                    <td><input onclick="select()" type="number" v-model="IPN[key]" name=""><div v-if="days[key] > 0">{{(worker.salary/26*days[key]-(42500+worker.salary/26*days[key]*0.02+worker.salary/26*days[key]*0.1)).toFixed(0)}}</div></td>
                    <td><input onclick="select()" type="number" v-model="OPV[key]" name="">{{(worker.salary/26*days[key]*0.1).toFixed(0)}}</td>-->
                    <!--<td><input type="number" v-model="total_income[key]" name=""><div v-if="days[key] > 0">

                        {{((worker.salary/26*days[key])-(worker.salary/26*days[key]*0.02)-(worker.salary/26*days[key]-(42500+worker.salary/26*days[key]*0.02+worker.salary/26*days[key]*0.1)-(worker.salary/26*days[key]*0.1))).toFixed(0)}}</div>-->
                    </td>
                    <td><input onclick="select()" type="number" name="" v-model="worker.saldo" disabled></td>
                    <td colspan="2">
                        <span v-if="days[key]">
                            {{(((worker.salary).toFixed(0) - getNalog(worker.salary).toFixed(0))/26*days[key] - worker.saldo).toFixed(0)}}
                        </span>
                    </td>
                </tr>

            </table>
        </div>
        <div class="mt-10 w-32 flex justify-start gap-5">
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center" @click="saveSalary()">сохранить</button>
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center" @click="endMonth()">завершить месяц</button>

        </div>

    </div>

    <!-- Вкладка: Долги клиентов  -->
    <div v-if="dolgi" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class="flex justify-start gap-5">
            <h3>Долги</h3>

            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded h-8" @click="showAddMoney()">+ Оплата</button>

        </div>
        <table class="w-full whitespace-nowrap mt-5">
            <tr class="text-left  border-b border-gray-200">
                <th>Магазин</th>
                <th>Начальный долг</th>
                <th>Продано</th>
                <th>Оплачено</th>
                <th>Остальной долг</th>
                <th>Реализатор</th>
            </tr>
            <!-- <tr v-for="(owe, key) in myowes1" class="text-left  border-b border-gray-200">
                <td style="cursor: pointer" @click="onCompanyClicked(owe, key)">{{owe.name}}</td>
                <td><input type="number" name="" v-model="oweshop[key].dolg_start" @change="changeDolgStart(oweshop[key])"></td>
                <td>{{owe.owe}}</td>
                <td><input type="number" name="" v-model="oweshop[key].paid" disabled></td>
                <td>{{parseInt(oweshop[key].dolg_start) + parseInt(owe.owe) - parseInt(oweshop[key].paid)}}</td>
                <td><div v-for="r in owe.realizator">{{r.first_name}}</div></td>
            </tr> -->
            <tr v-for="(market, key) in markets" :key="market.id" class="text-left  border-b border-gray-200">
                <td style="cursor: pointer" @click="onCompanyClicked(market, key)">{{market.name}}</td>
                <td><input type="number" name="" v-model="market.debt_start" @change="changeDebtStart(market)"></td>
                <td>{{ soldTotal(market) }}</td>
                <td>{{ paidTotal(market) }}</td>
                <td>{{ (market.debt_start + soldTotal(market) - paidTotal(market)).toFixed(2) }}</td>
                <td><div v-for="(r, key2) in getRealizators(market)" :key="key2">{{ r }}</div></td>
            </tr>
        </table>
          <div class="mt-10 flex justify-start gap-5">

        </div>
    </div>

    <!-- Вкладка: Долги (физ) -->
    <div v-if="dolgi_other" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class="flex flex-col h-full">


        <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
            <div class="flex justify-start gap-5">
                <h3>Долги</h3>
                <button @click="showPayOtherDebt()"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded h-8" >
                    + Оплата
                </button>
            </div>

            <table class="w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    <th>ФИО</th>
                    <th>Начальный долг</th>
                    <th>Оплачено</th>
                    <th>Сумма долга на сегодня</th>
                </tr>
                <tr v-for="debt in other_debts" class="text-left  border-b border-gray-200">
                    <td style="cursor: pointer" @click="onOtherDebtClicked(debt)">{{debt.fio}}</td>
                    <td>{{formatNum(debt.debt)}}</td>
                    <td>{{ formatNum(debt.payments.reduce((carry, item) => carry + item.amount, 0)) }}</td>
                    <td>{{ formatNum(debt.debt - debt.payments.reduce((carry, item) => carry + item.amount, 0)) }}</td>
                </tr>
            </table>

        </div>

        </div>
    </div>

    <!-- Оплата долга (физ) другого -->
    <modal name="pay-other-debt">
        <div class="p-5">
            <select-input v-model="other_debt_id" class="pr-6 pb-8 w-full lg:w-1/1" label="ФИО">
                <option v-for="debt in other_debts" :key="debt.id" :value="debt.id">{{ debt.fio }}</option>
            </select-input>
            <text-input  v-model="other_debt_amount" class="pr-6 pb-8 w-full lg:w-1/1" label="Сумма" />
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="payOtherDebt()">Оплатить долг</button>
        </div>
    </modal>

    <!-- Оплата долга клиентом -->
    <modal name="add-money">
        <div class="p-5">
            <select-input v-model="debt_branch_id" class="pr-6 pb-8 w-full lg:w-1/1" label="Магазин">
                <option v-for="branch in branches" :key="branch.id" :value="branch.id">{{ branch.name }}</option>
            </select-input>
            <text-input  v-model="debt_amount" class="pr-6 pb-8 w-full lg:w-1/1" label="Сумма" />
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="payDebt()">Оплатить долг</button>
        </div>
    </modal>

    <!-- Модалка для добавления расхода -->
    <modal name="rashod">
        <form onsubmit="return false;">
        <div class="p-5">
            <select-input v-model="rashod_category" class="pr-6 pb-8 w-full lg:w-1/2" label="Категория">
                <option v-for="category in categories" :value="category.id">{{category.name}}</option>
            </select-input>

            <select-input v-if="rashod_category == 21" v-model="rashod_other_debt_id" class="pr-6 pb-8 w-1/2" label="ФИО">
                <option v-for="debt in other_debts" :key="debt.id" :value="debt.id">{{ debt.fio }}</option>
            </select-input>
            <text-input @click="select" v-if="rashod_category == 21" v-model="rashod_other_debt_fio" class="pr-6 pb-8 w-full lg:w-1/2" label="ФИО (новый)" />

            <text-input @click="select" v-if="rashod_category !== 21" list="workers" v-model="rashod_user" class="pr-6 pb-8 w-full lg:w-1/2" label="Сотрудник" />
            <datalist id="workers">
                <option v-for="user in work_users">{{user.first_name}} {{user.last_name}}</option>
            </datalist>

            <span class="flex pb-8" v-if="rashod_category == 1 && rashod_salary_to_pay">Зарплата к оплате: {{ formatNum(rashod_salary_to_pay) }} тг</span>

            <text-input @click="select" v-if="sum_rashod" v-model="rashod_sum" class="pr-6 pb-8 w-full lg:w-1/2" label="Сумма" />


            <text-input @click="select" v-model="rashod_description" class="pr-6 pb-8 w-full lg:w-1/2" label="Описание" />
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addExpense()">сохранить</button>
        </div>
        </form>
    </modal>

     <!-- Модалка для добавления прихода -->
    <modal name="prihod">
        <form onsubmit="return false;">
        <div class="p-5">
            <text-input @click="select" v-model="income_sum" class="pr-6 pb-8 w-full lg:w-1/2" label="Сумма" />
            <text-input @click="select" v-model="income_user" list="workers" class="pr-6 pb-8 w-full lg:w-1/2" label="Сотрудник" />
            <datalist id="workers">
                <option v-for="user in users">{{user.first_name}} {{user.last_name}}</option>
            </datalist>

            <text-input @click="select" v-model="income_description" class="pr-6 pb-8 w-full lg:w-1/2" label="Описание" />
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addIncome()">сохранить</button>
        </div>
        </form>
    </modal>

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

    <modal name="ostatok">
        <div class="p-6">
            <p>Введите остаток</p>
            <br>
            <number-input v-model="ostatok" :error="form.errors.salary" class="pr-6 pb-8 w-full lg:w-1/2" label="Остаток" />
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addOstatok()">Сохранить</button>
        </div>
    </modal>

    <modal name="other_debt_history" v-if="selected_other_debt">
        <div class="px-6 py-6">
            История долга

            <table class="w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    <th>ФИО</th>
                    <th>Оплачено</th>
                    <th>Дата</th>
                </tr>
                <tr v-for="payment in selected_other_debt.payments" :key="payment.id" class="text-left  border-b border-gray-200">
                    <td>{{ selected_other_debt.fio }}</td>
                    <td>{{ formatNum(payment.amount) }}</td>
                    <td>{{ moment(new Date(payment.created_at)).format('YYYY-MM-DD HH:mm') }}</td>
                </tr>
            </table>
        </div>
    </modal>

    <modal name="company_branches">
        <div class="px-6 py-6">
            Филиалы магазина

            <!-- <div v-for="nak in company_naks">
                <a class='w-full border-3 mt-5 shadow-lg flex p-4' :href="'/blank/' + nak.id"><p>Накладная №{{nak.id}} от {{ moment(nak.created_at).format("DD-MM-YYYY") }}</p></a>

            </div> -->
            <table class="w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    <th>Название</th>
                    <th>Продано</th>
                    <th>Оплачено</th>
                    <th>Остальной долг</th>
                    <!-- <th>Реализатор</th>                -->
                </tr>
                <!-- <tr v-for="(owe, key) in myowes1" class="text-left  border-b border-gray-200">
                    <td style="cursor: pointer" @click="onCompanyClicked(owe, key)">{{owe.name}}</td>
                    <td><input type="number" name="" v-model="oweshop[key].dolg_start" @change="changeDolgStart(oweshop[key])"></td>
                    <td>{{owe.owe}}</td>
                    <td><input type="number" name="" v-model="oweshop[key].paid" disabled></td>
                    <td>{{parseInt(oweshop[key].dolg_start) + parseInt(owe.owe) - parseInt(oweshop[key].paid)}}</td>
                    <td><div v-for="r in owe.realizator">{{r.first_name}}</div></td>
                </tr> -->
                <tr v-for="branch in market_branches" :key="branch.id" class="text-left  border-b border-gray-200">
                    <td>{{ branch.name }}</td>
                    <td>{{ branch.sold }}</td>
                    <td>{{ branch.paid }}</td>
                    <td>{{ (branch.sold - branch.paid).toFixed(2) }}</td>
                    <!-- <td><div v-for="r in getRealizators(market)" :key="r.id">{{ r.first_name }}</div></td> -->
                </tr>
            </table>
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
import Vue from "vue";
import moment from "moment";
import LoadingButton from '@/Shared/LoadingButton'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'

export default {
    metaInfo: {
        title: 'Зарплата'
    },
    layout: Layout,
    components: {
        NumberInput,
        SelectInput,
        TextInput,
        SearchInput,
        LoadingButton,
        Datepicker
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
        total_expenses: Array,
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
            other_debts: this.db_other_debts,
            selected_other_debt: null,
            debts_other: this.debts_other,
            myowes1: this.owes1,
            myostatok: this.ostatok1,
            ostatok: 0,
            priem_moloka:false,
            return_expenses:false,
            overage_expenses:true,
            mytotalreport: this.expenses,
            moment: moment,
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
            years:[2022,2021,2020,2019,2018],
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
            debt_branch_id: 0,
            debt_amount: 0,

            other_debt_id: 0,
            other_debt_amount: 0,
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
        rashod_index:function(val){

            if(val == 1){


                this.mytotalreport = this.milk_expenses;
                /*this.expenses.forEach(element => {
                    if(element.category_id == 4 && element.kassa == 0){
                        this.mytotalreport.push(element);
                    }
                });*/
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
        rashod_category:function(val){
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
                // this.sum_rashod = false;
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
        changeDebtStart(market){
            axios.post('dolg-start',{id: market.id, amount: market.debt_start});
        },
        paidTotal(market) {
            let total = 0;
            for (let i = 0; i < market.branches.length; i++) {
                total += market.branches[i]['paid'];
            }
            return total;
        },
        soldTotal(market) {
            let total = 0;
            for (let i = 0; i < market.branches.length; i++) {
                total += market.branches[i]['sold'];
            }
            return total;
        },
        getRealizators(market) {
            const users = new Set();
            market.branches.map((branch) => {
                branch.realizators.map((realizator) => {
                    users.add(realizator.first_name);
                })
            })
            return users;
        },
        formatNum(num, type) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        },
        payDebt() {
            axios.post("pay-owe",{branch_id: this.debt_branch_id, amount: this.debt_amount}).then((response) => {
                this.$modal.hide('add-money');
                alert('долг оплачен!');
                location.reload();
            });
        },
        payOtherDebt() {
            axios.post("pay-other-debt",{id: this.other_debt_id, amount: this.other_debt_amount}).then((response) => {
                this.$modal.hide('pay-other-debt');
                alert('долг оплачен!');
                location.reload();
            });

        },
        showPayOtherDebt(){
            this.$modal.show('pay-other-debt');
        },
        showAddMoney(){
            this.$modal.show('add-money');
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
        addExpense(){
            if(confirm("Сохранить расход?")){
                axios.post('send-expense',{
                    sum: this.rashod_sum,
                    category: this.rashod_category,
                    description: this.rashod_description,
                    other_debt_id: this.rashod_other_debt_id,
                    other_debt_fio: this.rashod_other_debt_fio,
                    user: this.rashod_user
                }).then(response => {
                        if(response.data.error){
                            alert(response.data.error);
                        }else{
                            this.mysalary = response.data.zarplata;
                            this.myworkers = response.data.workers;
                            this.myexpenses.unshift(response.data.expense);

                            alert("расход добавлен");

                            location.reload();
                        }
                });
                this.$modal.hide('rashod');
            }

        },

        saveSalary(){
            if(confirm("Сохранить зарплату?")){
                axios.post('save-salary',{
                workers:this.myworkers,
                salary: this.salary,
                days: this.days,
                total_incomes: this.total_income
    /*            income: this.income1,
                OSMS: this.OSMS,
                IPN: this.IPN,
                OPV: this.OPV,
                total_income: this.total_income,
                end_saldo:  this.end_saldo*/
                }).then(response => {
                    console.log(response.data);

                    this.mysalary = response.data.zarplata;
                    this.myworkers = response.data.workers;

                    alert(response.data.message);
                    location.reload();
                });
            }
        },
        endMonth(){
            if(this.month1.month == new Date().getMonth()+1) {
                alert('Месяц еще не закончен');
                return;
            }

            if(confirm("Вы точно хотите завершить месяц?")) {
                axios.get('end-month').then(response => {
                    alert(response.data);
                    location.reload();
                });
            }
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
        getOSMS(salary){
            return salary*0.02;
        },
        getOPV(salary){
            return salary*0.1;
        },
        getIPN(salary){
            let OSMS = this.getOSMS(salary);
            let OPV = this.getOPV(salary);
            return salary-OSMS-OPV;
        },
        getNalog(salary){
            var osms = salary * 0.02;
            var opv = salary * 0.1;
            var ipn = (salary - 42882 - opv - osms) * 0.1;

            return osms + opv + ipn;

            //return (salary/50) + (salary - 42500 - (salary/10) - (salary/50))*0.1 + (salary/10);
        },
        addTotalIncome(key,income){
            this.total_income[key] = income;
        },
        getOwesByMonth(){
            alert(this.salary_month+' '+this.salary_year);
        },
        pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
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
        onCompanyClicked(market, key) {
            // console.log("clicked", owe, key);

            this.market_branches = market.branches;
            this.$modal.show('company_branches');

            // axios.get('get-company-naks', {company: owe.name}).then(response => {
            //     this.company_naks = response.data;

            //     this.$modal.show('company_naks');
            // });
        },
        onOtherDebtClicked(debt) {
            this.selected_other_debt = debt;
            this.$modal.show('other_debt_history');
        },
    }
}
</script>
