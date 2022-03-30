<template>
    <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-auto pt-2 ">
        <button @click="showAvans()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Получить аванс</button>
        <br><br>
        
        <table v-if="realizator" class="tableizer-table text-md">
            <thead>
                <tr class="tableizer-firstrow">
                    <th>Наименование товаров</th>
                    <th>Заявка</th>
                    <th>Отпущено</th>
                    <th>Возврат</th>
                    <th>Обмен брак</th>
                    <th>Брак на сумму</th>
                    <th>Продано</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in myreport" :class="item.sold > item.amount ? 'text-white bg-red-700':''">
                    <td>{{item.assortment.type}}</td><td>{{item.order_amount}}</td>
                    <td><p class="w-8">{{ item.amount }}</p></td>
                    <td><p class="w-8">{{ item.returned }}</p></td>
                    <td><p class="w-8">{{ item.defect }}</p></td>
                    <td>{{item.defect * item.assortment.price}}</td>
                    <td>{{item.sold}}</td>
                    <td><p class="w-8">{{ item.assortment.price }}</p></td>
                    <td>{{item.sold * item.assortment.price}}</td>
                    <td>&nbsp;</td>
                </tr>
                  
                <tr>
                    <td>накладное на возврат</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>ИТОГ</td>
                    <td>{{ totalBrak() }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>  </td>
                    <td>&nbsp;</td>
                </tr>
                
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>итог</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="2" class="text-right">{{ totalSum() }}</td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                    <td>сумма реализации</td>
                    <td><div  v-if="getRealizationSum()">{{ getRealizationSum() }}</div>
                        <div v-else></div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Продажа на нал</td>
                    <td>
                        <div v-if="getRealizationSum()">{{ totalSum() - getRealizationSum() - majit - sordor }}</div>
                        <!--<div v-if="getRealizationSum()">{{ totalSum() - totalSum() / 10 }}</div>-->
                        <div v-else>{{ totalSum() }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Мажит</td>
                    <td><p>{{ majit }}</p></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Сордор</td>
                    <td><p>{{ sordor }}</p></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>за услугу 10%</td>
                    <td><div v-if="getRealizationSum()">{{(totalSum()-getRealizationSum())/10}}</div>
                        <div v-else>{{totalSum()/10}}</div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>к оплате</td>
                    <td><div v-if="getRealizationSum()">{{(totalSum()-getRealizationSum()-majit-sordor)-((totalSum()-getRealizationSum())/10)}}</div>
                        <div v-else>
                            {{totalSum()-(totalSum()/10)-(majit)-(sordor)}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div v-if="avans" class="hidden sm:block">
            <div class="row">
                <div class="col-4 flex gap-5 mt-5">
                    <div>
                        <h6>Накладные под реализации</h6>
                        <div class="flex gap-3 mt-2" v-for="col in columns">
                            
                            <div>
                                <p>{{col.magazine.name}}</p>
                            </div>
                            <div><p class="block appearance-none mt-2 w-48 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" >{{ col.amount }}</p></div>
                        </div>
                    </div>

                </div> 
            </div>
        </div>
    </div>
</template>



<script>
    
import Layout from '@/Shared/Layout'
import axios from 'axios'
import $ from 'jquery'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import moment from "moment"

export default {
    metaInfo: {
        title: 'Dashboard'
    },
    
    layout: Layout,
    data() {

        return {
            avans: false,
            columns:[{
                magazine: null,
                amount: null,
                pivot: null
            }],
            mymagazines: [],
            myreport: this.report1,
        }
    },
    props: {
        realizator: Object,
        majit: Number,
        sordor: Number,
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
        showAvans() {
            axios.post("/realizator-order", {id : this.realizator.id} ).then(response => {
                this.avans = true;

                this.myreport = response.data.report;
                this.mymagazines = this.realizator.magazine;
                this.columns = response.data.columns;
                this.majit = response.data.majit;
                this.sordor = response.data.sordor;

                //console.log(this.myreport);
            });
        },

        totalBrak() {
            var total = 0;
            if (this.myreport != null) {
                this.myreport.forEach(element => {
                    total += element.defect * element.assortment.price;
                });
            }            

            return total;
        },

        totalSum() {
            var total = 0;

            if (this.myreport != null){
                this.myreport.forEach(element => {
                    total += element.sold * element.assortment.price;
                    //total -= element.defect * element.assortment.price;
                });
            }

            return total;
        },

        totalRealization() {
            let total = 0;

            if (this.columns != null) {
                this.columns.forEach(element => {
                    if (element != 0)
                        total = total + parseInt(element.amount);
                });
            }

            return total
        },

        getRealizationSum() {
            let total = 0;
            if (this.columns != null){
                this.columns.forEach(element => {
                    if(element != 0)
                        total = total+parseInt(element.amount);
                });
            }

            return total;
        }
    }
}
</script>    


