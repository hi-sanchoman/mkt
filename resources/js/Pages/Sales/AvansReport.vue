<template>
<div class="w-full bg-white">
    <!-- Select для таблицы в мобильной версии -->
    <select
        v-if="!hide"
        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 sm:hidden mb-4"
        id="grid-state" v-model="realizator" @change="loadTable()">
        <option v-for="item in realizators" :value="item">{{ item.first_name }}</option>
    </select>
    

    <!-- Таблица, которая появляется только в мобильной версии -->
    <div class="w-full overflow-x-auto">
        <table class="tableizer-table sm:hidden">
            <thead>
                <tr class="tableizer-firstrow">
                    <th>Наименование товаров</th>
                    <th>Заявка</th>
                    <th>Отпущено</th>
                    <th>Возврат</th>
                    <th>Обмен брак</th>
                    <th>Брак на сумму</th>
                    <th>Продано</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in myreport">
                    <td>{{ item.assortment.type }}</td>

                    <!-- Заявка -->
                    <td>{{ item.order_amount }}</td>

                    <!-- Отпущено -->
                    <td>
                        <input type="number" v-model="item.amount" class="w-8"
                            @change="setOrderAmount(item.id, item.amount)">
                    </td>

                    <!-- Возврат -->
                    <td>
                        <input class="w-8" type="number" v-model="item.returned"
                            @change="setOrderReturned(item.id, item.returned)">
                    </td>

                    <!-- Обмен брак -->
                    <td>
                        <input type="number" v-model="item.defect" class="w-8"
                            @change="setOrderDefect(item.id, item.defect)">
                    </td>

                    <!-- Брак на сумму -->
                    <td>{{ item.defect * item.price }}</td>

                    <!-- Продано -->
                    <td>{{ item.amount - item.returned - item.defect }}</td>

                    <!-- Цена -->
                    <td><input class="w-8" type="number" name="" :value="item.price"></td>

                    <!-- Сумма -->
                    <td>{{ item.price * (item.amount - item.returned - item.defect) }}</td>

                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>накладное на возврат</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>ИТОГ</td>
                    <td>{{ totalBrak() }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>итог</td>
                </tr>

                <!-- Итоги name:value -->
                <tr v-for="total in reportTotals">
                    <td colspan="8"></td>
                    <td>{{ total.name }}</td>
                    <td class="text-right">{{ total.value }}</td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- Выбор реализатора -->
    <div class="flex items-center mb-4 max-w-ef">
        <select
            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
            id="grid-state"
            v-model="realizator"
            @change="loadTable()"
        >
            <option value="">Выберите реализатора</option>
            <option v-for="item in realizators" :value="item">
                {{ item.first_name }}
            </option>
        </select>

        <!-- refresh -->
        <div class="rounded p-3.5 bg-blue-500 ml-3 h-48px" @click="loadTable()"><i class="fa fa-sync-alt fa fa-fw fa-inverse mt-1"></i></div>
    </div>


    <!-- Дата -->
    <div v-if="myreal" class="mb-3">
        Дата заявки: {{ moment(new Date(myreal.created_at)).format('DD.MM.YYYY HH:mm') }}
    </div>

    <!-- Таблица отчета -->
    <table v-if="realizator" class="tableizer-table text-md">
        <thead>
            <tr class="tableizer-firstrow">
                <th>№</th>
                <th>Наименование товаров</th>
                <th>Заявка</th>
                <th>Отпущено</th>
                <th>Возврат</th>
                <th>Обмен брак</th>
                <th>Брак на сумму</th>
                <th>Продано</th>
                <th>Цена</th>
                <th>Сумма</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>

            <tr v-for="(item, i) in myreport" :key="item.id"
                :class="item.sold > item.amount && item.order_amount > 0 ? ' bg-red-700' : ''"
            >
                <td>{{ (i + 1) }}</td>

                <!-- Продукт -->
                <td>{{ item.assortment.type }}</td>

                <!-- Заявка -->
                <td>{{ item.order_amount.toFixed(0) }}</td>

                <!-- Отпущено -->
                <td>
                    <input onclick="select()"
                        type="number"
                        v-model="item.amount"
                        class="w-8"
                        @keydown="onInputKey(item, 'amount')"
                        @change="onInputChange(item, 'amount')" />
                </td>

                <!-- Возврат -->
                <td :class="item.returnedUpdated ? 'bg-blue-100' : '' ">
                    <input onclick="select()"
                        type="number"
                        v-model="item.returned"
                        class="w-8"
                        @keydown="onInputKey(item, 'returned')"
                        @change="onInputChange(item, 'returned')" />
                </td> 

                <!-- Обмен брак -->
                <td>
                    <input onclick="select()"
                        type="number"
                        v-model="item.defect"
                        class="w-8"
                        @keydown="onInputKey(item, 'defect')"
                        @change="onInputChange(item, 'defect')" />
                </td>

                <!-- Брак на сумму -->
                <td>{{ (item.defect * item.price).toFixed(0) }}</td>

                <!-- Продано -->
                <td>{{ (item.sold - item.defect).toFixed(2) }}</td>

                <!-- Цена -->
                <td>
                    <input onclick="select()"
                        class="w-8"
                        @keydown="onInputKey(item, 'price')"
                        @change="onInputChange(item, 'price')"
                        type="number"
                        :value="item.price" />
                </td>

                <!-- Сумма -->
                <td>{{ ((item.sold - item.defect) * item.price).toFixed(1) }}</td>

                <td>&nbsp;</td>
            </tr>

            <tr>
                <td></td>
                <td>Накладное на возврат</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>ИТОГ</td>
                <td>{{ totalBrak().toFixed(1) }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td> </td>
                <td>&nbsp;</td>
            </tr>

            <!-- Возвратные накладные -->
            <tr v-for="(col, index) in columns" :key="index">
                <template v-if="col.is_return == 1">
                    <td></td>
                    <td>{{ col.magazine.name }}</td>
                    <td>{{ Math.abs(col.amount.toFixed(1)) }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </template>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>итог</td>
            </tr>
            
            <!-- Итоги name:value -->
            <tr v-for="(total, i) in reportTotals" :key="'total' + i">
                <td colspan="9"></td>
                <td>{{ total.name }}</td>
                <td class="text-right">{{ total.value }}</td>
            </tr>

        </tbody>
    </table>

     <!-- Накладные под реализации -->
    <div class="hidden sm:block mb-4">
        <div class="row">
            <div class="col-4 flex gap-5 mt-5">
                <div>
                    <h6 class="font-bold">Накладные под реализации</h6>

                    <div class="flex gap-3 mt-2 items-end" v-for="(col, ind) in columns" :key="ind">
                        <template v-if="col.is_return != 1 && col.isNal == false">
                            <div>
                                <select
                                    class="block appearance-none mt-2 w-96 bg-gray-200 border border-gray-200 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="grid-state" v-model="col.magazine">
                                    <option v-for="item in mymagazines" :key="item.id" :value="item">{{ item.name }}</option>
                                </select>
                            </div>
                            <div>
                                <input
                                    class="block appearance-none mt-2 w-48 bg-gray-200 border border-gray-200 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    type="number" name="amount" v-model="col.amount">
                            </div>

                            <span v-if="col != null && col.is_return == 1">(возвратная накладная)</span>
                        </template>
                    </div>

                    <div class="flex gap-3 mt-2 items-end" v-if="columns.length === 0">
                       Нет накладных
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Накладные (управление) -->
    <div v-if="myreal && myreal.is_produced == 1" class="hidden sm:block mb-4">
        <div class="row">
            <div class="col-4 flex gap-5 mt-5">
                <div>
                    <h6 class="font-bold mb-4">Накладные (управление)</h6>
                    <div v-for="nak in realizationNaks" :key="nak.name" class="flex gap-3 mb-1">

                        <button class="bg-red-500 hover:bg-red-800 text-white py-1 px-4 rounded"
                            @click="deleteNak(nak)" v-if="!hide">
                            удалить
                        </button>

                        <div class="hover:text-blue-500 cursor-pointer"
                            @click="showNakladnaya(nak.id)">
                            Накладная для <strong>{{ nak.shop !== null ? nak.shop.name : '' }}</strong>
                            от {{ moment(new Date(nak.created_at)).format('YYYY-MM-DD HH:mm') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Кнопки Действия над отчетом -->
    <div v-if="userIs([DIRECTOR, WORKER])"
        class="flex justify-start gap-5">

        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center"
            @click="saveRealization()"  v-if="!hide">
            Отгрузить
        </button>

        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center"
            @click="saveConfirmRealization()" v-if="!hide"
            :disabled="myreal && (myreal.is_released == 0 || myreal.is_accepted == 1)">
            Принять отчет и закрыть
        </button>

        <button v-if="myreal && getRealizator(myreal.realizator)"
            @click="exportExcel"
            class="text-white flex items-center font-bold py-2 px-4 rounded text-center cursor-pointer bg-gray-500"
            :class="avansReportLoading ? 'bg-gray-500' : 'bg-blue-500'">
            Cкачать отчет &nbsp;&nbsp; <img v-if="avansReportLoading" class="w-4 h-4" src="/img/loading.gif" alt="">
        </button>
    </div>
    
     <!-- Загрузка -->
    <modal name="loadingReport" class="modal-300p">
        <div class="flex flex-col items-center p-4">
            <img class="w-8 h-8 bg-white" src="/img/loading.gif" alt="">
            <p class="mt-4 text-center">{{ loadingText }}</p>
        </div>
    </modal>

    <!-- Накладная -->
    <modal name="nakladnaya">
        <div class="px-6 py-6 max-sm:text-xs">
            <nakladnaya :id="nakladnayaId" ref="nakladnaya" />
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="closeNakladnaya()">Закрыть</button>
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="$refs.nakladnaya.update()">Сохранить изменения</button>
        </div>
    </modal>

</div>
</template>

<script>
import axios from 'axios'
import moment from "moment";
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import MonthPicker from '@/Shared/MonthPicker'
import Nakladnaya from '@/Pages/Orders/Nakladnaya.vue'

export default {
    metaInfo: { title: 'Авансовый отчет' },
    components: {
        Datepicker,
        SelectInput,
        TextInput,
        MonthPicker,
        Nakladnaya
    },
    
    props: {
        realizators: Array,
        pivotPrices: Array,
        hide: Boolean,
    },

    data() {
        return {

            myreal: null, // Таблица реализации
            realizator: '', // фильтр по реализаторам
            moment: moment,

            /**
             * post: realizator-order
             */
            myreport: [], // отчет 
            columns: [{
                magazine: null,
                amount: null,
                pivot: null,
                isNal: false,
                nak: null,
            }], 
            mymagazines: [],
            reportTotals: [], // Итоги
            mypercent: null, // Процент прибыли для реализатора 10 или 15%
            realizationNaks: [],
            pageNakReturns: null, 
            majit: null,
            sordor: null,

            /**
             * post: report-avans
             */
            avansReportData: [], 
            avansReportLoading: true,
            avansReportFields: null,
            
            // накладная
            nakladnayaId: null,
          
            // предотвращение двойного запроса
            clickedConfirmRealization: false,
            loadingText: 'Запрос обрабатывается...'
        }
    },
    mounted() {
      let xlsxScript = document.createElement('script');
      xlsxScript.setAttribute('src', '/js/exceljs.min.js');
      document.head.appendChild(xlsxScript);
    },
    created() {},
    computed: {},
    methods: {

        // Обновить таблицу при смене фильтра Реализатор или триггер с родителя
        loadTable(data = null) {
            
            this.$modal.show('loadingReport');
            this.loadingText = 'Авансовый отчет загружается...'

            if(!data) {
                data = { id: this.realizator.id };
            } else {
                this.realizator = this.realizators.find(r => r.id === data.id);
            }

            if(data.id && !this.realizator) alert('Реализатор не найден');

            axios
                .post("realizator-order", data)
                .then(response => {
                
                    this.myreal = response.data.real;
                    this.mypercent = response.data.percent;

                    let report = this.withReturnNaks(response.data.report, response.data.return_naks);
                
                    report = response.data.real !== null && response.data.real.is_released === 0 
                        ? this.fillReleasedField(report)
                        : report

                    report = this.formProductPrices(report);
                    this.myreport = report

                    

                    this.mymagazines = response.data.magazine;
                    this.columns = response.data.columns;
            
                    this.realizationNaks = response.data.realizationNaks;

                    this.pageNakReturns = response.data.nakReturns;

                    this.avansReportLoading = true
                    this.avansReportData = []
                    this.formReportTotals();

                    this.countTotal(report);

                    if(!this.myreal) {
                        this.$modal.hide('loadingReport');
                        this.loadingText = ''
                        return;
                    }

                    this.realizator = this.realizators.find(r => r.id === this.myreal.realizator);

                    axios.post("report-avans", { id: this.myreal.id }).then(resp => {
                        this.avansReportData = resp.data.data;
                        this.avansReportFields = resp.data.fields;
                        this.avansReportLoading = false

                        

                        this.$modal.hide('loadingReport');
                        this.loadingText = ''
                    });
                });

        },

        formProductPrices(report) {
            
            report.forEach(r => {
                r.price = this.getPivotPrice(r.assortment);
                
                // returned

                if(r.amount - r.sold - r.defect === r.returned) {
                    r.returned = r.amount - r.sold
                    r.returnedUpdated = false;
                } else {
                    r.returnedUpdated = true;
                }
            });

            return report;
        },

        // @TODO Private
        withReturnNaks(report, return_naks) {
            return report;
        },  

        onInputKey (item, field) {
            // if(field === 'amount') {
            //     item.returned = +item.amount - +item.sold + item.returned;
            // }
            // if(field === 'returned') {
            //     item.amount = +item.returned + +item.sold - item.amount;
            // }
        },

        onInputChange(item, field) {

           

            this.countTotal(this.myreport);
        },

        countTotal(report) {

            let sum = 0;
            report.forEach(item => {
                sum += (item.sold - item.defect) * item.price;
            });


            // vozvrat nakladnye
            sum = sum + this.vozvratNakSum();

            // Итог
            this.reportTotals[0]['value'] = sum;

            // сумма реализации
            let realizationSum = Number(this.getRealizationSum())

            // Продажа на нал
            let Nal = sum - realizationSum
            this.reportTotals[2]['value'] = (Nal).toFixed(2)

            let orderPercent = Number(this.getOrderPercent());

            let amountToPay = realizationSum
                ? Nal * (100 - orderPercent) / 100 
                : sum * (100 - orderPercent) / 100;
            
            let zaUslugu = realizationSum
                ? Nal * orderPercent / 100 
                : sum * orderPercent / 100;

            // За услугу 10%
            this.reportTotals[4]['value'] = (zaUslugu).toFixed(2)

            // К оплате
            this.reportTotals[5]['value'] = (amountToPay).toFixed(2)
            
        },

        // @TODO helper 
        fillReleasedField(report) {

            return report;
            report.forEach(e => {
                if(e.amount == 0) e.amount = e.order_amount
            });

            return report
        },
    
        vozvratNakSum() {
            let v = 0;
            this.columns.forEach(c => {
                if(c.is_return == 1) v += Math.abs(c.amount);
            });

            return v;
        },

        // @TODO Private
        formReportTotals() {
            let data = [];

            let totalSum = Number(this.totalSum())
            let realizationSum = Number(this.getRealizationSum())
            let majit = Number(this.majit)
            let sordor = Number(this.sordor)
            let orderPercent = Number(this.getOrderPercent())
            let v = this.vozvratNakSum();
            
          

            if(isNaN(totalSum)) totalSum = 0;
            if(isNaN(realizationSum)) realizationSum = 0;
            if(isNaN(majit)) majit = 0;
            if(isNaN(sordor)) sordor = 0;
            if(isNaN(orderPercent)) orderPercent = 0;

            totalSum = totalSum + v;

            let amountToPay = realizationSum
                ? ((totalSum - realizationSum) - majit - sordor) - (
                    (totalSum - realizationSum) * orderPercent / 100
                ).toFixed(2)
                : (totalSum -
                    (totalSum * orderPercent / 100) -
                    majit - sordor
                )

            data.push({
                name: '',
                value: totalSum
            })

            data.push({
                name: 'сумма реализации',
                value: realizationSum
            })

            data.push({
                name: 'Продажа на нал',
                value: realizationSum
                    ? (totalSum - realizationSum).toFixed(2)
                    : (totalSum).toFixed(2)
            })

            data.push({
                name: 'Мажит',
                value: this.majit
            })

            data.push({
                name: "за услугу " + (this.mypercent == null ? 0 : this.mypercent.amount) + "%",
                value: realizationSum
                    ? ((totalSum - realizationSum) * orderPercent / 100).toFixed(2)
                    : (totalSum * orderPercent / 100).toFixed(2)
            })

            data.push({
                name: 'к оплате',
                value: Number(amountToPay).toFixed(2)
            })

            this.reportTotals = data
        },
        
        // @TODO Private
        totalSum() {
            var total = 0;

            if(!this.myreport) return total;

            this.myreport.forEach(el => {
                total += (el.sold - el.defect) * el.price;
            });

            if (this.pageNakReturns) {
                this.pageNakReturns.forEach(el => {
                    total += el.sum;
                });
            }
            
            return total;
        },

        // @TODO Private 
        getRealizationSum() {
            let total = 0;
            if (this.columns != null) {
                this.columns.forEach(element => {
                    if (element != null && element.isNal == false && element.is_return != 1)
                        total = total + Number(element.amount);
                });
            }
            return Number(Number(total).toFixed(2));
        },

        // @TODO Private 
        getOrderPercent() {
            return this.mypercent ? this.mypercent.amount : 1;
        },

        // @TODO 
        setOrderAmount(id, amount, returned){
            // if(returned == null){
            //     returned = 0;
            // }
            // axios.post("set-order-amount",{id: id, amount: amount, returned: returned});
        },

        // @TODO 
        setOrderDefect(id, amount) {
            // axios.post("set-order-defect",{id: id, amount: amount});
        },
        
        // @TODO 
        setOrderReturned(id, amount) {
            // axios.post("set-order-returned",{id: id, amount: amount});
        },

        // @TODO 
        setOrderDefectSum(id, amount) {
            // axios.post("set-order-defect-sum",{id: id, amount: amount});
        },
        
        // @TODO 
        setOrderSold(id, amount) {
            // axios.post("set-order-sold",{id: id, amount: amount});
        },

        // @TODO 
        getPivotPrice(item) {

            if (!this.mypercent) return 0;

            let priceItem = this.pivotPrices.find(el => el.percent_id == this.mypercent.id && el.store_id == item.id);
            return priceItem ? priceItem.price : 0;
        },

        // @TODO 
        totalBrak() {

            let total = 0;
            if(!this.myreport) return total;

            this.myreport.forEach(el => {
                total += el.defect * el.price;
            });

            return total;
        },

        // @TODO Удалить накладную
        deleteNak(nak) {
            if (!window.confirm('Вы уверены, что хотите удалить накладную?')) {
                return;
            }

            axios.delete('/naks/' + nak.id + '/delete').then((response) => {
                location.reload();
            });
        },

        // При клике на накладную
        showNakladnaya(id) {
            this.nakladnayaId = id;
            this.$modal.show('nakladnaya');
        },

        // Закрыть накладную модалку
        closeNakladnaya() {
            this.$modal.hide('nakladnaya');
            this.nakladnayaId = null;
        },

        // @TODO При клике "Отгрузить"
        saveRealization() {
            if (!confirm('Вы уверены?')) {
                return;
            }

            var percent = this.mypercent ? this.mypercent.amount : 1;

            let realizationSum = this.getRealizationSum();
            let totalSum = this.totalSum();
            let totalBrak = this.totalBrak();
            let majit = this.majit == null ? 0 : this.majit;

            const data = {
                realization_id: this.myreal.id,
                realization: this.myreal,
                realization_sum: totalSum,
                columns: this.columns,
                report: this.myreport,
                bill: realizationSum,
                majit: majit,
                defect_sum: totalBrak,
                realizator_income: realizationSum / percent,
                income: realizationSum - realizationSum / percent,
                cash: totalSum - realizationSum,
                percent: totalBrak / totalSum * 100,
            };

            axios.post('save-realization', data)
            .then(response => {

                alert(response.data.message);

                if (response.data.status == 'error') return;

                this.columns = response.data.columns;
                this.myreal = response.data.realization;
            });
        },

        // @TODO При клике "Принять отчет и закрыть"
        saveConfirmRealization() {
            if (!this.mypercent) return;
            if (this.clickedConfirmRealization) return;
            if (!confirm('Вы уверены?')) return;

            this.clickedConfirmRealization = true;
            this.$modal.show('loadingReport');
            this.loadingText = 'Подождите, отчет сохраняется...'

            // Calculate
            let totalSum = this.totalSum();
            let realization_sum = this.getRealizationSum();

            let cash = realization_sum
                ? totalSum - realization_sum
                : totalSum;

            var income = 0;

            if (realization_sum)
                income = (totalSum - realization_sum - this.majit - this.sordor) - ((totalSum - realization_sum) / this.mypercent.amount);
            else
                income = totalSum - (totalSum / this.mypercent.amount) - (this.majit) - (this.sordor);
            
            // Data
            const data = {
                real: this.myreal,
                realization: this.myreal,
                realizator_income: income / this.mypercent.amount,
                bill: realization_sum,
                cash: cash,
                majit: this.majit == null ? 0 : this.majit,
                income: income,
                columns: this.columns,
                report: this.myreport
            };

            axios.post('confirm-realization', data) 
            .then(response => {
                
                this.clickedConfirmRealization = false
                this.$modal.hide('loadingReport');
                this.loadingText = ''
                alert(response.data.message);

                if (response.data.status == 'error') return;
                
                location.reload();

            }).catch(error => {
                alert(error);
            });
        },

        // @TODO
        getRealizator(id) {
            for (var i = 0; i < this.realizators.length; i++) {
                if (this.realizators[i].id == id) {
                    return this.realizators[i]
                }
            }

            return null
        },

        async exportExcel() {
            this.$modal.show('loadingReport');
            this.loadingText = 'Авансовый отчет загружается...'

            await axios.post("export-avans-report", { id: this.myreal.id  }).then(res => {
                this.prepareExcelAndDownload(res.data);
                this.$modal.hide('loadingReport');
                this.loadingText = ''
            }).catch(e => {
                alert(e);
                console.log(e);
            });
        },

        async prepareExcelAndDownload(res) {
            const {data, styles, styleVariants, merges, sheetName} = res;

            // Create a new workbook
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(sheetName);

            // Set column widths
            worksheet.columns = [
                { width: 31 },
                { width: 10.33 },
                { width: 11.17 },
                { width: 11.17 },
                { width: 10 },
                { width: 12.83 },
                { width: 8.17 },
                { width: 15.67 },
                { width: 24.83 },
            ];

            // render cells
            data.forEach((rowData, r) => {
                worksheet.addRow(rowData).eachCell((cell, i) => {
                    cell.font = { bold: true, color: { argb: 'FF0000FF' } };
                    this.resolveCellStyle(styles, styleVariants, r, i - 1, cell);
                });
            });

            merges.forEach(merge => {
                worksheet.mergeCells(merge);
            });
            
            // Create a buffer and download the file
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${sheetName}_${data?.[0]?.[3]}.xlsx`;
            link.click();
        },

        async resolveCellStyle(styles, styleVariants,  r, i, cell) {
            let row = styles[r];
            if(row === undefined) return;
            let style = styleVariants[styles[r][i]];
            if(r === 0) console.log(r,i)
            if(style === undefined) return;
     
            if(style.font) cell.font = style.font;
            if(style.border) cell.border = style.border;
            if(style.alignment) {
                cell.alignment = style.alignment
            }
            if(style.mergeCells) {
                cell.mergeCells(style.mergeCells);
                cell.alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
            }
        }
    },
}
</script>

<style scoped>
.max-w-ef {
    max-width: 873px;
}
.tableizer-table th {
    padding: 5px 4px;
    text-align: left;
}
.h-48px {
        height: 46px;
    display: flex;
    align-items: center;
}
</style>