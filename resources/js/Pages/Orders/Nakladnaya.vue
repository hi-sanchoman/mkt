<template>
  <div class="relative  min-w-500px max-sm:text-xs">
    <p class="text-lg font-bold mb-3 border-b pb-2">Накладная №{{ id }}</p>

    <template v-if="searchingMarket">
      <button
        class="btn btn-primary btn-sm mb-3"
        @click="
          searchingMarket = false
          poisk = ''
        "
      >
        Вернуться в накладную
      </button>
      <search-input v-model="poisk" class="w-full mb-3" placeholder="Поиск по магазину" />
      <div class="results">
        <div v-if="results.length === 0" class="pb-6 flex gap-3 mb-6 items-center">
          <span>Ничего не найдено</span>
          <div v-if="results.length === 0" class="text-white px-2 bg-indigo-500 py-1 rounded inline-block" @click="createMarket">Создать магазин</div>
        </div>

        <div v-for="(result, i) in results" :key="i">
          <div class="flex items-center justify-between mb-6">
            <div
              class="font-bold hover:bg-gray-100 cursor-pointer px-2 py-1 rounded"
              @click="
                market = result
                searchingMarket = false
              "
            >
              {{ result.name }}
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else-if="nakladnaya">
      <div class="flex gap-3 mb-3">
        <p class="font-bold w-1/4">Тип:</p>
        <select v-model="type" class="border-b-2" label="опция" placeholder="Тип накладной">
          <option value="1">Консегнация для МКТ</option>
          <option value="2">Консегнация для себя</option>
          <option value="3">Оплата наличными</option>
          <option value="9">Возврат</option>
        </select>
      </div>
      <div class="flex gap-3 mb-3 items-center">
        <p class="font-bold w-1/4">Магазин:</p>
        <span class="font-bold" v-if="market">{{ market.name }}</span>
        <button class="bg-indigo-500 px-2 py-1 rounded ml-1 text-white" @click="searchingMarket = true">Поменять</button>
      </div>
      <div class="flex gap-3 mb-3">
        <p class="font-bold w-1/4">Сумма:</p>
        <span class="font-normal">{{ nakladnaya.sum }}</span>
      </div>
      <div class="flex gap-3 mb-3">
        <p class="font-bold w-1/4">Реализатор:</p>
        <span class="font-normal">{{ nakladnaya.realizator }}</span>
      </div>
      <div class="flex gap-3 mb-3">
        <p class="font-bold w-1/4">Дата:</p>
        <span class="font-normal">{{ nakladnaya.date }}</span>
      </div>

      <table class="w-full mb-6 min-w-640px">
        <tr class="text-left">
          <th class="text-left pb-3 py-1" v-for="(header, h) in nakladnaya.headers" :key="'header' + h">
            {{ header }}
          </th>
        </tr>
        <tr v-for="(row, r) in items" :key="'row' + r" class="border">
          <td class="pl-1">{{ row.index }}</td>
          <td class="py-1">{{ row.name }}</td>
          <td class="py-1">
            <input type="number" v-model="row.amount" class="w-8" />
          </td>
          <td class="py-1">
            <input type="number" v-model="row.brak" class="w-8" />
          </td>
          <td class="py-1">{{ row.price }}</td>
          <td class="py-1">{{ +row.price * (+row.amount - +row.brak) }}</td>
        </tr>
        <tr>
          <td class="pl-1"></td>
          <td class="py-1"></td>
          <td class="py-1"></td>
          <td class="py-1"></td>
          <td class="py-1">ИТОГ</td>
          <td class="py-1">{{ total }}</td>
        </tr>
      </table>
    </template>
    <template v-else-if="loading">
      <div class="flex flex-col items-center p-4">
        <img class="w-8 h-8 bg-white" src="/img/loading.gif" alt="" />
        <p class="mt-4 text-center">Подождите, накладная загружается...</p>
      </div>
    </template>

    <div class="flex flex-col p-4 absolute top-0 left-0 justify-center w-full h-full z-10 bg-white bottom" v-if="saving">
      <img class="w-8 h-8 bg-white" src="/img/loading.gif" alt="" />
      <p class="mt-4 text-center">Подождите, накладная сохраняется...</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import SearchInput from '@/Shared/SearchInput'

export default {
  metaInfo: { title: 'Накладная' },
  props: {
    id: Number,
  },
  components: {
    SearchInput,
  },
  data() {
    return {
      nakladnaya: null,
      market: null,
      type: null,
      items: [],
      branches: [],
      loading: true,
      saving: false,
      poisk: '', // поиск магазина
      results: [], // результаты поиска магазинов
      searchingMarket: false, // режим поиск магазина
    }
  },
  computed: {
    total() {
      let sum = 0
      this.items.forEach((i) => {
        let s = +i.price * (+i.amount - +i.brak)
        sum += isNaN(s) ? 0 : +s
      })

      return sum
    },
  },
  watch: {
    poisk() {
      if (this.poisk === '') {
        this.results = []
        return
      }

      this.updateResults()
    },
  },
  created() {
    this.get()
  },
  methods: {
    updateResults() {
        if(this.poisk === '') {
            this.results = this.branches
            return
        }

      this.results = this.branches.filter((i) => {
        return i.name.toLowerCase().includes(this.poisk.toLowerCase())
      })
    },
    createMarket() {
      if (this.poisk === '') return
      if (!confirm('Вы уверены, что хотите создать новый магазин?')) return

      this.searchingMarket = false
      this.loading = true

      try {
        axios
          .post(`create-market`, {
            name: this.poisk,
            user_id: this.nakladnaya.user_id
          })
          .then((response) => {
            this.loading = false
            this.market = response.data
            this.poisk = ''
          })
      } catch (e) {
        this.loading = false
        this.searchingMarket = true
        alert('Ошибка создания магазина')
      }
    },

    get() {
      try {
        axios.get(`nakladnaya/${this.id}`).then((response) => {
          this.nakladnaya = response.data
          this.type = response.data.type
          this.market = response.data.market
          this.branches = response.data.branches
          this.items = this.parse(response.data.table)
          this.loading = false
           this.updateResults()
        })
      } catch (e) {
        this.loading = false
      }
    },

    parse(data) {
      let arr = []

      data.forEach((i) => {
        arr.push({
          index: i[0],
          name: i[1],
          amount: i[2],
          brak: i[3],
          price: i[4],
          sum: i[5],
          store_id: i[6],
        })
      })

      return arr
    },

    update() {
      try {
        this.saving = true
        axios
          .post(`nakladnaya/update`, {
            id: this.id,
            items: this.items,
            shop_id: this.market?.id,
            type: this.type,
          })
          .then((response) => {
            alert('Накладная обновлена')
            this.loading = false
            this.saving = false
            this.$emit('callback');
          })
          .catch(e => {
            console.log(e)
              this.loading = false
            this.saving = false
            alert(e ? e?.response?.data?.message : 'Ошибка обновления накладной')
          })
      } catch (e) {
        console.log(e)
        this.loading = false
        this.saving = false
        alert('Ошибка обновления накладной')
      }
    },
  },
}
</script>

<style scoped>
.min-w-640px {
    min-width: 640px;
}
.bottom {
  justify-content: flex-end;
  align-items: center;
  padding-bottom: 150px;
  background: rgba(255, 255, 255, 0.7);
}
</style>
