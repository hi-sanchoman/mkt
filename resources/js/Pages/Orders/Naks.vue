<template>
  <div>
    <div class="bg-grey p-0 rounded-md">
      <h3 class="text-lg font-bold mb-3">Накладные</h3>

      <div class="mb-5 flex justify-between">
        <div class="inline-block w-full">
          <input type="text" name="mailykent" class="border-b-2 w-full bg-transparent pb-2 mb-3" v-model="company" /><br />
          <datepicker v-model="nak_date" type="date" :placeholder="nak_date" :show-time-header="time" class="!w-full"> </datepicker>
        </div>
      </div>
      <div class="mb-5 text-sm">
        <select v-model="option" class="border-b-2 w-full py-1 rounded mb-3" label="опция" placeholder="консегнация">
          <option :value="null">Выберите тип</option>
          <option value="Консегнация для МКТ">Консегнация для МКТ</option>
          <option value="Консегнация для себя">Консегнация для себя</option>
          <option value="Оплата наличными">Оплата наличными</option>
          <option value="vozvrat">Возврат</option>
        </select>

         <search-select :options="branches" v-model="branch" @change="(option) => branch = option" class="mb-3" placeholder="Выберите магазин..."/>

        <div class="flex gap-1">
          <div class="font-bold mb-1" @click="needCheck = !needCheck">Нужен чек:</div>
          <input id="needCheck" v-model="needCheck" class="mb-1 w-6 h-6" type="checkbox" />
        </div>
      </div>
      <div v-if="myrealizations[0]">
        <div>
          <div class="text-right mb-3 mt-2 font-medium">ИТОГ: {{ getNakTotal().toFixed(2) }} тг</div>
        </div>

        <div v-for="(item1, key1) in products">
          <div class="bg-white p-1 rounded-md mb-1 flex flex-row w-full items-center">
            <span class="mr-1 font-bold" style="font-size: 0.65rem">{{ key1 + 1 }}</span>

            <input type="hidden" v-model="nak_items[key1]" />
            <span class="flex-1" style="font-size: 0.65rem; width: 25%">{{ item1.type }}</span>

            <!-- Кол-во -->
            <input onclick="select()" type="number" style="font-size: 0.65rem" v-model="nak_amount[key1]" class="mr-1 text-xs w-8 border focus:border-indigo-500 focus:shadow-lg px-0.5" placeholder="Кол-во" :disabled="option == 'vozvrat'" />

            <!-- Брак-->
            <input onclick="select()" type="number" style="font-size: 0.65rem" v-model="nak_brak[key1]" class="mr-1 text-xs w-8 border focus:border-indigo-500 focus:shadow-lg px-0.5" placeholder="Брак" />

            <!-- Цена<-->
            <input onclick="select()" type="number" style="font-size: 0.65rem" v-model="nak_price[key1]" disabled="true" class="mr-1 text-xs w-8 border focus:border-indigo-500 focus:shadow px-0.5" placeholder="Цена" />

            <!-- Сумма<br> -->
            <span class="text-xs text-right w-8" style="font-size: 0.65rem; width: 10%; min-width: 30px">{{ (nak_price[key1] * (nak_amount[key1] - nak_brak[key1])).toFixed(2) }}</span>
          </div>
        </div>

        <div>
          <div class="text-right mb-12 mt-2 font-medium">ИТОГ: {{ getNakTotal().toFixed(2) }} тг</div>
        </div>
      </div>
    </div>
    <div class="panel sticky p-0 m-0 mb-4" style="position: fixed; bottom: 10px; margin: 0 auto">
      <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="saveNakladnoe()">сохранить</button>
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showNakHistory()">история</button>
      <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showNakReport()">отчет</button>
    </div>

    <modal name="nakreport">
      <div>
        <div class="p-5">
          <select v-model="nak_month" @change="getNakReportByMonth($event.target.selectedIndex)">
            <option v-for="(item, index) in list" :value="index">{{ item }}</option>
          </select>
        </div>
        <table class="w-full whitespace-nowrap mt-5 p-5">
          <tr class="text-center font-bold border-b border-gray-200 py-2">
            <th class="py-2">#</th>
            <th class="py-2">Наименование</th>
            <th class="py-2">Кол-во</th>
            <th class="py-2">Брак/Обмен</th>
            <th class="py-2">Сумма</th>
            <th class="py-2">Сумма Брак/Обмен</th>
          </tr>
          <tr v-for="(i, k) in my_nak_report" class="text-center">
            <td>{{ k + 1 }}</td>
            <td class="text-left">{{ i.name }}</td>
            <td>{{ i.amount }}</td>
            <td>{{ i.brak }}</td>
            <td>{{ i.sum }}</td>
            <td>{{ i.brak_sum }}</td>
          </tr>
        </table>
      </div>
    </modal>

    <modal name="nak_history">
      <div class="px-3 py-3">
        <h2 class="font-bold">История накладных</h2>

        <div v-for="nak in nakladnoe" :key="nak.id">
          <a :class="nak.consegnation == 2 && nak.paid == 0 ? 'rounded w-full border rounded mt-5  flex p-4 text-white bg-red-700' : 'rounded w-full rounded border mt-5   flex p-4'" :href="'/blank/' + nak.id">
            <p class="text-sm font-medium">
              Накладная для <span class="underline font-bold" v-if="nak.shop != null">{{ nak.shop.name }}</span
              ><br />
              <span class="text-xs font-normal">Сумма: {{ nak.sum }} тг</span><br />
              <span class="text-xs font-normal">от {{ moment(nak.created_at).format('DD-MM-YYYY H:mm') }} - №{{ nak.id }}</span>
            </p>
          </a>
          <button @click="nakIsPaid(nak)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded mt-1" v-if="nak.consegnation == 2 && nak.paid == 0">Оплачено</button>
        </div>

        <section class="mt-2 flex">
          <div class="bg-blue-500 text-white px-2 py-1 text-center mr-2" @click="showNewerNakladnye" v-if="nakladnyePage - 1 > 0">Показать новее</div>
          <div class="bg-blue-500 text-white px-2 py-1 text-center" @click="showOlderNakladnye">Показать старее</div>
        </section>
      </div>
    </modal>
  </div>
</template>



<script>
import Layout from '@/Shared/Layout'
import SearchSelect from '@/Shared/SearchSelect'
import axios from 'axios'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import moment from 'moment' 

export default {
  metaInfo: {
    title: 'Накладные',
  },

  layout: Layout,
  data() {
    return {
      list: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
      nak_date: moment(new Date()).format('DD-MM-YYYY'),
      nak_month: new Date().getMonth(),
      my_nak_report: this.nak_report,
      nak_amount: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      nak_brak: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      nak_sum: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      nak_price: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      nak_items: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
      // empty:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
      branch: null,
      option: null,
      company: 'СПК Майлыкент-Сут',
      myrealizations: this.auth_realization,
      moment: moment,
      time: true,
      nakladnoe: [],
      modeChoose: 'choose',
      new_branch: null,
      nakladnyePage: 1,
      needCheck: false // нужен чек или нет
    }
  },
  props: {
    nak_report: Array,
    branches: Array,
    auth_realization: Array,
    assortment: Object,
    percents: Array,
    pivotPrices: Array,
    products: Array,
  },
  mounted() {},
  created() {
    for (var i = 0; i < this.products.length; i++) {
      this.putRows(this.products[i].id, i, this.products[i].type)
    }
    this.fetchNakladnye(1)
  },
  components: {
    Datepicker,
    SearchSelect,
  },
  watch: {},
  computed: {},
  methods: {
    showNewerNakladnye() {
      this.nakladnyePage = this.nakladnyePage - 1
      this.fetchNakladnye(this.nakladnyePage)
    },
    showOlderNakladnye() {
      this.nakladnyePage = this.nakladnyePage + 1
      this.fetchNakladnye(this.nakladnyePage)
    },
    fetchNakladnye(page) {
      axios.get('/realizator-nakladnye?page=' + page).then((response) => {
        this.nakladnoe = response.data
      })
    },
    setMode(mode) {
      this.modeChoose = mode
    },
    resetChooseMode() {
      this.modeChoose = 'choose'
      this.branch = null
      this.new_branch = null
    },
    saveNakladnoe() {
         
      if (this.branch == null || this.branch == '') {
        alert('Ошибка: укажите магазин')
        return
      }

   
      if (this.option == null || this.option == '') {
        alert('Ошибка: выберите тип консегнации')
        return
      }

      if (confirm('Сохранить накладную?')) {
        let counter = 0
        var items = []
        var amounts = []
        var brak = []

        this.nak_items.forEach((element) => {
          if (element != 0) {
            items.push(element)
            amounts.push(this.nak_amount[counter])
            brak.push(this.nak_brak[counter])
          }
          counter++
        })

        var myoption = 2
        if (this.option == 'Консегнация для МКТ') {
          myoption = 1
        } else if (this.option == 'Оплата наличными') {
          myoption = 3
        } else if (this.option == 'vozvrat') {
          myoption = 9
        }

        axios.post('/save-nak', {
          items: items,
          needCheck: this.needCheck ? 1 : 0,
          amounts: amounts,
          brak: brak,
          branch_id: this.branch,
          new_branch: this.new_branch,
          option: myoption,
          realization_id: this.auth_realization[0].id
        }).then((response) => {
          alert(response.data.message)

          this.nak_amount = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
          this.nak_brak = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
          this.nak_sum = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
          this.nak_price = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
          this.nak_items = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        
          location.reload()
        })
      }
    },
    putRows(id, key, name) {
      var price = 0
      var sum = 0

      if (this.myrealizations[0]) {
        this.myrealizations[0].order.forEach((element) => {
          if (this.assortment[element.assortment_id]?.id == id) {
            price = this.getPivotPrice(element.assortment_id, this.myrealizations[0].percent)
            sum = element.order_amount * price
          }
        })
      }
      this.nak_items[key] = name
      this.nak_price[key] = price
    },
    showNakHistory() {
      this.$modal.show('nak_history')
    },
    showNakReport() {
      this.$modal.show('nakreport')
    },

    getNakReportByMonth(month) {
      axios.post('/nak-report-by-month', { month: month + 1 }).then((response) => {
        this.my_nak_report = response.data
      })
    },

    nakIsPaid(nak) {
      var conf = confirm('Подтвердить оплату?')
      if (conf === false) {
        return
      }

      axios.post('/pay-nak', { id: nak.id }).then((response) => {
        this.nakladnoe = response.data
      })
    },

    getPercent(amount) {
      for (var i in this.percents) {
        if (parseInt(this.percents[i].amount) == parseInt(amount)) {
          return this.percents[i]
        }
      }

      return null
    },

    getPivotPrice(itemId, percentAmount) {
      var percent = this.getPercent(percentAmount)
      if (percent == null) return 0

      for (var i in this.pivotPrices) {
        if (this.pivotPrices[i].percent_id == percent.id && this.pivotPrices[i].store_id == itemId) {
          return this.pivotPrices[i].price
        }
      }

      return 0
    },

    getNakTotal() {
      var sum = 0

      for (var i = 0; i < this.products.length; i++) {
        sum += this.nak_price[i] * (this.nak_amount[i] - this.nak_brak[i])
      }

      return sum
    },
  },
}
</script>

<style scoped>
.\!w-full {
  width: 100% !important;
}
</style>