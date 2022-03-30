<template>
    <div>
       Морозильник

    <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 mt-6">
        <div class="flex gap-5">
            
        </div>
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

            <tr v-for="item in myfreezer" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="item.id">
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex">
                        <p class="text-sm">{{item.assortment}}</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8 as">
                    <input type="number" name="" v-model="item.amount">
                    {{ item.amount }}
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
                    <input type="number" name="" v-model="item.price">
               </td> 
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.amount*item.price}}</p>
               </td> 
               
              
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
        title: 'Морозильник'
    },
    
    layout: Layout,
    data() {
        return {
            myfreezer: this.freezer
        }
    },
    props: {
        freezer: Array
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
        
    }
}
</script>