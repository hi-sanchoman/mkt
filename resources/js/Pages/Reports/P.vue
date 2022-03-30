<template>
  <div class="flex flex-col h-full">
  	<!-- верхняя панель-->

  	<div class=" relative flex  place-items-center mb-8">
    	<h1 class="font-bold text-2xl w-2/12">Планы</h1>
		<div class="w-10/12 flex justify-between items-center">
			<button @click="openCreateModal" class="ml-5  rounded-full pl-24 pr-24 text-white h-8" v-bind:style="{ backgroundImage: gradient}">новый план</button>
			<!--<a :href="route('documents.create')" class="  rounded-full pl-24 pr-24 text-white h-8" v-bind:style="{ backgroundImage: gradient}">новый документ</a>-->
			<div class="w-64 flex justify-start"></div>
			<div>
				<input class="p-1.5 px-5  border border-gray-200 rounded-full w-full" v-model="search_key" placeholder="Поиск"  @keyup="search"/>
			</div>
    	<div v-if="$page.props.auth.user.message"><img class="h-10" src="img/message2.png" @click="$page.props.auth.sidebar = true">
    		
    	</div>
    	<div v-else><img class="h-10" src="img/message.png" @click="$page.props.auth.sidebar = true">
    		
    	</div>
		</div>
    	
    	
	</div>

	<!--панель офиса-->
	<div class="flex gap-6 flex-auto overflow-hidden">
	<!-- <div class="w-2/12 bg-white rounded-2xl h-auto p-6">
		<div class="relative">
		  <input type="search" class="text-white  left-0 w-full shadow rounded-full border-0 p-1 pl-4 pr-8 text-sm font-normal" v-bind:style="{ backgroundColor: color1}" >
		  <div class="absolute right-1 pin-r pin-t mt-2 mr-2 text-purple-lighter">
		  	<svg  class="h-3 text-white fill-current" viewBox="0 0 52.966 52.966">
		    	<path d="M51.704,51.273L36.845,35.82c3.79-3.801,6.138-9.041,6.138-14.82c0-11.58-9.42-21-21-21s-21,9.42-21,21s9.42,21,21,21
		        c5.083,0,9.748-1.817,13.384-4.832l14.895,15.491c0.196,0.205,0.458,0.307,0.721,0.307c0.25,0,0.499-0.093,0.693-0.279
		        C52.074,52.304,52.086,51.671,51.704,51.273z M21.983,40c-10.477,0-19-8.523-19-19s8.523-19,19-19s19,8.523,19,19
		        S32.459,40,21.983,40z"  />
			</svg>
		  </div>
		</div>

		<div class="mt-5">
			<div class="flex h-5 justify-start gap-4 mt-3 font-normal"><img class="y-3" src="/img/icons/Word.png" /><p class="pt-0 text-sm">Текстовые</p></div>
			<div class="flex h-5 justify-start gap-4 mt-3 font-normal"><img class="y-3" src="/img/icons/PPT.png" /><p class="pt-0 text-sm">Презентации</p></div>
			<div class="flex h-5 justify-start gap-4 mt-3 font-normal"><img class="y-3" src="/img/icons/PDF.png" /><p class="pt-0 text-sm">PDF файлы</p></div>
			<div class="flex h-5 justify-start gap-4 mt-3 font-normal"><img class="y-3" src="/img/icons/Excel.png" /><p class="pt-0 text-sm">Таблицы</p></div>
			<div class="flex h-5 justify-start gap-4 mt-3 font-normal"><img class="y-3" src="/img/icons/PIC.png" /><p class="pt-0 text-sm">Изображения</p></div>
			<div class="flex h-5 justify-start gap-4 mt-3 font-normal"><img class="y-3" src="/img/icons/Star.png" /><p class="pt-0 text-sm">Избранные</p></div>
			<div class="flex h-5 justify-start gap-4 mt-3 font-normal text-sm">Все</div>
		</div>
	</div> -->
	<!-- список документов-->
	<div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
    	<table class="w-full whitespace-nowrap  ">
    		<tr class="text-left font-bold border-b border-gray-200">

	            <th class="px-6 pt-4 pb-4 flex">
	            	<p class="font-bold text-center">Сотрудник</p>
	            </th>
	            <th class="px-6 pt-4 pb-4">
	            	<p class="font-bold text-center">Тип</p>
	            </th>
	            <th class="px-6 pt-4 pb-4">
	            	<p class="font-bold text-center">План</p>
	            </th>
	        </tr>

	        <tr v-for="plan in plans" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="plan.id">
	        	<td class="px-6 pt-3 pb-3 w-8">
					<div class="flex">
						<person-card :id="plan.user.id" class="relative" :src="'/storage/' + plan.user.photo_path" :fullname="plan.user.last_name + ' '+ plan.user.first_name" :job="plan.user.position.name" :hide="false"></person-card>
					</div>
               </td>  
	       	   <td class="px-6 pt-3 pb-3 w-8">
	       	   		<p class="text-sm" v-if="plan.type == 1">Количество звонков</p>
	       	   		<p class="text-sm" v-if="plan.type == 2">Количество встреч</p>
	       	   		<p class="text-sm" v-if="plan.type == 3">Количество сделок</p>
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
               		<p class="text-sm">{{plan.value}}</p>
               </td> 
               
	       	</tr>

   		</table>
   </div>
   </div>

   <modal name="create" class="modal-50">
      <create-plan></create-plan>
    </modal>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import Icon from '@/Shared/Icon'
import PersonCard from '@/Shared/PersonCard'
import createPlan from './CreatePlan.vue'

export default {
	metaInfo: { title: 'Документы' },
	layout: Layout,
	components: {
	    Icon,
		createPlan,
		PersonCard
	  },
	  props:{
	  	plans: Array,
	  },
	data () {
    	return {      	
			color: "#875FDA",
			color1: "#4A32E3",
			angle: '50',
			icon: "download",
			search_key: "",
			my_documents: this.documents,
		}
  	},
  	computed:{
		gradient(){
			return `linear-gradient(${this.angle}deg, ${this.color1}, ${this.color})`;
		},
		
	},
	methods: {
		search(){
			var key = this.search_key.toLowerCase();
			this.my_documents = this.documents.filter((x) => x.name.toLowerCase().search(key) >= 0);

		},
		openCreateModal() {
		this.$modal.show('create')
		},
	}
}
</script>
