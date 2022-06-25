<template>
    <div>
        <div class="bg-grey p-0 rounded-md">
            <h3>Накладные</h3>
            <br><br>

            <div class="mb-5 flex justify-between">
                <div class="inline-block">
                    <input type="text" name="mailykent" class="border-b-2" v-model="company"><br>
                    <datepicker 
                        v-model="nak_date" 
                        type="date" 
                        :placeholder="nak_date"
                        :show-time-header = "time">
                    </datepicker>
                </div>
            </div>
            <div class="mb-5">
                <div class="inline-block">
                    <input type="text" list="shops" v-model="shop" class="border-b-2" label="Магазин" placeholder="магазины"/>
                    <datalist id="shops">
                        <option v-for="shop in shops">{{shop.name}}</option>
                    </datalist>
                    <br>
                    
                    <!-- <input type="text" list="option1" v-model="option" class="border-b-2" label="опция" placeholder="консегнация" />
                    <datalist id="option1">
                        <option>Консегнация для МКТ</option>
                        <option>Консегнация для себя</option>
                        <option>Оплата наличными</option>
                    </datalist> -->
                    <select v-model="option" class="border-b-2" label="опция" placeholder="консегнация">
                        <option value="Консегнация для МКТ">Консегнация для МКТ</option>
                        <option value="Консегнация для себя">Консегнация для себя</option>
                        <option value="Оплата наличными">Оплата наличными</option>
                    </select>
                </div>
            </div>
            <div v-if="myrealizations[0]">
                <div>
                    <div class="text-right mb-3">ИТОГ: {{ getNakTotal() }} тг</div>
                </div>
                
                <!--<tr class="text-center font-bold border-b border-gray-200">
                    <th>#</th>
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Брак</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>-->
                <div v-for="(item1, key1) in empty">
                    <div class="bg-white p-1 rounded-md mb-1 flex flex-row w-full">                       
                        
                        <!-- #{{key1+1}}: Наименование<br> -->
                        
                        <select name="items" class="border-b-2 text-xs w-40" v-model="nak_items[key1]" @change="putRows($event,key1)" style="font-size: 0.65rem">
                            <option></option>
                            <option v-for="item in assortment">{{item.type}}</option>
                        </select>
                        <!-- <br><br> -->
                        
                        <!-- Кол-во<br> -->
                        <input onclick="select()" type="text" style="border: 1px solid grey; font-size: 0.65rem" v-model="nak_amount[key1]" class="text-xs w-8" placeholder="Кол-во">
                        <!-- <br><br> -->

                        <!-- Брак<br> -->
                        <input onclick="select()" type="text" style="border: 1px solid grey; font-size: 0.65rem" v-model="nak_brak[key1]" class="text-xs w-8" placeholder="Брак">
                        <!-- <br><br> -->

                        <!-- Цена<br> -->
                        <input onclick="select()" type="text" style="border: 1px solid grey; font-size: 0.65rem" v-model="nak_price[key1]" disabled="true" class="text-xs w-8" placeholder="Цена">
                        <!-- <br><br> -->

                        <!-- Сумма<br> -->
                        <span class="text-xs text-right w-8" style="font-size: 0.65rem;">{{nak_price[key1] * (nak_amount[key1] - nak_brak[key1])}}</span>
                    </div>
                </div>

                <div>
                    <div class="text-right mb-12">ИТОГ: {{ getNakTotal() }} тг</div>
                </div>
            </div>
        </div>
        <div class="panel sticky p-0 m-0" style="position: fixed; bottom: 10px; margin: 0 auto;">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="saveNakladnoe()">
                сохранить
            </button>
            <!--<a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="/blank">
                скачать
            </a>-->
            <!--<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" >
                распечатать
            </button>-->
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showNakHistory()">
                история
            </button>
            <!--<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showNakReport()">
                отчет
            </button>-->

            
        </div>

        <modal name="nakreport">
            <div>
                <div class="p-5">
                    <select v-model="nak_month" @change="getNakReportByMonth($event.target.selectedIndex)">
                        <option v-for="(item, index) in list" :value="index">{{item}}</option>
                    </select>
                </div>
                <table class="w-full whitespace-nowrap mt-5 p-5">
                    <tr   class="text-center font-bold border-b border-gray-200">
                        <th>#</th>
                        <th>Наименование</th>
                        <th>Кол-во</th>
                        <th>Брак/Обмен</th>
                        <th>Сумма</th>
                        <th>Сумма Брак/Обмен</th>
                    </tr>
                    <tr v-for="(i, k) in my_nak_report" class="text-center">
                        <td>{{k+1}}</td>
                        <td class="text-left">{{i.name}}</td>
                        <td>{{i.amount}}</td>
                        <td>{{i.brak}}</td>
                        <td>{{i.sum}}</td>
                        <td>{{i.brak_sum}}</td>
                    </tr>
                </table>
            </div>
        </modal>

        <modal name="nak_history">
            <div class="px-6 py-6">
                История накладных
                
                <div v-for="nak in nakladnoe"   >
                    <a :class="nak.consegnation == 2 && nak.paid == 0 ? 'w-full border-3 mt-5 shadow-lg flex p-4 text-white bg-red-700':'w-full border-3 mt-5 shadow-lg flex p-4'" :href="'/blank/' + nak.id"><p>Накладная от {{moment(nak.created_at).format("DD-MM-YYYY")}}  №{{nak.id}}</p></a>
                    <button @click="nakIsPaid(nak)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded" v-if="nak.consegnation == 2 && nak.paid == 0">Оплачено</button>
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
import 'vue2-datepicker/index.css'
import moment from "moment"

export default {
    metaInfo: {
        title: 'Накладные'
    },
    
    layout: Layout,
    data() {

        return {
            
            list:['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            nak_date: moment(new Date()).format("DD-MM-YYYY"),
            nak_month: new Date().getMonth(),
            my_nak_report: this.nak_report,
            nak_amount:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            nak_brak:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            nak_sum:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            nak_price:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            nak_items:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            empty:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            shop:'',
            option:'',
            company: 'СПК Майлыкент-Сут',
            myrealizations: this.auth_realization,
            moment: moment,
            time: true,
        }
    },
    props: {
        nak_report: Array,
        shops: Array,
        nakladnoe: Array,
        auth_realization: Array,
        assortment: Object,        
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
        saveNakladnoe() {
            if (confirm("Сохранить накладную?")) {
                let counter = 0;
                var items = [];
                var amounts = [];
                var brak = [];

                this.nak_items.forEach(element => {                    
                    if(element != 0){
                        items.push(element);
                        amounts.push(this.nak_amount[counter]);
                        brak.push(this.nak_brak[counter]);
                    }
                    counter++;
                });

                var myoption = 2;
                if (this.option == "Консегнация для МКТ"){
                    myoption = 1;
                } else if (this.option == 'Оплата наличными') {
                    myoption = 3;
                }


                axios.post('/save-nak',{items: items,amounts:amounts,brak:brak,shop: this.shop,option:myoption,realization_id: this.auth_realization[0].id}).then(response => {
                    alert(response.data.message);
                    
                    this.nak_amount = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.nak_brak = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.nak_sum = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.nak_price = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.nak_items = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.empty = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    
                    this.option = '';
                    this.shop = '';

                    //location.reload();
                });
            }
        },
        putRows(event,key){
            var price = 0;
            var sum = 0;

            if (this.myrealizations[0]) {
                this.myrealizations[0].order.forEach(element => {
                    if(this.assortment[element.assortment_id].type == event.target.value){
                        price = this.getPivotPrice(element.assortment_id, this.myrealizations[0].percent);
                        sum = element.order_amount*price;
                    }
                });
            }
            this.nak_price[key] = price;
        },
        showNakHistory(){
            this.$modal.show('nak_history');
        },
        showNakReport(){
            this.$modal.show('nakreport');
        },

        getNakReportByMonth(month){           
            axios.post('/nak-report-by-month',{month: month+1}).then(response => {
                this.my_nak_report = response.data;
            });
        },

        nakIsPaid(nak) {
            var conf = confirm('Подтвердить оплату?');
            if (conf === false) {
                return;
            }

            axios.post('/pay-nak', {id: nak.id}).then(response => {
                this.nakladnoe = response.data;
            });
        },

        getPercent(amount) {
            for (var i in this.percents) {
                // console.log('compare', this.percents[i].amount, amount);

                if (parseInt(this.percents[i].amount) == parseInt(amount)) {
                    return this.percents[i];
                }
            }

            return null;
        },

        getPivotPrice(itemId, percentAmount) {
            var percent = this.getPercent(percentAmount);
            // console.log(percent, itemId, percentAmount);
            
            if (percent == null) return 0;

            for (var i in this.pivotPrices) {
                if (this.pivotPrices[i].percent_id == percent.id && this.pivotPrices[i].store_id == itemId) {
                    return this.pivotPrices[i].price;
                }
            }

            return 0;
        },

        getNakTotal() {
            var sum = 0;

            for (var key in this.empty) {
                sum += this.nak_price[key] * (this.nak_amount[key] - this.nak_brak[key]);
            }

            return sum;
        },
    }
}
</script>

