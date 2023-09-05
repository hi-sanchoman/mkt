<template>
    <div class="flex flex-col h-full">
        <div class="panel flex justify-start gap-4 mb-5">
            <button class="text-white font-bold py-2 px-4 rounded bg-blue-500" @click="openStore()">
              Вернуться назад
            </button>
        </div>
       
		<div class="flex justify-content mb-5 justify-between items-center">
			<h1 class="font-bold text-lg">Управление морозильником</h1>
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                @click="showRashod()">
                Приход/Расход
            </button>
		</div>
		
		<div class="flex justify-content justify-between w-full bg-white rounded-2xl p-6 h-auto overflow-y-auto">
			<div class="w-full">
                <div class="flex justify-content justify-between">
                    <!-- <div class="border-b-2">
                        Дата
                        <datepicker 
                            v-model="from" 
                            type="date" 
                            placeholder=""
                            :range="myrange"
                            :input="showRangedData()"
                            :show-time-header = "time">
                        </datepicker>
                    </div> -->

                    <div class="flex gap-3 w-72 max-w-full">
                        <select-input v-model="filterMonth"  class="w-full">
                            <option :value="1">Январь</option>
                            <option :value="2">Февраль</option>
                            <option :value="3">Март</option>
                            <option :value="4">Апрель</option>
                            <option :value="5">Май</option>
                            <option :value="6">Июнь</option>
                            <option :value="7">Июль</option>
                            <option :value="8">Август</option>
                            <option :value="9">Сентябрь</option>
                            <option :value="10">Октябрь</option>
                            <option :value="11">Ноябрь</option>
                            <option :value="12">Декабрь</option>
                        </select-input> 

                        <select-input v-model="filterYear"  class="w-full">
                            <option :value="year" v-for="year in years" :key="year">{{ year }}</option>
                        </select-input> 
                    </div> 
            
                    <search-input v-model="poisk" class="ml-auto w-full lg:w-1/2" placeholder='Поиск по продукту' />

                </div>

                <table class="w-full whitespace-nowrap mt-5">
                   <tr>
	                   	<th class="text-left pb-2">#</th>
	                   	<th class="text-left pb-2">От</th>
	                   	<th class="text-left pb-2">Продукт</th>
	                   	<th class="text-left pb-2">Количество</th>
	                   	<th class="text-left pb-2">Действие</th>
	                   	<th class="text-left pb-2">Описание</th>
                   </tr>
                   <tr
                    v-for="action in filtered"
                    class="w-full whitespace-nowrap mt-5 tableizer-table"
                    v-if=" from[0] && from[0].length == 0 && action.t_to.toLowerCase().includes(poisk.toLowerCase())"
                    >
        
	                   	<td>{{pad(new Date(action.created_at).getDate(), 2)+'.'+ pad(new Date(action.created_at).getMonth() + 1, 2)+'.'+new Date(action.created_at).getFullYear()}}</td>
	                   	<td>{{action.t_from}}</td>
	                   	<td>{{action.t_to}}</td>
	                   	<td>{{action.amount}}</td>
	                   	<td>{{action.type}}</td>
	                   	<td>{{action.description}}</td>
                   </tr>
                   <tr v-else-if="action.t_to.toLowerCase().includes(poisk.toLowerCase()) && new Date(action.created_at) >= new Date(from[0]) && new Date(action.created_at) <= new Date(from[1])" class="w-full whitespace-nowrap mt-5 tableizer-table">
                       <td>{{pad(new Date(action.created_at).getDate(), 2)+'.'+ pad(new Date(action.created_at).getMonth() + 1, 2)+'.'+new Date(action.created_at).getFullYear()}}</td>
                        <td>{{action.t_from}}</td>
                        <td>{{action.t_to}}</td>
                        <td>{{action.amount}}</td>
                        <td>{{action.type}}</td>
                        <td>{{action.description}}</td>
                   </tr>
                   <tr v-else-if="!from[0] && action.t_to.toLowerCase().includes(poisk.toLowerCase())" class="w-full whitespace-nowrap mt-5 tableizer-table">
                       <td>{{pad(new Date(action.created_at).getDate(), 2)+'.'+ pad(new Date(action.created_at).getMonth() + 1, 2)+'.'+new Date(action.created_at).getFullYear()}}</td>
                        <td>{{action.t_from}}</td>
                        <td>{{action.t_to}}</td>
                        <td>{{action.amount}}</td>
                        <td>{{action.type}}</td>
                        <td>{{action.description}}</td>
                   </tr>

                    <tr v-if="filtered.length === 0">
                        <td colspan="6" class="border-t py-2">Нет записей</td>
                    </tr>
                </table>

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
            poisk: "",
            myrange: true,
    		time: true,
            from: [Date.now,Date.now],
            to: new Date(),
            amount: null,
            amount1: null,
            myactions: this.actions,
            filtered: this.actions,
            product: null,
            freez: null,
            operation: null,
            operation1: null,
            myfreezers: this.freezers,
        	description: null,
            description1: null,
        	difference:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
        	myfreezer:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],//this.freezer
            filterMonth: new Date().getMonth() + 1,
            filterYear: new Date().getFullYear(),
            years: []
    	}
    },
    props: {
        products: Array,
        freezers: Array,
        actions: Array,
    },
    mounted(){

    },
    created(){

        // Fill years for select
        let a = new Date().getFullYear();
        let years = [];
        while(a > 2018) {
            years.push(a);
            a--;
        }
        this.years = years;
        this.filter();

    },

    watch: {
        filterMonth: function (val) { this.filter() },
        filterYear: function (val) { this.filter() },
    },
    computed: {
    
    },
    methods: {
        filter() {
            this.filtered = this.myactions.filter(a => {
                return new Date(a.created_at).getMonth() + 1 === this.filterMonth
                    && new Date(a.created_at).getFullYear() === this.filterYear
            });
        },

        resetFormFields() {  
            this.amount1 = null;
            this.freez = null;
            this.description1 = null;
            this.operation1 = null;
        },

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

                this.resetFormFields();
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