<template>
    <div>
       Реализаторы
       <div class="w-full">
           <div v-for="item in realizators" class="bg-white p-3 shadow rounded-md py-2 my-2">
               <p>{{item.first_name}}</p>
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
        }
    },
    props: {
        realizators: Array
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