<template>
<div class="sm:flex flex-col h-full">
  <div class="grid grid-cols-1 sm:flex panel justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden sm:block" @click="openCreateModal()">
          Новая поставка
        </button>

        <div class="bg-green-500 h-8 text-white font-bold py-2 px-4 rounded">
          {{today()}}
        </div>

        <div class="space-y-3 w-2/3 sm:flex gap-5">
            <label class="inline-flex items-center mt-3">
                <input type="radio" class="form-radio" name="accountType" v-bind:value="1" v-model="quarter1" @change="maintest1()">
                <span class="ml-2">1-10</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType"  v-bind:value="2" v-model="quarter1" @change="maintest2()">
                <span class="ml-2">11-20</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType"  v-bind:value="3" v-model="quarter1" @change="maintest3()">
                <span class="ml-2">21-{{ latestDay }}</span>
            </label>
            <!--<label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType" v-bind:value="4" v-model="quarter1" @change="maintest4()">
                <span class="ml-2">1-{{ latestDay }}</span>
            </label>-->
            <label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType"  v-bind:value="5" v-model="quarter1" @change="maintest5()">
                <span class="ml-2">Сегодня</span>
            </label>

            <label class="inline-flex items-center" >
                <select v-model="month" @change="fullMonth($event.target.selectedIndex)">
                    <option v-for="(item, index) in list" :value="index">{{item}}</option>
                </select>
            </label>

            <!-- <label class="inline-flex items-center" >
                <select v-model="year" @change="fullYear($event.target.selectedIndex)">
                    <option v-for="(item, index) in list2">{{item}}</option>
                </select>
            </label> -->

            <datepicker 
                v-model="mydate" 
                type="date" 
                placeholder=""
                :show-time-header = "time"
                range
                @change="setDay()">
            </datepicker>
        </div>

       
  </div>
  <br>

    <div class="sm:hidden">
        <div class="bg-white w-full p-4 rounded-lg">
            <div v-for="supply, index in combined" class="shadow supply-button" :id="index" @click="showContent(supply.id)">
                <div class="p-4 rounded-sm w-full flex justify-between relative mb-2">
                    <div v-if="supply.supplier.name" class="text-sm" style="cursor: pointer">{{supply.supplier.name}}</div>
                    <div v-else class="text-sm">{{getSupplierName(supply.supplier.id)}}</div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="supply-menu hidden">
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Физ. вес</p>
                        <p class="text-sm">{{supply.phys_weight}}</p>
                    </div>      
                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Жирность</p>
                        <p class="text-sm">{{supply.fat}}</p>
                   </div> 
                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Кислотность</p>
                        <p class="text-sm">{{supply.acid}}</p>
                   </div> 
                   
                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Плотность</p>
                        <p class="text-sm">{{supply.density}}</p>
                   </div> 

                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Базовый вес</p>
                        <p class="text-sm">{{supply.basic_weight}}</p>
                   </div> 

                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Жир кг.</p>
                        <p class="text-sm">{{supply.fat_kilo}}</p>
                   </div> 

                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Цена</p>
                        <p class="text-sm">{{supply.price}}</p>
                   </div> 

                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Сумма</p>
                        <p class="text-sm">{{supply.sum}}</p>
                   </div> 

                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Время</p>
                        <p class="text-sm">{{getDate(supply.created_at) }}</p>
                   </div> 

                   <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Принимающий</p>
                        <p class="text-sm">{{supply.responsible}}</p>
                   </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 hidden sm:block">
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Поставщик</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Физический вес</p>
                </th>
                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Жирность %</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Кислотность
                    </p> 
                </th>
                
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Плотность
                    </p> 
                </th>

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Базовый вес
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Жир килограмм
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Цена
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Сумма
                    </p> 
                </th>

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Время
                    </p> 
                </th>

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Принимающий
                    </p> 
                </th>
                
            </tr>

            <tr v-for="supply in combined" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="supply.id">
                <td class="px-6 pt-3 pb-3 w-8" v-on:click="history(supply.supplier)">
                    <div class="flex">
                        <div v-if="supply.supplier.name" class="text-sm">{{supply.supplier.name}}</div>
                        <div v-else class="text-sm">{{getSupplierName(supply.supplier.id)}}</div>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.phys_weight}}</p>
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.fat}}</p>
               </td> 
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.acid}}</p>
               </td> 
               
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.density}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.basic_weight.toFixed(2)}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.fat_kilo.toFixed(2)}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.price}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.sum}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">
                        <!-- {{hours(supply.created_at)}}:{{minutes(supply.created_at)}}
                        <br> -->
                        {{ getDate(supply.created_at) }}
                    </p>
                </td> 

                <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.responsible}}</p>
                </td> 

            </tr>

            
            <tr class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex">
                        <p class="text-sm">Итог</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm" id="total_phys_weight">{{phys_weight1}}</p>
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
                    <p class="text-sm" id="total_bas_weight">{{basic_weight1}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm" id="total_fat">{{fat_kilo1}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm"></p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm" id="total_sum">{{sum1}}</p>
               </td> 

            </tr>
        </table>
    </div>

    <!-- NEW ITEM -->
    <modal name="create" class="modal-50">
        <form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" onsubmit="return false;">
            <div class="mb-8 font-medium">
              Новая поставка
            </div>
            <div class="space-y-4 mb-8">
                <div class="flex">
                    <div class="w-7/12">
                        <p class="w-1/12">Поставщик<span class="text-red-400">*</span></p>
                        <select class="border-b-2 w-full pb-1 w-9/12" v-model="form.supplier">
                            <option v-for="option in suppliers" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                    <a class="w-3/12 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3" :href="route('create-supplier')">Новый поставщик</a>

                </div>

                    
                <div>
                    <p class="w-2/6">Тип поставки<span class="text-red-400">*</span></p>
                    <select class="border-b-2 w-full pb-1 w-9/12" v-model="form.type">
                        <option value="0">Утренняя</option>
                        <option value="1">Вечерняя</option>
                    </select>
                </div>

                <div>
                    <p class="w-2/6">Физический вес<span class="text-red-400">*</span></p>
                    <input v-on:keyup.enter="onEnter" onclick="select()" type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.phys_weight">
                </div>

                <div>
                    <p class="w-1/6">Жирность<span class="text-red-400">*</span></p>
                    <input v-on:keyup.enter="onEnter" onclick="select()" type="text" step=".01" class="flex-auto border-b-2 w-full pb-1" v-model="form.fat">
                </div>

                <div>
                    <p class="w-1/6">Кислотность<span class="text-red-400">*</span></p>
                    <input v-on:keyup.enter="onEnter" onclick="select()" type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.acid">
                </div>

                <div>
                    <p class="w-1/6">Плотность<span class="text-red-400">*</span></p>
                    <input v-on:keyup.enter="onEnter" onclick="select()" type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.density">
                </div>

                <div>
                    <p class="w-1/6">Цена<span class="text-red-400">*</span></p>
                    <input  type="text" onclick="select()" class="flex-auto border-b-2 w-full pb-1" v-model="form.price">
                </div>

                <div>
                    <p class="w-1/6">Принимающий<span class="text-red-400">*</span></p>
                    <input  type="text" onclick="select()" class="flex-auto border-b-2 w-full pb-1" v-model="form.responsible">
                </div>

            </div>




              <div class="mt-4">
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
                      <button @click="store()" type="button" class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Создать</span></button>
                    </div>  
                  </div>
              </div>
            
              
          </form>
    </modal>

    <!-- SHOW SUPPLY -->
    <modal name="show1" class="modal-80 p-3" width="100%">
        <div class="p-4 overflow-x-auto">
            <div class="flex gap-5">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType" v-bind:value="1" v-model="quarter1" @change="test1()">
                    <span class="ml-2">1-10</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType" v-bind:value="2" v-model="quarter1" @change="test2()">
                    <span class="ml-2">11-20</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType" v-bind:value="3" v-model="quarter1" @change="test3()">
                    <span class="ml-2">21-{{ latestDay }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType"  v-bind:value="5" v-model="quarter1" @change="test5()">
                    <span class="ml-2">Сегодня</span>
                </label>
              <!--<label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType" v-bind:value="4" v-model="quarter" @change="test4()">
                <span class="ml-2">1-{{ latestDay }}</span>
              </label>-->
                <label class="inline-flex items-center" >
                    <select v-model="month" @change="fullMonthSupplier($event.target.selectedIndex)">
                        <option v-for="(item, index) in list" :value="index">{{item}}</option>
                    </select>
                </label>
            </div>
            <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">
                <th>
                    Дата
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Принимающий
                    </p> 
                </th>

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Физический вес</p>
                </th>
                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Жирность %</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Кислотность
                    </p> 
                </th>
                
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Плотность
                    </p> 
                </th>

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Базовый вес
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Жир килограмм
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Цена
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Сумма
                    </p> 
                </th>

                

                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Операции
                    </p> 
                </th>

            </tr>

            <tr v-for="supply in mysupplies" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="supply.id">
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex">
                        <p class="text-sm">{{getDate(supply.created_at)}}</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.responsible}}</p>
               </td> 
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.phys_weight}}</p>
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.fat}}</p>
               </td> 
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.acid}}</p>
               </td> 
               
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.density}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.basic_weight}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.fat_kilo}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.price}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.sum}}</p>
               </td> 

               
                <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="deletePostavka(supply)">Удалить</button>
                </td> 

            </tr>
             <tr class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex">
                        <p class="text-sm">Итог</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm"></p>
               </td> 
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supplier_phys_weight}}</p>
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
                    <p class="text-sm">{{supplier_basic_weight}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supplier_fat_kilo}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm"></p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supplier_sum}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm"></p>
               </td> 


            </tr>
        </table>
        <br>
        <button class="w-3/12 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3" v-on:click="hideHistory">Закрыть</button>
        </div>
    </modal>
</div>
</template>

<script>

import Layout from '@/Shared/Layout'
import axios from 'axios'
import $ from 'jquery'
import Datepicker from 'vue2-datepicker'
import Vue from "vue";
import moment from "moment";
import 'vue2-datepicker/index.css'

export default {
    metaInfo: {
        title: 'Dashboard'
    },
    
    layout: Layout,
    data() {
        var today = new Date();
        var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth()+1, 0);
        let latestDay = lastDayOfMonth.getDate();

        console.log("last day", lastDayOfMonth);
        console.log("last day", latestDay);

        return {
            latestDay: latestDay,
            list2:[2020,2021,2022],
            list:['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            supplier_phys_weight: null,
            supplier_basic_weight: null,
            supplier_fat_kilo: null,
            supplier_sum: null,
            supplierid : null,
            quarter: this.getQuarter(),
            quarter1: 5,
            time: true,
            mydate: null,
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            date: new Date(),
            myday: null,
            picked: null,
            err : '',
            mysupplies: [],
            supplies1: this.supplies,
            sum1: this.sum,
            phys_weight1: this.phys_weight,
            basic_weight1: this.basic_weight,
            fat_kilo1: this.fat_kilo,
            period: false,
            form: this.$inertia.form({
                name: null,
                type: null,
                supplier: null,
                phys_weight: null,
                fat: null,
                acid: null,
                density: null,
                basic_weight: null,
                fat_kilo: null,
                price: null,
                sum: null,
                responsible: null
            }),
            combined: this.combinedSrc,
        }
    },
    props: {
        supplies: Array,
        suppliers : Array,
        sum: String,
        phys_weight: String,
        basic_weight: String,
        fat_kilo: String,
        combinedSrc: Array,
    },
    mounted(){

 
    },
    created() {
        
    },
    components: {
      Datepicker
    },
    watch: {

    },
    computed: {

    },
    methods: {
        fullMonthSupplier(index){
                        
            var month = index+1;
            if(month < 10){
                month = '0'+month;
            }

            var date = new Date();
            var lastDayDate = new Date(date.getFullYear(), this.month + 1, 0);
            this.latestDay = lastDayDate.getDate();
            
            axios.post('supplies/bysuppliermonth',{month: month, supplier: this.supplierid}).then(response => {
                this.mysupplies = response.data.mysupplies;
                this.combined = response.data.combined;
                this.supplier_phys_weight = response.data.phys_weight;
                this.supplier_basic_weight = response.data.basic_weight;
                this.supplier_fat_kilo = response.data.fat_kilo;
                this.supplier_sum = response.data.sum;

            });
        },
        fullMonth(index){
            
            var month = index+1;
            if(month < 10){
                month = '0'+month;
            }

            var date = new Date();
            var lastDayDate = new Date(date.getFullYear(), this.month + 1, 0);
            this.latestDay = lastDayDate.getDate();

            console.log("latest day", lastDayDate, this.latestDay);
            
            axios.post('supplies/bymonth',{year: this.year, month: month}).then(response => {
                this.supplies1 = response.data.mysupplies;
                this.combined = response.data.combined;
                this.phys_weight1 = response.data.phys_weight;
                this.basic_weight1 = response.data.basic_weight;
                this.fat_kilo1 = response.data.fat_kilo;
                this.sum1 = response.data.sum;
            });
        },
        fullYear(year){

            axios.post('supplies/byyear',{year: this.year, month: this.month}).then(response => {
                this.supplies1 = response.data.mysupplies;
                this.combined = response.data.combined;
                this.phys_weight1 = response.data.phys_weight;
                this.basic_weight1 = response.data.basic_weight;
                this.fat_kilo1 = response.data.fat_kilo;
                this.sum1 = response.data.sum;
            });
        },
        maintest1(){
            this.period = true;
            var date = new Date();

            var from = new Date(this.year, this.month, 1, 0, 0, 1);
            var to   = new Date(this.year, this.month, 10, 23,59,59);

            console.log("bydate", from, to);
 
            axios.post('supplies/bydate',{from : this.formatDate(from), to : this.formatDate(to)}).then(response => {
                this.supplies1 = response.data.mysupplies;
                this.combined = response.data.combined;
                this.phys_weight1 = response.data.phys_weight;
                this.basic_weight1 = response.data.basic_weight;
                this.fat_kilo1 = response.data.fat_kilo;
                this.sum1 = response.data.sum;
            });
        },
        maintest2(){
            this.period = true;
            var date = new Date();

            var from = new Date(this.year, this.month, 11, 0, 0, 1);
            var to   = new Date(this.year, this.month, 20, 23,59,59);
            
            axios.post('supplies/bydate',{from : this.formatDate(from), to : this.formatDate(to)}).then(response => {
                this.supplies1 = response.data.mysupplies;
                this.combined = response.data.combined;
                this.phys_weight1 = response.data.phys_weight;
                this.basic_weight1 = response.data.basic_weight;
                this.fat_kilo1 = response.data.fat_kilo;
                this.sum1 = response.data.sum;
            });
        },

        maintest3() {
            this.period = true;
            var date = new Date();

            // var lastDayDate = new Date(date.getFullYear(), this.month, 0);
            // this.latestDay = lastDayDate.getDate();
            
            var from = new Date(this.year, this.month, 21, 0, 0, 1);
            var to   = new Date(this.year, this.month, this.latestDay, 23, 59, 59);
            
            axios.post('supplies/bydate',{from : this.formatDate(from), to : this.formatDate(to)}).then(response => {
                this.supplies1 = response.data.mysupplies;
                this.combined = response.data.combined;
                this.phys_weight1 = response.data.phys_weight;
                this.basic_weight1 = response.data.basic_weight;
                this.fat_kilo1 = response.data.fat_kilo;
                this.sum1 = response.data.sum;
            });
        },

        maintest4(){
            this.period = true;

            var date = new Date();
            var lastDayDate = new Date(date.getFullYear(), this.month, 0);
            this.latestDay = lastDayDate.getDate();

            console.log("latest day", this.latestDay, this.month);
            
            axios.post('supplies/get-month',{month: this.month}).then(response => {
                this.supplies1 = response.data.mysupplies;
                this.combined = response.data.combined;
                this.phys_weight1 = response.data.phys_weight;
                this.basic_weight1 = response.data.basic_weight;
                this.fat_kilo1 = response.data.fat_kilo;
                this.sum1 = response.data.sum;
                this.mydate = null;
            });
        },

        maintest5(){
            this.period = false;
            var date = new Date();

            this.month = date.getMonth();
            this.year = date.getFullYear();

            var lastDayDate = new Date(date.getFullYear(), this.month+1, 0);
            this.latestDay = lastDayDate.getDate();

            console.log("latest day", this.latestDay, this.month);

            axios.post('supplies/bydate',{from : this.formatDate(date)}).then(response => {
                this.supplies1 = response.data.mysupplies;
                this.combined = response.data.combined;
                this.phys_weight1 = response.data.phys_weight;
                this.basic_weight1 = response.data.basic_weight;
                this.fat_kilo1 = response.data.fat_kilo;
                this.sum1 = response.data.sum;
                this.mydate = null;
            });
        },

        test1(){
            var date = new Date();
            var from = new Date(this.year, this.month, 1, 0, 0, 1);
            var to   = new Date(this.year, this.month, 10, 23, 59, 59);
            
            axios.post('supplies/bydate',{from : this.formatDate(from), to : this.formatDate(to), supplier : this.supplierid}).then(response => {
                this.mysupplies = response.data.mysupplies;
                this.combined = response.data.combined;
                this.supplier_phys_weight = response.data.phys_weight;
                this.supplier_basic_weight = response.data.basic_weight;
                this.supplier_fat_kilo = response.data.fat_kilo;
                this.supplier_sum = response.data.sum;
            });
        },
        test2(){
            var date = new Date();
            var from = new Date(this.year, this.month, 11, 0, 0, 1);
            var to   = new Date(this.year, this.month, 20, 23, 59, 59);
            
            axios.post('supplies/bydate',{from : this.formatDate(from), to : this.formatDate(to), supplier : this.supplierid}).then(response => {
                this.mysupplies = response.data.mysupplies;
                this.combined = response.data.combined;
                this.supplier_phys_weight = response.data.phys_weight;
                this.supplier_basic_weight = response.data.basic_weight;
                this.supplier_fat_kilo = response.data.fat_kilo;
                this.supplier_sum = response.data.sum;
            });
        },
        test3(){
            var date = new Date();
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

            var from = new Date(this.year, this.month, 21, 0, 0, 1);
            var to   = new Date(this.year, this.month, lastDay, 23, 59, 59);
            
            axios.post('supplies/bydate',{from : this.formatDate(from), to : this.formatDate(to), supplier : this.supplierid}).then(response => {
                this.mysupplies = response.data.mysupplies;
                this.combined = response.data.combined;
                this.supplier_phys_weight = response.data.phys_weight;
                this.supplier_basic_weight = response.data.basic_weight;
                this.supplier_fat_kilo = response.data.fat_kilo;
                this.supplier_sum = response.data.sum;
            });
        },
        test4(){
            var date = new Date();
            var from = new Date(this.year, this.month, 1);
            var to   = new Date(this.year, this.month, new Date(date.getFullYear(), date.getMonth() + 1, 0));
            
            axios.post('supplies/bydate',{from : this.formatDate(from), to : this.formatDate(to), supplier : this.supplierid}).then(response => {
                this.mysupplies = response.data.mysupplies;
                this.combined = response.data.combined;
                this.supplier_phys_weight = response.data.phys_weight;
                this.supplier_basic_weight = response.data.basic_weight;
                this.supplier_fat_kilo = response.data.fat_kilo;
                this.supplier_sum = response.data.sum;
            });
        },
        test5() {
            this.period = false;
            var date = new Date();
            
            this.month = date.getMonth();
            this.year = date.getFullYear();
            var lastDayDate = new Date(date.getFullYear(), this.month+1, 0);
            this.latestDay = lastDayDate.getDate();

            console.log("latest day", this.latestDay);

            axios.post('supplies/bydate',{from : this.formatDate(date), supplier: this.supplierid}).then(response => {
                this.mysupplies = response.data.mysupplies;
                this.combined = response.data.combined;
                this.supplier_phys_weight = response.data.phys_weight;
                this.supplier_basic_weight = response.data.basic_weight;
                this.supplier_fat_kilo = response.data.fat_kilo;
                this.supplier_sum = response.data.sum;
                this.mydate = null;
            });
        },

        test6() {
            // this.period = false;
            var date = this.mydate;
            
            axios.post('supplies/bydate',{from : this.formatDate(date[0]), to: this.formatDate(date[1]), supplier: this.supplierid}).then(response => {
                this.mysupplies = response.data.mysupplies;
                this.combined = response.data.combined;
                this.supplier_phys_weight = response.data.phys_weight;
                this.supplier_basic_weight = response.data.basic_weight;
                this.supplier_fat_kilo = response.data.fat_kilo;
                this.supplier_sum = response.data.sum;
                // this.mydate = new Date();
            });
        },

        getQuarter(){
            var day = new Date().getDate();
            if(day <= 10){
                return 1
            }
            else if(day >= 11 && day <= 20){
                return 2;
            }
            else if(day >= 21 && day <= 31){
                return 3;
            }else{
                return 4;
            }
        },
        getDate(timestamp){
            var date = new Date(timestamp);
            var month = date.toLocaleString('ru', { month: 'long' });
            var day = this.day(timestamp);
            
            // var h = this.hours(timestamp);
            var h = date.getHours();
            var m = this.minutes(timestamp);
    
            var formattedTime = (day-1) + ' ' + month + ' ' + h + ':' + m;
            return formattedTime;  
        },
        setDay(){
            console.log(this.mydate);
            if (!this.mydate[0]) return;

            this.period = false;
            this.quarter1 = null;

            // console.log(this.mydate);return;
            const data = {
                date_start : this.formatDate(this.mydate[0]),
                date_end : this.formatDate(this.mydate[1]),
            } 
            axios.post('supplies/date_range', data).then(response => {
                this.supplies1 = response.data.supplies;
                this.combined = response.data.combined;
                this.phys_weight1 = response.data.phys_weight;
                this.basic_weight1 = response.data.basic_weight;
                this.fat_kilo1 = response.data.fat_kilo;
                this.sum1 = response.data.sum;
            });
          
        },
        today(){
            var month  = this.date.toLocaleString('ru', { month: 'long' });
            this.myday = this.date.getDate();
            var day = this.date.getDate();

            if (this.mydate != null) {
                const start = new Date(this.mydate[0]);
                const end = new Date(this.mydate[1]);

                return `${start.getDate()} ${this.list[start.getMonth()]} - ${end.getDate()} ${this.list[end.getMonth()]}`;
            }

            if(this.period){
                if(this.quarter1 == 1){
                    var day = "1-10";
                }else if(this.quarter1 == 2){
                    var day = "11-20";
                }else if(this.quarter1 == 3){
                    var day = "21-" + this.latestDay;
                }else if(this.quarter1 == 4){
                    var day = "1-" + this.latestDay;
                }
            }

            return day + ' ' + this.list[this.month];
        },
        minutes(time){
            //var minutes = new Date(time);
            
            if (time === undefined) return -1;
            return time.substring(14,16);          
        },
        hours(time){
            console.log("time hour", time);
            if (time === undefined) return -1;

            var hour = parseInt(time.substring(11,13));
            return hour;
            
            // if(hour >= 18){
            //     return (hour) - 24;
            // }else{
            //     return hour;
            // }
        },
        day(time){
            if (time === undefined) return -1;

            return parseInt(time.substring(8,10))+1;
        },
        history(supplier) {
            console.log("show history", supplier);
            this.supplierid = supplier.id;
            
            if (this.mydate != null) {
                this.test6();
                this.quarter1 = null;
                
                this.$modal.show('show1');
                
                return;
            }

            if (this.quarter1 == 1) { this.test1();}
            else if(this.quarter1 == 2){this.test2();}
            else if(this.quarter1 == 3) {this.test3();}
            else this.test5();


            /*this.supplierid = supplier;
            axios.post('suppliers/history',{supplier : supplier}).then(response => {
              //console.log(response.data);
                
                this.mysupplies = response.data.supply;
                this.myday = response.data.day;

            });
            */

            this.$modal.show('show1');
        },
        hideHistory(){
            this.$modal.hide('show1');
        },
        openCreateModal() {
            this.$modal.show('create')
        },
        store(){
            if(confirm("Создать новую поставку?")){
                if(this.form.supplier === null) {
                    this.err = 'Выберите поставщика!'
                    return null;
                }

                if(this.form.type === null) {
                    this.err = 'Укажите тип поставки!'
                    return null;
                }

                if(this.form.phys_weight === null) {
                    this.err = 'Укажите вес!'
                    return null;
                }

                if(this.form.fat === null) {
                    this.err = 'Укажите процент жирности!'
                    return null;
                }

                if(this.form.acid === null) {
                    this.err = 'Укажите кислотность!'
                    return null;
                }

                if(this.form.density === null) {
                    this.err = 'Укажите плотность!'
                    return null;
                }

                if(this.form.price === null) {
                    this.err = 'Укажите цену!'
                    return null;
                }

                if (this.form.responsible === null) {
                    this.err = 'Укажите принимающего!'
                    return null;
                }

                this.$modal.hide('create')

                axios.post('supplies',{
                    supplier: this.form.supplier,
                    type: this.form.type,
                    phys_weight: this.form.phys_weight,
                    fat: this.form.fat,
                    acid: this.form.acid,
                    density: this.form.density,
                    price: this.form.price,
                    responsible: this.form.responsible,
                    base_weight: this.form.phys_weight/3.6*this.form.fat,
                    fat_kilo: (this.form.phys_weight*this.form.fat)/100,
                    sum:this.form.price*(this.form.phys_weight/3.6*this.form.fat),
                }).then(response => {
                    location.reload();

                    // console.log("response", response);
                    
                    //this.supplies1.push(response.data);



                    // this.supplies1 = [response.data, ...this.supplies1];

                    // this.form.reset();

                    // console.log(this.supplies1);

                    // var total_sum_el = document.getElementById('total_sum');
                    // var total_phys_weight_el = document.getElementById('total_phys_weight');
                    // var total_bas_weight_el = document.getElementById('total_bas_weight');
                    // var total_fat_el = document.getElementById('total_fat');
                    
                    // var total_sum = 0;
                    // var total_phys_weight = 0;
                    // var total_bas_weight = 0;
                    // var total_fat = 0;

                    // for (let i = 0; i < this.supplies1.length; i++) {
                    //     total_sum += this.supplies1[i].sum;
                    //     total_phys_weight += parseFloat(this.supplies1[i].phys_weight);
                    //     total_bas_weight += parseFloat(this.supplies1[i].basic_weight);
                    //     total_fat += parseFloat(this.supplies1[i].fat_kilo);
                    // }

                    // this.sum1 = Math.round(total_sum * 100) / 100;
                    // this.phys_weight1 = Math.round(total_phys_weight * 100) / 100;
                    // this.basic_weight1 = Math.round(total_bas_weight * 100) / 100;
                    // this.fat_kilo1 = Math.round(total_fat * 100) / 100;

                    // console.log(response.data);
                });
                /*this.supplies1.push({
                    name: this.form.supplier.name,
                    phys_weight: this.form.phys_weight,
                    fat_weight: this.form.fat,
                    acid: this.form.acid,
                    density: this.form.density,
                    price: this.form.price,
                    base_weight: this.form.phys_weight/3.6*this.form.fat,
                    fat_kilo: (this.form.phys_weight*this.form.fat)/100,
                    sum:this.form.price*(this.form.phys_weight/3.6*this.form.fat),
                    created_at: Date.now().toString()
                });
                console.log(this.form.supplier);
                //this.form.post(this.route('supply.store'))
                */
            }
        },
        getSupplierName(supplier){
            var name = supplier;
            this.suppliers.forEach(element => {
                    if(element.id == supplier){
                        name = element.name;
                    }
                });;
            return name;
        },
        showContent(index){
            // var myDiv = document.getElementById(index);
            // if(myDiv.children[1].style.display == 'none')
            //     myDiv.children[1].style.display = 'block';
            // else{
            //     myDiv.children[1].style.display = 'none';
            // }
        },

        onEnter(e) {
            console.log('on enter...', e);

            const form = event.target.form;
            const index = [...form].indexOf(event.target);
            
            const next_index = index + 1;
            form.elements[next_index].select();
            form.elements[next_index].focus();

            event.preventDefault();
        },


        formatDate(date) {
            return this.pad(date.getMonth() + 1, 2) + '/' + this.pad(date.getDate(), 2) + '/' + date.getFullYear() + ' ' + this.pad(date.getHours(), 2) + ':' + this.pad(date.getMinutes(), 2) + ':' + this.pad(date.getSeconds(), 2);
        },

        pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        },

        
        deletePostavka(supply) {
            var conf = confirm('Вы действительно хотите удалить поставку?');

            if (conf === false) return;

            console.log(supply);

            axios.post('supplies/delete-postavka', { supply: supply }).then((response) => {
                if (response.data == 0) {
                    alert('Произошла ошибка. Попробуйте позже');
                }
                
                if (response.data == 1) {
                    alert('Поставка удалена');
                    location.reload();
                }
            });
        },
    }
}
</script>