<template>
    <div class="flex flex-col h-full">

        <!-- Переключатель вкладок в ПК версии, в мобильной меню слева -->
        <div class="panel hidden sm:flex justify-start gap-5 mb-4">
            <button v-if="userIsNot([ACCOUNTANT])"
                :class="sales ? 'bg-green-500 text-white font-bold py-2 px-4 rounded' : 'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
                @click="showSales()">
                Заявки
            </button>
            <button v-if="userIs([DIRECTOR, TECHNICIAN])"
                :class="itog ? 'bg-green-500 text-white font-bold py-2 px-4 rounded' : 'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
                @click="showItog()">
                Итог заявок
            </button>
            <button v-if="userIsNot([TECHNICIAN, FACTORY_WORKER])"
                :class="real ? 'bg-green-500 text-white font-bold py-2 px-4 rounded' : 'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
                @click="showRealizators()">
                Реализаторы
            </button>
            <button v-if="userIsNot([TECHNICIAN, FACTORY_WORKER])"
                :class="report ? 'bg-green-500 text-white font-bold py-2 px-4 rounded' : 'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
                @click="showReport()">
                Авансовый отчет
            </button>
            <button
                v-if="userIsNot([TECHNICIAN, WORKER, FACTORY_WORKER, ACCOUNTANT, FACTORY_MANAGER])"
                :class="report3 ? 'bg-green-500 text-white font-bold py-2 px-4 rounded' : 'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
                @click="showReport4()">
                Отчет продаж
            </button>
            <button v-if="userIs([ACCOUNTANT])"
                :class="naks ? 'bg-green-500 text-white font-bold py-2 px-4 rounded' : 'bg-blue-500 text-white font-bold py-2 px-4 rounded'"
                @click="showNaks()">
                Накладные
            </button>
            <div v-if="alert > 0 && userIsNot([ACCOUNTANT])"
                class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 cursor-pointer"
                role="alert" @click="closeAlert()">
                <p class="font-bold">Новых заявок</p>
                <p class="text-sm">{{ alert }}</p>
            </div>
            <div v-if="alert1 > 0 && userIs([ACCOUNTANT])"
                class="bg-green-100 border-t border-b border-green-500 text-green-700 px-4 py-3 cursor-pointer"
                role="alert" @click="closeAlert1()">
                <p class="font-bold">Новая реализация</p>
                <p class="text-sm">{{ alert1 }}</p>
            </div>
            <div v-if="alert_dop > 0 && userIsNot([ACCOUNTANT])"
                class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 cursor-pointer"
                role="alert" @click="closeAlertDop()">
                <p class="font-bold">Доп. заявок</p>
                <p class="text-sm">{{ alert_dop }}</p>
            </div>

        </div>

        <!-- Контент -->
        <div class="w-full bg-white rounded-2xl  h-auto p-6 pt-6 hidden sm:block overflow-x-auto">

            <div class="flex gap-5 items-center">
                <!-- Фильтр для вкладки Реализаторы -->
                <datepicker
                    v-if="real"
                    v-model="selected_period"
                    type="date"
                    placeholder=""
                    :show-time-header = "time"
                    range
                    class="border"
                    @change="setPeriod()">
                </datepicker>

                <!-- Переключатель месяца на Итоги заявок -->
                <month-picker
                    v-if="itog"
                    class="mb-3 mt-3"
                    :month="monthOfItog"
                    :year="yearOfItog"
                    @monthChanged="changeItogMonth"
                ></month-picker>

                <!-- Дурацкий loader -->
                <img v-if="isLoading" class="w-8 h-8 bg-white" src="/img/loading.gif" alt="">
            </div>

            <!-- Вкладка: Авансовый ответ -->
            <div class="w-full bg-white h-auto pb-2 overflow-y-auto hidden" :class="report ? 'sm:block' : ''">
                <avans-report
                    :realizators="realizators"
                    :pivotPrices="pivotPrices"
                    ref="avansReport" />
            </div>

            <!-- Вкладка: Отчет продаж -->
            <div v-if="report3" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 hidden sm:block">
                <div class="flex space-x-10 mb-6">
                    <div class="flex space-x-2 items-center justify-center border p-2">
                        <button v-on:click="prevMonth" class="hover:bg-white hover:text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                            </svg>
                        </button>
                        <span class="w-40 text-center">{{getMonthName(this.month)}} {{ this.year }}</span>
                        <button v-on:click="nextMonth" class="hover:bg-white hover:text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                            </svg>
                        </button>
                    </div>

                    <select
                        class="block appearance-none w-1/4 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-state" v-model="mysold_realizator" @change="showRealizatorSold()">
                        <option value="all">Все реализаторы</option>
                        <option v-for="item in realizators" :value="item">{{ item.first_name }}</option>
                    </select>
                </div>

                <table class="tableizer-table w-full">
                    <thead>
                        <tr class="tableizer-firstrow">
                            <th>Наименование товара</th>
                            <th>Количество продаж</th>
                            <th>Количество брак</th>
                            <th>Цена завод</th>
                            <th>Сумма продаж</th>
                            <th>Сумма брак</th>
                            <th>Процент брак</th>
                        </tr>
                        <tr v-for="item in mysold1">
                            <td>{{ item.assortment }}</td>
                            <td>{{ formatNum(item.sold_amount.toFixed(2)) }}</td>
                            <td>{{ formatNum(item.defect_amount.toFixed(2)) }}</td>
                            <td>{{ formatNum(item.price_zavod.toFixed(2)) }}</td>
                            <td>{{ formatNum((item.sold_amount * item.price_zavod).toFixed(2)) }}</td>
                            <td>{{ formatNum((item.defect_amount * item.price_zavod).toFixed(2)) }}</td>
                            <td>{{ formatNum(item.sold_amount ? (item.defect_amount / item.sold_amount * 100).toFixed(2) : 0) }}%</td>
                        </tr>
                        <tr>
                            <td>Итого:</td>
                            <td>{{ formatNum(mysold1.reduce((acc, item) => acc + parseInt(item.sold_amount), 0).toFixed(2)) }}</td>
                            <td>{{ formatNum(mysold1.reduce((acc, item) => acc + parseInt(item.defect_amount), 0).toFixed(2)) }}</td>
                            <td></td>
                            <td>
                                {{ formatNum(mysold1.reduce((acc, item) => acc + item.sold_amount * item.price_zavod, 0).toFixed(2)) }}</td>
                            <td>{{ formatNum(mysold1.reduce((acc, item) => acc + parseInt(item.defect_amount * item.price_zavod), 0).toFixed(2)) }}</td>
                            <td v-if="mysold1">
                                {{
                                    formatNum((mysold1.reduce((acc, item) => {
                                        if (item.sold_amount) {
                                            return acc + item.defect_amount / item.sold_amount * 100;
                                        }
                                        return acc;
                                    }, 0) / mysold1.length).toFixed(2))
                                }}%
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- Deprecated -->
            <div v-if="report2" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 hidden sm:block">

                <table class="tableizer-table w-full">
                    <thead>
                        <tr class="tableizer-firstrow">
                            <th>Наименование</th>
                            <th>Сумма реализации</th>
                            <th>Сумма брака/обмена</th>
                            <th>Процент</th>
                            <th>Выручка</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="(item, index) in defects_report">
                            <td>{{ item.assortment }}</td>
                            <td>{{ item.sum.toFixed(2) }}</td>
                            <td>{{ item.defectSum.toFixed(2) }}</td>
                            <td>{{ item.percent.toFixed(2) }}</td>
                            <td>{{ item.income.toFixed(2) }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Вкладка: Итог заявок -->
            <div v-if="itog" class="w-full bg-white rounded-lg  h-auto overflow-auto sm:block">
                <table class="w-full whitespace-nowrap text-xs">
                    <thead class="bg-white custom1">
                        <tr class="text-left font-bold border-b border-gray-200 bg-white">

                            <th class="pl-3 pt-4 pb-4 bg-white  w-72 sticky" style="left: 0">
                                <p class="font-bold text-center w-48">Наименование</p>
                            </th>
                            <td class="px-6 pt-4 pb-4 top-0 bg-white " v-for="(n, i) in parseInt(days)">
                                <p class="font-bold text-center ">{{ i + 1 }} {{ monthes[monthOfItog] }}</p>
                            </td>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(item, j) in assortment" :key="item.id">
                            <th class="sticky pl-6 pt-4 pb-4 text-left left-0 bg-white w-48">{{ item.type }}</th>

                            <td v-for="(n, i) in parseInt(days)" class="px-6 pt-4 pb-4" :key="i">
                                <p v-if="itogData[n - 1]">{{ itogData[n - 1][item.id]['number'] }}</p>
                                <p v-else>0</p>
                            </td>

                            <td>
                                <p class="pl-5">{{ itogMonth[item.id] }}</p>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

            <!-- Вкладка: Накладные -->
            <div v-if="naks" class="px-6 py-6">
                Накладные

                <div v-for="nak in nakladnoe">
                    <a :class="nak.consegnation == 2 && nak.paid == 0 ? 'w-full border-3 mt-5 shadow-lg flex p-4 text-white bg-red-700' : 'w-full border-3 mt-5 shadow-lg flex p-4'"
                        :href="'/blank/' + nak.id">
                        <p>Накладная от {{ moment(nak.created_at).format("DD-MM-YYYY") }} №{{ nak.id }}</p>
                    </a>
                </div>
            </div>

            <!-- Вкладка: Заявки -->
            <table v-if="sales" class="w-full whitespace-nowrap">

                <tr class="text-left font-bold border-b border-gray-200">
                    <th class="text-left">#</th>
                    <th>Ассортимент</th>
                    <th v-for="item in myorder" style="width: 50px">

                        <div class="font-medium pr-5" :class="showReadyInput ? 'text' : 'text-sm'">{{ item.realizator.first_name }}</div>
                        <div class="font-normal pr-5" :class="showReadyInput ? 'text-sm' : 'text-sm'">{{ showReadyInput ? moment(item.real.created_at).format("DD-MM-YYYY HH:mm") : moment(item.real.created_at).format("DD.MM HH:mm") }}</div>
                        <div class="flex">
                            <div class="font-bold w-1/2 text-xs">Заявка</div>
                            <div class="font-bold w-1/2 text-xs" v-if="showReadyInput">Г. П. ({{ parseInt(item.percent) }}%)</div>
                        </div>
                        
                    </th>
                    <th>Итог</th>
                    <th>Запас</th>
                </tr>
                <tr v-for="(item, key) in assortment" class="border-b h-10">
                    <td class="w-8">{{ key + 1 }}</td>
                    <td class="w-64 border-r-4">{{ item.type }}</td>
                    <td class="border-r-4" :class="showReadyInput ? 'w-40' : 'w-20' " v-for="(i, key2) in myorder">
                        
                        <div class="flex justify-between items-center">
                            <div class="font-normal w-1/2 pl-2">{{ i.assortment[key].order_amount }}</div>
                            <div class="font-normal w-1/2" v-if="i.assortment[key].order_amount && showReadyInput">
                                <input type="number" v-model="i.assortment[key].amount[0].amount" v-on:keyup.enter="onEnter"
                                    onclick="select()"
                                    class="shadow-xs appearance-none hidden-arrows border rounded w-full py-2 px-2  text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="0">
                            </div>
                        </div>
                      
                    </td>
                    <td>
                        <p class="pl-5">{{ calculateTotal(key) }}</p>
                    </td>
                    <td>
                        <p class="pl-5">{{ calculateExtra(key) }}</p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td v-for="(i, key2) in order">
                        <div class='flex flex-col space-y-1'>
                            <button v-if="i.status != 5 && i.status != 3 && showReadyInput" v-bind:id="'save_' + key2"
                                class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center text-sm"
                                @click="saveOrder(i, key2)">Изготовлено
                            </button>
                            <button v-if="i.status != 5 && i.status != 3 && showReadyInput" v-bind:id="'save_' + key2"
                                class="bg-red-500 text-white font-bold py-2 px-4 rounded text-center text-sm"
                                @click="deleteRealization(i, key2)">Удалить
                            </button>

                            <button v-if="i.status != 5 && i.status != 3 && showReadyInput" v-bind:id="'download_' + key2"
                                class="bg-white text-black font-bold py-2 px-4 rounded text-center text-sm"
                                @click="exportRealizatorTable(key2)">Скачать
                            </button>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <!-- Вкладка: Реализаторы -->
            <table v-if="real" class="w-full whitespace-nowrap mt-5">
                <tr class="text-center font-bold border-b border-gray-200">
                    <th>Реализатор</th>
                    <th> Всего заказов</th>
                </tr>
                <tr @click="history(item.realizator.id)" v-for="item in count"
                    class="text-center border-b border-gray-200  hover:bg-gray-100">
                    <td class="cursor-pointer">{{ item.realizator.first_name }}</td>
                    <td>{{ item.amount }}</td>
                </tr>
            </table>

        </div>

        <!-- История релизатора ? -->
        <modal name="history">
            <div class="px-6 py-6">

                <div class="flex">
                    <div>
                        от
                        <datepicker v-model="from" type="date" placeholder="" :show-time-header="time">
                        </datepicker>
                    </div>
                    <div>
                        до
                        <datepicker v-model="to" type="date" placeholder="" :show-time-header="time">
                        </datepicker>
                    </div>

                </div>
                <br>
                <br>
                <table class="w-full whitespace-nowrap  ">
                    <tr class="text-left font-bold border-b border-gray-200">
                        <th class="px-6 pt-4 pb-4">Реализатор</th>
                        <th class="px-6 pt-4 pb-4">Номер</th>
                        <th class="px-6 pt-4 pb-4">Дата</th>
                        <th class="px-6 pt-4 pb-4">Отчет</th>

                    </tr>
                    <tr class="text-left border-b border-gray-200" v-for="item in myrealizations"
                        v-if="new Date(item.created_at) >= new Date(from)">
                        <td class="px-6 pt-3 pb-3 w-8">{{ item.realizator.first_name }}</td>
                        <td class="px-6 pt-3 pb-3 w-8">{{ item.id }}</td>
                        <td class="px-6 pt-3 pb-3 w-8">{{ moment(item.created_at).format("DD-MM-YYYY") }}</td>
                        <td class="px-6 pt-3 pb-3 w-8">
                            <div class="flex gap-2">
                                <button @click="showReport3(item.id, item.realizator.id)"
                                    class="bg-green-500 text-white font-bold py-2 px-4 rounded">просмотр</button>

                            </div>


                        </td>
                    </tr>
                </table>
            </div>
        </modal>

        <!-- Есть какое-то уведомление, при нажатии выходит эта модалка -->
        <modal name="new-orders">
            <div class="p-10">
                <table>
                    <tr>
                        <th>Заявка от:</th>
                    </tr>
                    <tr v-for="item in order_users" class="pt-4">
                        <td>{{ item.first_name }}</td>
                    </tr>
                </table>
            </div>
        </modal>

        <!-- Есть какое-то уведомление, при нажатии выходит эта модалка -->
        <modal name="dop-orders">
            <div class="p-10">
                <p class=" text-lg font-bold mb-6">Дополнительная заявка</p>

                <table class="w-full">
                    <tr>
                        <th class="text-left pb-3">От:</th>
                        <th class="text-left pb-3">Заявка на:</th>
                    </tr>
                    <tr v-for="item in dop_users" class="pt-4">
                        <td>{{ item.realizator ? item.realizator.first_name : 'Реализатор № ' + item.realizator_id }}</td>
                        <td>
                            <table>
                                <template v-for='dop in item.dops'>
                                    <tr v-if="dop.order_amount > 0" :key="'asd' + dop.id">
                                        <td>{{ typeof dop.assortment === 'object' ? dop.assortment.type : dop.assortment }}</td>
                                        <td class="pl-3"><b>{{ dop.order_amount }}</b></td>
                                    </tr>
                                </template>
                              
                            </table>
                        </td>
                    </tr>
                </table>

                <br><br>
                <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="acceptDop" id="acceptDop">Принять</button>
                <button class="bg-red-500 text-white font-bold py-2 px-4 rounded" @click="declineDop" id="declineDop">Отклонить</button>
            </div>
        </modal>
        
        <!-- Загрузка -->
        <modal name="loading" class="modal-300p">
            <div class="flex flex-col items-center p-4">
                <img class="w-8 h-8 bg-white" src="/img/loading.gif" alt="">
                <p class="mt-4 text-center">Подождите, отчет сохраняется...</p>
            </div>
        </modal>

    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import axios from 'axios'
import moment from "moment";
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import MonthPicker from '@/Shared/MonthPicker'
import AvansReport from '@/Pages/Sales/AvansReport.vue'

export default {
    layout: Layout,
    metaInfo: {
        title: 'Реализаторы'
    },
    components: {
        Datepicker,
        SelectInput,
        TextInput,
        MonthPicker,
        AvansReport,
    },
    props: {
        oweshops: Array,
        report1: Array,
        realizations: Array,
        realizators: Array,
        assortment: Array,
        monthes: Object,
        count: Array,
        realization_count: Number,
        dop_count: Number,
        nak_count: Number,
        order: Array,
        sold1: Array,
        days: Number,
        majit: Number,
        sordor: Number,
        nakladnoe: Array,
        pivotPrices: Array,
        currentMonth: Number,
    },
    data() {
        return {
   
            timerCount: 10,
            mysold1: this.sold1,
            defects_report: this.defects_report,
            realizators_month: new Date().getMonth() + 1,
            realizators_year: new Date().getFullYear(),
            years: [2022, 2021, 2020, 2019, 2018],
            months: [
                { id: 1, name: "Январь" },
                { id: 2, name: "Февраль" },
                { id: 3, name: "Март" },
                { id: 4, name: "Апрель" },
                { id: 5, name: "Май" },
                { id: 6, name: "Июнь" },
                { id: 7, name: "Июль" },
                { id: 8, name: "Август" },
                { id: 9, name: "Сентябрь" },
                { id: 10, name: "Октябрь" },
                { id: 11, name: "Ноябрь" },
                { id: 12, name: "Декабрь" },
            ],
            monthOfItog: new Date().getMonth() + 1,
            yearOfItog: new Date().getFullYear(),
            myorder: this.order,
            order_users: [],
            dop_users: [],
            time: true,
            from: new Date(),
            to: new Date(),
            moment: moment,

            report2: false,
            report3: false,
            sales: true,
            report: false,
            naks: false,
            alert: this.realization_count,
            alert1: this.nak_count,
            alert_dop: this.dop_count,
            realizator: '',
            myrealizations: this.realizations,
            total: 0,
            real: false,
            itog: false,
            form: this.$inertia.form({
                realizator: null,
                summ: null,
                defect: null,
            }),
    
            itogData: [],
            itogMonth: [],
            isLoading: false,
            month: new Date().getMonth() + 1,
            year: new Date().getFullYear(),
            selected_period: null,
            mysold_realizator: 'all',

            showReadyInput: true // в Orders
        }
    },
    mounted() {
      let xlsxScript = document.createElement('script')
      xlsxScript.setAttribute('src', '/js/xlsx.min.js')
      document.head.appendChild(xlsxScript)
    },
    created() {
        if(this.userIs([this.ACCOUNTANT])) {
            this.real = false;
            this.report = true;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
        }

        if(this.userIs([this.FACTORY_WORKER])) {
            this.showReadyInput = false;
        }

        this.realizators_month = this.currentMonth;
    },
    watch: {
        timerCount: {
            handler(value) {
                if (value > 0) {
                    setTimeout(() => {
                        this.timerCount--;
                    }, 1000);
                } else {
                    var latest = 0;

                    for (var i = 0; i < this.myorder.length; i++) {
                        // console.log('myorder', this.myorder[i]);

                        if (this.myorder[i].id > latest)
                            latest = this.myorder[i].id;
                    }



                    axios.post('get-order', { size: this.myorder.length, latest: latest }).then(response => {
                        //console.log(response.data); return;

                        response.data.refresh.forEach((item, key) => {

                            if (new Date(item.updated) >= new Date(Date.now() - 11000)) {
                                //this.myorder.splice(key,1,item);
                            }

                        });

                        if (response.data.order != null)
                            var neworder = response.data.order;

                        for (var i = 0; i < neworder.length; i++) {
                            var found = false;

                            for (var j = 0; j < this.myorder.length; j++) {
                                if (neworder[i].id == this.myorder[j].id) {
                                    found = true;

                                    // any updates?
                                    if (neworder[i].updated != this.myorder[j].updated) {
                                        for (var k = 0; k < neworder[i].assortment.length; k++) {
                                            if (this.myorder[j].assortment[k] === undefined) {
                                                this.myorder[j].assortment.push(neworder[i].assortment[k]);
                                                continue;
                                            }

                                            this.myorder[j].assortment[k].order_amount = neworder[i].assortment[k].order_amount;
                                        }

                                    }

                                    break;
                                }
                            }

                            if (found == false) {
                                // new zayavka
                                this.myorder.push(neworder[i]);
                                continue;
                            }

                        }

                        //this.myorder = this.myorder.concat(response.data.order);

                        this.alert = response.data.count;
                        this.alert1 = response.data.nak;
                        this.alert_dop = response.data.dop;
                        this.timerCount = 10;
                    });
                }

            },
            immediate: true
        },

        realizators_month: function (val) {
            this.realizators_month = val;

            var today = new Date();
            var lastDayOfMonth = new Date(this.realizators_year, this.realizators_month, 0);
            this.days = lastDayOfMonth.getDate();

            // console.log("month update", this.days);

            this.getSold1();
            this.getDefects();
            this.getNaks();

            this.getItogData(this.realizators_month, this.realizators_year);
        },

        realizators_year: function (val) {
            console.log('changed year', val);
            this.realizators_year = val;

            this.getSold1();
            this.getDefects();
            this.getNaks();

            this.getItogData(this.realizators_month, this.realizators_year);
        },
    },
    computed: {},
    methods: {

        exportRealizatorTable(i) {
            let merges = [
                { s: 'A1', e: 'B1' },
                { s: 'C1', e: 'D1' },
            ];

            let realizator = this.order[i].realizator;
            let date = moment(new Date(this.order[i].real.created_at)).format('DD.MM.YYYY HH:mm')

            this.exportExcel(
                this.dataRealizatorTable(i),
                merges,
                'Заявка реализатора - ' + realizator.first_name + ' от ' + date,
                'Авансовый отчет',
                [`${realizator.first_name} ${realizator.last_name}`, "", date]
            );
        },
        dataRealizatorTable(i) {
            let assortment = this.order[i].assortment;
            let data = [];

            data.push({
                c1: 'Наименование',
                c2: 'Заявка',
                c3: 'Изготовлено',
                c4: 'Отпущено',
            });

            assortment.forEach(e => {
                data.push({
                    c1: e.name,
                    c2: e.order_amount,
                });
            })

            return data
        },
        // main excel method
        exportExcel(jsonData, merges, fileName, sheetName, firstLine) {
            var myFile = fileName + ".xlsx";
            var myWorkSheet = XLSX.utils.json_to_sheet(jsonData);
            var merges = myWorkSheet['!merges'] = merges;// [{ s: 'A1', e: 'AA1' }];
            XLSX.utils.sheet_add_aoa(myWorkSheet, [firstLine], { origin: 'A1' });
            var myWorkBook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(myWorkBook, myWorkSheet, sheetName);
            XLSX.writeFile(myWorkBook, myFile);
        },
        //another
        setPeriod() {
            console.log(this.selected_period);
        },
        showNaks() {
            this.real = false;
            this.report = false;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
            this.naks = true;
            this.itog = false;
        },
        showReport4() {
            this.itog = false;
            this.real = false;
            this.report = false;
            this.sales = false;
            this.report2 = false;
            this.report3 = true;
            this.naks = false;

            this.mysold_realizator = 'all';
            this.showRealizatorSold();
        },
        calculateExtra(key) {
            let total = 0;
            this.order.forEach(element => {
                if (element.assortment[key].order_amount != null && element.assortment[key].amount[0].order_amount - element.assortment[key].amount[0].amount < 0) {
                    total += element.assortment[key].amount[0].amount - element.assortment[key].amount[0].order_amount;
                }

            });
            return total;
        },
        calculateTotal(key) {
            let total = 0;
            this.order.forEach(element => {
                if (element.assortment[key].order_amount != null) {
                    total += element.assortment[key].order_amount;
                }

            });
            return total;
        },
        // Deprecated
        showReport2() {
            this.real = false;
            this.report = false;
            this.sales = false;
            this.report2 = true;
            this.report3 = false;
            this.itog = false;
            this.naks = false;

            this.getDefects();
        },
        closeAlert() {
            axios.get('realization-status').then(response => {
                this.order_users = response.data;
                this.$modal.show('new-orders');
            });
            this.alert = 0;
        },
        closeAlertDop() {
            axios.get('dop-status').then(response => {
                this.dop_users = response.data;
                this.$modal.show('dop-orders');
            });
        },
        closeAlert1() {
            axios.get('nak-status');
            this.alert1 = 0;
        },

        showReport() {
            this.naks = false;
            this.itog = false;
            this.real = false;
            this.report = true;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
        },

        showReport3(id, realizator) {


            this.naks = false;
            this.itog = false;
            this.real = false;
            this.report = true;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
            this.$modal.hide('history');

            this.$refs.avansReport.loadTable({ id: id, realizator_id: realizator });


        },

        history(item) {
            axios.post('get-realizator', { id: item }).then(response => {
                ;
                this.myrealizations = response.data;
                this.$modal.show('history');
            });
        },

        showRealizators() {
            this.naks = false;
            this.real = true;
            this.report = false;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
            this.itog = false;
        },

        showSales() {
            this.naks = false;
            this.real = false;
            this.report = false;
            this.sales = true;
            this.report2 = false;
            this.report3 = false;
            this.itog = false;
        },
        showItog() {
            this.naks = false;
            this.real = false;
            this.report = false;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
            this.itog = true;

            this.getItogData(this.realizators_month, this.realizators_year);
        },
        getItogData(month, year) {
            this.isLoading = true;

            axios.get('/itog-zayavki?month=' + month + '&year=' + year).then((response) => {
                this.itogData = response.data.data;
                this.itogMonth = response.data.total;

                this.isLoading = false;
            });
        },
        changeItogMonth(month, year) {
            this.monthOfItog = month
            this.yearOfItog = year

            this.getItogData(month, year);
        },
        getItem(amount) {
            if (amount > 0 || amount == null) {
                return false;
            } else {
                return true;
            }
        },
        getSold1() {
            axios.post('sales/sold1', {
                month: this.realizators_month,
                year: this.realizators_year
            }).then(response => {
                this.mysold1 = response.data;
            })
        },
        getDefects() {
            axios.post('sales/defects', { month: this.realizators_month, year: this.realizators_year }).then(response => {
                this.defects_report = response.data;
            })
        },
        saveOrder(order, key) {
            var conf = confirm('Подтвердить изготовление продукции?');

            if (conf === false) {
                return;
            }

            axios.post('set-order-amount', { order: order.assortment }).then(response => {
                alert(response.data);
                var btn = document.getElementById("save_" + key);
                btn.style.display = 'none';

                location.reload();
            });
        },
        onEnter(e) {
            // console.log('on enter...', e);

            const form = event.target.form;
            const index = [...form].indexOf(event.target);

            const next_index = index + 1;
            form.elements[next_index].select();
            form.elements[next_index].focus();

            event.preventDefault();
        },

        declineDop() {
            let button = document.getElementById('declineDop');
            button.style.display = 'none';

            axios.post('decline-dop').then(response => {
                alert(response.data);

                this.alert_dop = 0;
                this.$modal.hide('dop-orders');

                location.reload();
            });
        },
        acceptDop() {
            let button = document.getElementById('acceptDop');
            button.style.display = 'none';

            axios.post('accept-dop').then(response => {
                alert(response.data);

                this.alert_dop = 0;
                this.$modal.hide('dop-orders');

                location.reload();
            }); 

        },
        getDay(timestamp) {
            var seconds = Date.parse(timestamp);
            var date = new Date(seconds);
            var day = date.getDay();

            return timestamp.substring(8, 10);
        },
        getNaks() {
            axios.post('sales/naks', {
                month: this.realizators_month,
                year: this.realizators_year
            }).then(response => {
                this.nakladnoe = response.data;
            });
        },

        day(time) {
            return parseInt(time.substring(8, 10)) + 1;
        },
        deleteRealization(real, key) {

            if (!window.confirm('Вы уверены, что хотите удалить заявку?')) {
                return;
            }

            axios.delete('/realization/' + real.id + '/delete').then((response) => {
                location.reload();
            });
        },
        getMonthName(month) {
            return this.months.find(m => m.id == month).name;
        },
        nextMonth() {
            this.month += 1;

            if (this.month > 12) {
                this.month = 1;
                this.year += 1;
            }

            axios.post('sales/sold1', {
                month: this.month,
                year: this.year,
                realizator: this.mysold_realizator
            }).then(response => {
                this.mysold1 = response.data;
            })
        },
        prevMonth() {
            this.month -= 1;

            if (this.month <= 0) {
                this.month = 12;
                this.year -= 1;
            }

            axios.post('sales/sold1', { month: this.month, year: this.year, realizator: this.mysold_realizator }).then(response => {
                this.mysold1 = response.data;
            })
        },
        showRealizatorSold() {
            axios.post('sales/sold1', { month: this.month, year: this.year, realizator: this.mysold_realizator }).then(response => {
                this.mysold1 = response.data;
            })
        },
        formatNum(num, type) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        },
    }
}
</script>
<style>
.modal-300p .vm--modal{
    width: 300px !important;
    min-width: 300px !important;
}
.cursor-pointer {
    cursor: pointer;
}
/* Chrome, Safari, Edge, Opera */
input.hidden-arrows::-webkit-outer-spin-button,
input.hidden-arrows::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input.hidden-arrows[type=number] {
  -moz-appearance: textfield;
}
</style>