<template>
<div class="flex flex-col h-full">
    <div class="panel flex justify-start gap-4">
        <button class="text-white font-bold py-2 px-4 rounded" v-bind:class="{'bg-green-500' : store, 'bg-blue-500' : !store}"  @click="openStore()">
          Склад
        </button>
        <button class=" text-white font-bold py-2 px-4 rounded" v-bind:class="{'bg-green-500' : weight_store, 'bg-blue-500' : !weight_store}" @click="openWeightStore()">
          Весовой склад
        </button>
        <button class=" text-white font-bold py-2 px-4 rounded" v-bind:class="{'bg-green-500' : freezer1, 'bg-blue-500' : !freezer1}" @click="openFreezer()">
          Морозильник
        </button>
 
        <button v-if="$page.props.auth.user.position_id != 2" class=" text-white font-bold py-2 px-4 rounded" v-bind:class="{'bg-green-500' : tara, 'bg-blue-500' : !tara}" @click="openTara()">
          Тара/Этикетки
        </button>

    </div>
    <br>

    <div v-if="tara">
         <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
            <!-- <button v-if="$page.props.auth.user.position_id != 7" class="group-hover:text-blue font-normal bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="showAddProduct()">Новый продукт</button> -->
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-left">#</p>
                </th>

                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold">Наименование</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold">Количество</p>
                </th>
                <!-- <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">шт. в упак.</p>
                </th>

                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Общ. кол.</p>
                </th> -->

                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Цена</p>
                </th>


                 <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Сумма</p>
                </th>

                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Управление</p> 
                </th>

               <!--<td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">Требуется</p>
               </td> -->
            </tr>

            <tr v-for="(item, i) in mytara" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 text-left">
                    <p class="text-sm">{{ i + 1 }}</p>
                </td>

                <td class="px-6 pt-3 pb-3 text-left">
                    <p class="text-sm">{{item.name}}</p>
                </td>  
                <td  class="px-6 pt-3 pb-3 ">
                    <div  @click="showTaraAmount(item.id)">
                        <input type="number" name="" :id="item.id" :ref="item.id" :disabled="enabledTaraAmount(item.id)" v-model="mytara[i].amount" @change="addTaraAmount(item.id,item.amount)">
                    </div>
               </td>      
               <!-- <td class="px-6 pt-3 pb-3">
                    <div  @click="showTaraInside(item.id)">
                        <input type="number" name="" :id="item.id" :ref="item.id" :disabled="enabledTaraInside(item.id)" v-model="mytara[i].inside" @change="addTaraInside(item.id,item.inside)">
                    </div>
               </td>                
               <td class="px-6 pt-3 pb-3">
                    <p class="text-sm">{{ mytara[i].inside * mytara[i].amount }}</p>
               </td>  -->
               <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <div  @click="showTaraPrice(item.id)">
                        <input type="number" name="" :id="item.id" :ref="item.id" :disabled="enabledTaraPrice(item.id)" v-model="mytara[i].price" @change="addTaraPrice(item.id,item.price)">
                    </div>
               </td> 
              

               <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{ formatNum(((item.amount * item.price).toFixed(2))) }}</p>
               </td> 


               <!--<td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{getAmount(item.store)}}</p>
               </td>--> 

               <!-- <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="editItem(item, 'tara')">Редактировать</button>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="deleteItem(item, 'tara')">Удалить</button>
                </td> -->
            </tr>

            <tr v-if="$page.props.auth.user.position_id == 1" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8 text-left">Итог:</td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8">{{ getTaraSum() }}</td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>

            </tr>
        </table>
        </div>
    </div>

    <div v-if="data">
        <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
        <button class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
          день
        </button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          месяц
        </button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          год
        </button>
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">приход</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">расход</p>
                </th>
                 <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">списание</p>
                </th>
            </tr>

            <tr  class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8">
                    
                    <p class="text-sm">0</p>
                  
               </td>  
               <td  class="px-6 pt-3 pb-3 w-auto flex justify-center">
                    <p class="text-sm">0</p>
               </td>      
               <td class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">0</p>
               </td> 
               
              
            </tr>
        </table>
        </div>
    </div>

   <div v-if="store" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
        <!-- <button v-if="$page.props.auth.user.position_id != 7" class="hover:text-blue font-normal bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="showAddProduct()">Новый продукт</button> -->
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-left">#</p>
                </th>
                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Вид товара</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold">Количество</p>
                </th>

                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold">Цена (завод)</p>
                </th>
                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Сумма
                    </p> 
                </th>
                
                <!-- <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Цена (реализ.)</p>
                </th> -->

                <th v-for="percent in percents" v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Цена {{ percent.amount }}%</p>
                </th>

                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Управление</p> 
                </th>
            </tr>

            <tr v-for="(item, i) in mygoods" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="item.id">
                <td class="px-6 pt-3 pb-3 text-left">
                    {{ i + 1 }}
                </td>
                <td class="px-6 pt-3 pb-3 w-8 " v-on:click="history(supply.supplier)">
                    <div class="flex text-left">
                        <p class="text-sm">{{item.type}}</p>
                    </div>
                </td>  
                <td  class="px-6 pt-3 pb-3 w-auto" >
                    <div  @click="showInput(item.id)">
                        <template v-if="$page.props.auth.user.position_id == 1">
                            <!--             :disabled="enabled(item.id)" -->
                            <input 
                                type="number"
                                name=""
                                :id="item.id"
                                :ref="item.id"
                                v-model="mygoods[i].amount"
                                @change="addStore(item.id,item.amount, i)" />
                        </template>
                        <!-- <input type="number" name="" :id="item.id" :ref="item.id" v-model="mygoods[i].amount" @change="addStore(item.id,item.amount)"> -->
                        <span v-else>{{ mygoods[i].amount.toFixed(2) }}</span>
                    </div>
                </td>      
                <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <div  @click="showPriceInput(item.id)">
                        <input type="number" name="" :id="item.id" :ref="item.id" :disabled="enabledprice(item.id)" v-model="mygoods[i].price_zavod" @change="addPriceZavod(item.id, item.price_zavod, i)">
                    </div>
                </td> 
                <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{ formatNum((mygoods[i].price_zavod * mygoods[i].amount).toFixed(2)) }}</p>
                </td> 

                <!-- <div v-if="$page.props.auth.user.position_id == 1" @click="showPriceInput(item.id)">
                    <input type="number" name="" :id="item.id" :ref="item.id" :disabled="enabledprice(item.id)" v-model="mygoods[i].price" @change="addPrice(item.id, item.price, i)">
                </div> -->

                <td v-for="percent in percents" v-if="$page.props.auth.user.position_id == 1" @click="showPriceInput(item.id)">
                    
                    <input type="number" name="" :id="item.id" :ref="item.id" :disabled="enabledprice(item.id)" v-model="mygoods[i].percents[percent.id].price" @change="addPivotPrice(item.id, percent.id, getPivotPrice(item.id, percent.id), i)">
                </td>
               
                <!-- <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="editItem(mygoods[i], 'store')">Редактировать</button>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="deleteItem(mygoods[i], 'store')">Удалить</button>
                </td> -->
            </tr>

            <tr v-if="$page.props.auth.user.position_id == 1" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8 text-left">Итог:</td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8">{{ getSum() }}</td>

            </tr>
        </table>
    </div>

    <div v-if="weight_store" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
        <div class="flex gap-5">
            <inertia-link v-if="$page.props.auth.user.position_id != 7" class="flex items-center group py-3" :href="route('sklad')">    
                <div class="group-hover:text-blue font-normal bg-blue-500 text-white font-bold py-2 px-4 rounded" >Управление складом</div>
            </inertia-link>
            <!-- <button v-if="$page.props.auth.user.position_id != 7" class="group-hover:text-blue font-normal bg-blue-500 text-white font-bold py-2 px-4 my-3 rounded" @click="showAddProduct()">Новый продукт</button> -->
        </div>
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-left">#</p>
                </th>
                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Вид товара</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold">Количество</p>
                </th>
                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold">Цена</p>
                </th>
                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Сумма</p> 
                </th>
                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Управление</p> 
                </th>
            </tr>

            <tr v-for="(item,i) in myweight" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="item.id">
                <td class="px-6 pt-3 pb-3 w-8 text-left">
                    <p class="text-sm">{{ i + 1}}</p>
               </td> 
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex text-left">
                        <p class="text-sm">{{item.assortment}}</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <template v-if="$page.props.auth.user.position_id == 1">
                        <input type="number" name="" v-model="item.amount" @change="setAmountWeightStore(item.id,item.amount)">
                    </template>
                    <template v-else>
                        <p class="text-sm">{{ item.amount.toFixed(2) }}</p>
                    </template>
               </td>      
               <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <div  @click="showWeightInput(item.id)">
                        <input type="number" name="" :id="item.id" :ref="item.id" :disabled="enabledweight(item.id)" v-model="myweight[i].price" @change="addWeightPrice(item.id,item.price,i)">
                    </div>
               </td> 
               <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{ formatNum((item.amount * item.price).toFixed(2)) }}</p>
               </td> 
               

                <!-- <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="editItem(item, 'weight')">Редактировать</button>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="deleteItem(item, 'weight')">Удалить</button>
                </td> -->
              
            </tr>

            <tr v-if="$page.props.auth.user.position_id == 1" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8 text-left">Итог:</td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8">{{ getWeightSum() }}</td>
            </tr>

        </table>
    </div>

    <div v-if="freezer1" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto pt-2">
        <div class="flex gap-5">
            <inertia-link v-if="$page.props.auth.user.position_id != 7" class="flex items-center group py-3" :href="route('sklad.freezer')">    
                <div class="group-hover:text-blue font-normal bg-blue-500 text-white font-bold py-2 px-4 rounded" >Управление складом</div>
            </inertia-link>
            <!-- <button v-if="$page.props.auth.user.position_id != 7" class="group-hover:text-blue font-normal bg-blue-500 text-white font-bold py-2 px-4 my-3 rounded" @click="showAddProduct()">Новый продукт</button> -->
        </div>
        <table class="w-full whitespace-nowrap  ">
            <tr class="text-left font-bold border-b border-gray-200">

                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold text-left">#</p>
                </th>
                <th class="px-6 pt-4 pb-4 flex">
                    <p class="font-bold text-center">Вид товара</p>
                </th>
                <th class="px-6 pt-4 pb-4">
                    <p class="font-bold">Количество</p>
                </th>
                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Цена</p>
                </th>
                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">
                        Сумма
                    </p> 
                </th>
                <th v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-4 pb-4">
                    <p class="font-bold text-center">Управление</p> 
                </th>
            </tr>

            <tr v-for="(item, index) in myfreezer" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" :key="item.id">
                <td class="px-6 pt-3 pb-3 w-8 text-left">
                    <p class="text-sm">{{ index + 1 }}</p>
               </td>
                <td class="px-6 pt-3 pb-3 w-8">
                    <div class="flex text-left">
                        <p class="text-sm">{{item.assortment}}</p>
                    </div>
               </td>  
               <td class="px-6 pt-3 pb-3 w-8">
                    <template v-if="$page.props.auth.user.position_id == 1">
                        <input type="number" name="" v-model="item.amount" @change="setAmount(item.id,item.amount)">
                    </template>
                    <template v-else>
                        {{ item.amount.toFixed(2) }}
                    </template>
               </td>      
               <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <input type="number" name="" v-model="item.price" @change="setPrice(item.id,item.price)">
               </td> 
               <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <p class="text-sm">{{ formatNum(((item.amount * item.price).toFixed(2))) }}</p>
               </td>

                <!-- <td v-if="$page.props.auth.user.position_id == 1" class="px-6 pt-3 pb-3 w-8">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="editItem(item, 'freezer')">Редактировать</button>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="deleteItem(item, 'freezer')">Удалить</button>
                </td>  -->
            </tr>

            <tr v-if="$page.props.auth.user.position_id == 1" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
                <td class="px-6 pt-3 pb-3 w-8 text-left">Итог:</td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8"></td>
                <td class="px-6 pt-3 pb-3 w-8">{{ getFreezerSum() }}</td>
            </tr>
               
        </table>
    </div>

    <modal name="prihod" class="w-auto">
        <div class="p-5 justify-start gap-4">
            <table>
                <tr class="text-left font-bold border-b border-gray-200">
                    <th class="px-6 pt-3 pb-3 w-8">Наименование</th>
                    <th class="px-6 pt-3 pb-3 w-8">Количество</th>
                </tr>
                <tr>
                    <td class="px-6 pt-3 pb-3 w-8">
                        <select>
                            <option v-for="(item, i) in mygoods">
                                {{item.type}}
                            </option>
                        </select>
                    </td>
                    <td class="px-6 pt-3 pb-3 w-8">
                        
                        <input type="number" name="" v-model="tara_amount" placeholder="Количество">
                    </td>
                </tr>
            </table>
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="addTara()">
                сохранить
            </button>
           
        </div>
    </modal>

    <modal name="addProduct" class="w-auto">
        <div class="p-5 justify-start gap-4">
            <table>
                <tr class="text-left font-bold border-b border-gray-200">
                    <th class="px-6 pt-3 pb-3 w-10">Склад</th>
                    <th class="px-6 pt-3 pb-3 w-10">Наименование</th>
                </tr>
                <tr>
                    <td class="px-6 pt-3 pb-3 w-10">
                        <select v-model="new_product_sklad">
                            <option>Продукция</option>
                            <option>Весовой склад</option>
                            <option>Морозильник</option>
                            <option>Тара</option>
                        </select>
                    </td>
                    <td class="px-6 pt-3 pb-3 w-10">
                        <input type="text" name="" v-model="new_product_name" placeholder="имя">
                    </td>
                    <td v-if="new_product_sklad == 'Морозильник'" class="px-6 pt-3 pb-3 w-15">
                        <input  type="checkbox" v-model="is_zakvaska"> Закваска?
                    </td>
                </tr>
            </table>
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="addNewProduct()">
                Добавить
            </button>
           
        </div>
    </modal>

    <modal name="editProduct" class="w-auto">
        <div class="p-5 justify-start gap-4">
            <table>
                <tr class="text-left font-bold border-b border-gray-200">
                    <th class="px-6 pt-3 pb-3 w-10">Склад</th>
                    <th class="px-6 pt-3 pb-3 w-10">Наименование</th>
                </tr>
                <tr>
                    <td class="px-6 pt-3 pb-3 w-10">
                        <select v-model="new_product_sklad">
                            <option>Продукция</option>
                            <option>Весовой склад</option>
                            <option>Морозильник</option>
                            <option>Тара</option>
                        </select>
                    </td>
                    <td class="px-6 pt-3 pb-3 w-10">
                        <input type="text" name="" v-model="new_product_name" placeholder="имя">
                    </td>
                    <td v-if="new_product_sklad == 'Морозильник'" class="px-6 pt-3 pb-3 w-15">
                        <input  type="checkbox" v-model="is_zakvaska"> Закваска?
                    </td>
                </tr>
            </table>

            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="updateItem()">
                Сохранить
            </button>
           
        </div>
    </modal>

</div>
</template>

<script>

import Layout from '@/Shared/Layout'
import axios from 'axios'
import $ from 'jquery'

export default {
    metaInfo: {
        title: 'Storage'
    },
    
    layout: Layout,
    data(){
        return {
            new_product_name: null,
            new_product_sklad: null,
            is_zakvaska: 0,
            myfreezer: this.freezer,
            tara_amount: 0,
            clickedTaraPrice: false,
            clickedTaraInside: false,
            clickedTaraAmount: false,
            tara: false,
            clickedweight: null,
            clickedprice : null,
            data: false,
            mygoods : this.goods,
            myweight : this.weightgoods,
            mytara: this.tara1,
            clicked: null,
            store: true,
            weight_store: false,
            freezer1: false,
            currentItemId: null,
            currentSklad: null,
        }
    },
    props: {
        pivotPrices: Array,
        percents: Array,
        goods: Array,
        weightgoods: Array,
        freezer: Array,
        tara1: Array
    },
    mounted(){

 
    },
    created() {

       // console.log(this.mytara[0].store[0].amount);
    },
    components: {
      
    },
    watch: {

    },
    computed: {

    },
    methods: {
        addTara(){
            axios.post('add-tara',{amount: this.tara_amount});
        },
        openPrihod(){
            this.$modal.show('prihod');
        },
        getAmount(array){
            console.log(array);
            var sum = 0;
            for(var i=0;i<array.length;i++){
                sum += array[i].amount;
            }
            return sum;
        },
        addTaraAmount(id,amount){
            this.clickedTaraAmount = 0;
            axios.post('store/addtaraamount',{
                id : id,
                amount : amount
            }).then(response => {
                this.mytara[id-1].amount = response.data.amount;
                this.mytara[id-1].total = response.data.total;
                this.mytara[id-1].sum = response.data.sum;
            });
        },
        addTaraInside(id,inside){
            this.clickedTaraInside = 0;
            axios.post('store/addtarainside',{
                id : id,
                inside : inside
            }).then(response => {
                this.mytara[id-1].inside = response.data.inside;
                this.mytara[id-1].total = response.data.total;
                this.mytara[id-1].sum = response.data.sum;
            });
        },
        addTaraPrice(id,price){
            this.clickedTaraPrice = 0;
            axios.post('store/addtaraprice',{
                id : id,
                price : price
            }).then(response => {
                this.mytara[id-1].price = response.data.price;
                this.mytara[id-1].sum = response.data.sum;
               // this.mytara[id-1].total = response.data.total;
            });
        },
        showTaraAmount(id){
            this.clickedTaraAmount = id;
        },
        showTaraInside(id){
            this.clickedTaraInside = id;
        },
        showTaraPrice(id){
            this.clickedTaraPrice = id;
        },
        enabledTaraAmount(id){
            if (this.$page.props.auth.user.position_id == 7) return true;

            if(this.clickedTaraAmount == id)
                return false;
            else
                return true;
        },
        enabledTaraInside(id){
            if(this.clickedTaraInside == id)
                return false;
            else
                return true;
        },
        enabledTaraPrice(id){
            if(this.clickedTaraPrice == id)
                return false;
            else
                return true;
        },
        addWeightPriceZavod(id,price,i){
            this.clickedweight = 0;
            axios.post('store/addweightzavod',{
                assortment : id,
                price_zavod : price
            }).then(response => {
                this.myweight[i].sum = response.data.sum;
            });
        },

        setAmountWeightStore(id, amount) {
            // axios

            axios.post('weightstore/update-amount',{
                id : id,
                amount : amount
            }).then(response => {
                alert('Обновлено!');
            });
        },

        addWeightPrice(id,price,i){
            this.clickedweight = 0;
            axios.post('store/addweight',{
                assortment : id,
                price : price
            }).then(response => {
                // this.myweight[i].sum = response.data.sum;
            });
        },
        showWeightInput(id){
            this.clickedweight = id;
        },
        enabledweight(id){
            if(this.clickedweight == id)
                return false;
            else
                return true;
        },
        getTaraSum(){
            console.log(this.mytara);
            var sum = 0;
            for(var i = 0; i < this.mytara.length; i++){
                sum = sum + this.mytara[i].amount * this.mytara[i].price;
            }
            return this.formatNum(sum.toFixed(2));
        },
        getFreezerSum() {
            var sum = 0;
            for(var i = 0; i < this.freezer.length; i++){
                sum = sum + this.freezer[i].amount * this.freezer[i].price;
            }
            return this.formatNum(sum.toFixed(2));
        },
        getWeightSum(){
            var sum = 0;
            for(var i = 0; i < this.myweight.length; i++){
                sum = sum + this.myweight[i].amount * this.myweight[i].price;
            }
            return this.formatNum(sum.toFixed(2));
        },
        getSum(){
            var sum = 0;
            for(var i = 0; i < this.mygoods.length; i++){
                //console.log(this.mygoods[i]);
                sum = sum + this.mygoods[i].amount * this.mygoods[i].price_zavod;
            }
            return this.formatNum(sum.toFixed(2));
        },

        formatNum(num, type) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        },

        addPriceZavod(id, price){
            this.clickedprice = 0;
            axios.post('store/addpricezavod',{
                assortment : id,
                price_zavod : price
            }).then(response => {
                this.mygoods[id-1].sum = response.data.sum;
            });
        },

        addPrice(id,price){
            this.clickedprice = 0;
            axios.post('store/addprice',{
                assortment : id,
                price : price
            }).then(response => {
                //this.mygoods[id-1].sum = response.data.sum;
            });
        },

        addPivotPrice(itemId, percentId, price, i) {
            this.clickedprice = 0;

            console.log(itemId, percentId, this.mygoods[i].percents[percentId].price, i);

            axios.post('store/addpivotprice', {
                assortment: itemId, 
                percent: percentId,
                price: this.mygoods[i].percents[percentId].price
            }).then(response => {
                
            });
        },

        enabledprice(id){
            if(this.clickedprice == id)
                return false;
            else
                return true;
        },
        // showPriceZavodInput(id) {
        //     this.clickedprice = id;
        // },
        showPriceInput(id){
            this.clickedprice = id;
        },
        openData(){
            this.tara = false,
            this.data = true,
            this.store = false,
            this.weight_store = false,
            this.freezer1 = false
        },
        addStore(id,amount, i){
            
            this.clicked = 0;
            axios.post('store/add',{
                assortment : id,
                amount : amount
            }).then(response => {
                alert('Обновлено!');
                //this.mygoods[id-1].sum = response.data.sum;
            });

        },
        enabled(id){
            if(this.clicked == id)
                return false;
            else
                return true;
       },
       showInput(id){
        
            this.clicked = id;
        
       },
       openStore(){
            this.tara = false;
            this.data = false,
            this.store = true,
            this.weight_store = false,
            this.freezer1 = false
       },
       openWeightStore(){
            this.tara = false;
            this.data = false,
            this.store = false,
            this.weight_store = true,
            this.freezer1 = false
       },
       openFreezer(){
            this.tara = false;
            this.data = false,
            this.store = false,
            this.weight_store = false,
            this.freezer1 = true
       },
       openTara(){
            this.tara = true;
            this.data = false,
            this.store = false,
            this.weight_store = false,
            this.freezer1 = false;
       },
       setPrice(id,price){
            axios.post('set-freezer-price',{id:id,price:price});
       },
       setAmount(id,amount){
            axios.post('set-freezer-amount',{id:id,amount:amount})
            .then(() => { alert('Обновлено!')});
       },
        showAddProduct(){
            this.$modal.show('addProduct');
        },

        resetFormFields() {
            this.new_product_sklad = null;
            this.new_product_name = null;
            this.is_zakvaska = null;
        },

        addNewProduct(){
            if (this.new_product_name == null || this.new_product_sklad == null){
                alert('Заполните необходимые поля!');
            } else {
                axios.post('add-new-product',{
                    sklad: this.new_product_sklad,
                    product: this.new_product_name,
                    is_zakvaska: this.is_zakvaska,
                }).then(response => {
                    if (this.new_product_sklad == 'Продукция'){
                       this.mygoods = response.data.list;
                    }
                    else if(this.new_product_sklad == 'Морозильник'){
                        this.myfreezer = response.data.list;
                    }
                    else if(this.new_product_sklad == 'Весовой склад'){
                        this.myweight = response.data.list;
                    }else{
                        this.mytara = response.data.list;
                    }

                    alert(response.data.message);

                    this.resetFormFields();

                    this.$modal.hide('addProduct');
                });
            }
        },

        editItem(item, sklad) {
            this.new_product_name = item.name;

            if (sklad == 'store') { 
                this.new_product_sklad = 'Продукция';
                this.new_product_name = item.type;
            }
            else if (sklad == 'weight') {
                this.new_product_sklad = 'Весовой склад';
                this.new_product_name = item.assortment;
            }
            else if (sklad == 'freezer') {
                this.new_product_sklad = 'Морозильник';
                this.is_zakvaska = sklad = 'freezer' ? item.is_zakvaska : null;
                this.new_product_name = item.assortment;
            } else if (sklad == 'tara') {
                this.new_product_sklad = 'Тара';
            }
            
            this.currentSklad = sklad;
            this.currentItemId = item.id;

            this.$modal.show('editProduct');
        },

        updateItem() {
            this.err = '';

            if (this.new_product_sklad === null) {
                this.err = 'Выберите тип склада!'
                return null;
            }

            if (this.new_product_name === null) {
                this.err = 'Заполните название продукта!'
                return null;
            }

            axios.put('store/' + this.currentItemId, { id: this.currentItemId, form: {
                'sklad': this.currentSklad,
                'name': this.new_product_name,
                'is_zakvaska': this.is_zakvaska,
            } }).then((response) => {
                // console.log(response);

                this.$modal.hide('editProduct');
                
                this.resetFormFields();

                alert('Изменения сохранены');

                location.reload();
            });
        },

        deleteItem(item, sklad) {
            var conf = confirm('Вы действительно хотите удалить товар?');
            if (conf === false) return;


            axios.post('store/delete', { item: item, sklad: sklad }).then((response) => {
                if (response.data == 0) {
                    alert('Произошла ошибка. Попробуйте позже');
                    return;
                }
                
                if (response.data == 1) {
                    alert('Товар удален');
                    location.reload();
                }
            });

        },

        getPivotPrice(itemId, percentId) {
            for (var pivot in this.pivotPrices) {
                if (pivot.percent_id == percentId && pivot.store_id == itemId) {
                    return pivot.price;
                }
            }

            return 0;
        }
    }
}
</script>