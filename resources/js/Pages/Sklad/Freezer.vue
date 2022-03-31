<template>
    <div class="flex flex-col h-full">
        <div class="panel flex justify-start gap-4">
            <button class="text-white font-bold py-2 px-4 rounded bg-blue-500"  @click="openStore()">
              Вернуться назад
            </button>
            

        </div>
        <br>

		<div class="flex justify-content justify-between">
			<h1><b>Управление морозильником</b></h1>
			
		</div>
		<br>
		<div class="flex justify-content justify-between w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
			    <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class="flex justify-content justify-between">
                <div class="border-b-2">
                    Дата
                    <datepicker 
                        v-model="from" 
                        type="date" 
                        placeholder=""
                        :range="myrange"
                        :input="showRangedData()"
                        :show-time-header = "time">
                    </datepicker>
                </div>
                
                <search-input v-model="poisk" class="pr-6 w-full lg:w-1/2" />

            </div>
        <br>
        <br>
        <div class="grid grid-cols-2 ">

              <div>
                <div class="flex justify-start gap-5">
                    <h3>Морозильник</h3>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showRashod()">
                        Приход/Расход
                    </button>
                </div>
            </div>
        </div>
         <table class="w-full whitespace-nowrap mt-5">
                   <tr>
	                   	<th>#</th>
	                   	<th>От</th>
	                   	<th>Продукт</th>
	                   	<th>Количество</th>
	                   	<th>Действие</th>
	                   	<th>Описание</th>
                   </tr>
                   <tr v-for="action in myactions" class="w-full whitespace-nowrap mt-5 tableizer-table" v-if=" from[0] && from[0].length == 0 && action.t_to.toLowerCase().includes(poisk.toLowerCase())">
                    <!--
                   <tr v-for="action in myactions" class="w-full whitespace-nowrap mt-5 tableizer-table" v-if=" action.t_to.includes(poisk) && new Date(action.created_at) >= new Date(from[0]) && new Date(action.created_at) <= new Date(from[1])">-->

	                   	<td>{{pad(new Date(action.created_at).getDate(), 2)+'.'+ pad(new Date(action.created_at).getMonth(), 2)+'.'+new Date(action.created_at).getFullYear()}}</td>
	                   	<td>{{action.t_from}}</td>
	                   	<td>{{action.t_to}}</td>
	                   	<td>{{action.amount}}</td>
	                   	<td>{{action.type}}</td>
	                   	<td>{{action.description}}</td>
                   </tr>
                   <tr v-else-if="action.t_to.toLowerCase().includes(poisk.toLowerCase()) && new Date(action.created_at) >= new Date(from[0]) && new Date(action.created_at) <= new Date(from[1])" class="w-full whitespace-nowrap mt-5 tableizer-table">
                       <td>{{pad(new Date(action.created_at).getDate(), 2)+'.'+ pad(new Date(action.created_at).getMonth(), 2)+'.'+new Date(action.created_at).getFullYear()}}</td>
                        <td>{{action.t_from}}</td>
                        <td>{{action.t_to}}</td>
                        <td>{{action.amount}}</td>
                        <td>{{action.type}}</td>
                        <td>{{action.description}}</td>
                   </tr>
                   <tr v-else-if="!from[0] && action.t_to.toLowerCase().includes(poisk.toLowerCase())" class="w-full whitespace-nowrap mt-5 tableizer-table">
                       <td>{{pad(new Date(action.created_at).getDate(), 2)+'.'+ pad(new Date(action.created_at).getMonth(), 2)+'.'+new Date(action.created_at).getFullYear()}}</td>
                        <td>{{action.t_from}}</td>
                        <td>{{action.t_to}}</td>
                        <td>{{action.amount}}</td>
                        <td>{{action.type}}</td>
                        <td>{{action.description}}</td>
                   </tr>
                </table>

                <!--<div class="pagination">
                  <a href="#" @click="shiftLeft()">&laquo;</a>
                  <a href="#" v-for="index in (actions.length/4)">{{index+1}}</a>
                  <a href="#" @click="shiftRight()">&raquo;</a>
                </div>-->
               
        <br>
    </div>
		</div>


		<modal name="freezer" >
            <form onsubmit="return false">
			<div class="p-5">
	            <select-input v-model="freez" class="pr-6 pb-8 w-full lg:w-2/2" label="Продукция">
	            	<option v-for="item in freezers" :value="item">{{item.assortment}}</option>
	            </select-input>
	            <select-input v-model="operation1" class="pr-6 pb-8 w-full lg:w-2/2" label="Операция">
	            	<option>Приход</option>
	            	<option>Расход</option>
	            	<option>Весовой склад</option>
	            </select-input>

	            <text-input v-model="amount1" class="pr-6 pb-8 w-full lg:w-2/2" label="Количество" /> 
	            <text-input v-model="description1" class="pr-6 pb-8 w-full lg:w-2/2" label="Описание" /> 

	            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addToFreezer()">сохранить</button>
	        </div>
            </form>
		</modal>
	</div>
</template>
<script>
import Layout from '@/Shared/Layout';
import axios from 'axios';
import $ from 'jquery';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css'

import NumberInput from '@/Shared/NumberInput'
import TextInput from '@/Shared/TextInput'
import SearchInput from '@/Shared/SearchInput'
import SelectInput from '@/Shared/SelectInput'
 
export default {
    metaInfo: {
        title: 'Dashboard'
    },
    
    layout: Layout,
    components: {
    	NumberInput,
        SelectInput,
        TextInput,
        SearchInput,
        Datepicker
    },
    data() {
    	return {
            page_actions: this.actions.slice(0, 3),
            poisk: "",
            myrange: true,
    		time: true,
            from: [Date.now,Date.now],
            to: new Date(),
            amount: null,
            amount1: null,
            myactions: this.actions,
            product: null,
            freez: null,
            operation: null,
            operation1: null,
            myfreezers: this.freezers,
        	description: null,
            description1: null,
        	difference:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        	myfreezer:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]//this.freezer
    	}
    },
    props: {
        products: Array,
        freezers: Array,
        actions: Array,
       // freezer: Array
    },
    mounted(){

    },
    created(){
    },

    watch: {

    },
    computed: {

    },
    methods: {

    	addToFreezer(){
    		//if(this.myproducts[key].amount >= this.difference[key]){
    		this.$modal.hide('freezer');
    		axios.post('/add-to-freezer',{amount: this.amount1, id: this.freez.id,description:this.description1,operation:this.operation1}).then(response => {
                    if(response.data.error){
                        alert('response.data.error');
                    }else{
                        this.myactions.push(response.data.action);
                        alert(response.data.message);
                    }
    		});
    		//}
    	},
    	showPrihod(){
    		this.$modal.show('sklad');
    	},
    	showRashod(){
    		this.$modal.show('freezer');
    	},
        showRangedData(){

           
        },
        shiftLeft(){
           
        },
        shiftRight(){},

        pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        },
        openStore() {
            window.location.href = '/store';
        },
    }
}
</script>