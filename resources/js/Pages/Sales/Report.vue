<template>
    <div>
       Отчет
       <div class="mt-4">
           <select class="form-select appearance-none
              block
              w-full
              px-3
              py-1.5
              text-base
              font-normal
              text-gray-700
              bg-white bg-clip-padding bg-no-repeat
              border border-solid border-gray-300
              rounded
              transition
              ease-in-out
              m-0
              focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example">
                <option selected>Реализатор</option>
                <option v-for="item, index in realizators" :value="index">{{item.first_name}}</option>
            </select>
       </div>
       <br>
       <div class="p-4">
           
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