<template>
	<div>
		<div class="sm:p-4 bg-white rounded-lg">
			<form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" @submit.prevent="store()">
            <div class="mb-8 font-medium">
              Новая поставка
            </div>
            <div class="space-y-4 mb-8">
                <div class="flex">
                    <div class="w-full">
                        <p class="w-1/12">Поставщик<span class="text-red-400">*</span></p>
                        <select class="border-b-2 w-full pb-1 " v-model="form.supplier">
                            <option v-for="option in suppliers" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                    

                </div>

                    
                

                <div>
                    <p class="w-1/6">Физический вес.<span class="text-red-400">*</span></p>
                    <input type="number" class="flex-auto border-b-2 w-full pb-1" v-model="form.phys_weight">
                </div>

                <div>
                    <p class="w-1/6">Жирность<span class="text-red-400">*</span></p>
                    <input type="number" step=".01" class="flex-auto border-b-2 w-full pb-1" v-model="form.fat">
                </div>

                <div>
                    <p class="w-1/6">Кислотность<span class="text-red-400">*</span></p>
                    <input type="number" class="flex-auto border-b-2 w-full pb-1" v-model="form.acid">
                </div>

                <div>
                    <p class="w-1/6">Плотность<span class="text-red-400">*</span></p>
                    <input type="number" class="flex-auto border-b-2 w-full pb-1" v-model="form.density">
                </div>

                <div>
                    <p class="w-1/6">Цена<span class="text-red-400">*</span></p>
                    <input type="number" class="flex-auto border-b-2 w-full pb-1" v-model="form.price">
                </div>

            

            </div>




              <div class="mt-4">
                <div class="w-full">
                    <div class="lg:w-1/4"> 
                     <p class="font-medium leading-6">Заполните поле
                        <span class="text-red-400">*</span> 
                      </p>  
                    </div>
                    <div class="lg:w-3/4 items-center">
                      <div class="text-red-500 font-medium mr-3">
                        {{ err }}
                      </div>
                      <button class="ml-3 text-sm leading-8 px-20 login_button rounded-full text-white h-8 w-auto flex justify-center items-center font-light"><span>Создать</span></button>
                    </div>  
                  </div>
              </div>
            
              
          </form>

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
            form: this.$inertia.form({
                name: null,
                supplier: null,
                phys_weight: null,
                fat: null,
                acid: null,
                density: null,
                basic_weight: null,
                fat_kilo: null,
                price: null,
                sum: null
            }),
        }
    },
    props: {
    	suppliers : Array,
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
        store(){
           
            if(this.form.supplier === null) {
                this.err = 'Выберите поставщика!'
                return null;
            }

            if(this.form.phys_weight === null) {
                this.err = 'Выберите вес!'
                return null;
            }

            if(this.form.fat === null) {
                this.err = 'Выберите процент жирности!'
                return null;
            }

            if(this.form.acid === null) {
                this.err = 'Выберите кислотность!'
                return null;
            }

            if(this.form.density === null) {
                this.err = 'Выберите плотность!'
                return null;
            }

            if(this.form.price === null) {
                this.err = 'Выберите цену!'
                return null;
            }

            this.$modal.hide('create')

            axios.post('supplies',{
                supplier: this.form.supplier,
                phys_weight: this.form.phys_weight,
                fat: this.form.fat,
                acid: this.form.acid,
                density: this.form.density,
                price: this.form.price,
                base_weight: this.form.phys_weight/3.6*this.form.fat,
                fat_kilo: (this.form.phys_weight*this.form.fat)/100,
                sum:this.form.price*(this.form.phys_weight/3.6*this.form.fat),
            }).then(response => {
                //this.supplies1.push(response.data);
                alert("Поставка отправелна");
            });
            /*this.supplies1.push({
                name: this.form.supplier.name,
                phys_weight: this.form.phys_weight,
                fat_weight: this.form.fat,
                acid: this.form.acid,
                density: this.form.density,
                price: this.form.price,
                base_weight: this.form.phys_weight/3.6*this.form.fat,
                fat_kilo: (this.form.phys_weight*this.form.fat)/100,
                sum:this.form.price*(this.form.phys_weight/3.6*this.form.fat),
                created_at: Date.now().toString()
            });
            console.log(this.form.supplier);
            //this.form.post(this.route('supply.store'))
            */
        }
    }
}
</script>