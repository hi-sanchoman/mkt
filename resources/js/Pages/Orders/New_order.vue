<template>
    <div>
        <div class="p-2">
            <h5><strong>Новая заявка с</strong></h5>

            <div style="width: 100%; margin-top: 30px; margin-bottom: 30px;">
                <p class="w-6/6">Процентная ставка %<span class="text-red-400">*</span></p>
                <select class="border-b-2 w-full pb-1 w-9/12" v-model="orderPercent">
                    <option v-for="percent in percents" :value="percent.id">{{ percent.amount }}%</option>
                </select>
            </div>

            <div v-if="assortment" v-for="item in assortment" class="bg-white shadow p-3 rounded-lg mb-3">
                                
                <div class="text-sm">{{item.type}}</div>
                    <div>
                        <div class="custom-number-input h-10 w-32">                          
                            <div class=" rounded-lg relative bg-transparent mt-1">                               
                                <input type="number" v-model="order[item.id]" class="outline-none focus:outline-none text-center w-full bg-gray-300 font-medium text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none" name="custom-input-number"></input>
                            </div>
                        </div>
                    </div>
                </div>
                            
                <button @click="sendOrder()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Оформить заявку</button>
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

export default {
    metaInfo: {
        title: 'Dashboard'
    },
    
    layout: Layout,
    data() {

        return {
            order: this.assorder1,
            myorder: this.assorder1,
            orderPercent: -1,
        }
    },
    props: {
        assortment: Object,
        assorder1: Object,
        auth_realization: Array,
        percents: Array,
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
      sendOrder(){
            var conf = confirm('Оформлять заявку?');
            if (conf == false) {
                return;
            }

                        
            axios.post('orders/send',{
                order : this.order,
                percent: this.orderPercent,
            }).then(response => {
                
                location.href = '/realizators';

            });
        },
    }
}
</script>