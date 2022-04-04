<template>
<div class="flex flex-col h-full">
    <div class="sidebar fixed  w-screen h-screen overflow-hidden flex  bg-indigo-500 bg-opacity-40 left-0"
        :class="[sidebar_zakvaska ? 'left-0' : 'left-full']"
        v-if="sidebar_zakvaska" >
        <div class="w-3/5 cursor-pointer" @click="sidebar_zakvaska = false"></div>

        

        <div class="w-2/5 bg-white overflow-y-auto">
    
            <div class="mb-8 flex justify-start w-full p-8 mr-2 items-center border-b">
                <h2>Информация о закваске:</h2>
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

                    <!-- <tr>
                        <button class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="saveZakvaska()">Сохранить</button>
                    </tr> -->
                </table>

        
            </div>
        </div>
    </div>


    <div class="grid grid-cols-2 sm:flex panel justify-start gap-3 hidden sm:block">
        <button v-if="real" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showInput()">
          Новая переработка
        </button>
         <!--<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Добавить ассортимент
        </button>-->
        <button class="hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" v-bind:class="{'bg-green-500' : real, 'bg-blue-500' : !real}" @click="showReport">
          Помесячный отчет
        </button>
        <a class="bg-blue-500 text-white font-bold py-4 px-4 rounded" :href="'conversions/'+month" >Скачать отчет</a>
        <!--<download-excel
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded text-center cursor-pointer"
          :data="json_data"
          :fields="json_fields"
          worksheet="My Worksheet"
          name="filename.xls"
        >
          Скачать отчет 
        </download-excel>-->

        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 flex justify-between hidden sm:flex">
            
            <div class="relative">
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" v-model="month" @change="changeMonth()">
                    <option v-for="month in selectMonth" :value="month.id" >{{month.month}}</option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>

            <div class="relative">
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" v-model="year" @change="changeYear()">
                    <option v-for="year in selectYear" >{{year}}</option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </div>
         <button v-if="month1.completed == 0 && month == month1.month" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="endMonth()">
            Завершить месяц
        </button>

        <div class="relative block sm:hidden">
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" v-model="month" @change="changeMonth()">
                    <option v-for="month in selectMonth" :value="month.id" >{{month.month}}</option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>

        <div class="relative block sm:hidden">
                <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" v-model="year" @change="changeYear()">
                    <option v-for="year in selectYear" >{{year}}</option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>

  </div>
  <br>
    <div v-if="real" class="w-full bg-white rounded-lg  h-auto overflow-auto sm:hidden">
    

        <table   class="w-full whitespace-nowrap text-xs">
            <thead class="bg-white custom1">
                <tr class="text-left font-bold border-b border-gray-200 bg-white">

                    <th class="pl-3 pt-4 pb-4 absolute bg-white custom ">
                        <p class="font-bold text-center w-48">Наименование</p>
                    </th>
                    <td class="px-6 pt-4 pb-4 sticky top-0 bg-white " v-for="(n, i) in parseInt(days)">
                        <p class="font-bold text-center ">{{i+1}}</p>
                    </td>
                    <th class="px-6 pt-4 pb-4 sticky top-0 bg-white ">Итог</th>

                </tr>
            </thead>
            <!--<tr class="text-left font-bold border-b border-gray-200" v-for="item in conversions">-->
            <tbody>
                <tr v-for="(item,j) in assortments" v-if="item.id != 25"> <!-- don't show: Дефростация. Приход -->
                    <th class="pl-6 pt-4 pb-4 text-left sticky left-0 bg-white w-48">{{item.name}}</th>
                    <td v-for="(n, i) in parseInt(days)"  class="px-6 pt-4 pb-4">
                        <p v-if="getKilo(i+1,item.id)">{{getKilo(i+1,item.id).kg}}</p>
                        <p v-else>0</p>
                    </td>   
                    <td v-if="getTotalKilo(item.id)" class="px-6 pt-4 pb-4">{{getTotalKilo(item.id)}}</td>
                    <td v-else class="px-6 pt-4 pb-4">0</td>
                </tr>
               
                 <tr>
                    <th class="px-6 pt-4 pb-4 text-left">Итог</th>
                    <td class="px-6 pt-4 pb-4" v-for="(n, i) in parseInt(days)">
                        
                    </td>
                    <td>{{mytotal}}</td>
                </tr>
                        
            </tbody>
        </table>
    </div>


    <div v-if="real" class="w-full bg-white rounded-2xl  h-auto overflow-auto hidden sm:block">
        <table   class="w-full whitespace-nowrap">
            <thead class="bg-white custom1">
                <tr class="text-left font-bold border-b border-gray-200 bg-white">

                    <th class="pt-4 pb-4 absolute bg-white w-72 custom">
                        <p class="font-bold text-center w-fit">Наименование</p>
                    </th>
                    <td class="px-6 pt-4 pb-4 sticky top-0 bg-white " v-for="(n, i) in parseInt(days)"  :class="{ 'red-column': getKilo(i, 1) != null && itog[i] != getKilo1(i, 1).kg }">
                        <p class="font-bold text-center " :id="itog[i]">{{i+1}}</p>
                        <!--<p v-if="getKilo(i,1)" class="font-bold text-center " :id="itog[i]">{{getKilo1(i, 1).kg}}</p>-->
                    </td>
                    <th class="px-6 pt-4 pb-4 sticky top-0 bg-white ">Итог</th>

                </tr>
            </thead>
            <!--<tr class="text-left font-bold border-b border-gray-200" v-for="item in conversions">-->
            <tbody>
                <tr v-for="(item,j) in assortments" v-if="item.id != 25">
                    <th class="pr-6 pt-4 pb-4 pl-7 text-left sticky left-0 bg-white ">{{item.name}} </th>
                    <td v-for="(n, i) in parseInt(days)"  class="px-6 pt-4 pb-4" :class="{ 'red-column': getKilo(i, 1) != null && itog[i] != getKilo1(i, 1).kg }">
                        <p v-if="getKilo(i,item.id)" >{{getKilo1(i,item.id, n).kg}}</p>
                        <p v-else>0</p>
                    </td>   
                    <td v-if="getTotalKilo(item.id)" class="px-6 pt-4 pb-4">{{getTotalKilo(item.id)}}</td>
                    <td v-else class="px-6 pt-4 pb-4">0</td>
                </tr>
               
                <!-- <tr>
                    <th class="pr-6 pt-4 pb-4 pl-7 text-left sticky left-0 bg-white ">Итог</th>
                    <td class="px-6 pt-4 pb-4" v-for="(n, i) in parseInt(days)" :class="{ 'red-column': getKilo(i, 1) != null && itog[i] != getKilo1(i, 1).kg }">
                        {{itog[i]}}
                    </td>
                    <td>{{mytotal}}</td>
                </tr> -->
                            <!--<tr class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                    <td class="px-6 pt-3 pb-3 w-8">
                        <div class="flex">
                            <p class="text-sm">Итог</p>
                        </div>
                   </td>  
                   <td class="px-6 pt-3 pb-3 w-8">
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
                   </td> 

                </tr>-->
            </tbody>
        </table>
    </div>


    <div v-if="input" class="w-auto bg-white rounded-2xl  h-auto p-6 overflow-auto pt-2">
        <form onsubmit="return false;">
            <button type="button" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="save()">
              {{buttonValue}}
            </button>

            <table class="w-auto whitespace-nowrap" v-if="current_month">
                <tr>
                    <td class="flex pt-5 pl-6">
                        <p>Выбрать другой день:</p>
                        <select class="border-2 rounded-lg ml-4" v-model="today" @change="getConversionsByDate()">
                            <option v-for="(n, i) in parseInt(days)">{{i+1}}</option>
                        </select> 
                    </td>
                    <td></td>
                    <td style="width: 150px;">&nbsp;</td>
                    
                    <td class="px-6 pt-4 pb-4">Молоко жир</td>
                    <td style="width: 100px;">&nbsp;</td>
                    <td class="px-6 pt-4 pb-4">Закваска</td>
                </tr>
                <tr v-for="item in assortments" v-if="item.id != 25">
                    <td class="px-6 pt-4 pb-4 text-left">{{item.name}}</td>
                    
                    <td v-if="item.id == 1 || item.id == 2 || item.id == 3" class="px-6 pt-4 pb-4">{{ getItem(item.id).kg }}</td>

                    <td v-else-if="getItem(item.id) && $page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                        
                        <input v-if="" class="pt-2 pb-2 border-b-2" type="text" v-on:keyup.enter="onEnter" onclick="select()" :name='item.assortment' :id='item.id' v-model='conversion[item.id]' >

                        в базе: {{getItem(item.id).kg}}

                    </td>

                    <td v-else-if="getItem(item.id) && $page.props.auth.user.position_id != 1" class="px-6 pt-4 pb-4">{{getItem(item.id).kg}}</td>

                    
                    <td v-else class="px-6 pt-4 pb-4">
                        <!-- $page.props.auth.user.position_id != 1 -->

                        <input v-if="" class="pt-2 pb-2 border-b-2" type="text" v-on:keyup.enter="onEnter" onclick="select()" :name='item.assortment' :id='item.id' v-model='conversion[item.id]'>
                    </td>
                    
                    <td style="width: 150px;">&nbsp;</td>

                    <td v-if="getMilkItem(item.id) && $page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                        <input v-if="inMilk(item.id)" class="pt-2 pb-2 border-b-2" type="text" v-on:keyup.enter="onEnter" onclick="select()" :name='item.assortment' :id='"m" +   item.id' v-model='dopMilk[item.id]'>

                        <input v-else-if="item.id == 21" type="text" v-model="vSlivki" disabled>

                        в базе: {{ getMilkItem(item.id).kg }}
                    </td>
                    
                    <td v-else-if="getMilkItem(item.id) && $page.props.auth.user.position_id != 1" class="px-6 pt-4 pb-4">
                        <span v-if="inMilk(item.id)">{{ getMilkItem(item.id).kg }}</span>

                        <input v-else-if="item.id == 21" type="text" v-model="vSlivki" disabled>
                    </td>
                    
                    <td v-else class="px-6 pt-4 pb-4">
                        <input v-if="inMilk(item.id) && isInTime()" class="pt-2 pb-2 border-b-2" type="text" v-on:keyup.enter="onEnter" onclick="select()" :name='item.assortment' :id='"m" +   item.id' v-model='dopMilk[item.id]'>

                        <input v-else-if="item.id == 21 && isInTime()" type="text" v-model="vSlivki" disabled>
                    </td>
                        
                    <td style="width: 100px;">&nbsp;</td>
                    
                    <td v-if="inZakvaska(item.id)">
                        <button class="btn" style="outline: 1px solid grey;" @click="showZakvaska(item.id)">посм. закваски</button>
                        <!-- <input v-if="inZakvaska(item.id)" class="pt-2 pb-2 border-b-2" type="text" v-on:keyup.enter="onEnter" onclick="select()" :name='item.assortment' :id='"z-" + item.id' v-model='dopZakvaska[item.id]'> -->
                    </td>
                </tr>

                <!--<tr class="text-left font-bold border-b border-gray-200" v-for="assortment in conversions">-->
               
               
                            <!--<tr class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                    <td class="px-6 pt-3 pb-3 w-8">
                        <div class="flex">
                            <p class="text-sm">Итог</p>
                        </div>
                   </td>  
                   <td class="px-6 pt-3 pb-3 w-8">
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
                   </td> 

                </tr>-->
            </table>
        </form>
    </div>

    <modal name="create" class="modal-50">
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
    </modal>
    <modal name="report" class="modal-50">
          <div class="w-full flex mb-8">
            
            <div class="lg:w-1/4">
              <p class="font-medium leading-6">Выберите месяц
                <span class="text-red-400">*</span> 
              </p>  
            </div>
            <div class="lg:w-3/4">
              <div class="border-b-2 w-full pb-1">
                
                <datepicker style="width: 100%;" v-model="month" type="datetime" placeholder=""></datepicker>
              </div>
            </div>  
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
import JsonExcel from "vue-json-excel";
 
Vue.component("downloadExcel", JsonExcel);


export default {
    metaInfo: {
        title: 'Dashboard'
    },
    
    layout: Layout,
    components: {
       JsonExcel
    },
    data() {

        return {
            //days: new Date(new Date().getFullYear(), new Date().getMonth()+1, 0).getDate(),
            mytotal: this.total,
            itog: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            current_month: this.month1.month == new Date().getMonth()+1,
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
            selectYear: [2020,2021,2022],
            since: new Date(),
            today: new Date().getDate(),//String(new Date().getDate()).padStart(2, '0'),
            conversion: [],
            dopMilk: [],
            dopZakvaska: [],
            loadedMilkFats: this.milkFats,
            loadedDopZakvaskas: this.dopZakvaskas,
            changedConversions: this.myconversions,
            weights: [],
            myrows: this.rows,
            myconversions1: this.conversions,
            real: true,
            input: false,
            createAss: false,
            timestamp: this.getTodaysTimestamp(),
            month: this.month1.month,
            year: this.month1.year,
            err : '',
            buttonValue: "сохранить",
            mysupplies: [],
            form: this.$inertia.form({
                supplier: null,
                phys_weight: null,
                fat: null,
                acid: null,
                density: null,
                basic_weight: null,
                fat_kilo: null,
                price: null,
                sum: null
            }),
            sidebar_zakvaska: false,
            selected_zakvaska: -1,
        }
    },
    props: {
        
        oiltotal: Number,
        assortments: Array,
        conversions: Array,
        milkFats: Array,
        dopZakvaskas: Array,
        myconversions: Array,
        rows: Array,
        assortments_total: Array,
        total: Number,
        price: Array,
        days: Number,
        month1: Object,
        zakvaskas: Array,
    },
    mounted(){

        console.log(this.$zakvaskaBlock);

    },
    created(){
            console.log(this.month1);
        //console.log(this.myconversions1);
        /*for(var i = 1; i <= this.assortments.length; i++ ){
            var data = {
                id: i,
                assortment: this.assortments[i-1].name,
                kg: this.myconversions1[i-1].kg, 
                store: null
            };
            this.json_data.push(data);
        }*/

        for (var day = 0; day < this.days; day++) {
            for (var j = 0; j < this.assortments.length; j++) {
                //console.log(this.assortments[j]);
                //continue;

                for (var i = 0; i <= this.myrows.length - 1; i++) {
                    if(this.getDay(this.myrows[i].created_at) == day+1 && this.myrows[i].assortment == this.assortments[j].id){
                        if([4,5,6,8,12,13,17,18,20].includes(this.assortments[j].id)){
                            this.itog[day] += parseFloat(this.myrows[i].kg);
                        }else if([10,11].includes(this.assortments[j].id)){
                            this.itog[day] -= parseFloat(this.myrows[i].kg);
                        }
                    }

                    //console.log("itog for", day, this.itog[day]);
                }
            }
        }

    },

    watch: {

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
                
                // if (this.dopMilk[4] !== undefined)
                //     val -= this.dopMilk[4];
                
                return val;
            }
        }
    },
    methods: {
        
        getKg(id){
            for(var i = 0; i < this.myconversions1.length;i++){
                if(this.myconversions1[i].assortment == id){
                    return this.myconversions1[i].kg;
                    break;
                }
            }

            return 0;
        },
        getTotalKilo(id){
            for(var i = 0; i < this.myconversions1.length;i++){
                if(this.myconversions1[i].assortment == id){
                    return this.myconversions1[i].kg;
                    break;
                }
            }

            return 0;
        },
        getTotal(){
            var total = 0;
            for(var i = 0; i < this.myconversions1.length; i++){
                if(i != 1 || i !=2)
                    total += parseInt(this.myconversions1[i] ? this.myconversions1[i].kg : 0);          
            }
            return total;
        },
        changeMonth(){
            axios.post('conversions/change',{month : this.month, year: this.year}).then(response => {
                this.myconversions1 = response.data.conversions;
                this.myrows = response.data.rowconversions;
                this.days = new Date(this.year, this.month, 0).getDate();
            });
        },
        getItem(assortment_id){
            for(var i = 0; i < this.changedConversions.length; i++){

                if(this.changedConversions[i].assortment == assortment_id){
                    return this.changedConversions[i];
                }
            }
            return null;
        },
        getMilkItem(assortment_id) {
            for(var i = 0; i < this.loadedMilkFats.length; i++){

                if(this.loadedMilkFats[i].assortment == assortment_id){
                    return this.loadedMilkFats[i];
                }
            }
            return null;
        },
        // getZakvaskaItems(assortment_id) {
        //     for (var i = 0; i < this.loadedDopZakvaskas.length; i++) {
        //         if (this.loadedDopZakvaskas[i].assortment == assortment_id) {
        //             return this.loadedDopZakvaskas[i];
        //         }
        //     }
        //     return null;
        // },
        getZakvaskaItem(assortment_id, zakvaska_id) {
            
            for (var i = 0; i < this.loadedDopZakvaskas.length; i++) {
                // console.log(this.loadedDopZakvaskas[i]);
                if (this.loadedDopZakvaskas[i].assortment == assortment_id && this.loadedDopZakvaskas[i].zakvaska_id == zakvaska_id) {
                    return this.loadedDopZakvaskas[i];
                }
            }

            return null;
        },
        getTodaysTimestamp(){
            var nil = '';
        
            if(this.today < 10){
                nil = '0';
            }
        
            var timestamp = this.pad(new Date().getFullYear()) + '-' + this.pad(new Date().getMonth()+1) + '-'  + nil + this.today;

            return timestamp;
        },
        getConversionsByDate(){
            this.current_month = true;
            axios.post('conversions/change',{timestamp : this.getTodaysTimestamp(), month : this.month1.month}).then(response => {
                console.log(response.data);

                // this.changedConversions = response.data.myconversions;
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
            let isAct = confirm("Действительно хотите сохранить?");

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
                //console.log(response.data); return;

                this.myrows = response.data.rows;
                alert(response.data.message);

                this.buttonValue = "Сохранить";
                this.input = false;
                this.real = true;
                
                if (this.month1.month != new Date().getMonth()+1){
                    this.current_month = false;
                }

                location.reload();
            });
                
            },
        getKilo(day, assortment){
            for (var i = 0; i <= this.myrows.length - 1; i++) {
                if(this.getDay(this.myrows[i].created_at) == day+1 && this.myrows[i].assortment == assortment){
                    return this.myrows[i]; 
                }
            }
        },
        getKilo1(day, assortment, n){
            //console.log("getKilo1", day, assortment, n);

            //return this.myrows[i];

            for (var i = 0; i <= this.myrows.length - 1; i++) {
                if(this.getDay(this.myrows[i].created_at) == day+1 && this.myrows[i].assortment == assortment){
                    /*if([4,5,6,8,12,13,17,19,21].includes(assortment)){
                        this.itog[day] += parseInt(this.myrows[i].kg);
                    }else if([10,11].includes(assortment)){
                        this.itog[day] -= parseInt(this.myrows[i].kg);
                    }*/
                    return this.myrows[i]; 
                }
            }
        },
        getDay(timestamp){
            var seconds = Date.parse(timestamp);
            var date = new Date(seconds);
            var day = date.getDay();
            
            return timestamp.substring(8, 10);
        },
        createAssortment(){
            this.createAss = !this.createAss;
        },
        report(){
          this.$modal.show('report');
        },
        history(supplier){
         
            axios.post('suppliers/history',{supplier : supplier}).then(response => {
                var supplies = response.data;
                this.mysupplies = supplies;
            });


            this.$modal.show('show');
        },
        hideHistory(){
            this.$modal.hide('show');
        },
        openCreateModal() {
            this.$modal.show('create')
        },
        showInput(){
            this.real = false;
            this.input = true;
        },
        showReport(){
            
            this.input = false;
            this.real = true;
        },
        store(){
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
        endMonth(){
            if(this.month1.month == new Date().getMonth()+1){
                alert('Месяц еще не закончен');
            }
            else{
                axios.get('conversions/end-month').then(response => {
                    alert(response.data);
                });
            }
        },
        downloadReport(month){
            axios.post
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
            // console.log(now, now.getTime(), date, date.getTime(), diff);

            return  diff <= hours * 60 * 60 * 1000;
        }, 

        showZakvaska(id) {
            this.selected_zakvaska = id;

            if (this.dopZakvaska[id] == undefined) {
                this.dopZakvaska[id] = [];
            }
            
            this.sidebar_zakvaska = true;
        }
    }
}
</script>