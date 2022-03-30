<template>
    <div>
       Склад готовой продукции
       <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 mt-6">
        
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Вид товара</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Количество</p>
                </th>
                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Цена</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Сумма
                    </p> 
                </th>
            </tr>

            <tr v-for="(item, i) in mygoods" class="text-left hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="item.id">
                <td class="px-6 pt-3 pb-3 w-8" >
                    <div class="flex">
                        <p class="text-sm">{{item.type}}</p>
                    </div>
                </td>  
                <td  class="px-6 pt-3 pb-3 w-auto flex justify-center" >
                    <div  @click="showInput(item.id)">
                        <!--<input type="number" name="" :id="item.id" :ref="item.id" :disabled="enabled(item.id)" v-model="mygoods[i].amount" @change="addStore(item.id,item.amount)">-->
                        <input type="number" name="" :id="item.id" :ref="item.id" v-model="mygoods[i].amount" @>
                    </div>
                </td>      
                <td class="px-6 pt-3 pb-3 w-8">
                    <div  @click="showPriceInput(item.id)">
                        <input type="number" name="" :id="item.id" :ref="item.id" disabled="true" v-model="mygoods[i].price">
                    </div>
                </td> 
                <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{mygoods[i].price*mygoods[i].amount}}</p>
                </td> 
               
              
            </tr>
            <tr class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8 text-left">Итог:</td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8">{{getSum()}}</td>

            </tr>
        </table>
    </div>
    </div>
</template>
<script>
	
import Layout from '@/Shared/Layout'
import axios from 'axios'
import $ from 'jquery'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'

export default {
    metaInfo: {
        title: 'New conversion'
    },
    
    layout: Layout,
    data() {

        return {
            mygoods: this.store
        }
    },
    props: {
        store: Array
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
        getSum(){
            var sum = 0;
            for(var i = 0; i < this.mygoods.length; i++){
                sum = sum + this.mygoods[i].sum;
            }
            return sum;
        },
    }
}
</script>