<template>
    <div>
       <div class="flex justify-between"><h3>Заявки</h3> <p class="text-sm">{{getToday()}}</p></div>
       <div class="bg-white w-full py-3 mt-3 rounded-sm">
           <div v-for="(item, number) in myorder" class="p-3 w-64 m-3 shadow  relative rounded-lg">
                <div class="flex justify-between" @click="showOrder(number)">
                   <p>{{item.realizator.first_name}}</p>
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <br>
                <div :id="number" class="hidden">
                    <div v-for="(item1, index) in item.assortment" v-if="item1.order_amount" class="flex justify-between text-sm">
                        <p>{{item1.name}}</p>
                        <p>{{item1.order_amount}}</p>
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

export default {
    metaInfo: {
        title: 'Морозильник'
    },
    
    layout: Layout,
    data() {
        return {
            myorder: this.order
        }
    },
    props: {
        order: Array
    },
    mounted(){

 
    },
    created() {

        console.log(this.myorder);
    },
    components: {
      Datepicker
    },
    watch: {

    },
    computed: {

    },
    methods: {
        showOrder(number){
            var myDiv = document.getElementById(number);
            if(myDiv.style.display == 'none')
                myDiv.style.display = 'block';
            else{
                myDiv.style.display = 'none';
            }
        },
        getToday(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = mm + '/' + dd + '/' + yyyy;
            return today;
        }
    }
}
</script>