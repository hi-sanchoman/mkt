<template>
  <div class="flex flex-col h-full">
    <div class="panel flex gap-3 items-center">
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="openCreateModal">Добавить магазин</button>
      <div class="mt-3 mb-3 flex gap-3">
        <input class="search-input" v-model="search_marketname" placeholder="Поиск по названию..." @keydown.enter="fetch(1)" />
        <input class="search-input" v-model="search_realizator" placeholder="Поиск по реализатору..." @keydown.enter="fetch(1)" />
        <button @click="fetch(1)" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Найти</button>
      </div>
      <p>Показано: {{ markets.length }} из {{ pagination.total }}</p>

      <div class="paginatiosn flex gap-1">
        <div v-if="pagination.current_page > 1">
          <a @click.prevent="changePage(pagination.current_page - 1)" href="#" class="bg-white hover:bg-blue-500 hover:text-white px-2 py-1"><i class="fas fa-chevron-left"></i></a>
        </div>

        <div v-for="page in pages" :key="page" >
          <a @click.prevent="changePage(page)" href="#" :class="{ 'bg-white hover:bg-blue-500 hover:text-white px-2 py-1': true, '!bg-blue-600': page === pagination.current_page }">
            {{ page }}
          </a>
        </div>

        <div v-if="pagination.current_page < pagination.last_page">
          <a @click.prevent="changePage(pagination.current_page + 1)" href="#" class="bg-white hover:bg-blue-500 hover:text-white px-2 py-1"><i class="fas fa-chevron-right"></i></a>
        </div>
      </div>
    </div>

    <div class="w-full bg-white rounded h-auto overflow-y-auto mt-6">
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
          <th class="px-6 pt-4 pb-4">
            <p class="font-bold text-center">Реализаторы</p>
          </th>
          <th class="px-6 pt-4 pb-4 w-8">
            <p class="font-bold text-center">Управление</p>
          </th>
        </tr>

        <tr v-for="branch in markets" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3 baka" :key="branch.id">
          <td class="px-6 pt-1 pb-1 w-8">
            <p class="text-sm">{{ branch.id }}</p>
          </td>
          <td class="px-6 pt-1 pb-1">
            <p class="text-sm text-left">{{ branch.name }}</p>
          </td>
          <td class="px-6 pt-1 pb-1">
            <p class="text-sm">{{ branch.city ? branch.city.name : branch.city_id }}</p>
          </td>
          <div class="gap-1 mt-2 mb-2 flex flex-wrap">
            <div class="px-2 py-0.5 bg-green-300 rounded text-xs" v-for="user in branch.realizators" :key="user.id">
              <span>{{ findUser(user) }}</span>
            </div>
          </div>
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

        <div>
          <p class="w-1/2 mb-2 font-medium">Реализаторы</p>

          <select @change="addUser" class="flex-auto border-b-2 w-full pb-1 px-0">
            <option :value="null">Выберите реализатора</option>
            <option v-for="user of users" :key="user.id" :value="user.id">{{ user.first_name }} {{ user.last_name }}</option>
          </select>

          <div class="gap-1 mt-2 mb-2 flex flex-wrap">
            <div class="px-2 py-1 bg-gray-200 rounded" v-for="user in this.form.realizators" :key="user.id">
              <span>{{ findUser(user) }}</span>
              <span @click="removeUser(user)" class="cursor-pointer hover:text-indigo-500"><i class="fa fa-times"></i></span>
            </div>
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

          <div>
            <p class="w-1/2 mb-2 font-medium">Реализаторы</p>

            <select @change="addUser" class="flex-auto border-b-2 w-full pb-1 px-0">
              <option :value="null">Выберите реализатора</option>
              <option v-for="user of users" :key="user.id" :value="user.id">{{ user.first_name }} {{ user.last_name }}</option>
            </select>

            <div class="gap-1 mt-2 mb-2 flex flex-wrap">
              <div class="px-2 py-1 bg-gray-200 rounded" v-for="user in this.form.realizators" :key="user.id">
                <span>{{ findUser(user) }}</span>
                <span @click="removeUser(user)" class="cursor-pointer hover:text-indigo-500"><i class="fa fa-times"></i></span>
              </div>
            </div>
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
    cities: Array,
    users: Array,
  },
  data() {
    return {
      err: '',
      search_marketname: '',
      search_realizator: '',
      pagination: {},
      markets: [],
      page: 1,
      form: this.$inertia.form({
        city_id: null,
        name: '',
        id: null,
        realizators: [],
      }),
    }
  },
  mounted() {
    this.fetch(1)
  },
  created() {},
  components: {},
  watch: {},
   computed: {
    pages() {
      if (!this.pagination.to) {
        return [];
      }
      let from = this.pagination.current_page - 2;
      if (from < 1) {
        from = 1;
      }
      let to = from + 4;
      if (to > this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      const pagesArray = [];
      for (let i = from; i <= to; i++) {
        pagesArray.push(i);
      }
      return pagesArray;
    },
  },
  methods: {
    async fetch(page) {

      let m = this.search_marketname !== '' ? `&branch=${this.search_marketname}` : ''
      let r = this.search_realizator !== '' ? `&realizator=${this.search_realizator}` : ''

      await axios.get(`/markets/get?page=${page}${m}${r}`).then((res) => {
        this.markets = res.data.branches.data
        this.pagination = res.data.branches
      })
    },

    changePage(page) {
      this.page = page;
      this.fetch(page)
    },

    findUser(user) {
      return this.users.find((u) => u.id === user.id)?.first_name ?? user.id
    },
    removeUser(user) {
      let a = this.form.realizators
      let i = a.findIndex((u) => u.id === user.id)
      if (i !== -1) a.splice(i, 1)
      this.form.realizators = a
    },
    addUser(e) {
      if(e.target.value === null) return
      
      let real = this.form.realizators.find((u) => +u.id === +e.target.value)
      if (real) return
      let user = this.users.find((u) => +u.id === +e.target.value)
      if (user) {
        this.form.realizators.push(user)
      }
    },
    openCreateModal() {
      this.$modal.show('create')
    },

    getMarkets() {},

    async store() {
      this.err = ''
      if (this.form.name == '' || !this.form.city_id) {
        this.err = 'Заполните все поля!'
        return null
      }

      await axios.post('/markets/create', { city_id: this.form.city_id, name: this.form.name, realizators: this.form.realizators }).then((res) => {
        this.success('Успешно создан!')
        this.reset()
        this.fetch(this.page)
        this.$modal.hide('create')
      })
    },

    editModal(branch) {
      this.$modal.show('edit')
      this.form.id = branch.id
      this.form.name = branch.name
      this.form.city_id = branch.city_id
      this.form.realizators = branch.realizators
    },

    reset() {
      this.form.id = null
      this.form.name = ''
      this.form.city_id = null
      this.form.realizators = []
    },

    success(message) {
      this.$notify({
        group: 'foo',
        title: message,
        type: 'success',
        duration: 2000,
        text: this.$page.props.flash.success,
      })
    },

    update() {
      this.err = ''

      if (this.form.amount === null) {
        this.err = 'Заполните %!'
        return null
      }

      axios.put('markets/' + this.form.id, { id: this.form.id, form: this.form }).then((response) => {
        this.$modal.hide('edit')
        this.reset()
        this.fetch(this.page)
        this.success('Успешно!')
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
          this.success('Успешно удален!')
          this.fetch(this.page)
        }
      })
    },
  },
}
</script>

<style scoped>
tr.baka:nth-child(odd) {
  background-color: #f7f6ff; /* Change this to your desired color */
}
.search-input {
  padding: 0.25rem 1rem;
  border-radius: 5px;
  font-size: 0.8rem;
}
.\!bg-blue-600 {
  background-color: #3b82f6 !important;
}
</style>
