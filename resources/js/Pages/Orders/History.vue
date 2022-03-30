<template>
    <div>
        <h3>История заявок</h3>

            <div class="sm:hidden">
        <div class="w-full whitespace-nowrap ">
            <div v-for="(item1, key1) in orders" :id="key1" class="bg-white shadow rounded-lg py-5  px-3 my-3" @click="showContent(key1)">
                <div class="flex justify-between relative">
                    <p>{{item1.created_at.substring(0,10)}}</p>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="supply-menu hidden">
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between" v-for="item in item1.order">
                        <p class="text-sm w-40 truncate">{{assortment[item.assortment_id-2].type}}</p>       
                        <p class="text-sm">{{item.order_amount}}</p>
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

export default {
    metaInfo: {
        title: 'Dashboard'
    },
    
    layout: Layout,
    data() {

        return {
        }
    },
    props: {
        orders: Array,
        assortment: Array
    },
    mounted(){

 
    },
    created() {
        console.log(this.orders[0]);

    },
    components: {
      Datepicker
    },
    watch: {

    },
    computed: {

    },
    methods: {
        showContent(index){
            var myDiv = document.getElementById(index);
            if(myDiv.children[1].style.display == 'none')
                myDiv.children[1].style.display = 'block';
            else{
                myDiv.children[1].style.display = 'none';
            }
        }
    }
}
</script>