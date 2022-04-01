<template>
<div class="flex flex-col h-full">

     <div class="panel grid grid-cols-2 hidden sm:flex justify-start gap-5 hidden ">
        <button v-if="myrealizations.length <= 0" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="newOrder()">
            Новая заявка
        </button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" v-bind:class="{'bg-green-500' : nakladnye, 'bg-blue-500' : !nakladnye}" @click="showNakladnye()">
            Накладные
        </button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="updateOrder()">
            Дополнить
        </button>
    
        <button class="hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" v-bind:class="{'bg-green-500' : report, 'bg-blue-500' : !report}" @click="showReport()">
            Текущая заявка
        </button>

        <button v-if="$page.props.auth.user.position_id == 3" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showReport2()">
            История заявок
        </button>
        
        <button v-if="$page.props.auth.user.position_id == 3" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showMyAvans()">
            Мой авансовый отчет
        </button>
        
        
        <div class="pt-3"><h2>{{$page.props.auth.user.first_name}}</h2></div>
    </div>
    

    <div class="pt-3 sm:hidden">
        <h2>
            <span v-if="report">Текущая заявка, </span>
            {{$page.props.auth.user.first_name}}
        </h2>

        <div v-if="myrealizations.length <= 0" class="mt-3"><a class="bg-blue-500 hover:bg-blue-700 text-white font-bold mt-3 py-2 px-4 rounded" href="/realizators/new-order">Новая заявка</a></div>
    </div>
    
    <br>
    <br>
    <modal name="myorder">

        <form id="order_form" onsubmit="return false;">

        <div class="p-6">
            <h5>Ассортимент</h5>
            <table class="w-full whitespace-nowrap mt-5">
                <tr class="text-center font-bold border-b border-gray-200">
                    <th>Ассортимент</th>
                    <th>Количество</th>
                </tr>
                <tr v-if="assortment" v-for="item in assortment">
                    <td>{{item.type}}</td>
                    <td>
                        <div class="custom-number-input h-10 w-32">
                                  
                            <div class="flex flex-row h-10 w-full rounded-lg relative bg-transparent mt-1">
                                <!--<button data-action="decrement" class=" bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-l cursor-pointer outline-none">
                                  <span class="m-auto text-2xl font-thin">−</span>
                                </button>-->

                                <input type="number" v-model="order[item.id]" v-on:keyup.enter="onEnter" class="outline-none focus:outline-none text-center w-full bg-gray-300 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none" name="custom-input-number" placeholder="0" onclick="select()" ></input>

                                <!--<button data-action="increment" class="bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-r cursor-pointer">
                                <span class="m-auto text-2xl font-thin">+</span>
                                </button>-->
                            </div>
                        </div>
                    </td>

                </tr>
            </table>
            <button @click="sendOrder()" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Оформить заявку</button>
        </div>

        </form>
    </modal>

    <modal name="myorder1">
        <form id="dop_order_form" onsubmit="return false;">
                
        <div class="p-6">
            <h5>Ассортимент</h5>
            <table class="w-full whitespace-nowrap mt-5">
                <tr class="text-center font-bold border-b border-gray-200">
                    <th>Ассортимент</th>
                    <th>Количество</th>
                </tr>

                <tr v-for="item in assortment">
                    <td>{{item.type}}</td>
                    <td>
                        <div class="custom-number-input h-10 w-32">
                              
                            <div class="flex flex-row h-10 w-full rounded-lg relative bg-transparent mt-1">
                            <!--<button data-action="decrement" class=" bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-l cursor-pointer outline-none">
                              <span class="m-auto text-2xl font-thin">−</span>
                            </button>-->

                            <input type="number" v-model="dopOrder[item.id]" v-on:keyup.enter="onEnter" onclick="select()" class="outline-none focus:outline-none text-center w-full bg-gray-300 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none" name="custom-input-number" placeholder="0"  />
                                  
                        </div>
                        </div>
                    </td>
                </tr>
            </table>
            <button @click="sendUpdateOrder()" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Сохранить</button>
        </div>

        </form>
    </modal>


    <div class="sm:hidden">
        <div v-if="myrealizations[0]" class="w-full whitespace-nowrap ">
            <div v-for="(item1, key1) in myrealizations[0].order" :id="key1" class="bg-white shadow rounded-lg py-5  px-3 my-3" @click="showContent(key1)">
                <div class="flex justify-between relative">
                    <p>
                        {{assortment[item1.assortment_id].type}}, <span style="color: #AAA">ост.: {{ item1.amount - item1.defect - item1.sold }}</span>
                    </p>


                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="supply-menu hidden">
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Заявка</p>
                        <p class="text-sm">{{item1.order_amount}}</p>
                    </div>   
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Отпущено</p>
                        <p class="text-sm">{{item1.amount}}</p>
                    </div> 
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Возврат</p>
                        <p class="text-sm">{{item1.returned}}</p>
                    </div> 
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Обмен брак</p>
                        <p class="text-sm">{{item1.defect}}</p>
                    </div> 
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Брак на сумму</p>
                        <p class="text-sm">{{item1.defect_sum}}</p>
                    </div>  
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Продано</p>
                        <p class="text-sm">{{item1.sold}}</p>
                    </div> 
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Цена</p>
                        <p class="text-sm">{{assortment[item1.assortment_id].price}}</p>
                    </div> 
                    <div class="px-6 pt-3 pb-3 w-full flex justify-between">
                        <p class="text-sm">Сумма</p>
                        <p class="text-sm">{{assortment[item1.assortment_id].price*item1.order_amount}}</p>
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <div v-if="report" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-auto pt-2 hidden sm:block">
        <table v-if="myrealizations[0]" class="w-full whitespace-nowrap ">
            <tr class="text-left font-bold border-b border-gray-200">
                <th>Наименование товаров</th>
                <th>Заявка</th>
                <th>Отпущено</th>
                <th>Возврат</th>
                <th>Обмен/Брак</th>
                <th>Брак на сумму</th>
                <th>Продано</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>
            <tr  v-for="(item1, key1) in myrealizations[0].order"  class="text-center border-b border-r-4" >
                <td class="text-left border-r">{{assortment[item1.assortment_id].type}}</td>
                <td class="text-left border-r">{{item1.order_amount}}</td>
                <td class="text-left border-r">{{item1.amount}}</td>
                <td class="text-left border-r">{{item1.returned}}</td>
                <td class="text-left border-r">{{item1.defect}}</td>
                <td class="text-left border-r">{{item1.defect_sum}}</td>
                <td class="text-left border-r">{{item1.sold}}</td>
                <td class="text-left border-r">{{assortment[item1.assortment_id].price}}</td>
                <td class="text-left border-r">{{assortment[item1.assortment_id].price*item1.order_amount}}</td>
            </tr>
        </table>
    </div>


    <div v-if="avans" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-auto pt-2 hidden sm:block">
        <table v-if="realizator" class="tableizer-table text-md">
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
                <tr v-for="item in myreport" :class="item.sold > item.amount ? 'text-white bg-red-700':''">
                    <td>{{item.assortment.type}}</td><td>{{item.order_amount}}</td>
                    <td><p class="w-8">{{ item.amount }}</p></td>
                    <td><p class="w-8">{{ item.returned }}</p></td>
                    <td><p class="w-8">{{ item.defect }}</p></td>
                    <td>{{item.defect * item.assortment.price}}</td>
                    <td>{{item.sold}}</td>
                    <td><p class="w-8">{{ item.assortment.price }}</p></td>
                    <td>{{item.sold * item.assortment.price}}</td>
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
                    <td>  </td>
                    <td>&nbsp;</td>
                </tr>

                <!--<tr><td>накладное на возврат</td><td>2150</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>сумма под реализации</td><td>{{getRealizationSum()}}</td></tr>
                <tr><td></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>продажа на наличные деньги</td><td> <input type="number" class="w-16">  </td></tr>
                <tr><td></td><td></td><td>&nbsp;</td><td>&nbsp;</td>&nbsp;<td></td><td>&nbsp;</td><td>&nbsp;</td><td>за услугу 10 %</td><td>{{getRealizationSum()/10}}</td></tr>
                <tr><td>Накладные под реализацию</td><td></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>К оплате</td><td>{{getRealizationSum()-getRealizationSum()/10}}  </td></tr>-->
                
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
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="2" class="text-right">{{ totalSum() }}</td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                    <td>сумма реализации</td>
                    <td><div  v-if="getRealizationSum()">{{ getRealizationSum() }}</div>
                        <div v-else></div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Продажа на нал</td>
                    <td>
                        <div v-if="getRealizationSum()">{{ totalSum() - getRealizationSum() }}</div>
                        <!--<div v-if="getRealizationSum()">{{ totalSum() - totalSum() / 10 }}</div>-->
                        <div v-else>{{ totalSum() }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Мажит</td>
                    <td><p>{{ majit }}</p></td>
                </tr>
                <!-- <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Сордор</td>
                    <td><p>{{ sordor }}</p></td>
                </tr> -->
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>за услугу 10%</td>
                    <td><div v-if="getRealizationSum()">{{(totalSum()-getRealizationSum())/10}}</div>
                        <div v-else>{{totalSum()/10}}</div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>к оплате</td>
                    <td><div v-if="getRealizationSum()">{{(totalSum()-getRealizationSum()-majit-sordor)-((totalSum()-getRealizationSum())/10)}}</div>
                        <div v-else>
                            {{totalSum()-(totalSum()/10)-(majit)-(sordor)}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="hidden sm:block">
            <div class="row">
                <div class="col-4 flex gap-5 mt-5">
                    <div>
                        <h6>Накладные под реализации</h6>
                        <div class="flex gap-3 mt-2" v-for="col in columns" v-if="col.isNal == false">
                            
                            <div>
                                <p>
                                    {{col.magazine.name}} 
                                </p>
                            </div>
                            <div><p class="block appearance-none mt-2 w-48 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" >{{ col.amount }}</p></div>
                        </div>
                    </div>

                </div> 
            </div>
        </div>
    </div>







    <!--<modal name="assortment">
        <div class="p-6">
            
                <label>Ассортимент</label>
                <br>
                    <div class="relative">
                    <select v-model="orderProduct" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                        <option v-for="item in assortment" :value="item.id">{{item.type}}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
                <label>Количество</label>
                <br>
                    <div class="relative">
                    <input v-model="orderAmount" type="number" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                </div>
                <br>
                <button @click="addToOrder()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Добавить</button>
                <button v-if="order.length > 0" @click="sendOrder()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Оформить заказ</button>
                <table class="w-full whitespace-nowrap mt-5">
                    <tr class="text-center font-bold border-b border-gray-200">
                        <th>Ассортимент</th>
                        <th>Количество</th>
                    </tr>
                    <tr v-for="item in order" class="text-left  border-b border-gray-200">
                        <td class="px-6 pt-3 pb-3 w-8">{{assortment[item.item-2].type}}</td>
                        <td class="px-6 pt-3 pb-3 w-8">{{item.amount}}</td>
                    </tr>
                </table>
            
        </div>
    </modal>-->
    
    <modal name="history">
        <div class="p-6">
            <div class="flex">
                <div>
                    от
                    <datepicker 
                        v-model="from" 
                        type="date" 
                        placeholder=""
                        :show-time-header = "time">
                    </datepicker>
                </div>
                <div>
                    до
                    <datepicker 
                        v-model="to" 
                        type="date" 
                        placeholder=""
                        :show-time-header = "time">
                    </datepicker>
                </div>
                
            </div>
            <br>
            <br>
            <table class="w-full whitespace-nowrap  ">
                <tr class="text-left font-bold border-b border-gray-200">
                    <th class="px-6 pt-4 pb-4">Реализатор</th>
                    <th class="px-6 pt-4 pb-4">Номер</th>
                    <th class="px-6 pt-4 pb-4">Дата</th>
                    <th class="px-6 pt-4 pb-4">Отчет</th>
                </tr>
                <tr class="text-left border-b border-gray-200" v-for="item in auth_realization" v-if="new Date(from) <= new Date(item.created_at) && new Date(to) >= new Date(item.created_at)">
                    <td class="px-6 pt-3 pb-3 w-8">{{item.realizator.first_name}}</td>
                    <td class="px-6 pt-3 pb-3 w-8">{{item.id}}</td>
                    <td class="px-6 pt-3 pb-3 w-8">{{moment(item.created_at).format("DD-MM-YYYY")}}</td>
                    <td class="px-6 pt-3 pb-3 w-8">
                        <div class="flex gap-2">
                            <button v-if="$page.props.auth.user.position_id != 3" @click="showReport3(item.id, item.realizator.id)" class="bg-green-500 text-white font-bold py-2 px-4 rounded">редактировать</button>
                            <a :href="'/realization_report/'+item.id" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center"
                            >
                              Скачать отчет 
                            </a>

                        </div>
                        

                    </td>
                </tr>
            </table>
        </div>
    </modal>
    

    <modal name="avans_report">
        <div class="px-6 py-6">
            <!--<div class="flex gap-5">
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType" v-bind:value="1" v-model="quarter1" @change="maintest1()">
                    <span class="ml-2">сегодня</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType"  v-bind:value="2" v-model="quarter1" @change="maintest2()">
                    <span class="ml-2">неделя</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType"  v-bind:value="3" v-model="quarter1" @change="maintest3()">
                    <span class="ml-2">месяц</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType"  v-bind:value="4" v-model="quarter1" @change="maintest4()">
                    <span class="ml-2">год</span>
                </label>
            </div>-->
            <div class="flex">
                <div>
                    от
                    <datepicker 
                        v-model="from" 
                        type="date" 
                        placeholder=""
                        :show-time-header = "time">
                    </datepicker>
                </div>
                <div>
                    до
                    <datepicker 
                        v-model="to" 
                        type="date" 
                        placeholder=""
                        :show-time-header = "time">
                    </datepicker>
                </div>
                
            </div>
            <br>
            <br>
            <table class="w-full whitespace-nowrap  ">
                <tr class="text-left font-bold border-b border-gray-200">
                    <th class="px-6 pt-4 pb-4">Реализатор</th>
                    <th class="px-6 pt-4 pb-4">Номер</th>
                    <th class="px-6 pt-4 pb-4">Дата</th>
                    <th class="px-6 pt-4 pb-4">Отчет</th>
                    
                </tr>
                <tr class="text-left border-b border-gray-200" v-for="item in myrealizations" v-if="new Date(item.created_at) >= new Date(from)">
                    <td class="px-6 pt-3 pb-3 w-8">{{item.realizator.first_name}}</td>
                    <td class="px-6 pt-3 pb-3 w-8">{{item.id}}</td>
                    <td class="px-6 pt-3 pb-3 w-8">{{moment(item.created_at).format("DD-MM-YYYY")}}</td>
                    <td class="px-6 pt-3 pb-3 w-8">
                        <div class="flex gap-2">                           
                            <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center"
        @click="showReport3(item.id, item.realizator.id)">
                              посмотреть отчет 
                            </a>

                        </div>
                        

                    </td>
                </tr>
            </table>
        </div>
    </modal>

    <div v-if="nakladnye">
        Накладные

        <div class="bg-white p-5 rounded-md mt-5">
            <div class="mb-5 flex justify-between">
                <div class="inline-block">
                    <input type="text" name="mailykent" class="border-b-2" v-model="company"><br>
                    <datepicker 
                        v-model="nak_date" 
                        type="date" 
                        :placeholder="nak_date"
                        :show-time-header = "time">
                    </datepicker>
                </div>
                <div class="inline-block">
                    <input type="text" list="shops" v-model="shop" class="border-b-2" label="Магазин" placeholder="магазины"/>
                    <datalist id="shops">
                        <option v-for="shop in shops">{{shop.name}}</option>
                    </datalist><br>
                    <input type="text" list="option1" v-model="option" class="border-b-2" label="опция" placeholder="консегнация" />
                    <datalist id="option1">
                        <option>Консегнация</option>
                        <option>Консегнация МКТ</option>
                        <option>Оплата наличными</option>
                    </datalist>

                </div>
            </div>
            <div v-if="myrealizations[0]">
                <table class="w-full whitespace-nowrap mt-5">
                <tr   class="text-center font-bold border-b border-gray-200">
                    <th>#</th>
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Брак</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
                <tr v-for="(item1, key1) in empty">
                    <td>{{key1+1}}</td>
                    <td>
                        <select name="items" class="border-b-2" v-model="nak_items[key1]" @change="putRows($event,key1)">
                            <option></option>
                            <option v-for="item in myrealizations[0].order">{{assortment[item.assortment_id].type}}</option>
                        </select>
                    </td>
                    <td><input onclick="select()" type="text" v-model="nak_amount[key1]" class="ml-3"></td>
                    <td><input onclick="select()" type="text" v-model="nak_brak[key1]"></td>
                    <td><input onclick="select()" type="text" v-model="nak_price[key1]" disabled="true"></td>
                    <td><input onclick="select()" type="text">{{nak_price[key1] * (nak_amount[key1] - nak_brak[key1])}}</td>
                </tr>
            </table>
            </div>
        </div>
        <div class="panel">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="saveNakladnoe()">
                сохранить
            </button>
            <!--<a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="/blank">
                скачать
            </a>-->
            <!--<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" >
                распечатать
            </button>-->
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showNakHistory()">
                история
            </button>
            <!--<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showNakReport()">
                отчет
            </button>-->
        </div>
    </div>

    <modal name="nakreport">
        <div>
            <div class="p-5">
                <select v-model="nak_month" @change="getNakReportByMonth($event.target.selectedIndex)">
                    <option v-for="(item, index) in list" :value="index">{{item}}</option>
                </select>
            </div>
            <table class="w-full whitespace-nowrap mt-5 p-5">
                <tr   class="text-center font-bold border-b border-gray-200">
                    <th>#</th>
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Брак/Обмен</th>
                    <th>Сумма</th>
                    <th>Сумма Брак/Обмен</th>
                </tr>
                <tr v-for="(i, k) in my_nak_report" class="text-center">
                    <td>{{k+1}}</td>
                    <td class="text-left">{{i.name}}</td>
                    <td>{{i.amount}}</td>
                    <td>{{i.brak}}</td>
                    <td>{{i.sum}}</td>
                    <td>{{i.brak_sum}}</td>
                </tr>
            </table>
        </div>
    </modal>

    <modal name="nak_history">
        <div class="px-6 py-6">
            История накладных
            
            <div v-for="nak in nakladnoe"   >
                <a :class="nak.consegnation == 2 && nak.paid == 0 ? 'w-full border-3 mt-5 shadow-lg flex p-4 text-white bg-red-700':'w-full border-3 mt-5 shadow-lg flex p-4'" :href="'/blank/' + nak.id"><p>Накладная от {{moment(nak.created_at).format("DD-MM-YYYY")}}  №{{nak.id}}</p></a>
                <button @click="nakIsPaid(nak)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded" v-if="nak.consegnation == 2 && nak.paid == 0">Оплачено</button>
            </div>
        </div>
    </modal>
</div>
</template>

<script>



import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import axios from 'axios'
import $ from 'jquery'
import Datepicker from 'vue2-datepicker'
import moment from "moment"
import 'vue2-datepicker/index.css'

export default {
    metaInfo: {
        title: 'Orders'
    },
    
    layout: Layout,
    data() {

        return {
            list:['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            my_nak_report: this.nak_report,
            nak_amount:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            nak_brak:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            nak_sum:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            nak_price:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            nak_items:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            empty:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            shop:'',
            option:'',
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
            realization_id: null,//this.realizations[this.realizations.length-1].id,
            orderAmount: 0,
            orderProduct: null,
            sklad: null,
            order: this.assorder1,//[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            dopOrder: this.assorder1,
            myorder: this.assorder,//[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            secondorder:[],
            nakladnye: false,
            myreport: this.report1,
            mymagazines: [],
            columns:[{
                magazine: null,
                amount: null,
                pivot: null,
                isNal: false,
            }],
        }
    },
    props: {
        nak_report: Array,
        shops: Array,
        realizations : Array,
        assortment: Object,
        realizator: Object,
        realcount: Array,
        auth_realization: Array,
        assorder: Object,
        assorder1: Object,
        nakladnoe: Array,

        majit: Number,
        sordor: Number,
    },
    mounted(){

    },
    created() {
        if(this.myrealizations != undefined && this.myrealizations[0]){
            this.myrealizations[0].order.forEach(element => {
                //console.log(element.order_amount);
                this.myorder[element.assortment_id] = element.order_amount;

            });
        }
    },
    components: {
      Datepicker,
      TextInput,
    },
    watch: {

    },
    computed: {

    },
    methods: {
        showReport2(){
            this.$modal.show('history');
        },
        setDay(){
          
            axios.post('order/date',{date : this.mydate}).then(response => {
                this.myrealizations = response.data.realizations;
            });
          
        },
        sendOrder(){
            if(confirm('Оформлять заявку?')){    
                this.$modal.hide('myorder');
                axios.post('orders/send',{
                    order : this.order,
                }).then(response => {
                    // this.order = this.assorder1;
                    this.myrealizations = [];
                    this.myrealizations.push(response.data.realization[0]) ;
                    
                    //this.$router.go()
                });
            }
        },
        sendUpdateOrder(){
            var id = 1;
            if(this.myrealizations != undefined && this.myrealizations.length > 0){
                id = this.myrealizations[this.myrealizations.length-1].id;
            }

            console.log("id", id);
            
            this.$modal.hide('myorder1');

            axios.post('orders/update',{
                order : this.dopOrder,
                realization_id : id,

            }).then(response => {
                console.log("after update", response.data);
                
                this.myrealizations = [];
                this.myrealizations.push(response.data.realization[0]);

                location.reload();
            });
        },
        newOrder(){
            this.$modal.show('myorder');
        },
        updateOrder(){
            this.$modal.show('myorder1');
        },


        showReport(){
            this.report = true;
            this.nakladnye = false;
            this.avans = false;
        },



        history1(id){
            this.$modal.show('avans_report');
            
        },
        showReport3(id,realizator){
            this.$modal.hide('history1');
            axios.post('realization-order',{id: id, realizator: realizator}).then(response => {
                this.myreport = response.data.report;
                this.mymagazines = response.data.realizator.magazine;
                this.realizators.forEach(element => {
                    if(element.id == response.data.realizator.id){
                        this.realizator = element;
                    }
                });
                this.columns = response.data.columns;
                //console.log(this.columns);
            });
        },
        showContent(index){
            var myDiv = document.getElementById(index);
            if(myDiv.children[1].style.display == 'none')
                myDiv.children[1].style.display = 'block';
            else{
                myDiv.children[1].style.display = 'none';
            }
        },
        showNakladnye(){
            this.nakladnye = true;
            this.report = false;
            this.avans = false;

            console.log("show naks");
        },
        saveNakladnoe() {
            if (confirm("Сохранить накладную?")) {
                let counter = 0;
                var items = [];
                var amounts = [];
                var brak = [];
                this.nak_items.forEach(element => {
                    
                    if(element != 0){
                        items.push(element);
                        amounts.push(this.nak_amount[counter]);
                        brak.push(this.nak_brak[counter]);
                    }
                    counter++;
                });

                var myoption = 2;
                if (this.option == "Консегнация МКТ"){
                    myoption = 1;
                } else if (this.option == 'Оплата наличными') {
                    myoption = 3;
                }


                axios.post('save-nak',{items: items,amounts:amounts,brak:brak,shop: this.shop,option:myoption,realization_id: this.auth_realization[0].id}).then(response => {
                    alert(response.data.message);
                    
                    this.nak_amount = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.nak_brak = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.nak_sum = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.nak_price = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.nak_items = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    this.empty = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    
                    this.option = '';
                    this.shop = '';

                    //location.reload();
                });
            }
        },
        putRows(event,key){
            var price = 0;
            var sum = 0;

            if (this.myrealizations[0]) {
                this.myrealizations[0].order.forEach(element => {
                    if(this.assortment[element.assortment_id].type == event.target.value){
                        price = this.assortment[element.assortment_id].price;
                        sum = element.order_amount*price;
                    }
                });
            }
            this.nak_price[key] = price;
        },
        showNakHistory(){
            this.$modal.show('nak_history');
        },
        showNak(){
            alert("proverka");
        },
        showNakReport(){
            this.$modal.show('nakreport');
        },
        getNakReportByMonth(month){           
            axios.post('nak-report-by-month',{month: month+1}).then(response => {
                this.my_nak_report = response.data;
            });
        },

        nakIsPaid(nak) {
            var conf = confirm('Подтвердить оплату?');
            if (conf === false) {
                return;
            }

            axios.post('pay-nak', {id: nak.id}).then(response => {
                this.nakladnoe = response.data;
            });
        },
        onEnter(e) {
            console.log('on enter...', e);

            const form = event.target.form;
            const index = [...form].indexOf(event.target);
            
            const next_index = index + 1;
            form.elements[next_index].select();
            form.elements[next_index].focus();

            event.preventDefault();
        },

        showMyAvans() {
            axios.post("/realizator-order", {id : this.realizator.id} ).then(response => {
                this.avans = true
                this.report = false;
                this.nakladnye = false;

                this.myreport = response.data.report;
                this.mymagazines = this.realizator.magazine;
                this.columns = response.data.columns;
                this.majit = response.data.majit;
                this.sordor = response.data.sordor;

                //console.log(this.myreport);
            });

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

            if (this.myreport != null){
                this.myreport.forEach(element => {
                    total += element.sold * element.assortment.price;
                    //total -= element.defect * element.assortment.price;
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
            if (this.columns != null){

                this.columns.forEach(element => {
                    if (element != null && element.isNal == false)
                        total = total + parseInt(element.amount);
                });
            }

            return total;
        }
    }
}
</script>