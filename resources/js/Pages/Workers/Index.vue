<template>
<div class="flex flex-col h-full">
    <div class="panel flex justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="openCreateModal">
            Добавить сотрудника
        </button>
    </div>

    <br>
    <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Имя</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Фамилия</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Зарплата</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Управление
                    </p> 
                </th>
            </tr>

            <tr v-for="worker in workers" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="worker.id">
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex" >
                        <p class="text-sm">{{worker.name}}</p>
                    </div>
                </td>  
                <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{worker.surname}}</p>
                </td>
                <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{worker.salary}}</p>
                </td>
                <td class="px-6 pt-3 pb-3 w-8">
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="deleteWorker(worker)">Удалить</button>
                </td>
            </tr>

        </table>
    </div>


    <modal name="create" class="modal-50">
        <form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" @submit.prevent="store">
            <div class="mb-8 font-medium">
              Новый сотрудник
            </div>
            <div class="space-y-4 mb-8">
                <div>
                    <p class="w-1/6">Имя<span class="text-red-400">*</span></p>
                    <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.name">
                </div>

                <div>
                    <p class="w-1/6">Фамилия<span class="text-red-400">*</span></p>
                    <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.surname">
                </div>

                <div>
                    <p class="w-1/6">Зарплата<span class="text-red-400">*</span></p>
                    <input type="number" class="flex-auto border-b-2 w-full pb-1" v-model="form.salary">
                </div>
            </div>




              <div class="mt-4">
                <div class="w-full flex justify-between">
                    <div class="lg:w-1/4"> 
                     <p class="font-medium leading-6">Заполните поля
                        <span class="text-red-400">*</span> 
                      </p>  
                    </div>
                    <div class="lg:w-3/4 flex justify-end items-center">
                      <div class="text-red-500 font-medium mr-3">
                        {{ err }}
                      </div>
                      <button class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Создать</span></button>
                    </div>  
                  </div>
              </div>
            
              
          </form>
    </modal>

    
</div>
</template>

<script>

import Layout from '@/Shared/Layout'
import axios from 'axios'

export default {
    metaInfo: {
        title: 'Сотрудники'
    },
    
    layout: Layout,
    data() {
        return {
            err:'',
            myworkers: null,
            form: this.$inertia.form({
                name: null,
                surname: null,
                salary: null,
            }),
        }
    },
    props: {
        workers: Array,
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
        openCreateModal() {
            this.$modal.show('create');
        },
        
        store(){
            this.err = '';
            if(this.form.name === null) {
                this.err = 'Заполните название!'
                return null;
            }

            if(this.form.surname === null) {
                this.err = 'Заполните фамилию!'
                return null;
            }

            if(this.form.salary === null) {
                this.err = 'Заполните зарплату!'
                return null;
            }


            this.$modal.hide('create')
            this.form.post(this.route('workers.store'))
        },


        deleteWorker(worker) {
            var conf = confirm('Вы действительно хотите удалить сотрудника?');

            if (conf === false) return;

            console.log(worker);

            axios.post('workers/delete', { worker: worker }).then((response) => {
                if (response.data == 0) {
                    alert('Произошла ошибка. Попробуйте позже');
                    return;
                }
                
                if (response.data == 1) {
                    alert('Сотрудник удален');
                    location.reload();
                }
            });
        },

    }
}
</script>