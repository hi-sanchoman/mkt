<template>
    <div>
        <div class="p-2">
            
            <div v-for="item in assortment" class="bg-white shadow p-3 rounded-lg mb-3">
                <div class="text-sm">{{item.type}}</div>
                    <div>
                        <div class="custom-number-input h-10 w-32">                          
                            <div class=" rounded-lg relative bg-transparent mt-1">
                               
                                <input type="number" v-model="dopOrder[item.id]" class="outline-none focus:outline-none text-center w-full bg-gray-300 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none" name="custom-input-number"></input>
                              
                            </div>
                        </div>
                    </div>
                </div>
                            
                <button @click="updateOrder()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Дополнить заявку</button>
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
            dopOrder: this.assorder1,
            myrealizations: this.auth_realization,
        }
    },
    props: {
        assortment: Object,
        assorder1: Object,
        auth_realization: Array,
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
      updateOrder() {
            var conf = confirm('Дополнить заявку?');
            if (conf == false) {
                return;
            }

            
            var id = 1;
            if(this.myrealizations != undefined && this.myrealizations.length > 0){
                id = this.myrealizations[this.myrealizations.length-1].id;
            }

            console.log("id", id);
            

            axios.post('orders/update',{
                order : this.dopOrder,
                realization_id : id,
            }).then(response => {
                location.href = '/realizators';
            });
        },
    }
}
</script>