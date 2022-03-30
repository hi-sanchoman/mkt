<template>
    <div class="p-1">
        <div class="">
            <div>
                от
                <datepicker 
                    v-model="from" 
                    type="date" 
                    placeholder=""
                    :show-time-header = "time">
                </datepicker>
            </div>
            <div>
                до
                <datepicker 
                    v-model="to" 
                    type="date" 
                    placeholder=""
                    :show-time-header = "time">
                </datepicker>
            </div>
            
        </div>
        <br>
        <br>
        <table class="w-full  ">
            <tr class="text-left font-bold border-b border-gray-200">
                <th class="px-1 pt-4 pb-4">Реализатор</th>
                <th class="px-1 pt-4 pb-4">Номер</th>
                <th class="px-1 pt-4 pb-4">Дата</th>
                <th class="px-1 pt-4 pb-4">Отчет</th>
            </tr>
            <tr class="text-left border-b border-gray-200" v-for="item in auth_realization" v-if="new Date(from) <= new Date(item.created_at) && new Date(to) >= new Date(item.created_at)">
                <td class="px-1 pt-3 pb-3 w-8">{{item.realizator.first_name}}</td>
                <td class="px-1 pt-3 pb-3 w-8">{{item.id}}</td>
                <td class="px-1 pt-3 pb-3 w-8 ">{{moment(item.created_at).format("DD-MM-YYYY")}}</td>
                <td class="px-1 pt-3 pb-3 w-8">
                    <div class="flex gap-2">
                        <button v-if="$page.props.auth.user.position_id != 3" @click="showReport3(item.id, item.realizator.id)" class="bg-green-500 text-white font-bold py-2 px-4 rounded">редактировать</button>
                        <a :href="'/realization_report/'+item.id" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center"
                        >
                          Скачать отчет 
                        </a>

                    </div>
                    

                </td>
            </tr>
        </table>
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
        	from: new Date(),
            to: new Date(),
            time: true,
            moment: moment,

            myrealizations: this.auth_realization,
        }
    },
    props: {
        auth_realization: Array,
    },
    mounted(){

 
    },
    created() {
        if (this.myrealizations != undefined && this.myrealizations[0]) {
            this.myrealizations[0].order.forEach(element => {
                //console.log(element.order_amount);
                this.myorder[element.assortment_id] = element.order_amount;

            });
        }
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