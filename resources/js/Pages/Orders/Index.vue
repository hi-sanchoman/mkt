<template>
    <div class="flex flex-col h-full">

        <!-- Табы в Desktop -->
        <div class="panel grid grid-cols-2 hidden sm:flex justify-start gap-5">
            <button v-if="canApply <= 0" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                @click="newOrder()">
                Новая заявка
            </button>

            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                v-bind:class="{ 'bg-green-500': nakladnye, 'bg-blue-500': !nakladnye }" @click="showNakladnye()">
                Накладные
            </button>

            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="updateOrder()">
                Дополнить
            </button>

            <button class="hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                v-bind:class="{ 'bg-green-500': report, 'bg-blue-500': !report }" @click="showReport()">
                Текущая заявка
            </button>

            <button v-if="$page.props.auth.user.position_id == 3"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="$modal.show('history')">
                История заявок
            </button>
            
             <div v-if="dops.length > 0"
                class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 cursor-pointer"
                role="alert" @click="showDops()">
                <p class="font-bold">Ваши доп. заявки</p>
            </div>

            <div class="pt-3">
                <h2>{{ $page.props.auth.user.first_name }}</h2>
            </div>
        </div>
        
        <!-- Mobile version -->
        <div class="sm:hidden">
            <a v-if="canApply <= 0" class="mb-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                href="/realizators/new-order">Новая заявка</a>

            <div v-if="myrealizations.length <= 0" class="mt-3">
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 py-2 px-4 rounded"
                    href="/realizators/new-order">Новая заявка</a>
            </div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 py-2 px-4 rounded mb-3"
                @click="$modal.show('nak_history')">
                история накладных
            </button>
        </div>

        <!-- Mobile version -->
        <div class="sm:hidden">
            <h2 class="font-bold">
                <span v-if="report">Текущая заявка, </span>
                {{ $page.props.auth.user.first_name }}
            </h2>

            <div v-if="myrealizations[0]" class="text-sm mt-3">
                Дата заявки: {{ moment(myrealizations[0].created_at).format('DD.MM.YYYY HH:mm') }}
            </div>

            <div v-if="myrealizations[0]" class="w-full whitespace-nowrap mt-4">
                <div class=" p-1 rounded-md mb-1 flex flex-row w-full">
                    <span class="mr-1" style="width: 20px; font-size: 0.65rem">#:</span>
                    <span class="flex-1" style="font-size: 0.65rem; width: 30%">Наименование</span>
                    <span class="mr-2 text-right" style="width: 40px; font-size: 0.65rem; ">Зак.</span>
                    <span class="text-right mr-1" style="width: 40px; font-size: 0.65rem; ">Ост.</span>
                </div>

                <div v-for="(item1, key1) in myrealizations[0].order" :key="key1 + '-0'" :id="key1 + '-0'"
                    class="bg-white shadow rounded-lg ">

                    <template v-if="assortment[item1.assortment_id]">
                        <div class="bg-white p-1 rounded-md mb-1 flex flex-row w-full">

                            <span class="mr-1" style="width: 20px; font-size: 0.65rem">#{{ key1 + 1 }}:</span>
                            <span class="flex-1" style="font-size: 0.65rem; width: 30%; overflow: hidden;">{{
                                    assortment[item1.assortment_id].type
                            }}</span>
                            <span class="mr-2 text-right" style="width: 40px; font-size: 0.65rem; ">{{
                                    item1.order_amount.toFixed(2)
                            }}</span>
                            <span class="text-right mr-1" style="width: 40px; font-size: 0.65rem; ">{{ (item1.amount -
                                    item1.defect - item1.sold).toFixed(2)
                            }}</span>

                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-3 font-bold" style="margin-top: 40px;">
                НОВАЯ ЗАЯВКА
            </div>

            <div v-if="myrealizations[1]" class="text-sm mt-3">
                Дата заявки: {{ formatDate(myrealizations[1].created_at) }}
            </div>

            <div v-if="myrealizations[1]" class="w-full whitespace-nowrap ">
                <div class=" p-1 rounded-md mb-1 flex flex-row w-full">
                    <span class="mr-1" style="width: 20px; font-size: 0.65rem">#:</span>
                    <span class="flex-1" style="font-size: 0.65rem; width: 30%; ">Наименование</span>
                    <span class="mr-2 text-right" style="width: 40px; font-size: 0.65rem; ">Зак.</span>
                    <span class="text-right mr-1" style="width: 40px; font-size: 0.65rem; ">Ост.</span>
                </div>
                <div v-for="(item1, key1) in myrealizations[1].order" :key="key1" :id="key1 + '-1'"
                    class="bg-white shadow rounded-lg">
                    <template v-if="assortment[item1.assortment_id]">
                        <div class="bg-white p-1 rounded-md mb-1 flex flex-row w-full">

                            <span class="mr-1" style="width: 20px; font-size: 0.65rem">#{{ key1 + 1 }}:</span>
                            <span class="flex-1" style="font-size: 0.65rem; width: 30%; overflow: hidden;">{{
                                    assortment[item1.assortment_id].type
                            }}</span>
                            <span class="mr-2 text-right" style="width: 40px; font-size: 0.65rem; ">{{
                                    item1.order_amount.toFixed(2)
                            }}</span>
                            <span class="text-right mr-1" style="width: 40px; font-size: 0.65rem; ">{{ (item1.amount -
                                    item1.defect - item1.sold).toFixed(2)
                            }}</span>

                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Текущая заявка -->
        <div v-if="report" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-auto pt-2 hidden sm:block md:mt-4">
            <div v-if="myrealizations[0]" class="text-bold mt-2">
                Дата заявки: {{ moment(myrealizations[0].created_at).format('DD.MM.YYYY HH:mm') }}
            </div>

            <div v-if="myrealizations[0]" style="margin: 20px 0">
                Процентная ставка: <strong>{{ parseInt(myrealizations[0].percent) }}%</strong>
            </div>

            <table v-if="myrealizations[0]" class="w-full whitespace-nowrap ss">
                <tr class="text-left font-bold border-b border-gray-200">
                    <th class="text-left px-2 py-2">#</th>
                    <th class="text-left px-2 py-2">Наименование товаров</th>
                    <th class="text-left px-2 py-2">Заявка</th>
                    <th class="text-left px-2 py-2">Отпущено</th>
                    <th class="text-left px-2 py-2">Возврат</th>
                    <th class="text-left px-2 py-2">Обмен/Брак</th>
                    <th class="text-left px-2 py-2">Брак на сумму</th>
                    <th class="text-left px-2 py-2">Продано</th>
                    <th class="text-left px-2 py-2">Цена</th>
                    <th class="text-left px-2 py-2">Сумма</th>
                </tr>
                <tr v-for="(item1, key1) in myrealizations[0].order" :key="assortment[item1.assortment_id].id"
                    class="text-center border-b border-r-4">
                    <template v-if="(item1.order_amount > 0 || item1.sold > 0 || item1.amount > 0) && assortment[item1.assortment_id]">
                        <td class="text-left px-2 py-2 border-r border-l w-8">{{ (key1 + 1) }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ assortment[item1.assortment_id].type }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ Math.round(item1.order_amount * 100) / 100  }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ Math.round(item1.amount * 100) / 100 }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ Math.round(item1.returned * 100) / 100 }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ Math.round(item1.defect * 100) / 100 }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ Math.round(item1.defect_sum * 100) / 100 }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ Math.round(item1.sold * 100) / 100 }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ getPivotPrice(item1.assortment_id, myrealizations[0].percent) }}</td>
                        <td class="text-left px-2 py-2 border-r">{{ (getPivotPrice(item1.assortment_id, myrealizations[0].percent) * item1.sold).toFixed(2) }}</td>
                    </template>
                </tr>

                <tr>
                    <td class="text-left px-2 py-2 border-r" colspan="8"></td>
                    <td class="text-left px-2 py-2 border-r border-b" >ИТОГ</td>
                    <td class="text-left px-2 py-2 border-r border-b">{{ getCurrentSum().toFixed(2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Накладные -->
        <div v-if="nakladnye">
            
            <div class="bg-white p-5 rounded-md mt-5">
                <div class="mb-5 flex justify-between">
                    <div class="inline-block">
                        <input type="text" name="mailykent" class="border-b-2" v-model="company"><br>
                        <datepicker v-model="nak_date" type="date" :placeholder="nak_date" :show-time-header="time">
                        </datepicker>
                    </div>
                    <div class="inline-block">

                        <template v-if="modeChoose === 'choose'">
                            <a href="#" style="font-size: 1rem; color: blue; text-decoration: underline;"
                                @click="setMode('pick_branch')">Выбрать магазин</a>
                            или
                            <a href="#" style="font-size: 1rem; color: blue; text-decoration: underline;"
                                @click="setMode('enter_branch')">Ввести свой</a>
                        </template>

                        <template v-else-if="modeChoose === 'pick_branch'">
                            <select v-model="branch" class="border-b-2" label="магазин" placeholder="Магазин">
                                <option v-for="branch in branches" :key="branch.id" :value="branch.id">{{ branch.name }}
                                </option>
                            </select>
                            <br />
                            <a href="#" @click="resetChooseMode()">x назад</a>
                        </template>

                        <template v-else>
                            <input style="border: 1px solid gray;" placeholder="Введите название" type="text"
                                v-model="new_branch" />
                            <br />
                            <a href="#" @click="resetChooseMode()">x назад</a>
                        </template>

                        <br /><br />

                        <select v-model="option" class="border-b-2" label="опция" placeholder="Тип накладной">
                            <option value="Консегнация для МКТ">Консегнация для МКТ</option>
                            <option value="Консегнация для себя">Консегнация для себя</option>
                            <option value="Оплата наличными">Оплата наличными</option>
                            <option value="vozvrat">Возврат</option>
                        </select>


                    </div>
                </div>
                <div v-if="myrealizations[0]" class="w-full overflow-auto">
                    <table class="w-full whitespace-nowrap mt-5">
                        <tr class="text-center font-bold border-b border-gray-200">
                            <th>#</th>
                            <th>Наименование</th>
                            <th>Кол-во</th>
                            <th>Брак</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                        </tr>
                        <tr v-for="(item1, key1) in products">
                            <td>{{ (key1 + 1) }}</td>
                            <td>
                                <input type="hidden" v-model="nak_items[key1]">
                                <span>{{ item1.type }}</span>
                            </td>
                            <td><input onclick="select()" type="number" v-model="nak_amount[key1]" class="ml-3"
                                    :disabled="option == 'vozvrat'"></td>
                            <td><input onclick="select()" type="number" v-model="nak_brak[key1]"></td>
                            <td><input onclick="select()" type="number" v-model="nak_price[key1]" disabled="true"></td>
                            <td>
                                <span v-if="option == 'vozvrat'">
                                    {{
                                        (nak_price[key1] * nak_brak[key1]).toFixed(2)
                                    }}
                                </span>
                                <span v-else>
                                    {{
                                        (nak_price[key1] * (nak_amount[key1] - nak_brak[key1])).toFixed(2)
                                    }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"></td>
                            <td class="text-right">ИТОГ</td>
                            <td class="text-right">{{ getNakTotal() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel py-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    @click="saveNakladnoe()">
                    сохранить
                </button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    @click="$modal.show('nak_history')">
                    история
                </button>
            </div>
        </div>

        <!-- Новая заявка -->
        <modal name="myorder">

            <form id="order_form" onsubmit="return false;">

                <div class="p-6">
                    <h5><strong>Ассортимент</strong></h5>

                    <div style="width: 300px; margin-top: 30px; margin-bottom: 30px;">
                        <p class="w-6/6">Процентная ставка %<span class="text-red-400">*</span></p>
                        <select class="border-b-2 w-full pb-1 w-9/12" v-model="orderPercent">
                            <option v-for="percent in percents" :value="percent.id" :key="percent.id">{{ percent.amount
                            }}%</option>
                        </select>
                    </div>

                    <table class="w-full whitespace-nowrap mt-5">
                        <tr class="text-center font-bold border-b border-gray-200">
                            <th class="text-left">#</th>
                            <th>Ассортимент</th>
                            <th>Количество</th>
                        </tr>
                        <tr v-if="assortment" v-for="(item, ind, index) in assortment" :key="item.id">
                            <td>{{ (index + 1) }}</td>
                            <td>{{ item.type }}</td>
                            <td>
                                <div class="custom-number-input h-10 w-32">

                                    <div class="flex flex-row h-10 w-full rounded-lg relative bg-transparent mt-1">
                                  
                                        <input type="number" v-model="order[item.id]" v-on:keyup.enter="onEnter"
                                            class="outline-none focus:outline-none text-center w-full bg-gray-300 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none"
                                            name="custom-input-number" placeholder="0" onclick="select()" />

                                    </div>
                                </div>
                            </td>

                        </tr>
                    </table>
                    <button @click="sendOrder()" id="newBtn" type="button"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Оформить
                        заявку</button>
                </div>

            </form>
        </modal>

        <!-- Дополнить заявку -->
        <modal name="myorder1">
            <form id="dop_order_form" onsubmit="return false;">

                <div class="p-6">
                    <h5>Ассортимент</h5>
                    <table class="w-full whitespace-nowrap mt-5">
                        <tr class="text-center font-bold border-b border-gray-200">
                            <th class="text-left">#</th>
                            <th class="text-left">Ассортимент</th>
                            <th class="text-left">Количество</th>
                        </tr>

                        <tr v-for="(item, ind, index) in assortment" :key="item.id">
                            <td>{{ (index + 1) }}</td>
                            <td>{{ item.type }}</td>
                            <td>
                                <div class="custom-number-input h-10 w-32">

                                    <div class="flex flex-row h-10 w-full rounded-lg relative bg-transparent mt-1">
                                   
                                        <input type="number" v-model="dopOrder[item.id]" v-on:keyup.enter="onEnter"
                                            onclick="select()"
                                            class="outline-none focus:outline-none text-center w-full bg-gray-300 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none"
                                            name="custom-input-number" placeholder="0" />

                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <button @click="sendUpdateOrder()" id="updateBtn" type="button"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Сохранить</button>
                </div>

            </form>
        </modal>

        <!-- История заявок -->
        <modal name="history">
            <div class="p-6">
                <div class="flex">
                    <div>
                        от
                        <datepicker v-model="from" type="date" placeholder="" :show-time-header="time">
                        </datepicker>
                    </div>
                    <div>
                        до
                        <datepicker v-model="to" type="date" placeholder="" :show-time-header="time">
                        </datepicker>
                    </div>
                </div>
                <br>
                <br>
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold border-b border-gray-200">
                        <th class="px-6 pt-4 pb-4">Реализатор</th>
                        <th class="px-6 pt-4 pb-4">Номер</th>
                        <th class="px-6 pt-4 pb-4">Дата</th>
                        <th class="px-6 pt-4 pb-4">Отчет</th>
                    </tr>
                    <tr class="text-left border-b border-gray-200" v-for="item in auth_realization"
                        v-if="new Date(from) <= new Date(item.created_at) && new Date(to) >= new Date(item.created_at)">
                        <td class="px-6 pt-3 pb-3 w-8">{{ item.realizator.first_name }}</td>
                        <td class="px-6 pt-3 pb-3 w-8">{{ item.id }}</td>
                        <td class="px-6 pt-3 pb-3 w-8">{{ moment(item.created_at).format("DD-MM-YYYY") }}</td>
                        <td class="px-6 pt-3 pb-3 w-8">
                            <div class="flex gap-2">
                                <button v-if="$page.props.auth.user.position_id != 3"
                                    @click="showReport3(item.id, item.realizator.id)"
                                    class="bg-green-500 text-white font-bold py-2 px-4 rounded">редактировать</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </modal>

        <!-- Накладные: история -->
        <modal name="nak_history">
            <div class="px-6 py-6 overflow-auto">
                <h2 class="font-bold text-xl">История накладных</h2>

                <table class="w-full mt-3 min-w-640px">
                    <tr>
                        <th class="text-left px-2 py-2">№</th>
                        <th class="text-left px-2 py-2">Название</th>
                        <th class="text-left px-2 py-2">Дата</th>
                        <th class="text-left px-2 py-2">Действия</th>
                        <th class="text-left px-2 py-2">Скачать</th>
                    </tr>

                    <tr class="border" v-for="nak in nakladnoe" :key="nak.id">
                        <td class="text-left px-2 py-2">{{ nak.id }}</td>
                        <td class="text-left px-2 py-2 hover:text-blue-500" @click="showNakladnaya(nak.id)">Накладная для <span v-if="nak.shop != null"
                                class="underline">{{ nak.shop.name }}</span></td>
                        <td class="text-left px-2 py-2">{{ moment(nak.created_at).format("DD.MM.YYYY H:mm") }}</td>
                        <td class="text-left px-2 py-2">
                            <button @click="nakIsPaid(nak)"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded"
                                v-if="nak.consegnation == 2 && nak.paid == 0">Оплачено</button>
                        </td>
                        <td class="text-left px-2 py-2">
                            <a class="border rounded px-1 py-1 hover:bg-blue-400 hover:text-white text-xs"
                                :href="'/blank/' + nak.id">
                                Скачать
                            </a>
                        </td>
                    </tr>
                </table>
                
                <section class="mt-2 flex">
                    <div class="bg-blue-500 text-white px-2 py-1 text-center mr-2" @click="showNewerNakladnye" v-if="(nakladnyePage - 1) > 0">
                        Показать новее
                    </div>
                    <div class="bg-blue-500 text-white px-2 py-1 text-center" @click="showOlderNakladnye">
                        Показать старее
                    </div>
                </section>
              
            </div>
        </modal>

        <modal name="loading" class="modal-300p">
            <div class="flex flex-col items-center p-4">
                <img class="w-8 h-8 bg-white" src="/img/loading.gif" alt="">
                <p class="mt-4 text-center">Подождите, накладная сохраняется...</p>
            </div>
        </modal>

        <!-- Дополнительная заявка: результат -->
        <modal name="dops">
            <div class="px-6 py-6">
                <p class=" text-lg font-bold mb-6">Дополнительная заявка</p>

                <table class="w-full">
                    <tr>
                        <th class="text-left pb-3">Заявка на:</th>
                        <th class="text-left pb-3 pl-3">Кол-во:</th>
                        <th class="text-left pb-3 pl-3">Статус:</th>
                    </tr>
                    <template v-for='dop in dops'>
                        <tr v-if="dop.order_amount > 0" :key="'asd' + dop.id">
                            <td>{{ typeof dop.assortment === 'object' ? dop.assortment.type : dop.assortment }}</td>
                            <td class="pl-3"><b>{{ dop.order_amount }}</b></td>
                            <td class="pl-3">
                                <div v-if="dop.status === 1" class="bg-green-500 text-white font-bold py-1 px-4 rounded inline-block">Принято</div>
                                <div v-if="dop.status === 2" class="bg-red-400 text-white font-bold py-1 px-4 rounded  inline-block">Отклонено</div>
                            </td>
                        </tr>
                    </template>
                </table>

                <br><br>
                <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="readDops">Ознакомлен</button>

            </div>
        </modal>
        
        <!-- Накладная -->
        <modal name="nakladnaya">
            <div class="px-6 py-6 overflow-auto">
                <nakladnaya :id="nakladnaya" ref="nakladnaya" />
                <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded mb-3" @click="closeNakladnaya()">Закрыть</button>
                <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="$refs.nakladnaya.update()">Сохранить изменения</button>
            </div>
        </modal>

    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import axios from 'axios'
import Datepicker from 'vue2-datepicker'
import moment from "moment"
import 'vue2-datepicker/index.css'
import Nakladnaya from './Nakladnaya.vue'

export default {
    metaInfo: {
        title: 'Заявки'
    },
    layout: Layout,
    components: {
        Datepicker,
        TextInput,
        Nakladnaya,
    },
    props: {
        canApply: Number,
        nak_report: Array,
        branches: Array,
        realizations: Array,
        assortment: Object,
        realizator: Object,
        realcount: Array,
        auth_realization: Array,
        assorder: Object,
        assorder1: Object,
        percents: Array,
        pivotPrices: Array,
        majit: Number,
        sordor: Number,
        products: Array,
        dops: Array
    },
    data() {
        return {
            orderPercent: -1,
            list: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            my_nak_report: this.nak_report,


            nak_amount: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            nak_brak: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            nak_sum: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            nak_price: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            nak_items: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
       
            nakladnoe: [],
            branch: '',
            option: '',
            company: 'СПК Майлыкент-Сут',
            moment: moment,
            from: new Date(),
            to: new Date(),
            report: true,
            history: false,
            avans: false,
            myrealizations: this.auth_realization,
            nak_date: moment(new Date()).format("DD-MM-YYYY"),
            nak_month: new Date().getMonth(),
            mydate: null,
            time: true,
            realization_id: null,
            orderAmount: 0,
            nakladnyePage: 1,
            orderProduct: null,
            sklad: null,
            order: this.assorder1,//[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            myorder: this.assorder,//[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            dopOrder: this.assorder1,
            secondorder: [],
            nakladnye: false,
            myreport: this.report1,
            mymagazines: [],
            columns: [{
                magazine: null,
                amount: null,
                pivot: null,
                isNal: false,
            }],

            modeChoose: 'choose',
            new_branch: null,

            nakladnaya: null
        }
    },
    mounted() {

    },
    created() {

        if (this.myrealizations != undefined && this.myrealizations[0]) {
            this.myrealizations[0].order.forEach(element => {
                this.myorder[element.assortment_id] = element.order_amount;
            });
        }

        for (var i = 0; i < this.products.length; i++) {
            this.putRows(this.products[i].id, i, this.products[i].type);
        }

        this.fetchNakladnye(this.nakladnyePage);

    },
    methods: {
        showNewerNakladnye() {
            this.nakladnyePage = this.nakladnyePage - 1;
            this.fetchNakladnye(this.nakladnyePage);
        },
        showOlderNakladnye() {
            this.nakladnyePage = this.nakladnyePage + 1;
            this.fetchNakladnye(this.nakladnyePage);
        },
        fetchNakladnye(page) {
            axios.get('realizator-nakladnye?page=' + page).then(response => {
                this.nakladnoe = response.data;
            });
        },
        showDops() {
            this.$modal.show('dops');
        },
        showNakladnaya(id) {
            this.nakladnaya = id;
            this.$modal.show('nakladnaya');
        },
        closeNakladnaya() {
            this.$modal.hide('nakladnaya');
            this.nakladnaya = null;
        },
        showReport() {
            this.report = true;
            this.nakladnye = false;
            this.avans = false;
        },
        showNakladnye() {
            this.nakladnye = true;
            this.report = false;
            this.avans = false;
        },
        showReport3(id, realizator) {

            this.$modal.hide('history1');

            axios.post('realization-order', { id: id, realizator: realizator }).then(response => {

                this.myreport = response.data.report;
                this.mymagazines = response.data.realizator.magazine;
                this.realizators.forEach(element => {
                    if (element.id == response.data.realizator.id) {
                        this.realizator = element;
                    }
                });
                this.columns = response.data.columns;

            });

        },
        showContent(index, idx) {
            var myDiv = document.getElementById(index + "-" + idx);

            if (myDiv.children[1].style.display == 'none') {
                myDiv.children[1].style.display = 'block';
            }
            else {
                myDiv.children[1].style.display = 'none';
            }
        },
        setDay() {

            axios.post('order/date', { date: this.mydate }).then(response => {
                this.myrealizations = response.data.realizations;
            });

        },
        sendOrder() {
            if (this.orderPercent < 0) {
                alert('Ошибка: укажите процентную ставку!')
                return;
            }

            if (confirm('Оформлять заявку?')) {
                let button = document.getElementById('newBtn'); 
                button.style.display = 'none';

                this.$modal.hide('myorder');

                axios.post('orders/send', {
                    order: this.order,
                    percent: this.orderPercent,
                }).then(response => {
                    location.reload()
                });
            }
        },
        sendUpdateOrder() {
            let button = document.getElementById('updateBtn');
            button.style.display = 'none';

            var id = -1;
            if (this.myrealizations != undefined && this.myrealizations.length > 0) {
                id = this.myrealizations[this.myrealizations.length - 1].id;
            }

            if (id < 0) {
                console.log('no id');
                return;
            }

            console.log(this.myrealizations);
            console.log("id", id, this.dopOrder);

            this.$modal.hide('myorder1');

            axios.post('orders/update', {
                order: this.dopOrder,
                realization_id: id,

            }).then(response => {
                if (response) {
                    location.reload();
                }
            });
        },
        newOrder() {
            this.$modal.show('myorder');
        },
        updateOrder() {
            this.$modal.show('myorder1');
        },
        saveNakladnoe() {

            if ((!this.branch || !this.new_branch) && !this.option) {
                alert('Ошибка: укажите магазин и выберите тип консегнации');
                return;
            }

            if (!confirm("Сохранить накладную?")) {
                return;
            }
            
            this.$modal.show('loading');

            let counter = 0;
            var items = [];
            var amounts = [];
            var brak = [];

            this.nak_items.forEach(element => {

                if (element != 0) {
                    items.push(element);
                    amounts.push(this.nak_amount[counter]);
                    brak.push(this.nak_brak[counter]);
                }
                counter++;

            });

            var myoption = 2;
            if (this.option == "Консегнация для МКТ") myoption = 1;
            if (this.option == 'Оплата наличными') myoption = 3;
            if (this.option == 'vozvrat') myoption = 9;

            axios.post('save-nak', {
                items: items,
                amounts: amounts,
                brak: brak,
                branch_id: this.branch,
                new_branch: this.new_branch,
                option: myoption,
                realization_id: this.auth_realization[0].id
            }).then(response => {
                alert(response.data.message);

                this.$modal.hide('loading');
                location.reload();
            });

        },
        readDops() {
            axios.post('read-dop-status', {
                dops: this.dops.map(d => d.id)
            }).then(response => {
                this.dops = [];
                this.$modal.hide('dops');
            });
        },
        putRows(id, key, name) {
            var price = 0;
            var sum = 0;

            if (this.myrealizations[0]) {
                this.myrealizations[0].order.forEach(element => {

                    if (!this.assortment[element.assortment_id]) {
                        console.log(element.assortment_id, this.assortment);
                    }
                    if (this.assortment[element.assortment_id].id == id) {
                        price = this.getPivotPrice(element.assortment_id, this.myrealizations[0].percent);
                        sum = element.order_amount * price;
                    }
                });
            }
            this.nak_items[key] = name;
            this.nak_price[key] = price;
        },
        nakIsPaid(nak) {
            var conf = confirm('Подтвердить оплату?');
            if (conf === false) {
                return;
            }

            axios.post('pay-nak', { id: nak.id }).then(response => {
                this.nakladnoe = response.data;
            });
        },
        onEnter(e) {
            const form = event.target.form;
            const index = [...form].indexOf(event.target);

            const next_index = index + 1;
            form.elements[next_index].select();
            form.elements[next_index].focus();

            event.preventDefault();
        },
        totalBrak() {
            var total = 0;
            this.myreport.forEach(element => {
                total += element.defect * element.assortment.price;
            });

            return total;
        },
        totalSum() {
            var total = 0;

            if (this.myreport != null) {
                this.myreport.forEach(element => {
                    total += element.sold * element.assortment.price;
                });
            }

            return total;
        },
        totalRealization() {
            let total = 0;

            if (this.columns != null) {
                this.columns.forEach(element => {
                    if (element != 0)
                        total = total + parseInt(element.amount);
                });
            }

            return total
        },
        getRealizationSum() {
            let total = 0;
            if (this.columns != null) {

                this.columns.forEach(element => {
                    if (element != null && element.isNal == false)
                        total = total + parseInt(element.amount);
                });
            }

            return total;
        },
        getCurrentSum() {

            var sum = 0;

            for (var key in this.myrealizations[0].order) {
                var item = this.myrealizations[0].order[key];

                sum += this.getPivotPrice(this.assortment[item.assortment_id].id, this.myrealizations[0].percent) * item.sold;
            }

            return sum;
        },
        getNakTotal() {
            var sum = 0;

            for (var i = 0; i < this.products.length; i++) {
                sum += this.nak_price[i] * (this.nak_amount[i] - this.nak_brak[i]);
            }

            return sum.toFixed(2);
        },
        getPercent(amount) {
            for (var i in this.percents) {

                if (parseInt(this.percents[i].amount) == parseInt(amount)) {
                    return this.percents[i];
                }
            }

            return null;
        },
        getPivotPrice(itemId, percentAmount) {
            var percent = this.getPercent(percentAmount);

            if (percent == null) return 0;

            for (var i in this.pivotPrices) {
                if (this.pivotPrices[i].percent_id == percent.id && this.pivotPrices[i].store_id == itemId) {
                    return this.pivotPrices[i].price;
                }
            }

            return 0;
        },

        // Helpers
        formatDate(timestamp) {
            var date = new Date(timestamp);
            var month = date.toLocaleString('ru', { month: 'long' });
            var day = this.day(timestamp);

            var h = this.hours(timestamp);
            var m = this.minutes(timestamp);

            var formattedTime = (day - 1) + ' ' + month + ' ' + h + ':' + m;

            return formattedTime;
        },
        minutes(time) {
            return time.substring(14, 16);
        },
        hours(time) {
            var hour = parseInt(time.substring(11, 13));
            return hour;
        },
        day(time) {
            return parseInt(time.substring(8, 10)) + 1;
        },
        setMode(mode) {
            this.modeChoose = mode;
        },
        resetChooseMode() {
            this.modeChoose = 'choose';
            this.branch = null;
            this.new_branch = null;
        },
    }
}
</script>

<style>
.min-w-640px {
    min-width: 640px;
}
.modal-300p .vm--modal{
    width: 300px !important;
    min-width: 300px !important;
}
.ss td, .ss th {
    font-family: sans-serif;
}
</style>