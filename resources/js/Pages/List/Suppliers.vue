<template>
<div class="flex flex-col h-full">
    <div class="panel flex justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="openCreateModal">
          Новый поставщик
        </button>
    </div>

    <br>
    <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Поставщик</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Контакты</p>
                </th>
                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Поставки</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Сумма
                    </p> 
                </th>
            </tr>

            <tr v-for="supplier in suppliers" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="supplier.id">
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex" v-on:click="history(supplier.id)">
                        <p class="text-sm">{{supplier.name}}</p>
                    </div>
               </td>  
                <td class="px-6 pt-3 pb-3 w-8">
                    
                        <p class="text-sm">{{supplier.tel}}</p>
                    
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    
                        <p class="text-sm">{{supplier.count}}</p>
                    
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    
                        <p class="text-sm">{{supplier.sum}}</p>
                    
               </td>  

            </tr>

        </table>
    </div>
    <modal name="create" class="modal-50">
        <form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" onsubmit="return false;">
            <div class="mb-8 font-medium">
              Новая поставка
            </div>
            <div class="space-y-4 mb-8">
                <div>
                    <p class="w-1/6">Поставщик<span class="text-red-400">*</span></p>
                    <input v-on:keyup.enter="onEnter" onclick="select()" type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.name">
                </div>

                <div>
                    <p class="w-1/6">Телефон<span class="text-red-400">*</span></p>
                    <input v-on:keyup.enter="onEnter" onclick="select()" type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.tel">
                </div>

                <div>
                    <p class="w-1/6">Адрес<span class="text-red-400">*</span></p>
                    <input v-on:keyup.enter="onEnter" onclick="select()" type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.address">
                </div>

            </div>




              <div class="mt-4">
                <div class="w-full flex justify-between">
                    <div class="lg:w-1/4"> 
                     <p class="font-medium leading-6">Заполните поле
                        <span class="text-red-400">*</span> 
                      </p>  
                    </div>
                    <div class="lg:w-3/4 flex justify-end items-center">
                      <div class="text-red-500 font-medium mr-3">
                        {{ err }}
                      </div>
                      <button type="button" @click="store" class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Создать</span></button>
                    </div>  
                  </div>
              </div>
            
              
          </form>
    </modal>

    <modal name="show" class="modal-80 p-3" width="100%">
        <div class="p-4">
            <div class="flex gap-5">
              <label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType" value="personal" checked="true">
                <span class="ml-2">1-10</span>
              </label>
              <label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType" value="personal">
                <span class="ml-2">11-20</span>
              </label>
              <label class="inline-flex items-center">
                <input type="radio" class="form-radio" name="accountType" value="personal">
                <span class="ml-2">21-30</span>
              </label>
            </div>
            <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">
                <th>
                    Дата
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Физический вес</p>
                </th>
                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Жирность %</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Кислотность
                    </p> 
                </th>
                
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Плотность
                    </p> 
                </th>

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Базовый вес
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Жир килограмм
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Цена
                    </p> 
                </th>


                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Сумма
                    </p> 
                </th>

            </tr>

            <tr v-for="supply in mysupplies" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="supply.id">
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex">
                        <p class="text-sm">{{getDate(supply.created_at)}}</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.phys_weight}}</p>
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.fat}}</p>
               </td> 
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.acid}}</p>
               </td> 
               
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.density}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.basic_weight}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.fat_kilo}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.price}}</p>
               </td> 

               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{supply.sum}}</p>
               </td> 

            </tr>
        </table>
        <br>
        <button class="w-3/12 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3" v-on:click="hideHistory">Закрыть</button>
        </div>
    </modal>
</div>
</template>

<script>

import Layout from '@/Shared/Layout'
import axios from 'axios'

export default {
    metaInfo: {
        title: 'Suppliers'
    },
    
    layout: Layout,
    data() {
        return {
            err:'',
            mysupplies: null,
            form: this.$inertia.form({
                name: null,
                tel: null,
                address: null
            }),
        }
    },
    props: {
        suppliers: Array,
    },
    mounted(){

 
    },
    created() {
     

    },
    components: {

    },
    watch: {

    },
    computed: {

    },
    methods: {
        minutes(time){
          //var minutes = new Date(time);
          return time.substring(14,16);          
        },
        hours(time){
          return time.substring(11,13);
        },
        day(time){
          return parseInt(time.substring(8,10))+1;
        },
        getDate(timestamp){

          var date = new Date(timestamp);
          var month = date.toLocaleString('ru', { month: 'long' });
          var day = this.day(timestamp);
         
          var h = this.hours(timestamp);
          var m = this.minutes(timestamp);
  
          var formattedTime = day + ' ' + month + ' ' + h + ':' + m;
          return formattedTime;  
        },
        hideHistory(){
            this.$modal.hide('show');
        },
        history(supplier){
            axios.post('suppliers/history',{supplier : supplier}).then(response => {
                var supplies = response.data;
                this.mysupplies = supplies;
            });

            this.$modal.show('show');
        },
        openCreateModal() {
            this.$modal.show('create');
        },
        async store(){
            this.err = '';
            if(this.form.name === null) {
                this.err = 'Заполните название!'
                return null;
            }

            if(this.form.tel === null) {
                this.err = 'Выберите вес!'
                return null;
            }

            if(this.form.address === null) {
                this.err = 'Выберите процент жирности!'
                return null;
            }

            this.$modal.hide('create')
            await this.form.post(this.route('suppliers.store'))

            this.form.reset();

            console.log('created')
            
            //window.location.href = '/supp'
        },

        onEnter(e) {
            console.log('on enter...', e);

            const form = event.target.form;
            const index = [...form].indexOf(event.target);
            
            const next_index = index + 1;
            form.elements[next_index].select();
            form.elements[next_index].focus();

            event.preventDefault();
        }

    }
}
</script>