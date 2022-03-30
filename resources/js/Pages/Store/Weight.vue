<template>
    <div>
       Весовой склад
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

            <tr v-for="(item,i) in myweight" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="item.id">
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex">
                        <p class="text-sm">{{item.assortment}}</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.amount}}</p>
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
                    <div  >
                        <input type="number" name="" :id="item.id" :ref="item.id" disabled="true" v-model="weightgoods[i].price">
                    </div>
               </td> 
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.sum}}</p>
               </td> 
               
              
            </tr>

            <tr class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8 text-left">Итог:</td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8">{{getWeightSum()}}</td>

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
            myweight: this.weightgoods
        }
    },
    props: {
        weight: Array,
        weightgoods: Array
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
        getWeightSum(){
            var sum = 0;
            for(var i = 0; i < this.myweight.length; i++){
                sum = sum + this.myweight[i].sum;
            }
            return sum;
        },
    }
}
</script>