<template>
    <div>
       Тара и этикетки

       <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 mt-6">
            
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Наименование</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Упаковки</p>
                </th>
                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">шт. в упак.</p>
                </th>

                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Общ. кол.</p>
                </th>

                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Цена</p>
                </th>


                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Сумма</p>
                </th>

               <!--<td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">Требуется</p>
               </td> -->
            </tr>

            <tr v-for="(item, i) in mytara" class="text-left hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3">
                    
                    <p class="text-sm">{{item.name}}</p>
                  
               </td>  
               <td  class="px-6 pt-3 pb-3 ">
                    <div>
                        <input type="number" name="" :id="item.id" :ref="item.id" disabled="true" v-model="mytara[i].amount">
                    </div>
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
                    <div>
                        <input type="number" name="" :id="item.id" :ref="item.id" disabled="true" v-model="mytara[i].inside">
                    </div>
               </td> 
               
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.total-mytara[i].need}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <div>
                        <input type="number" name="" :id="item.id" :ref="item.id" disabled="true" v-model="mytara[i].price">
                    </div>
               </td> 
              

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.sum}}</p>
               </td> 


               <!--<td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{getAmount(item.store)}}</p>
               </td>--> 
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
            mytara: this.tara
        }
    },
    props: {
        tara: Array
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