<template>
  <div class="flex flex-col h-full">
    <div class="panel flex justify-between items-start">
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="openCreateModal">Добавить магазин</button>
           <!-- <div class="mt-3 mb-3 flex gap-3">

          <input class="search-input" v-model="search_marketname" placeholder="Поиск по названию..." @keydown.enter="getMarkets(1)"/>
          <button @click="getMarkets(1)" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Найти</button>
        </div> -->
      <p>Показано: {{ branches.length }}</p>
    </div>

    <div class="w-full bg-white rounded-2xl h-auto overflow-y-auto mt-6">
      <table class="w-full whitespace-nowrap relative">
        <tr class="text-left font-bold border-b border-gray-200 bg-gray-200 sticky top-0">
          <th class="px-6 pt-4 pb-4 w-8">
            <p class="font-bold text-center">№</p>
          </th>
          <th class="px-6 pt-4 pb-4">
            <p class="font-bold text-left">Название</p>
          </th>
          <th class="px-6 pt-4 pb-4">
            <p class="font-bold text-center">Город</p>
          </th>
          <th class="px-6 pt-4 pb-4 w-8">
            <p class="font-bold text-center">Управление</p>
          </th>
        </tr>

        <tr v-for="branch in branches" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="branch.id">
          <td class="px-6 pt-1 pb-1 w-8">
            <p class="text-sm">{{ branch.id }}</p>
          </td>
          <td class="px-6 pt-1 pb-1">
            <p class="text-sm text-left">{{ branch.name }}</p>
          </td>
          <td class="px-6 pt-1 pb-1">
            <p class="text-sm">{{ branch.city ? branch.city.name : branch.city_id }}</p>
          </td>
          <td class="px-6 pt-1 pb-1 w-8">
            <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded" @click="editModal(branch)">
              <i class="fas fa-edit"></i>
            </button>
            <button class="bg-red-500 hover:bg-red-700 text-white font-medium py-2 px-3 rounded" @click="deleteModal(branch)">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      </table>
    </div>

    <modal name="create" class="modal-50">
      <form class="py-6 px-6 bg-white rounded-lg overflow-y-auto overflow-x-hidden h-full" @submit.prevent="store">
        <div class="mb-8 font-medium text-lg">Новый магазин</div>
        <div class="space-y-4 mb-8">
          <div>
            <p class="w-1/2 mb-2 font-medium">Название<span class="text-red-400">*</span></p>
            <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.name" />
          </div>

          <div>
            <p class="w-1/2 mb-2 font-medium">
              Город<span class="text-red-400">* {{ this.form.city_id }}</span>
            </p>
            <select v-model="form.city_id" class="flex-auto border-b-2 w-full pb-1 px-0">
              <option v-for="city of cities" :key="city.id" :value="city.id">{{ city.name }}</option>
            </select>
          </div>
        </div>

        <div class="mt-4">
          <div class="w-full flex justify-between">
            <div class="lg:w-1/4">
              <p class="font-medium leading-6">
                Заполните поля
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
        <div class="mb-8 font-medium text-lg">Редактировать магазин</div>
        <div class="space-y-4 mb-8">
          <div>
            <p class="w-1/2 mb-2 font-medium">Название<span class="text-red-400">*</span></p>
            <input type="text" class="flex-auto border-b-2 w-full pb-1" v-model="form.name" />
          </div>

          <div>
            <p class="w-1/2 mb-2 font-medium">
              Город<span class="text-red-400">* {{ this.form.city_id }}</span>
            </p>
            <select v-model="form.city_id" class="flex-auto border-b-2 w-full pb-1 px-0">
              <option v-for="city of cities" :key="city.id" :value="city.id">{{ city.name }}</option>
            </select>
          </div>
        </div>

        <div class="mt-4">
          <div class="w-full flex justify-between">
            <div class="lg:w-1/4">
              <p class="font-medium leading-6">
                Заполните поля
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
  layout: Layout,
  metaInfo: {
    title: 'Магазины',
  },
  props: {
    branches: Array,
    cities: Array,
  },
  data() {
    return {
      err: '',
      search_marketname: '',
      markets: [],
      form: this.$inertia.form({
        city_id: null,
        name: '',
        id: null,
      }),
    }
  },
  mounted() {
    this.markets = this.branches;
  },
  created() {},
  components: {},
  watch: {},
  computed: {},
  methods: {
    openCreateModal() {
      this.$modal.show('create')
    },

    getMarkets() {
        //this.markets = this.branches.findIndex()
    },

    async store() {
      this.err = ''
      if (this.form.name == '' || !this.form.city_id) {
        this.err = 'Заполните все поля!'
        return null
      }

     

      await axios.post('/markets/create', {city_id: this.form.city_id, name: this.form.name }).then(res => {
    
        this.$notify({
          group: 'foo',
          title: 'Успешно создан!',
          type: 'success',
          duration: 2000,
          text: this.$page.props.flash.success,
        })
            location.reload()
         this.$modal.hide('create')
      })
       
    },

    editModal(branch) {
      this.$modal.show('edit')
      this.form.id = branch.id
      this.form.name = branch.name
      this.form.city_id = branch.city_id
    },

    update() {
      this.err = ''

      if (this.form.amount === null) {
        this.err = 'Заполните %!'
        return null
      }

      axios.put('markets/' + this.form.id, { id: this.form.id, form: this.form }).then((response) => {
        this.$modal.hide('edit')

        this.form.id = null
        this.form.name = ''
        this.form.city_id = null

                  location.reload()

         this.$notify({
            group: 'foo',
            title: 'Успешно!',
            type: 'success',
            duration: 2000,
            text: this.$page.props.flash.success,
          })
      })
    },

    deleteModal(branch) {
      var conf = confirm('Вы действительно хотите удалить магазин?')

      if (conf === false) return

      axios.post('markets/delete', { branch: branch }).then((response) => {
        if (response.data == 0) {
          alert('Произошла ошибка. Попробуйте позже')
          return
        }

        if (response.data == 1) {
          alert('Магазин удален')
          location.reload()
        }
      })
    },
  },
}
</script>
