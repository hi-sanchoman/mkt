<template>
    <div>
       <div class="w-auto bg-white rounded-2xl  h-auto p-6 overflow-auto pt-2">

        <div class="flex pt-5 pl-6">
          <p>Выбрать другой день:</p>
          <select class="border-2 rounded-lg ml-4" v-model="today" @change="getConversionsByDate()">
            <option v-for="(n, i) in parseInt(days)-1">{{i+1}}</option>
          </select> 
        </div>
        <div class="w-auto whitespace-nowrap  text-xs">
             <div v-for="item in assortments">
                <div class="px-6 pt-1 pb-1 text-left">{{item.name}}</div>
                <div v-if="getItem(item.id)" class="px-6 pt-1 pb-1">{{getItem(item.id).kg}}</div>
                <div v-else class="px-6 pt-1 pb-1"><input class="border-b-2" type="number" :name='item.assortment' :id='item.id' v-model='conversion[item.id-1]'></div>
                
            </div>
            <center><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2" @click="save()">сохранить</button></center>
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
        title: 'New conversion'
    },
    
    layout: Layout,
    data() {

        return {
           today: String(new Date().getDate()).padStart(2, '0'),
           changedConversions: this.myconversions,
           conversion: []
        }
    },
    props: {
        myconversions: Array,
        days: Number,
        assortments: Array
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
        getItem(assortment_id){
            for(var i = 0; i < this.changedConversions.length; i++){

                if(this.changedConversions[i].assortment == assortment_id){
                    return this.changedConversions[i];
                    break;
                }
            }
            return null;
        },
        getConversionsByDate(){
      
            axios.post('change',{timestamp : this.getTodaysTimestamp()}).then(response => {
         
                this.changedConversions = response.data;
          
            });
        },
        save(){
            axios.post('save', {conversions : this.conversion, timestamp : this.getTodaysTimestamp()}).then(response => {
                alert("Переработка сохранена");
            }); 
        },
        getTodaysTimestamp(){
            var nil = '';
        
            if(this.today < 10){
                nil = '0';
            }
        
            var timestamp = this.pad(new Date().getFullYear()) + '-' + this.pad(new Date().getMonth()+1) + '-'  + nil + this.today;

            return timestamp;
        },
        pad(number) {
            if ( number < 10 ) {
                return '0' + number;
            }
            return number;
        },
    }
}
</script>