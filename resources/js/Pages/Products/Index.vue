<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Все товары</h1>
   	<div class="mb-6 flex justify-between items-center">
   
  
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
    	<table class="w-full whitespace-nowrap">
	    	<tr class="text-left font-bold">
	          <th class="px-6 pt-6 pb-4">Наименование</th>
	          <th class="px-6 pt-6 pb-4">Описание</th>
	          <th class="px-6 pt-6 pb-4">Категория</th>
	        </tr>
	        <tr v-for="product in products" class="hover:bg-gray-100 focus-within:bg-gray-100">
	       		<td>{{product.name}}</td>
	       		<td class="description">{{product.description}}</td>
	       		<td>{{categories[product.category_id]}}</td>
	       	</tr>
    	</table>
    </div>
  </div>
</template>
<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  metaInfo: { title: 'Create Contact' },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    products: Array,
    categories: Array,
    filters: Object,
  },
  methods: {
  },
}
</script>
