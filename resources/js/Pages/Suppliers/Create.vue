<template>
<div class="flex flex-col h-full">

 
 <form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" @submit.prevent="store">
    <div class="mb-8 font-medium">
      Новый поставщик
    </div>
    <div class="space-y-4 mb-8">
        <div>
            <p class="w-1/6">Поставщик<span class="text-red-400">*</span></p>
            <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.name">
        </div>

        <div>
            <p class="w-1/6">Телефон<span class="text-red-400">*</span></p>
            <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.tel">
        </div>

        <div>
            <p class="w-1/6">Адрес<span class="text-red-400">*</span></p>
            <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.address">
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
            
              <button class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Создать</span></button>
            </div>  
          </div>
      </div>
    
      
</form>
    
</div>
</template>

<script>

import Layout from '@/Shared/Layout'

export default {
    metaInfo: {
        title: 'Create'
    },
    
    layout: Layout,
    data() {
        return {
            form: this.$inertia.form({
                name: null,
                tel: null,
                address: null
            }),
        }
    },
    props: {
        supplies: Array,
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
        store(){
           // alert();
            
           
            if(this.form.name === null) {
                alert('Заполните имя!');
                return null;
            }

            if(this.form.tel === null) {
                alert('Заполните номер телефона!');
                return null;
            }

            if(this.form.address === null) {
                alert('Заполните адрес!');
                return null;
            }

            this.form.post(this.route('suppliers.store'));
              
        }
    }
}
</script>