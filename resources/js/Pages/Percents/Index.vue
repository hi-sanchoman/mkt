<template>
<div class="flex flex-col h-full">
    <div class="panel flex justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="openCreateModal">
            Добавить процент
        </button>
    </div>

    <br>
    <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Процент</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Управление
                    </p> 
                </th>
            </tr>

            <tr v-for="percent in mypercents" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="percent.id">
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex" >
                        <p class="text-sm">{{percent.amount}}%</p>
                    </div>
                </td>  
                <td class="px-6 pt-3 pb-3 w-8">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="editModal(percent)">Редактировать</button>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="deleteModal(percent)">Удалить</button>
                </td>
            </tr>

        </table>
    </div>


    <modal name="create" class="modal-50">
        <form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" @submit.prevent="store">
            <div class="mb-8 font-medium">
              Новый процент
            </div>
            <div class="space-y-4 mb-8">
                <div>
                    <p class="w-1/6">Процент %<span class="text-red-400">*</span></p>
                    <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.amount">
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
                      <button class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Добавить</span></button>
                    </div>  
                  </div>
              </div>
            
              
          </form>
    </modal>


    <modal name="edit" class="modal-50">
        <form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" @submit.prevent="update">
            <div class="mb-8 font-medium">
              Редактировать процент
            </div>
            <div class="space-y-4 mb-8">
                <div>
                    <p class="w-1/6">Процент %<span class="text-red-400">*</span></p>
                    <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.amount">
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
                      <button class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Сохранить</span></button>
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
        title: 'Проценты'
    },
    
    layout: Layout,
    data() {
        return {
            err:'',
            mypercents: this.percents,
            form: this.$inertia.form({
                amount: null
            }),
        }
    },
    props: {
        percents: Array,
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
        
        store() {
            this.err = '';
            if(this.form.amount == null || this.form.amount == '') {
                this.err = 'Заполните %!';
                return null;
            }

            this.$modal.hide('create');

            axios.post(this.route('percents.store'), { form: this.form }).then((response) => {
                location.reload();
            });
        },

        editModal(percent) {
            console.log(percent);
            this.$modal.show('edit');

            this.form.id = percent.id;
            this.form.amount = percent.amount;
        },

        update() {
            this.err = '';

            if (this.form.amount === null) {
                this.err = 'Заполните %!'
                return null;
            }

            axios.put('percents/' + this.form.id, { id: this.form.id, form: this.form }).then((response) => {
                // console.log(response);

                this.$modal.hide('edit');
                
                this.form.id = null;
                this.form.amount = null;

                this.mypercents = response.data.percents;
            });
        },

        deleteModal(percent) {
            var conf = confirm('Вы действительно хотите удалить процент?');

            if (conf === false) return;

            axios.post('percents/delete', { percent: percent }).then((response) => {
                if (response.data == 0) {
                    alert('Произошла ошибка. Попробуйте позже');
                    return;
                }
                
                if (response.data == 1) {
                    alert('Процент удален');
                    location.reload();
                }
            });
        },

    }
}
</script>