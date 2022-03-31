<template>
<div class="flex flex-col h-full">

    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 sm:hidden mb-4" id="grid-state" v-model="realizator" @change="showTable()">
        <option v-for="item in realizators" :value="item">{{item.first_name}}</option>
    </select>

<div class="w-full overflow-x-auto">
    <table class="tableizer-table sm:hidden">
<thead><tr class="tableizer-firstrow"><th>Наименование товаров</th><th>Заявка</th><th>Отпущено</th><th>Возврат</th><th>Обмен брак</th><th>Брак на сумму</th><th>Продано</th><th>Цена</th><th>Сумма</th><th>&nbsp;</th></tr></thead>
<tbody>
 <tr v-for="item in myreport" >
    <td>{{item.assortment.type}}</td><td>{{item.order_amount}}</td>
    <td><input type="number" v-model="item.amount" class="w-8" @change="setOrderAmount(item.id, item.amount)"></td>
    <td><input class="w-8" type="number" v-model="item.returned" @change="setOrderReturned(item.id, item.returned)"></td>
    <td><input type="number" v-model="item.defect" class="w-8" @change="setOrderDefect(item.id, item.defect)"></td>
    <td>{{item.defect*item.assortment.price}}</td>
    <td>{{item.amount-item.returned-item.defect}}</td>
    <td><input class="w-8" type="number" name="" v-model="item.assortment.price"></td>
    <td>{{item.assortment.price*(item.amount-item.returned-item.defect)}}</td><td>&nbsp;</td></tr>



 <tr><td>накладное на возврат</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>ИТОГ</td><td>{{totalBrak()}}</td><td>&nbsp;</td><td>&nbsp;</td><td>  </td><td>&nbsp;</td></tr>



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
                <td colspan="2" class="text-right">{{totalSum()}}</td>
            </tr>
            <tr>
                <td colspan="8"></td>
                <td>сумма реализации</td>
                <td><div  v-if="getRealizationSum()">{{getRealizationSum()}}</div>
                    <div v-else></div></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="4"></td>
                <td>Продажа на нал</td>
                <td><div v-if="getRealizationSum()">{{totalSum()-getRealizationSum()}}</div>
                    <div v-else>{{totalSum()}}</div></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="4"></td>
                <td>Мажит</td>
                <td><input type="number" name="majit" v-model="majit"></td>
            </tr>
            <!-- <tr>
                <td colspan="4"></td>
                <td colspan="4"></td>
                <td>Сордор</td>
                <td><input type="number" name="sordor" v-model="sordor"></td>
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
            <!--<tr>
                <td colspan="4"></td>
                <td colspan="4"></td>
                <td>продано</td>
                <td>{{soldTotal()}}</td>
            </tr>-->
</tbody>
</table>
</div>

    <div class="panel hidden sm:flex justify-start gap-5 ">
        <button v-if="$page.props.auth.user.position_id != 6" :class="sales ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showSales()">
          Заявки
        </button>

        <button v-if="$page.props.auth.user.position_id == 2 || $page.props.auth.user.position_id == 1" :class="itog ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showItog()">
          Итог заявок
        </button>

        <button v-if="$page.props.auth.user.position_id != 2 && $page.props.auth.user.position_id != 5" :class="real ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showRealizators()">
          Реализаторы
        </button>
        <button v-if="$page.props.auth.user.position_id != 2 && $page.props.auth.user.position_id != 5" :class="report ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showReport()">
          Авансовый отчет
        </button>
        <button v-if="$page.props.auth.user.position_id != 2 && $page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5" :class="report2 ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showReport2()">
          Отчет
        </button>
        <button v-if="$page.props.auth.user.position_id != 2 && $page.props.auth.user.position_id != 6 && $page.props.auth.user.position_id != 5" :class="report3 ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showReport4()">
          Продано
        </button>

        <button v-if="$page.props.auth.user.position_id == 6" :class="naks ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showNaks()">
          Накладные
        </button>


        <div v-if="alert > 0 && $page.props.auth.user.position_id != 6" class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 cursor-pointer" role="alert" @click="closeAlert()">
          <p class="font-bold">Новых заявок</p>
          <p class="text-sm">{{alert}}</p>
        </div>
        <div v-if="alert1 > 0 && $page.props.auth.user.position_id == 6" class="bg-green-100 border-t border-b border-green-500 text-green-700 px-4 py-3 cursor-pointer" role="alert" @click="closeAlert1()">
          <p class="font-bold">Новая реализация</p>
          <p class="text-sm">{{alert1}}</p>
        </div>
        <div v-if="alert_dop > 0 && $page.props.auth.user.position_id != 6" class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 cursor-pointer" role="alert" @click="closeAlertDop()">
          <p class="font-bold">Доп. заявок</p>
          <p class="text-sm">{{alert_dop}}</p>
        </div>
        
    </div>
    <br>
    

    <div v-if="report" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 hidden sm:block">
        <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" v-model="realizator" @change="showTable()">
            <option v-for="item in realizators" :value="item">{{item.first_name}}</option>
        </select>
        <br>
        <br>


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
                <tr v-for="item in myreport" :class="item.sold + item.defect > item.amount ? ' bg-red-700':''">
                    <td>{{ item.assortment.type }}</td>
                    <td>{{ item.order_amount }}</td>
                    <td><input onclick="select()" type="number" v-model="item.amount" class="w-8" @change="setOrderAmount(item.id, item.amount)"></td>
                    <td><input onclick="select()" class="w-8" type="number" v-model="item.returned" @change="setOrderReturned(item.id, item.returned)"></td>
                    <td><input onclick="select()" type="number" v-model="item.defect" class="w-8" @change="setOrderDefect(item.id, item.defect)"></td>
                    <td>{{item.defect*item.assortment.price}}</td>
                    <td>{{item.sold}}</td>
                    <td><input onclick="select()" class="w-8" type="number" name="" v-model="item.assortment.price"></td>
                    <td>{{item.sold*item.assortment.price}}</td>
                    <td>&nbsp;</td>
                </tr>
                
                <tr>
                    <td>накладное на возврат</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>ИТОГ</td>
                    <td>{{totalBrak()}}</td>
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
                    <td colspan="2" class="text-right">{{totalSum()}}</td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                    <td>сумма реализации</td>
                    <td><div  v-if="getRealizationSum()">{{getRealizationSum()}}</div>
                        <div v-else></div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Продажа на нал</td>
                    <td><div v-if="getRealizationSum()">{{totalSum()-getRealizationSum()}}</div>
                        <div v-else>{{totalSum()}}</div></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Мажит</td>
                    <td><input type="number" name="majit" v-model="majit"></td>
                </tr>
                <!-- <tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>Сордор</td>
                    <td><input type="number" name="sordor" v-model="sordor"></td>
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
                <!--<tr>
                    <td colspan="4"></td>
                    <td colspan="4"></td>
                    <td>продано</td>
                    <td>{{soldTotal()}}</td>
                </tr>-->
            </tbody>
        </table>

        <div class="hidden sm:block">
            <div class="row">
                <div class="col-4 flex gap-5 mt-5">
                    <div>
                        <h6>Накладные под реализации</h6>
                        <div class="flex gap-3 mt-2" v-for="col in columns"  v-if="col.isNal == false">
                            
                            <div>
                                <select class="block appearance-none mt-2 w-48 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" v-model="col.magazine">
                                    <option v-for="item in mymagazines" :value="item">{{item.name}}</option>
                                </select>
                            </div>
                            <div><input class="block appearance-none mt-2 w-48 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" type="number" name="amount" v-model="col.amount"></div>
                        </div>
                        <!--<button class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addColumn()">добавить магазин</button>-->
                    </div>

                </div> 
            </div>
        </div>

        <br>
        <div v-if="$page.props.auth.user.position_id == 1" class="flex justify-start gap-5">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center" @click="saveRealization()">сохранить</button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center" @click="saveConfirmRealization()">завершить и сохранить</button>
            <a v-if="myreport[0]" :href="'realization_report/'+myreport[0].realization_id"class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center cursor-pointer">
                          Скачать отчет 
                        </a>
        </div>
    </div>

    <br>


    <div v-if="!report" class="w-full bg-white rounded-2xl  h-auto p-6 pt-2 hidden sm:block">
        <div class="flex gap-5">
            <select-input v-model="realizators_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц" >
                <option v-for="month in months" :value="month.id">{{month.name}}</option>
            </select-input>

            <select-input v-model="realizators_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год">
                <option v-for="year in years" :value="realizators_year" >{{year}}</option>
            </select-input> 
        </div>

        <div v-if="report3" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 hidden sm:block">
            <!-- <div class="flex gap-5">

                <select-input v-model="realizators_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц" >
                    <option v-for="month in months" :value="month.id">{{month.name}}</option>
                </select-input>

                <select-input v-model="realizators_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год">
                    <option v-for="year in years" :value="realizators_year" >{{year}}</option>
                </select-input> 
            </div>
            <br>
            <br> -->
            <table class="tableizer-table w-full">
                <thead>
                    <tr class="tableizer-firstrow"><th>Наименование товара</th><th>количество</th><th>цена</th><th>Сумма</th></tr>
                    <tr v-for="item in mysold1" >
                        <td>{{item.assortment}}</td>
                        <td>{{item.sold_amount}}</td>
                        <td>{{item.price}}</td>
                        <td>{{item.sold_amount * item.price}}</td>
                    </tr>
                    <tr>
                        <td>Итого:</td>
                        <td>{{mysold1.reduce((acc, item) => acc + parseInt(item.sold_amount),0)}}</td>
                        <td></td>
                        <td>{{mysold1.reduce((acc, item) => acc + item.sold_amount * item.price,0)}}</td>
                    </tr>
                </thead>
            </table>
        </div>

        <div v-if="report2" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2 hidden sm:block">
            <!-- <div class="flex gap-5">
                <select-input v-model="realizators_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                    <option v-for="month in months" :value="month.id">{{month.name}}</option>
                </select-input>

                <select-input v-model="realizators_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                    <option v-for="year in years" :value="realizators_year">{{year}}</option>
                </select-input>      
            </div>
                <br>
                <br> -->
            <table class="tableizer-table w-full">
            <thead>
                <tr class="tableizer-firstrow">
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Брак/обмен</th>
                    <th>Сумма брак/обмена</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="item in defects_report" >
                    <td>{{item.assortment}}</td>
                    <td>{{item.amount}}</td>
                    <td>{{item.defect_amount}}</td>
                    <td>{{item.defect_sum}}</td>
                    <td>{{item.total_sum}}</td>
                </tr>

            <!--<tr v-for="realizator in myrealizators" v-if="new Date(realizator.created_at).getFullYear() == realizators_year">
                <td>{{realizator.first_name}}</td><td><p>{{myRealizationSum(realizator.realization)}}</p></td><td>{{myBrakSum(realizator.realization)}}</td><td>{{myBrakSum(realizator.realization) ? (myBrakSum(realizator.realization)/myRealizationSum(realizator.realization)*100).toFixed(2) : 0}}%</td><td>{{(myRealizationSum(realizator.realization))-(0.1*(myRealizationSum(realizator.realization)))}}</td>
            </tr>-->

                <tr>
                    <td>ОБЩ.</td>
                    <td>{{defects_report.reduce((acc, item) => acc + item.amount * 1, 0)}}</td>
                    <td>{{defects_report.reduce((acc, item) => acc + item.defect_amount * 1, 0)}}</td>
                    <td>{{defects_report.reduce((acc, item) => acc + item.defect_sum * 1, 0)}}</td>
                    <td>{{defects_report.reduce((acc, item) => acc + item.total_sum * 1, 0)}}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div v-if="itog" class="w-full bg-white rounded-lg  h-auto overflow-auto sm:block">
            

            <table   class="w-full whitespace-nowrap text-xs">
                <thead class="bg-white custom1">
                    <tr class="text-left font-bold border-b border-gray-200 bg-white">

                        <th class="pl-3 pt-4 pb-4 bg-white  w-72 sticky">
                            <p class="font-bold text-center w-48">Наименование</p>
                        </th>
                        <td class="px-6 pt-4 pb-4 top-0 bg-white " v-for="(n, i) in parseInt(days)">
                            <p class="font-bold text-center ">{{ i + 1 }} {{ monthes[realizators_month] }}</p>
                        </td>
                        <!--<th class="px-6 pt-4 pb-4 sticky top-0 bg-white ">Итог</th>-->

                    </tr>
                </thead>
                <!--<tr class="text-left font-bold border-b border-gray-200" v-for="item in conversions">-->
                
                <tbody>
                    <tr v-for="(item,j) in assortment">
                        <th class="sticky pl-6 pt-4 pb-4 text-left left-0 bg-white w-48">{{item.type}}</th>

                        <td v-for="(n, i) in parseInt(days)" class="px-6 pt-4 pb-4">
                            <p v-if="getData(i, item.id)">{{getData(i, item.id)}}</p>
                            <p v-else>0</p>
                        </td>   
                        
                        <td>
                            <p class="pl-5">{{calculateTotal(j)}}</p>
                        </td>
                    </tr>
                   
                    <!--<tr>
                        <th class="px-6 pt-4 pb-4 text-left">Итог</th>
                        <td class="px-6 pt-4 pb-4" v-for="(n, i) in parseInt(days)-1">
                            
                        </td>
                    </tr>-->
                            
                </tbody>

            </table>
        </div>

        <div v-if="naks" class="px-6 py-6">
            Накладные
            
            <div v-for="nak in nakladnoe">
                <a :class="nak.consegnation == 2 && nak.paid == 0 ? 'w-full border-3 mt-5 shadow-lg flex p-4 text-white bg-red-700':'w-full border-3 mt-5 shadow-lg flex p-4'" :href="'/blank/' + nak.id"><p>Накладная от {{moment(nak.created_at).format("DD-MM-YYYY")}}  №{{nak.id}}</p></a>
            </div>
        </div>

        <table v-if="sales" class="w-full whitespace-nowrap mt-5">

                <tr class="text-left font-bold border-b border-gray-200">
                    <th >Ассортимент</th>
                    <th v-for="item in myorder">
                        <tr colspan="2">
                            <td>
                                {{ item.realizator.first_name }}
                            </td>
                        </tr>
                        <tr class="flex justify-start">
                            <td >Заявка</td>
                            <td class="ml-10">Г. П.</td>
                        </tr>
                    </th>
                    
                    <th>Итог</th>
                    <th>Запас</th>
                </tr>
                <tr v-for="(item, key) in assortment" class="border-b">
                
                  <td class="w-64 border-r-4">{{item.type}}</td>
                  <td class="w-48 border-r-4" v-for="(i, key2) in myorder">
                    <tr class=" flex justify-between">
                        <td>{{i.assortment[key].order_amount}}</td>
                        
                        <td v-if="i.assortment[key].order_amount">
                            <input type="number" v-model="i.assortment[key].amount[0].amount" v-on:keyup.enter="onEnter" onclick="select()" class="shadow appearance-none border rounded w-20 py-2 px-3  text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="0">
                        </td>
                    </tr>
                  </td>
                    <!--
                        
                        -->
                            <!--<input type="number" v-model="i.assortment[key+2].amount[0].amount" class="shadow appearance-none border rounded w-20 py-2 px-3  text-gray-700 leading-tight focus:outline-none focus:shadow-outline" @change="setOrderAmount(i.assortment[key+2].amount[0].id, i.assortment[key+2].amount[0].amount, calculateExtra(key))">
                        <!--</td>
                        <td v-else >
                            
                        </td>

                    </tr>
                  </td>-->
                  <td>
                      <p class="pl-5">{{calculateTotal(key)}}</p>
                  </td>
                <td>
                    <p class="pl-5">{{calculateExtra(key)}}</p>
                </td>
                  

                  <!--<td v-for="(item1, key1) in myrealizations" class="text-center  border-r-4" >
                    <tr v-for="(item2, key2) in item1.order" v-if="item2.assortment == item.id" class="flex justify-center"><td class="mr-10 mt-3">{{addTotal(item2.order_amount,item2.amount,item.id)}}</td><td><input type="number" v-if="item2.amount > 0" :v-model="item2.amount" class="shadow appearance-none border rounded w-20 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" @change="setOrderAmount(item2.id, item2.amount)">
                        <input type="number" v-else v-model="item2.amount" class="shadow appearance-none border rounded w-20 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" @change="setOrderAmount(item2.id, item2.amount)">
                    </td>
                    </tr>

                  </td>
                  <td class="text-center">{{getTotal()}}</td>
                  <td v-if="">{{getReserve()}}</td>-->
                </tr>
                <tr>
                    <td></td>
                    <td v-for="(i, key2) in order">
                        <button v-if="i.status != 5 && i.status != 3" v-bind:id="'save_' + key2" class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center" @click="saveOrder(i, key2)">Сохранить</button>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
        </table>

         <table v-if="real" class="w-full whitespace-nowrap mt-5">
                <tr class="text-center font-bold border-b border-gray-200">
                    <th >Реализатор</th>
                    <th> Всего заказов</th>
                </tr>
                <tr @click="history(item.realizator.id)" v-for="item in count" class="text-center border-b border-gray-200  hover:bg-gray-100">
                    <td class="cursor-pointer">{{item.realizator.first_name}}</td>
                    <td>{{item.amount}}</td>
                </tr>
               
        </table>
        <!--<table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">#</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Реализатор</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Сумма</p>
                </th>
                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Брак</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Процент
                    </p> 
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Выручка
                    </p> 
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Статус
                    </p> 
                </th>
            </tr>

            <tr v-for="item in realizations" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="item.id" @click="showOrder(item.id)">
                <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.id}}</p>
               </td>  
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex">
                        <p class="text-sm">{{item.realizator.first_name}}</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.realization_sum}}</p>
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.defect_sum}}</p>
               </td> 
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.percent}}</p>
               </td> 
               
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.income}}</p>
               </td> 
                  <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{item.status.name}}</p>
               </td> 
            </tr>
        </table>-->
    </div>

    

    <modal name="history">
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
                            <button @click="showReport3(item.id, item.realizator.id)" class="bg-green-500 text-white font-bold py-2 px-4 rounded">редактировать</button>
                            <a :href="'/realization_report/'+item.id"
                              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center"
                            >
                              Скачать отчет 
                            </a>

                        </div>
                        

                    </td>
                </tr>
            </table>
        </div>
    </modal>

  
    
 

   <!-- <modal name="order">
        <div class="p-6">
            <table class="w-full whitespace-nowrap  ">
                <tr class="text-left font-bold border-b border-gray-200">
                    <th>Наименование</th>
                    <th>Заявка</th>
                    <th>Отпущено</th>
                </tr>
                <tr v-for="item in myorder" class="text-left hover:bg-gray-100 focus-within:bg-gray-100 mb-3">
                    <td class="px-6 pt-3 pb-3 w-8">
                        {{item.assortment.type}}
                    </td>
                    <td class="px-6 pt-3 pb-3 w-8">
                        {{item.order_amount}}
                    </td>
                    <td class="px-6 pt-3 pb-3 w-8">
                        {{item.amount}}
                    </td>
                </tr>
            </table>
        </div>
    </modal>-->
    <modal name="new-orders">
        <div class="p-10">
            <table>
                <tr>
                    <th>Заявка от:</th>
                </tr>
                <tr v-for="item in order_users" class="pt-4">
                    <td>{{item.first_name}}</td>
                </tr>
            </table>
        </div>
    </modal>

    <modal name="dop-orders">
        <div class="p-10">
            <table>
                <tr>
                    <th>Дополнительная заявка от:</th>
                </tr>
                <tr v-for="item in dop_users" class="pt-4">
                    <td>{{item.first_name}}</td>
                </tr>
            </table>

            <br><br>
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="acceptDop">Принять</button>
            <button class="bg-red-500 text-white font-bold py-2 px-4 rounded" @click="declineDop">Отклонить</button>
        </div>
    </modal>

</div>
</template>

<script>

import Layout from '@/Shared/Layout'
import axios from 'axios'
import $ from 'jquery'
import Vue from "vue";
import JsonExcel from "vue-json-excel";
import moment from "moment";
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'

Vue.component("downloadExcel", JsonExcel);


export default {
    metaInfo: {
        title: 'Realization'
    },
    data(){


        return{
            timerCount: 10,
            mysold1: this.sold1,
            defects_report: this.defects_report,
            realizators_month: new Date().getMonth()+1,
            realizators_year: new Date().getFullYear(),
            years: [2022, 2021, 2020, 2019, 2018],
            months:[
            {id: 1, name: "Январь"},
            {id: 2, name: "Февраль"},
            {id: 3, name: "Март"},
            {id: 4, name: "Апрель"},
            {id: 5, name: "Май"},
            {id: 6, name: "Июнь"},
            {id: 7, name: "Июль"},
            {id: 8, name: "Август"},
            {id: 9, name: "Сентябрь"},
            {id: 10, name: "Октябрь"},
            {id: 11, name: "Ноябрь"},
            {id: 12, name: "Декабрь"},
            ],
            myorder: this.order,
            order_users: [],
            dop_users: [],
            time: true,
            from: new Date(),
            to: new Date(),
            //majit: 0,
            //sordor: 0,
            moment: moment,
            columns:[{
                magazine: null,
                amount: null,
                pivot: null,
                isNal: false,
            }],
            counter: 0,
            nakladnaya:[null],
            mymagazines: [],
            magazines: [0,0,0,0,0,0],
            myrealizators: this.realizators,
            myrealizators_total: null,
            myreport: this.report1,
            report2: false,
            report3: false,
            sales: true,
            report: false,
            naks: false,
            monthes: this.monthes,
            myreports: this.reports,
            alert: this.realization_count,
            alert1: this.nak_count,
            alert_dop: this.dop_count,
            realizator_order: [],
            realizator: this.realizators[0],
             json_fields: 
                {
                     "Товар": "assortment.type",
                     "Заявка": "order_amount",
                     "Отпущено": "amount",
                     "Возврат" : "returned",
                     "Обмен/Брак": "defect",
                     "Брак на сумму": "defect_sum",
                     "Продано": "sold"
                },
            json_data: this.report1,
            json_meta: [
                {
                    key : 'charset',
                    value : 'utf-8'
                }
            ],
            myrealizations: this.realizations,
            total: 0,
            reserve: 0,
            real: false,
            itog: false,
            form:this.$inertia.form({
                realizator: null,
                summ: null,
                defect: null,
            }),
        }
    },
    layout: Layout,

    props: {
        report1: Array,
        //realizators_total: Array,
        realizations: Array,
        realizators: Array,
        assortment: Array,
        monthes: Object,
        count: Array,
        realization_count: Number,
        dop_count: Number,
        nak_count: Number,
        order: Array,
        sold1: Array,
        days: Number,
        reports: Array,
        majit: Number,
        sordor: Number,
        nakladnoe: Array,
    },
    mounted(){

 
    },
    created() {
        //console.log(this.myorder[0].assortment[1]);
        
        console.log(this.myreports);

        if(this.$page.props.auth.user.position_id == 6){
            this.real = false;
            this.report = true;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
        }
        
    },
    components: {
        JsonExcel,
        Datepicker,
        SelectInput,
        TextInput
    },
    watch: {

        timerCount:{
            handler(value) {
                    if (value > 0) {
                        setTimeout(() => {
                            this.timerCount--;
                        }, 1000);
                    } else {
                        var latest = 0;

                        for (var i = 0; i < this.myorder.length; i++) {
                            console.log('myorder', this.myorder[i]);

                            if (this.myorder[i].id > latest)
                                latest = this.myorder[i].id;
                        }



                        axios.post('get-order',{size:this.myorder.length, latest: latest}).then(response => {
                            //console.log(response.data); return;

                            response.data.refresh.forEach((item, key) => {
                                
                                if(new Date(item.updated) >= new Date(Date.now() - 11000)){
                                    //this.myorder.splice(key,1,item);                               
                                }
                                
                            });

                            if (response.data.order != null)
                                var neworder = response.data.order;

                                for (var i = 0; i < neworder.length; i++) {
                                    var found = false;

                                    for (var j = 0; j < this.myorder.length; j++) {
                                        if (neworder[i].id == this.myorder[j].id) {
                                            found = true;

                                            // any updates?
                                            if (neworder[i].updated != this.myorder[j].updated) {
                                                for (var k = 0; k < neworder[i].assortment.length; k++) {
                                                    if (this.myorder[j].assortment[k] === undefined) {
                                                        this.myorder[j].assortment.push(neworder[i].assortment[k]);
                                                        continue;
                                                    }

                                                    this.myorder[j].assortment[k].order_amount = neworder[i].assortment[k].order_amount;
                                                }

                                            }

                                            break;
                                        }
                                    }

                                    if (found == false) {
                                        // new zayavka
                                        this.myorder.push(neworder[i]);
                                        continue;
                                    }

                                }

                                //this.myorder = this.myorder.concat(response.data.order);

                                this.alert = response.data.count;
                                this.alert1 = response.data.nak;
                                this.alert_dop = response.data.dop;
                                this.timerCount = 10;
                        });
                    }

                },
                immediate: true
        },
        realizators_month:function(val) {
            this.realizators_month = val;
            
            console.log(val, this.realizators_year);

            this.getSold1();
            this.getDefects();
            this.getNaks();
        },
        realizators_year:function(val) {
            console.log(val);
            this.realizators_year = val;
            
            this.getSold1();
            this.getDefects();
            this.getNaks();
       },
    },
    computed: {

    },
    methods: {
        showNaks() {
            this.real = false;
            this.report = false;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
            this.naks = true;
            this.itog = false;
        },
        showReport4(){
            this.itog = false;
            this.real = false;
            this.report = false;
            this.sales = false;
            this.report2 = false;
            this.report3 = true;
            this.naks = false;
        },
        myRealizationSum(realization){
            return realization.reduce((acc, item) => acc + (new Date(item.created_at).getMonth()+1 == this.realizators_month ? item.realization_sum : 0), 0);
        },
        myBrakSum(realization){
            return realization.reduce((acc, item) => acc + (new Date(item.created_at).getMonth()+1 == this.realizators_month ? item.defect_sum : 0), 0);
        },
        soldTotal(){
            var total = 0;
            if(this.myreport != null){
                this.myreport.forEach(element => {
                    total += element.sold;
                });
            }
            return total;
        },
        calculateExtra(key){
            let total = 0;
            this.order.forEach(element => {
                    if (element.assortment[key].order_amount != null && element.assortment[key].amount[0].order_amount - element.assortment[key].amount[0].amount < 0){
                        total += element.assortment[key].amount[0].amount - element.assortment[key].amount[0].order_amount;
                    }
                
                });
            return total;
        },
        calculateTotal(key){
            let total = 0;
            this.order.forEach(element => {
                    if(element.assortment[key].order_amount != null){
                        total += element.assortment[key].order_amount;
                    }
                
                });
            return total;
        },
        addColumn(){
            this.columns.push({magazine: null, amount: null, pivot: null, isNal: false});
        },
        saveRealization(){
            var conf = confirm('Вы уверены?');
            if (conf === false) {
                return;
            }

            axios.post('save-realization',{
                realization_sum: this.totalSum(),
                realization: this.realizator.realization[this.realizator.realization.length-1],
                realizator_income: this.getRealizationSum()/10,
                bill: this.getRealizationSum(),
                income: this.getRealizationSum()-this.getRealizationSum()/10,
                cash: this.totalSum()-this.getRealizationSum(),
                percent: this.totalBrak()/this.totalSum()*100,
                defect_sum: this.totalBrak(),
                majit: this.majit == null ? 0 : this.majit,
                // sordor: this.sordor,
                columns: this.columns,
                report: this.myreport
            }).then(response => {
                alert(response.data.message);
                this.columns = response.data.columns;
            });
        },
        saveConfirmRealization(){
            var conf = confirm('Вы уверены?');
            if (conf === false) {
                return;
            }

            let cash = this.getRealizationSum() ? this.totalSum()-this.getRealizationSum() : this.totalSum();

            var income = 0;

            if (this.getRealizationSum())
                income = (this.totalSum() - this.getRealizationSum() - this.majit - this. sordor) - ((this.totalSum() - this.getRealizationSum())/10);
            else
                income = this.totalSum() - (this.totalSum() / 10) - (this.majit) - (this. sordor);

            axios.post('confirm-realization',{
                realization: this.realizator.realization[this.realizator.realization.length-1],
                realizator_income: income / 10,
                bill: this.getRealizationSum(),
                cash: cash,
                majit: this.majit == null ? 0 : this.majit,
                // sordor: this.sordor,
                income: income,
                columns: this.columns,
                report: this.myreport
            }).then(response => {
                this.columns = response.data.columns;
                alert(response.data.message);
            }).catch(error => {
                 alert(error);
              });
        },
        getRealizationSum(){
            let total = 0;
            if(this.columns != null){
                this.columns.forEach(element => {
                    if(element != null && element.isNal == false)
                        total = total + parseInt(element.amount);
                });
            }
            return total;
        },
        totalSum(){
            var total = 0;

            if (this.myreport != null){
                this.myreport.forEach(element => {
                    total += element.sold * element.assortment.price;
                    //total -= element.defect * element.assortment.price;
                });
            }
            return total;
        },
        totalBrak(){
            var total = 0;
            this.myreport.forEach(element => {
                
                total += element.defect*element.assortment.price;
            });
            return total;
        },
        maintest11(){          
            axios.get('order/today_t').then(response => {
                this.myrealizators = response.data.realizators;
                this.myrealizators_total = response.data.total;
            })
        },
        maintest12(){
            axios.get('order/week_t').then(response => {
                this.myrealizators = response.data.realizators;
                //console.log(response.data.realizators);
                this.myrealizators_total = response.data.total;
            })
        },
        maintest13(){
            axios.get('order/month_t').then(response => {
                this.myrealizators = response.data.realizators;
                this.myrealizators_total = response.data.total;
            })
        },
        maintest14(){
             axios.get('order/year_t').then(response => {
                this.myrealizators = response.data.realizators;
                this.myrealizators_total = response.data.total;
            })
        },

        maintest1(){          
            axios.get('order/today').then(response => {
                this.myrealizations = response.data;
            })
        },
        maintest2(){
            axios.get('order/week').then(response => {
                this.myrealizations = response.data;
            })
        },
        maintest3(){
            axios.get('order/month').then(response => {
                this.myrealizations = response.data;
            })
        },
        maintest4(){
             axios.get('order/year').then(response => {
                this.myrealizations = response.data;
            })
        },
        showReport2(){
            this.real = false;
            this.report = false;
            this.sales = false;
            this.report2 = true;
            this.report3 = false;
            this.itog = false;
            this.naks = false;

            this.getDefects();
            console.log('get defects');
        },
        /*setOrderAmount(id, amount, returned){
            if(returned == null){
                returned = 0;
            }
            axios.post("set-order-amount",{id: id, amount: amount, returned: returned});
        },*/

        setOrderReturned(id, amount){
            axios.post("set-order-returned",{id: id, amount: amount});
        },

        setOrderDefect(id, amount){
            axios.post("set-order-defect",{id: id, amount: amount});
        },

        setOrderDefectSum(id, amount){
            axios.post("set-order-defect-sum",{id: id, amount: amount});
        },
        setOrderSold(id, amount){
            axios.post("set-order-sold",{id: id, amount: amount});
        },
        closeAlert(){
            axios.get('realization-status').then(response => {
                this.order_users = response.data;
                this.$modal.show('new-orders');
            });
            this.alert = 0;
        },
        closeAlertDop(){
            axios.get('dop-status').then(response => {
                this.dop_users = response.data;
                this.$modal.show('dop-orders');
            });
            //this.alert_dop = 0;
        },
        closeAlert1(){
            axios.get('nak-status');
            this.alert1 = 0;
        },
        showTable(){
            axios.post("realizator-order",{id : this.realizator.id}).then(response => {
                this.myreport = response.data.report;
                this.mymagazines = this.realizator.magazine;
                this.columns = response.data.columns;
                this.majit = response.data.majit;
                this.sordor = response.data.sordor;
                console.log(this.myreport);
            });
        },
        showReport(){
            this.naks = false;
            this.itog = false;
            this.real = false;
            this.report = true;
            this.sales =false;
            this.report2 = false;
            this.report3 = false;
        },
        showReport3(id,realizator) {
            this.naks = false;
            this.itog = false;
            this.real = false;
            this.report = true;
            this.sales =false;
            this.report2 = false;
            this.report3 = false;
            this.$modal.hide('history');

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
        history(item){
            axios.post('get-realizator',{id: item}).then(response => {
                ;
                this.myrealizations = response.data;
                this.$modal.show('history');
            });
        },
        addTotal(number,amount,assortment){
            this.total += number;
            if(amount>number){
                this.reserve += amount-number;
                //axios.post('add-reserve',{ assortment: assortment, amount: this.reserve});
            }
            return number;
        },
        getTotal(){
            var mytotal = this.total;
            this.total = 0;

            return mytotal;
        },
        getReserve(){

            var myreserve = this.reserve;
            this.reserve = 0;

            return myreserve;  
        },
        showRealizators(){
            this.naks = false;
            this.real = true;
            this.report = false;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
            this.itog = false;
        },
        showOrder(id){
            axios.post('sales/order',{id : id}).then(response => {

                this.myorder = response.data.order;
            });
            this.$modal.show('order');
        },
       showSales(){
            this.naks = false;
            this.real = false;
            this.report = false;
            this.sales = true;
            this.report2 = false;
            this.report3 = false;
            this.itog = false;
       },
       showItog(){
            this.naks = false;
            this.real = false;
            this.report = false;
            this.sales = false;
            this.report2 = false;
            this.report3 = false;
            this.itog = true;
       },
       getItem(amount){
        if(amount>0 || amount == null){
            return false;
        }else{
            return true;
        }
       },
       getSold1(){
        axios.post('sales/sold1',{month:this.realizators_month,year:this.realizators_year}).then(response => {
            this.mysold1 = response.data;
        })
       },
        getDefects(){
            axios.post('sales/defects',{month:this.realizators_month,year:this.realizators_year}).then(response => {
                this.defects_report = response.data;
            })
        },
        saveOrder(order, key){
            var conf = confirm('Сохранить заказ?');

            if (conf === false) {
                return;
            } 

            axios.post('set-order-amount',{order: order.assortment}).then(response => {
               alert(response.data);
               var btn = document.getElementById("save_" + key);
               btn.style.display = 'none';
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

        declineDop() {
            axios.post('decline-dop').then(response => {
                alert(response.data);
                this.alert_dop = 0;
                this.$modal.hide('dop-orders');
                
                location.reload();
            });
        },

        acceptDop() {

            axios.post('accept-dop').then(response => {
                alert(response.data);
                this.alert_dop = 0;
                this.$modal.hide('dop-orders');

                location.reload();
            });
            
        },

        getDay(timestamp){
            var seconds = Date.parse(timestamp);
            var date = new Date(seconds);
            var day = date.getDay();
            
            return timestamp.substring(8, 10);
        },

        getData(day, assortment){
            var total = 0;

            for (var i = 0; i <= this.myreports.length - 1; i++) {
                if (this.getDay(this.myreports[i].created_at) == day + 1 && this.myreports[i].assortment_id == assortment) {
                    total += this.myreports[i].order_amount; 
                }
            }

            return total;
        },

        getNaks() {
            console.log('get naks', this.realizators_month, this.realizators_year);

            axios.post('sales/naks', {month: this.realizators_month, year: this.realizators_year}).then(response => {
                console.log(response.data);
                //return;

                this.nakladnoe = response.data;
            });
        }
    }
}
</script>