<template>
  <div class="py-6 px-6  overflow-y-auto overflow-x-hidden h-full">
    <h1 class="mb-8 font-medium text-xl">
      Новый план
    </h1>
    <div class="ma">
     
        <div class="mt-4 flex flex-wrap">

          


           <div class="w-full flex">
            <div class="lg:w-1/4">
             <p class="font-medium leading-6">Сотрудник
                <span class="text-red-400">*</span> 
              </p>  
            </div>
            <div class="lg:w-3/4">


              <v-select class="border-b-2 w-full pb-1" :options="users" v-model="selectedUser" ref="user_id"></v-select>


            </div>  
          </div>

  <div class="flex py-5 w-full">
    <div class="lg:w-1/4 ">
             <p class="font-medium leading-6">Тип 
                <span class="text-red-400">*</span> 
              </p>  
            </div>
            <div class="lg:w-3/4 ">
             <select  v-model="type" class="border-b-2 w-full pb-1">
               <option value="1">Количество звонков</option>
               <option value="2">Количество встреч</option>
               <option value="3">Количество сделок</option>
             </select>
            </div>  
          </div>
  </div>
        
          
          <div class="w-full flex mb-8">
           
            <div class="lg:w-1/4">
             <p class="font-medium leading-6">Значение
                <span class="text-red-400">*</span> 
              </p>  
            </div>
            <div class="lg:w-3/4">
              <input type="number" v-model="value"  class="border-b-2 w-full pb-1">
            </div>  
          </div>


          
 
          <div class="w-full flex">
            <div class="lg:w-1/4">
             <p class="font-medium leading-6">Заполните поле
                <span class="text-red-400">*</span> 
              </p>  
            </div>
            <div class="lg:w-3/4 flex justify-end items-center">
              <div class="text-red-500 font-medium mr-3">
                {{ err }}
              </div>
              <div>
          
              </div>
              <button @click="save" class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light">
                <span>Создать</span>
              </button>
            </div>  
          </div> 

        <div class="w-full flex mb-8">
           

        </div>
     
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import Checkbox from '@/Shared/Checkbox2'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import moment from 'moment'
import axios from "axios";

export default {
  computedDate() {
    return date.toISOString().substring(0, 10)
  },
  name: 'CreateTask',
  metaInfo: { title: 'Новая Задача' },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    Datepicker,
    Checkbox
  },
  props: {
    type: Number,
  },
  data() {
    return {
      type: null,
      value: null,
      err: '',
      selectedUser: {},
      users: [],
    }
  },
  created() {
    axios.get('/tasks/create')
      .then(response => {
        this.users = response.data.users 
    })
  },
  methods: {
     save(event) {
      axios.post('/plans/save', {
        type : this.type,
        value : this.value,
        user : this.selectedUser,
      })
      .then(response => {
          this.$modal.hide('create')
      })
    },
    CallDateFunction(date) {
      if (date) {
        this.form.deadline = date.toISOString().substring(0, 10)

        const dateString = date.toISOString().substring(0, 10)
        console.log(dateString)
      } else {
        console.log('null date')
      }
    },
    store() {
      this.err = '';
      if(this.form.value === null) {
        this.err = 'Заполните план!'
        return null;
      }
   
      if(this.selectedUser.code === undefined) {
        this.err ='Выберите ответственного!'
        return null;
      }

    },
    customFormatter(date) {
      return moment(date).format('YYYY-MM-DD hh:mm:ss')
    },
  },
}
</script>
