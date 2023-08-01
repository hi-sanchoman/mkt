<template>
<div class="flex flex-col h-full">

    <!-- Кнопки в первом ряду DESKTOP -->
    <div class="grid grid-cols-2 sm:flex panel justify-start gap-3 hidden sm:block mb-3">
        <button v-if="real && this.$page.props.auth.user.position_id != 7"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            @click="showInput()">
            Новая переработка
        </button>

        <button class="hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            :class="{'bg-green-500' : real, 'bg-blue-500' : !real}"
            @click="showReport">
            Помесячный отчет
        </button>

        <!-- Переключатель месяца на Desktop -->
        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 flex justify-start hidden sm:flex">

            <div class="relative mr-4">
                <div class="flex space-x-2 items-center justify-center border p-2">
                    <button v-on:click="prevMonth" class="hover:bg-white hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>
                    </button>
                    <span class="w-40 text-center">{{getMonthName(this.month)}} {{  this.year }}</span>
                    <button v-on:click="nextMonth" class="hover:bg-white hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="relative"></div>
        </div>

        <button v-if="this.$page.props.auth.user.position_id != 7"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            @click="endMonth()">
            Завершить месяц
        </button>
    </div>

    <!-- Переключатель месяца на Мобильной -->
    <div class="grid grid-cols-2 panel flex justify-start gap-3 sm:hidden mb-3">
            <div class="relative block sm:hidden">
            <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                id="grid-state" v-model="month"
                @change="changeMonth()">
                <option v-for="month in selectMonth" :value="month.id" >{{month.month}}</option>
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>

        <!-- Переключатель года на Мобильной -->
        <div class="relative block sm:hidden">
            <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                id="grid-state" v-model="year"
                @change="changeYear()">
                <option v-for="year in selectYear" >{{year}}</option>
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
    </div>

    <!-- Таблица выработки  -->
    <div v-if="real" class="w-full bg-white rounded-2xl md:rounded-lg h-auto overflow-auto">
        <table class="w-full whitespace-nowrap text-xs sm:text-base">
            <thead class="bg-white custom1">
                <tr class="text-left font-bold border-b border-gray-200 bg-white">
                    <th class="pl-3 sm:pl-0 pt-4 pb-4 absolute bg-white w-72 custom">
                        <p class="font-bold text-center w-48 sm:w-fit">Наименование</p>
                    </th>
                    <td v-for="(n, i) in days" class="px-6 pt-4 pb-4 sticky top-0 bg-white "
                        :class="{
                            'red-column': existsAssortmentDay(1, n) && itog[n] != getKilo(1, n)
                        }">
                        <p class="font-bold text-center " :id="itog[n]">{{ n }}</p>
                    </td>
                    <th class="px-6 pt-4 pb-4 sticky top-0 bg-white">Итог</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(item,j) in assortments">
                    <th class="pl-6 sm:pl-7 pr-6 pt-4 pb-4 text-left sticky left-0 bg-white w-48 sm:w-auto">{{item.name}} </th>
                    <td v-for="(n, i) in days" class="px-6 pt-4 pb-4"
                        :class="{
                            'red-column': existsAssortmentDay(1, n) && itog[n] != getKilo(1, n)
                        }">
                        <p>
                            {{ getKilo(item.id, n) }}
                        </p>
                    </td>
                    <td class="px-6 pt-4 pb-4 oooo">
                        {{ getTotalKilo(item.id) }}
                    </td>
                </tr>

                <tr>
                    <th class="sm:pr-6 pt-4 pb-4 pl-6 sm:pl-7 text-left sticky left-0 bg-white ">Итог</th>
                    <td v-for="(n, i) in days" class="px-6 pt-4 pb-4"
                        :class="{
                            'red-column': existsAssortmentDay(1, n) && itog[n] != getKilo(1, n)
                        }
                    ">
                        {{ itog[i+1] }}
                    </td>
                    <td>{{ mytotal }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Форма при нажатии на "Новая переработка" -->
    <div v-if="input" class="w-auto bg-white rounded-2xl  h-auto p-6 overflow-auto pt-2">
        <form onsubmit="return false;">

            <!-- Первый ряд c выбором дня -->
            <div class="flex mb-4 mt-4 items-center">
                <div class="flex pl-6 items-center">
                    <p>Выбрать другой день:</p>
                    <select class="border-2 rounded-lg ml-4 p-1" v-model="today" @change="getConversionsByDate()">
                        <option v-for="(n, i) in days">{{n}}</option>
                    </select>
                </div>
                <button type="button" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-5" @click="save()">
                    {{ buttonValue }}
                </button>
            </div>



            <table class="w-auto whitespace-nowrap" v-if="current_month">


                <tr>
                    <th class="text-left pl-6">Название</th>
                    <td></td>
                    <td style="width: 150px;">&nbsp;</td>

                    <th class="px-6 pt-4 pb-4">Молоко жир</th>
                    <td style="width: 100px;">&nbsp;</td>
                    <th class="px-6 pt-4 pb-4">Закваска</th>
                </tr>

                <!-- Цикл ассортиментов -->
                <tr v-for="item in assortments" v-if="item.id != 25">
                    <td class="px-6 pt-4 pb-4 text-left">{{item.name}}</td>

                    <td v-if="item.id == 1 || item.id == 2 || item.id == 3" class="px-6 pt-4 pb-4">
                        {{ getItem(item.id).kg }}
                    </td>

                    <!-- DIRECTOR -->
                    <td v-else-if="getItem(item.id) && $page.props.auth.user.position_id == 1"
                        class="px-6 pt-4 pb-4">
                        <input type="text" class="pt-2 pb-2 border-b-2"
                            v-on:keyup.enter="onEnter"
                            onclick="select()"
                            :name='item.assortment'
                            :id='item.id'
                            v-model='conversion[item.id]' />
                        в базе: {{getItem(item.id).kg}}
                    </td>

                    <!-- SAVED -->
                    <td v-else-if="getItem(item.id).status != null && $page.props.auth.user.position_id != 1"
                        class="px-6 pt-4 pb-4">
                        {{getItem(item.id).kg}}
                    </td>

                    <!-- CAN BE ENTERED -->
                    <td v-else class="px-6 pt-4 pb-4">
                        <input
                            v-if="isInTime()"
                            class="pt-2 pb-2 border-b-2"
                            type="text"
                            v-on:keyup.enter="onEnter"
                            onclick="select()"
                            :name='item.assortment'
                            :id='item.id'
                            v-model='conversion[item.id]' />
                    </td>

                    <td style="width: 150px;">&nbsp;</td>

                    <!-- MILK: ADMIN -->
                    <td v-if="getMilkItem(item.id) && $page.props.auth.user.position_id == 1"
                        class="px-6 pt-4 pb-4">
                        <input
                            v-if="inMilk(item.id)"
                            class="pt-2 pb-2 border-b-2"
                            type="text"
                            v-on:keyup.enter="onEnter"
                            onclick="select()"
                            :name='item.assortment'
                            :id='"m" + item.id'
                            v-model='dopMilk[item.id]' />
                        <input
                            v-else-if="item.id == 21"
                            type="text"
                            v-model="vSlivki"
                            disabled />
                        в базе: {{ getMilkItem(item.id).kg }}
                    </td>

                    <td v-else-if="getMilkItem(item.id) && $page.props.auth.user.position_id != 1" class="px-6 pt-4 pb-4">
                        <span v-if="inMilk(item.id)">
                            {{ getMilkItem(item.id).kg }}
                        </span>

                        <input v-else-if="item.id == 21" type="text" v-model="vSlivki" disabled />
                    </td>

                    <td v-else class="px-6 pt-4 pb-4">
                        <input v-if="inMilk(item.id) && isInTime()"
                            type="text"
                            class="pt-2 pb-2 border-b-2"
                            v-on:keyup.enter="onEnter"
                            onclick="select()"
                            :name='item.assortment'
                            :id='"m" + item.id'
                            v-model='dopMilk[item.id]' />

                        <input v-else-if="item.id == 21 && isInTime()"
                            type="text"
                            v-model="vSlivki"
                            disabled />
                    </td>

                    <td style="width: 100px;">&nbsp;</td>

                    <!-- Ассортимент входит в закваску -->
                    <td v-if="inZakvaska(item.id)">
                        <button class="btn" style="outline: 1px solid grey;"
                            @click="showZakvaska(item)"
                        >
                            посм. закваски
                        </button>
                    </td>
                </tr>

                <!-- Итоги -->
                <tr class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                    <td class="px-6 pt-3 pb-3 w-8">
                        <div class="flex">
                            <p class="text-sm">Итог</p>
                        </div>
                    </td>

                    <!-- <td class="px-6 pt-3 pb-3 w-8">
                        <p class="text-sm">{{phys_weight}}</p>
                    </td>
                    <td class="px-6 pt-3 pb-3 w-8">
                        <p class="text-sm"></p>
                    </td>
                    <td class="px-6 pt-3 pb-3 w-8">
                        <p class="text-sm"></p>
                    </td>

                    <td class="px-6 pt-3 pb-3 w-8">
                        <p class="text-sm"></p>
                    </td>

                    <td class="px-6 pt-3 pb-3 w-8">
                        <p class="text-sm">{{basic_weight}}</p>
                    </td>

                    <td class="px-6 pt-3 pb-3 w-8">
                        <p class="text-sm">{{fat_kilo}}</p>
                    </td>

                    <td class="px-6 pt-3 pb-3 w-8">
                        <p class="text-sm"></p>
                    </td>

                    <td class="px-6 pt-3 pb-3 w-8">
                        <p class="text-sm">{{sum}}</p>
                    </td> -->

                </tr>
            </table>
        </form>
    </div>

    <!-- Deprecated: непонятная модалка для создания новойпереработки -->
    <!-- <modal name="create" class="modal-50">
        <form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" onsubmit="return false;">
            <div class="mb-8 font-medium">
                Новая переработка
            </div>
            <div class="space-y-4 mb-8">
                <div>
                    <div class="flex">
                        <div class="w-7/12">
                            <p class="w-1/12">Ассортимент<span class="text-red-400">*</span></p>
                            <select class="border-b-2 w-full pb-1 w-9/12" v-model="form.supplier">

                                <option v-for="option in assortments" :value="option.id">{{option.name}}</option>
                            </select>
                        </div>
                        <a class="w-3/12 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3" @click="createAssortment">Новый ассортимент</a>

                    </div>
                    <div v-if="createAss">
                        <div>
                            <p class="w-1/6">Название ассортимента<span class="text-red-400">*</span></p>
                            <input type="text"  class="flex-auto border-b-2 w-full pb-1" v-model="form.phys_weight">
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Сохранить</button>

                    </div>
                </div>

                <div class="flex justify-between" v-if="form.supplier == 1">
                    <div class="flex"><input type="checkbox" name="phys_weight"><p>Физический вес</p></div>
                    <div class="flex"><input type="checkbox" name="basic_weight"><p>Базовый вес</p></div>
                    <div class="flex"><input type="checkbox" name="fat_weight"><p>Жир</p></div>
                </div>


                <div>
                    <p class="w-1/6">Физический вес.<span class="text-red-400">*</span></p>
                    <input type="number" class="flex-auto border-b-2 w-full pb-1" v-model="form.phys_weight">
                </div>



            </div>




                <div class="mt-4" >
                <div class="w-full flex justify-between">
                    <div class="lg:w-1/4">
                        <p class="font-medium leading-6">Заполните поле
                        <span class="text-red-400">*</span>
                        </p>
                    </div>
                    <div class="lg:w-3/4 flex justify-end items-center">
                        <div class="text-red-500 font-medium mr-3">
                        {{ err }}
                        </div>
                        <button type="button" @click="store" class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Создать</span></button>
                    </div>
                    </div>
                </div>


            </form>
    </modal> -->

    <!-- sidebar Закваска -->
    <div class="sidebar fixed  w-screen h-screen overflow-hidden flex  bg-indigo-500 bg-opacity-40 left-0"
        :class="[sidebar_zakvaska ? 'left-0' : 'left-full']"
        v-if="sidebar_zakvaska" >
        <div class="w-3/5 cursor-pointer" @click="sidebar_zakvaska = false"></div>

        <div class="w-2/5 bg-white overflow-y-auto">

            <div class="mb-8 flex justify-start w-full p-8 mr-2 items-center border-b">
                <h2>Информация о закваске: <strong>{{ sidebar_zakvaska_name }}</strong></h2>
            </div>

            <div class="bg-white rounded-md  overflow-hidden w-full px-8">

                <table>
                    <tr>
                        <th>
                            Закваска
                        </th>
                        <th>
                            Количество
                        </th>
                    </tr>

                    <tr v-for="zakvaska in zakvaskas">
                        <td>
                            {{ zakvaska.assortment }}
                        </td>
                        <td v-if="getZakvaskaItem(selected_zakvaska, zakvaska.id) && $page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                            <input class="pt-2 pb-2 border-b-2" v-on:keyup.enter="onEnter" onclick="select()" :name='dopZakvaska[selected_zakvaska][zakvaska.id]' :id='zakvaska.id' type="text" v-model="dopZakvaska[selected_zakvaska][zakvaska.id]">

                            в базе: {{ getZakvaskaItem(selected_zakvaska, zakvaska.id).kg }}
                        </td>

                        <td v-else-if="getZakvaskaItem(selected_zakvaska, zakvaska.id) && $page.props.auth.user.position_id != 1" class="px-6 pt-4 pb-4">
                            <span>{{ getZakvaskaItem(selected_zakvaska, zakvaska.id).kg }}</span>
                        </td>

                        <td v-else-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                            <input class="pt-2 pb-2 border-b-2" v-on:keyup.enter="onEnter" onclick="select()" :name='dopZakvaska[selected_zakvaska][zakvaska.id]' :id='zakvaska.id' type="text" v-model="dopZakvaska[selected_zakvaska][zakvaska.id]">
                        </td>

                        <td v-else class="px-6 pt-4 pb-4">
                            <input v-if="isInTime()" class="pt-2 pb-2 border-b-2" v-on:keyup.enter="onEnter" onclick="select()" :name='dopZakvaska[selected_zakvaska][zakvaska.id]' :id='zakvaska.id' type="text" v-model="dopZakvaska[selected_zakvaska][zakvaska.id]">
                        </td>
                    </tr>

                </table>


            </div>
        </div>
    </div>

</div>
</template>

<script>
import Layout from '@/Shared/Layout'
import axios from 'axios'
import Datepicker from 'vue2-datepicker'
import Vue from "vue";
import JsonExcel from "vue-json-excel";
import 'vue2-datepicker/index.css'

Vue.component("downloadExcel", JsonExcel);

export default {
    metaInfo: {
        title: 'Выработка'
    },

    layout: Layout,

    components: {
        JsonExcel,
        Datepicker
    },

    props: {
        dbAssortments: Array,
        dbAssortments_total: Array,
        dbPrice: Array,
        dbDays: Number,
        dbMonth1: Object,
        dbZakvaskas: Array,
    },
    // data
    data() {
        return {
            mytotal: [],
            itog: [],
            current_month: this.dbMonth1.month == new Date().getMonth()+1,
            selectedMonth: null,
            selectMonth: [
                {id: 1,month: 'Январь'},
                {id: 2,month: 'Февраль'},
                {id: 3,month: 'Март'},
                {id: 4,month: 'Апрель'},
                {id: 5,month: 'Май'},
                {id: 6,month: 'Июнь'},
                {id: 7,month: 'Июль'},
                {id: 8,month: 'Август'},
                {id: 9,month: 'Сентябрь'},
                {id: 10,month: 'Октябрь'},
                {id: 11,month: 'Ноябрь'},
                {id: 12,month: 'Декабрь'}
            ],
            selectYear: [2020,2021,2022,2023,2024],
            since: new Date(),
            today: new Date().getDate(),
            conversion: [],
            dopMilk: [],
            dopZakvaska: [],
            loadedMilkFats: [],
            loadedDopZakvaskas: [],
            changedConversions: [],
            weights: [],
            myrows: [],
            real: true,
            input: false,
            createAss: false,
            timestamp: this.getTodaysTimestamp(),
            month: this.dbMonth1.month,
            year: this.dbMonth1.year,
            err : '',
            buttonValue: "Сохранить",
            mysupplies: [],
            sidebar_zakvaska: false,
            sidebar_zakvaska_name: '',
            selected_zakvaska: -1,

            days: this.dbDays,
            selectedPeriod: [],

            oiltotal: [],
            assortments: this.dbAssortments,
            conversions: [],
            rows: [],
            assortments_total: this.dbAssortments_total,
            total: [],
            price: this.dbPrice,
            month1: this.dbMonth1,
            zakvaskas: this.dbZakvaskas,
        }
    },

    created() {
        this.changeMonth()
        this.getConversionsByDate()
        this.prepareItog()
    },

    computed: {
        vSlivki: {
            get: function() {
                var val = this.getItem(3).kg;

                if (this.getMilkItem(21) && this.$page.props.auth.user.position_id != 1) {
                    val = this.getMilkItem(21).kg;
                }

                if (this.dopMilk[4] !== undefined) val -= this.dopMilk[4];

                if (this.dopMilk[5] !== undefined) val -= this.dopMilk[5];

                if (this.dopMilk[6] !== undefined) val -= this.dopMilk[6];

                if (this.dopMilk[8] !== undefined) val -= this.dopMilk[8];

                if (this.dopMilk[12] !== undefined) val -= this.dopMilk[12];

                if (this.dopMilk[13] !== undefined) val -= this.dopMilk[13];

                if (this.dopMilk[17] !== undefined) val -= this.dopMilk[17];

                if (this.dopMilk[18] !== undefined) val -= this.dopMilk[18];

                return val;
            }
        }
    },

    methods: {

        prepareItog() {
            this.itog = [0];

            for (var day = 1; day <= this.days; day++) {
                this.itog[day] = 0;

                [4,5,6,8,12,13,17,18,20].forEach(id => {
                    this.itog[day] += parseFloat(this.getKilo(id, day));
                });

                [10,11].forEach(id => {
                    this.itog[day] -= parseFloat(this.getKilo(id, day));
                });
            }
        },

        existsAssortmentDay(assortment_id, day) {
            return this.myrows[assortment_id] !== undefined
                && this.myrows[assortment_id][day] !== undefined
                ? true
                : false;
        },

        getKilo(assortment_id, day) {
            return this.myrows[assortment_id] !== undefined
                && this.myrows[assortment_id][day] !== undefined
                ? Math.round(this.myrows[assortment_id][day] * 100) / 100
                : 0;
        },

        getTotalKilo(id) {
            if(this.myrows[id] == undefined) return 0;

            let total = 0;
            Object.values(this.myrows[id]).forEach(kg => {
                total += parseFloat(kg);
            });


            return Math.round(total * 100) / 100
        },

        changeMonth() {
            axios.post('conversions/change',{
                month : this.month,
                year: this.year
            }).then(response => {
                this.myrows = response.data.rowconversions;
                this.days = new Date(this.year, this.month, 0).getDate();
                this.mytotal = response.data.total;
                this.oiltotal = response.data.oiltotal
                this.prepareItog();
            });
        },

        getItem(assortment_id) {
            for(var i = 0; i < this.changedConversions.length; i++) {
                if(this.changedConversions[i].assortment == assortment_id){
                    return this.changedConversions[i];
                }
            }

            return {
                'assortment': 0,
                'kg': '-',
                'status': null
            };
        },

        getMilkItem(assortment_id) {
            for(var i = 0; i < this.loadedMilkFats.length; i++){
                if(this.loadedMilkFats[i].assortment == assortment_id){
                    return this.loadedMilkFats[i];
                }
            }
            return null;
        },

        getZakvaskaItem(assortment_id, zakvaska_id) {

            for (var i = 0; i < this.loadedDopZakvaskas.length; i++) {
                if (this.loadedDopZakvaskas[i].assortment == assortment_id && this.loadedDopZakvaskas[i].zakvaska_id == zakvaska_id) {
                    return this.loadedDopZakvaskas[i];
                }
            }

            return null;
        },

        getTodaysTimestamp() {
            var nil = '';

            if(this.today < 10) {
                nil = '0';
            }

            var timestamp = this.pad(new Date().getFullYear()) + '-' + this.pad(new Date().getMonth()+1) + '-'  + nil + this.today;

            return timestamp;
        },

        getConversionsByDate() {
            this.current_month = true;
            axios.post('conversions/get-by-day',{
                timestamp : this.getTodaysTimestamp(),
                month : this.month1.month
            }).then(response => {
                this.changedConversions = response.data.myconversions;
                this.loadedMilkFats = response.data.milkFats;
                this.loadedDopZakvaskas = response.data.dopZakvaskas;
            });
        },

        pad(number) {
            if ( number < 10 ) {
                return '0' + number;
            }
            return number;
        },

        save() {

            let text = this.today + ' ' + this.getMonthName(this.month);  
            let isAct = confirm("Действительно хотите сохранить выработку за " +  text + "?");

            if (!isAct) {
                return;
            }

            this.buttonValue = "Выполняется";
            
            axios.post('conversions/save', {
                conversions : this.conversion,
                dopMilk: this.dopMilk,
                slivki: this.vSlivki,
                dopZakvaska: this.dopZakvaska,
                timestamp : this.getTodaysTimestamp(),
                today: this.today,
                year: this.year,
                month: this.month1.month
            }).then(response => {

                alert(response.data.message);
                location.reload();
            }).catch(e => {
                this.buttonValue = 'Ошибка';
                console.error(e);
            })

        },

        getDay(timestamp) {
            var seconds = Date.parse(timestamp);
            var date = new Date(seconds);
            var day = date.getDay();

            return timestamp.substring(8, 10);
        },

        // deprecated
        createAssortment() {
            this.createAss = !this.createAss;
        },

        history(supplier) {

            axios.post('suppliers/history',{
                supplier : supplier
            })
            .then(response => {
                var supplies = response.data;
                this.mysupplies = supplies;
            });

            this.$modal.show('show');
        },

        hideHistory() {
            this.$modal.hide('show');
        },

        // deprecated
        openCreateModal() {
            this.$modal.show('create')
        },

        showInput() {
            this.real = false;
            this.input = true;
        },

        showReport() {
            this.input = false;
            this.real = true;
        },

        // deprecated
        store() {
            this.err = '';
            if(this.form.name === null) {
                this.err = 'Заполните название!'
                return null;
            }

            if(this.form.phys_weight === null) {
                this.err = 'Выберите вес!'
                return null;
            }

            if(this.form.fat === null) {
                this.err = 'Выберите процент жирности!'
                return null;
            }

            if(this.form.acid === null) {
                this.err = 'Выберите кислотность!'
                return null;
            }

            if(this.form.density === null) {
                this.err = 'Выберите плотность!'
                return null;
            }

            if(this.form.price === null) {
                this.err = 'Выберите цену!'
                return null;
            }

            this.$modal.hide('create')
            this.form.post(this.route('supply.store'))
        },

        endMonth() {
            if(this.month1.month == new Date().getMonth()+1) {
                alert('Месяц еще не закончен');
                return;
            }

            if(this.month !== this.month1.month) {
                alert('Месяц еще не закончен');
                return;
            }

            axios.get('conversions/end-month').then(response => {
                alert(response.data);
                location.reload();
            });
        },



        onEnter(e) {

            const form = event.target.form;
            const index = [...form].indexOf(event.target);

            const next_index = index + 1;
            form.elements[next_index].select();
            form.elements[next_index].focus();

            event.preventDefault();
        },

        inMilk(id) {
            return [4, 5, 6, 8, 12, 13, 17, 18].includes(id);
        },

        inZakvaska(id) {
            return [5, 6, 8, 12, 13, 17, 18].includes(id);
        },

        isInTime() {
            var hours = 48;

            var date = new Date(this.year, this.month1.month - 1, this.today);
            var now = new Date();

            var diff = now.getTime() - date.getTime();

            return  diff <= hours * 60 * 60 * 1000;
        },

        showZakvaska(item) {
            var id = item.id
            this.selected_zakvaska = id;

            if (this.dopZakvaska[id] == undefined) {
                this.dopZakvaska[id] = [];
            }

            this.sidebar_zakvaska = true;
            this.sidebar_zakvaska_name = item.name;
        },

        getMonthName(month) {
            return this.selectMonth.find(m => m.id == month).month;
        },

        nextMonth() {
            this.month += 1;

            if (this.month > 12) {
                this.month = 1;
                this.year += 1;
            }

            this.changeMonth()
        },

        prevMonth() {
            this.month -= 1;

            if (this.month <= 0) {
                this.month = 12;
                this.year -= 1;
            }

            this.changeMonth()
        }
    }
}
</script>
