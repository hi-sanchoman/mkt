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
                <tr v-for="item in myreport" :class="item.sold + item.defect > item.amount ? 'text-white bg-red-700':''">
                    <td>{{item.assortment.type}}</td>
                    <td>{{item.order_amount}}</td>
                    <td><p class="w-8">{{ item.amount }}</p></td>
                    <td><p class="w-8">{{ item.returned }}</p></td>
                    <td><p class="w-8">{{ item.defect }}</p></td>
                    <td>{{item.defect*getPivotPrice(item.assortment)}}</td>
                    <td>{{item.sold}}</td>
                    <td><p class="w-8">{{ getPivotPrice(item.assortment) }}</p></td>
                    <td>{{item.sold*getPivotPrice(item.assortment)}}</td>
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
                    <td><div  v-if="getRealizationSum()">{{getRealizationSum()}}</div>
                        <div v-else></div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Продажа на нал</td>
                    <td><div v-if="getRealizationSum()">{{totalSum()-getRealizationSum()}}</div>
                        <div v-else>{{totalSum()}}</div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Мажит</td>
                    <td><input type="number" name="majit" v-model="majit"></td>
                </tr>
                <!-- <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Сордор</td>
                    <td><input type="number" name="sordor" v-model="sordor"></td>
                </tr> -->
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>за услугу {{ mypercent == null ? 0 : mypercent.amount }}%</td>
                    <td><div v-if="getRealizationSum()">{{ ((totalSum()-getRealizationSum()) * getOrderPercent() / 100).toFixed(2) }}</div>
                        <div v-else>{{ (totalSum() * getOrderPercent() / 100).toFixed(2) }}</div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>к оплате</td>
                    <td>
                        <div v-if="getRealizationSum()">
                            {{ ((totalSum()-getRealizationSum()-majit-sordor)-((totalSum()-getRealizationSum()) * getOrderPercent() / 100)).toFixed(2) }}</div>
                        <div v-else>
                            {{ (totalSum()-(totalSum() * getOrderPercent() / 100)-(majit)-(sordor)).toFixed(2) }}
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
                        <!-- <div class="flex gap-3 mt-2" v-for="col in columns">
                            
                            <div>
                                <p>{{col.magazine.name}}</p>
                            </div>
                            <div><p class="block appearance-none mt-2 w-48 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" >{{ col.amount }}</p></div>
                        </div> -->
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
            mypercent: null,
            columns:[{
                magazine: null,
                amount: null,
                pivot: null,
                isNal: false,
            }],
            mymagazines: [],
            myreport: this.report1,
            
        }
    },
    props: {
        realizator: Object,
        majit: Number,
        sordor: Number,
        report1: Array,
        percents: Array,
        pivotPrices: Array,
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

                console.log(response.data);

                this.myreal = response.data.real;
                this.mypercent = response.data.percent;
                this.myreport = response.data.report;
                this.mymagazines = this.realizator.magazine;
                this.columns = response.data.columns;
                this.majit = response.data.majit;
                this.sordor = response.data.sordor;

                //console.log(this.myreport);
            });
        },

        getRealizationSum(){
            let total = 0;
            if(this.columns != null){
                this.columns.forEach(element => {
                    if(element != null && element.isNal == false)
                        total = total + parseInt(element.amount);
                });
            }
            return total;
        },
        totalSum() {
            var total = 0;

            if (this.myreport != null){
                this.myreport.forEach(element => {
                    total += element.sold * this.getPivotPrice(element.assortment);
                    //total -= element.defect * element.assortment.price;
                });

                if (this.pageNakReturns) {
                    this.pageNakReturns.forEach(element => {
                        total += element.sum;
                    });
                }
            }

            return total;
        },

        totalBrak(){
            var total = 0;
            this.myreport.forEach(element => {
                
                total += element.defect * this.getPivotPrice(element.assortment);
            });
            return total;
        },

        getOrderPercent() {
            if (this.mypercent != null) {
                // console.log('order percent', this.mypercent);
                return this.mypercent.amount;
            }

            return 1;
        },

        getPivotPrice(item) {
            // console.log(item, this.mypercent);

            if (this.mypercent == null) {
                console.log('mypercent is 0');
                return 0;
            }

            for (var i in this.pivotPrices) {
                if (this.pivotPrices[i].percent_id == this.mypercent.id && this.pivotPrices[i].store_id == item.id) {
                    console.log(this.pivotPrices[i].price);

                    return this.pivotPrices[i].price;
                }
            }

            return 0;
        },
    }
}
</script>    


