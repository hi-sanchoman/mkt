<template>
<div class="flex flex-col h-full">
    <div class="panel flex justify-start gap-5 ">
        <button v-if="this.$page.props.auth.user.position_id != 6" :class="kassa ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showKassa()">
          Касса
        </button>
        
        
        <button v-if="this.$page.props.auth.user.position_id != 6" :class="salary ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showSalary()">
            Зарплата
        </button>
        <button :class="dolgi ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showOwes()">
            Долги
        </button>
        <!--<button v-if="this.$page.props.auth.user.position_id != 6" :class="reports ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showReports()">
            Отчеты
        </button>-->
        <button v-if="this.$page.props.auth.user.position_id != 6" :class="vedomost ? 'bg-green-500 text-white font-bold py-2 px-4 rounded':'bg-blue-500 text-white font-bold py-2 px-4 rounded'" @click="showVedomost()">
            Учет расходов
        </button>
    </div>
    <br>
    <br>

    <div v-if="vedomost" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class="flex justify-start gap-5">
                <!--<select-input v-model="rashod_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                    <option v-for="month in months" :value="month.id">{{month.name}}</option>
                </select-input>

                <select-input v-model="rashod_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                    <option v-for="year in years" >{{year}}</option>
                </select-input> -->
                <!--<button :class="priem_moloka ? 'bg-green-500 text-white font-bold py-2 px-4 rounded h-8':'bg-blue-500 text-white font-bold py-2 px-4 rounded h-8'" @click="showRashodReports(1)">Прием молока</button>-->
                
                <button :class="return_expenses ? 'bg-green-500 text-white font-bold py-2 px-4 rounded h-8':'bg-blue-500 text-white font-bold py-2 px-4 rounded h-8'" @click="showRashodReports(2)">расходы с возвратом</button>
                <button :class="overage_expenses ? 'bg-green-500 text-white font-bold py-2 px-4 rounded h-8':'bg-blue-500 text-white font-bold py-2 px-4 rounded h-8'" @click="showRashodReports(3)">общий расход</button>
            </div>
            


            

            <div class="p-5">
                <div class="flex justify-start gap-5">
                    <select-input v-model="rashod_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                        <option v-for="month in months" :value="month.id">{{month.name}}</option>
                    </select-input>

                    <select-input v-model="rashod_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                        <option v-for="year in years" >{{year}}</option>
                    </select-input>
                </div>
                <table v-if="this.overage_expenses == false" class="w-full whitespace-nowrap mt-5 tableizer-table">
                    <tr>
                        <th>
                           Дата 
                        </th>
                        <th>
                           Сумма
                        </th>

                        <th>
                           Категория 
                        </th>

                        <!--<th>
                           сотрудник 
                        </th>-->
                    </tr>
                    <tr v-for="item in mytotalreport" v-if="new Date(item.created_at).getMonth()+1 == rashod_month && new Date(item.created_at).getFullYear() == rashod_year">
                        <td>
                           {{moment(item.created_at).format("DD-MM-YYYY")}}
                        </td>
                        <td>
                           {{item.sum}} 
                        </td>

                        <td>
                           {{categories[item.category_id-1].name}}
                        </td>

                        <!--<td>
                           {{item.user}} 
                        </td>-->
                    </tr>
                </table>

                <table v-if="this.overage_expenses == true" class="w-full whitespace-nowrap mt-5 tableizer-table">
                    <tr>
                        <th>
                           Дата 
                        </th>
                        <th>
                           сумма
                        </th>

                        <th>
                           категория 
                        </th>
                    </tr>
                    <tr v-for="(item, key, index) in total_expenses[rashod_month + '-' + rashod_year]" v-if="item.date == rashod_month + '-' + rashod_year">
                        <td>
                           {{ pad(rashod_month, 2) }}.{{ pad(rashod_year, 4) }}
                        </td>
                        <td>
                           {{ total_expenses[rashod_month + '-' + rashod_year][key]['sum'] }}
                        </td>

                        <td>
                           {{ categories[total_expenses[rashod_month + '-' + rashod_year][key]['name'] - 1].name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                          Итог
                        </td>
                        <td>
                           {{ total_expenses[rashod_month + '-' + rashod_year]['total'] }}
                        </td>

                        <td>
                        </td>
                    </tr>
                </table>
            </div>


            <br><br>
            <a class="bg-blue-500 text-white font-bold py-2 px-4 rounded cursor-pointer" :href="'rashod/'+myindex" >Скачать отчет</a>
    </div>

    <div v-if="salary" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class="flex justify-start gap-5">
            <h3>Зарплата</h3>
            <select-input v-model="salary_month1" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                <option v-for="month in months" :value="month.id">{{month.name}}</option>
            </select-input>

            <select-input v-model="salary_year1" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                <option v-for="year in years" >{{year}}</option>
            </select-input>
        </div>
        <!--<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showSalaryForm()">выдать зарплату</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showAddWorkerForm()">добавить сотрудника</button>-->
        <div class="overflow-y-auto h-80">
            <table class="w-full whitespace-nowrap mt-5 tableizer-table mytable">
                <tr class="text-left  border-b border-gray-200">
                    <th class="sticky top-0">Сотрудник</th>
                    <th class="sticky top-0">Оклад</th>
                    <th class="sticky top-0">Налог</th>
                    <th class="sticky top-0">На руки</th>
                    <th class="sticky top-0">Дни</th>
                    <th class="sticky top-0">К оплате</th>
                    <th class="sticky top-0">Начальное сальдо</th>
                    <th class="sticky top-0" colspan="2">Конечное сальдо</th>
                </tr>
                <tr v-for="sal in mysalary" class="pt-2">
                    <td>{{sal.worker.name}}&nbsp;{{sal.worker.surname}}</td>
                    <td><input type="number" v-model="sal.worker.salary" name="" disabled></td>
                    <td>{{getNalog(sal.worker.salary).toFixed(0)}}</td>

                    <td>{{(sal.worker.salary)-getNalog(sal.worker.salary).toFixed(0)}}</td>

                    <td><input type="number" v-model="sal.days" name="" disabled></td>

                    <td>{{(((sal.worker.salary)-getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0)}}</td>
                    
                    <td><input type="number" v-model="sal.initial_saldo" name="" disabled></td>
                    <td >{{ getPositiveEndSaldo(sal) }}</td>
                    <td >{{ getNegativeEndSaldo(sal) }}</td>
                </tr>
                

                <tr class="text-left pt-5 border-b border-gray-200">
                    <th>Итог</th>
                    <th>{{countOklad()}}</th>
                    <th>{{mysalary.reduce((acc, sal) => acc + parseInt(getNalog(sal.worker.salary).toFixed(0)),0)}}</th>
                    <th>{{mysalary.reduce((acc, sal) => acc + (sal.worker.salary)-parseInt(getNalog(sal.worker.salary).toFixed(0)),0)}}</th>
                    <th></th>
                    <th>{{mysalary.reduce((acc, sal) => acc + parseInt((((sal.worker.salary)-getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0)),0)}}</th>
                    <th>{{countSaldo()}}</th>
                    <th>{{countEndSaldo()}}</th>
                    <th>{{countNegativeSaldo()}}</th>
                    
                </tr>
                <!--
                <tr v-for="sal in mysalary" class="pt-2">
                    <td>{{sal.worker.name}}&nbsp;{{sal.worker.surname}}</td>
                    <td><input type="number" v-model="sal.worker.salary" name="" disabled></td>
                    <td><input type="number" v-model="sal.days" name="" disabled></td>
                    <td><input type="number" v-model="sal.income" name="" disabled></td>
                    <td><input type="number" v-model="sal.OSMS" name="" disabled></td>
                    <td><input type="number" v-model="sal.IPN" name="" disabled></td>
                    <td><input type="number" v-model="sal.OPV" name="" disabled></td>
                    <td><!--<input type="number" v-model="sal.total_income" name="">{{sal.total_income-sal.initial_saldo}}</td>
                    <td><input type="number" v-model="sal.initial_saldo" name="" disabled></td>
                    <td><input type="number" v-model="sal.end_saldo" name="" disabled></td>
                </tr>
                <tr class="text-left pt-5 border-b border-gray-200">
                    <th>Итог</th>
                    <th>{{countOklad()}}</th>
                    <th></th>
                    <th>{{countIncome()}}</th>
                    <th>{{countOSMS()}}</th>
                    <th>{{countIPN()}}</th>
                    <th>{{countOPV()}}</th>
                    <th>{{countTotalIncome()}}</th>
                    <th>{{countSaldo()}}</th>
                    <th>{{countEndSaldo()}}</th>
                </tr>-->

                <tr v-for="(worker,key) in myworkers" class="pt-2">
                    <td>{{worker.name}}&nbsp;{{worker.surname}}</td>
                    <td><input type="number" v-model="worker.salary" name=""></td>
                    <td>{{getNalog(worker.salary).toFixed(0)}}</td>
                    
                    <td>{{(worker.salary).toFixed(0) - getNalog(worker.salary).toFixed(0)}}</td>
                    <td><input type="number" v-model="days[key]" onclick="select()" name=""></td>
                    <td>{{total_income[key] = (((worker.salary).toFixed(0) - getNalog(worker.salary).toFixed(0))/26*days[key]).toFixed(0)}}</td>
                    <!--<td><input type="number" v-model="OSMS[key]" name="">{{(worker.salary/26*days[key]*0.02).toFixed(0)}}</td>
                    <td><input onclick="select()" type="number" v-model="IPN[key]" name=""><div v-if="days[key] > 0">{{(worker.salary/26*days[key]-(42500+worker.salary/26*days[key]*0.02+worker.salary/26*days[key]*0.1)).toFixed(0)}}</div></td>
                    <td><input onclick="select()" type="number" v-model="OPV[key]" name="">{{(worker.salary/26*days[key]*0.1).toFixed(0)}}</td>-->
                    <!--<td><input type="number" v-model="total_income[key]" name=""><div v-if="days[key] > 0">

                        {{((worker.salary/26*days[key])-(worker.salary/26*days[key]*0.02)-(worker.salary/26*days[key]-(42500+worker.salary/26*days[key]*0.02+worker.salary/26*days[key]*0.1)-(worker.salary/26*days[key]*0.1))).toFixed(0)}}</div>-->
                    </td>
                    <td><input onclick="select()" type="number" name="" v-model="worker.saldo" disabled></td>
                    <td colspan="2"><input type="number" v-model="end_saldo[key]" name=""></td>
                </tr>
                
            </table>
        </div>
        <div class="mt-10 w-32 flex justify-start gap-5">
            <download-excel
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center cursor-pointer"
                :data="json_data"
                :fields="json_fields"
                worksheet="My Worksheet"
                name="Зарплата.xls"
            >
                Скачать отчет 
            </download-excel>
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center" @click="saveSalary()">сохранить</button>
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center" @click="endMonth()">завершить месяц</button>
            
        </div>
               
    </div>
    <!--<div v-if="reports" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <h3>Отчеты</h3>
        <br>
        <div class="flex justify-start gap-5">
            <table class="tableizer-table">
                <tr>
                    <th>Остаток на {{new Date().getDay()-1}} {{months[rashod_month].name}}</th>
                    <td><input type="number" v-model="left" name="left"></td>
                    <th>{{parseInt(totalreport.kassa) + parseInt(totalreport.bank_amount) + parseInt(totalreport.freezer) + parseInt(totalreport.store) + parseInt(totalreport.owesrealization) + parseInt(totalreport.workers) + parseInt(totalreport.actives) + parseInt(totalreport.tetrapack) + parseInt(totalreport.fuel)}}</th>
                    <th></th>
                </tr>
                <tr>
                    <td>касса</td>
                    <td><input type="number" name="" v-model="totalreport.kassa"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>Банк счет</td>
                    <td><input type="number" name="" v-model="totalreport.bank_amount"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>морозильник</td>
                    <td><input type="number" name="" v-model="totalreport.freezer"></td>
                    <td><input type="number" name="" v-model="totalreport.salary"></td>
                    <td>зарплата</td>
                </tr>
                <tr>
                    <td>склад</td>
                    <td><input type="number" name="" v-model="totalreport.store"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>долги + реализация</td>
                    <td><input type="number" name="" v-model="totalreport.owesrealization"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>рабочие</td>
                    <td><input type="number" name="" v-model="totalreport.workers"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>конт. ст. активы</td>
                    <td><input type="number" name="" v-model="totalreport.actives"></td>
                    <td colspan="2"></td>
                </tr>
                                <tr>
                    <td>тетрапак</td>
                    <td><input type="number" name="" v-model="totalreport.tetrapack"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>Диз. топливо</td>
                    <td><input type="number" name="" v-model="totalreport.fuel"></td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <table class="tableizer-table h-5 w-1/2">
                <tr>
                    <th>id</th>
                    <th>дата</th>
                    <th>отчет</th>
                </tr>
                <tr v-for="item in totalreports">
                    <td>{{item.id}}</td>
                    <td>{{moment(item.created_at).format("DD-MM-YYYY")}}</td>
                    <td>
                        <div class="flex justify-between gap-5 w-32 pl-5 pr-5"> 
                            <button class="bg-green-500 text-white font-bold py-2 px-4 rounded" @click="updateTotalReport(item)">Редактировать</button>
                            <a class="bg-blue-500 text-white font-bold py-2 px-4 rounded" :href="'/report/'+item.id">Скачать</a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <br>
        <div class="flex justify-start gap-5">
            <div>
                <div class="border-2 p-3">{{parseInt(totalreport.kassa) + parseInt(totalreport.bank_amount) + parseInt(totalreport.freezer) + parseInt(totalreport.store) + parseInt(totalreport.owesrealization) + parseInt(totalreport.workers) + parseInt(totalreport.actives) + parseInt(totalreport.tetrapack) + parseInt(totalreport.fuel) - parseInt(totalreport.salary)}}</div>
                <p>ост на 01.07.2021</p>
            </div>
            <div>
                <button v-if="report_save" class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="saveTotalReport()">сохранить</button>
                <button v-else class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="changeButton()">изменить</button>
                
            </div>
        </div>
    </div>-->

    <div v-if="owes" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class="flex flex-col h-full">


        <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
            <div class="flex justify-start gap-5">
                <h3>Долги</h3>
                <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center h-8" @click="showRealizators()">Реализаторы</button>
                <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center h-8" @click="showMagazines()">Магазины</button>
                <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center h-8" @click="showOther()">Другое</button>

                <select-input v-model="dolgi_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                    <option v-for="month in months" :value="month.id">{{month.name}}</option>
                </select-input>

                <select-input v-model="dolgi_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                    <option v-for="year in years" >{{year}}</option>
                </select-input>
            </div>
            
            
            <table v-if="realizators" class="tableizer-table w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    
                    <th>Реализатор</th>
                    <th>Сумма долга на сегодня</th>      
                    <th>Оплачено</th>         
                </tr>
                <tr v-for="owes in owerealizator" v-if="new Date(owes.created_at).getMonth() >= new Date(dolgi_month)" class="text-left  border-b border-gray-200">
                    <td>{{owes.realizator.first_name}}</td>
                    <td>{{owes.amount}}</td>
                    <td><input type="checkbox" name=""></td>
                </tr>
            </table>


            <table v-if="magazines" class="tableizer-table w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    
                    <th>Магазин</th>
                    <th>Сумма долга на сегодня</th> 
                    <th>Оплачено</th>               
                </tr>
                <tr v-for="owe in myowes1" v-if="new Date(owe.created_at).getMonth() >= new Date(dolgi_month)" class="text-left  border-b border-gray-200">
                    <td>{{owe.magazine.name}}</td>
                    <td>{{owe.remains}}</td>
                    <td><input type="checkbox" name=""></td>
                </tr>
            </table>



            <table v-if="other" class="tableizer-table w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    
                    <th>Сотрудник</th>
                    <th>Сумма долга на сегодня</th>      
                    <th>Оплачено</th>          
                </tr>
                <tr v-for="owe in oweother" v-if="new Date(owe.created_at).getMonth() >= dolgi_month" class="text-left  border-b border-gray-200">
                    <td>{{owe.name}}</td>
                    <td>{{owe.amount}}</td>
                    <td><input type="checkbox" name=""></td>
                </tr>
            </table>
              <div class="mt-10 flex justify-start gap-5">
       
            </div>
        </div>

        </div>
    </div>

    <div v-if="kassa" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
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
        <center><p @click="showAddOstatok()">Начальный остаток: {{parseInt(myostatok) }}</p></center>
        <br>
        <div class="grid grid-cols-2 ">

  

            <div class="border-r-2 mr-5 pr-5">
                <div class="flex justify-start gap-5">
                    <h3>Приход</h3>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showPrihod()">
                      Добавить
                    </button>
                </div>
                <br>
                
                <search-input v-model="income_sotrudnik" class="pr-6 pb-8 w-full lg:w-2/2" />

                <table class="w-full whitespace-nowrap mt-5">
                    <tr v-for="income in myincomes" class="text-left  border-b border-gray-200" v-if="income.user.toLowerCase().includes(income_sotrudnik.toLowerCase()) && new Date(income.created_at) >= new Date(from) && new Date(income.created_at) <= new Date(to).setDate(new Date(to).getDate()+2)" :title="income.description">
                        <td>{{new Date(income.created_at).toISOString().split('T')[0]}}</td>
                        <td>{{income.user}}</td>
                        <td>{{income.sum}}</td>
                       
                    </tr>
                </table>
                <br>
                <div>Итого: {{myincomes.reduce((acc, item) => acc + parseInt(item.sum),0)}}</div>
            </div>
            <div>
                <div class="flex justify-start gap-5">
                    <h3>Расход</h3>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="showRashod()">
                      Расход
                    </button>
                </div>
                <br>
                <search-input v-model="rashod_sotrudnik" class="pr-6 pb-8 w-full lg:w-2/2" /> 
                <table class="w-full whitespace-nowrap mt-5">
                    <tr v-for="expense in myexpenses" class="text-left  border-b border-gray-200" v-if="(expense.user.toLowerCase().includes(rashod_sotrudnik.toLowerCase()) || categories[expense.category_id-1].name.toLowerCase().includes(rashod_sotrudnik.toLowerCase()))  && new Date(expense.created_at) >= new Date(from) && new Date(expense.created_at) <= new Date(to).setDate(new Date(to).getDate()+2)" :title="expense.description">
                        <td>{{new Date(expense.created_at).toISOString().split('T')[0]}}</td>
                        <td>{{expense.sum}}</td>
                         <td>{{categories[expense.category_id-1].name}}</td>
                        <td>{{expense.user}}</td>
                       
                    </tr>
                </table>
                <br>
                <div class="flex justify-start gap-5">
                    <p>Итого: {{myexpenses.reduce((acc, item) => acc + parseInt(item.sum),0)}}</p>
                    
                </div>
            </div>
        </div>
        <br>
        <div class="font-bold rounded text-center w-full">Остаток: {{(myincomes.reduce((acc, item) => acc + parseInt(item.sum),0)+myostatok)-(myexpenses.reduce((acc, item) => acc + parseInt(item.sum),0))}}</div>
    </div>

    <div v-if="dolgi" class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
        <div class="flex justify-start gap-5">
            <h3>Долги</h3>
        
       <!-- <select-input v-model="salary_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                <option v-for="month in months" :value="month.id">{{month.name}}</option>
            </select-input>

            <select-input v-model="salary_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год">
                <option v-for="year in years" >{{year}}</option>
            </select-input>-->
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded h-8" @click="showAddMoney()">Оплачено</button>
             <download-excel
                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center h-8 cursor-pointer"
                          :data="json_data4"
                          :fields="json_fields4"
                          worksheet="My Worksheet"
                          name="долги.xls"
                        >
                          Скачать отчет 
                        </download-excel>
        </div>
        <table class="w-full whitespace-nowrap mt-5">
            <tr class="text-left  border-b border-gray-200">
                <th>Магазин</th>
                <th>Начальный долг</th>
                <th>Продано</th>
                <th>Оплачено</th>
                <th>Остальной долг</th>
                <th>Реализатор</th>               
            </tr>
            <tr v-for="(owe, key) in myowes1" class="text-left  border-b border-gray-200">
                <td style="cursor: pointer" @click="onCompanyClicked(owe, key)">{{owe.name}}</td>
                <td><input type="number" name="" v-model="oweshop[key].dolg_start" @change="changeDolgStart(oweshop[key])"></td>
                <td>{{owe.owe}}</td>
                <td><input type="number" name="" v-model="oweshop[key].paid" disabled></td>
                <td>{{parseInt(oweshop[key].dolg_start) + parseInt(owe.owe) - parseInt(oweshop[key].paid)}}</td>
                <td><div v-for="r in owe.realizator">{{r.first_name}}</div></td>
            </tr>
        </table>
          <div class="mt-10 flex justify-start gap-5">
   
        </div>
    </div>

    <modal name="add-money">
        <div class="p-5">
            <select-input v-model="magazine_dolg" class="pr-6 pb-8 w-full lg:w-1/1" label="Магазин">
                <option v-for="(magazine, key) in myowes1" v-if="oweshop[key] !== undefined">{{oweshop[key].shop}}</option>
            </select-input>
            <text-input  v-model="dolgi_magazin" class="pr-6 pb-8 w-full lg:w-1/1" label="Сумма" />
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="payOwe()">Оплатить долг</button>
        </div>
    </modal>

    <modal name="rashod">
        <form onsubmit="return false;">
        <div class="p-5">
            <select-input v-model="rashod_category" class="pr-6 pb-8 w-full lg:w-1/2" label="Категория">
                <option v-for="category in categories" :value="category.id">{{category.name}}</option>
            </select-input>
            
            <text-input @click="select" v-if="sum_rashod" v-model="rashod_sum" class="pr-6 pb-8 w-full lg:w-1/2" label="Сумма" />
            
            <text-input @click="select" list="workers" v-model="rashod_user" class="pr-6 pb-8 w-full lg:w-1/2" label="Сотрудник" />
            <datalist id="workers">
                <option v-for="user in work_users">{{user.first_name}} {{user.last_name}}</option>
            </datalist>
            <!--<select-input v-model="rashod_user" class="pr-6 pb-8 w-full lg:w-1/2" label="Сотрудник">
                <option v-for="user in users" :value="user.id">{{user.first_name}}</option>
            </select-input>-->
            <text-input @click="select" v-model="rashod_description" class="pr-6 pb-8 w-full lg:w-1/2" label="Описание" /> 
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addExpense()">сохранить</button>
        </div>
        </form>
    </modal>

    <modal name="prihod">
        <form onsubmit="return false;">
        <div class="p-5">
            <text-input @click="select" v-model="income_sum" class="pr-6 pb-8 w-full lg:w-1/2" label="Сумма" />
            <text-input @click="select" v-model="income_user" list="workers" class="pr-6 pb-8 w-full lg:w-1/2" label="Сотрудник" />
            <datalist id="workers">
                <option v-for="user in users">{{user.first_name}} {{user.last_name}}</option>
            </datalist>
            
            <text-input @click="select" v-model="income_description" class="pr-6 pb-8 w-full lg:w-1/2" label="Описание" /> 
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addIncome()">сохранить</button>
        </div>
        </form>
    </modal>

    <modal name="salaryForm">
        <div class="p-5">
            <table class="w-full whitespace-nowrap mt-5">
                <tr>
                    <th>Сотрудник</th>
                    <th>Отработано дней</th>
                    <th>Сальдо</th>
                    <th>Операция</th>
                </tr>
                <tr v-for="(worker, key) in myworkers"> 
                    <td>{{worker.name}}&nbsp;{{worker.surname}}</td>
                    <td><input type="number" v-model="mydays[worker.id-1]" /></td>
                    <td><input type="number" v-model="mysaldo[worker.id-1]" /></td>
                    <td><button @click="giveSalary(worker.id, key)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">выдать зарплату</button></td>
                </tr>
            </table>
        </div>
    </modal>

    <modal name="addWorker">
        <form @submit.prevent="addWorker">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Имя" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Фамилия" />
          <text-input v-model="form.salary" :error="form.errors.salary" class="pr-6 pb-8 w-full lg:w-1/2" label="Оклад" />
      </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Добавить сотрудника</loading-button>
        </div>
      </form>
    </modal>

    <modal name="rashod_reports">
        <div class="p-5">
            <div class="flex justify-start gap-5">
                <select-input v-model="rashod_month" class="pr-6 pb-8 w-full lg:w-1/6" label="Месяц">
                    <option v-for="month in months" :value="month.id">{{month.name}}</option>
                </select-input>

                <select-input v-model="rashod_year" class="pr-6 pb-8 w-full lg:w-1/6" label="Год" >
                    <option v-for="year in years" >{{year}}</option>
                </select-input>
            </div>
            <table class="w-full whitespace-nowrap mt-5 tableizer-table">
                <tr>
                    <th>
                       Дата 
                    </th>
                    <th>
                       сумма d
                    </th>

                    <th>
                       категория 
                    </th>

                    <!--<th>
                       сотрудник 
                    </th>-->
                </tr>
                <tr v-for="item in mytotalreport" v-if="new Date(item.created_at).getMonth()+1 == rashod_month && new Date(item.created_at).getFullYear() == rashod_year">
                    <td>
                       {{moment(item.created_at).format("DD-MM-YYYY")}}
                    </td>
                    <td>
                       {{item.sum}} 
                    </td>

                    <td>
                       {{categories[item.category_id-1].name}}
                    </td>

                    <!--<td>
                       {{item.user}} 
                    </td>-->
                </tr>
            </table>
        </div>
    </modal>
    <modal name="ostatok">
        <div class="p-6">
            <p>Введите остаток</p>
            <br>
            <number-input v-model="ostatok" :error="form.errors.salary" class="pr-6 pb-8 w-full lg:w-1/2" label="Оклад" />
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="addOstatok()">Сохранить</button>
        </div>
    </modal>

    <modal name="company_naks">
        <div class="px-6 py-6">
            История накладных
            
            <div v-for="nak in company_naks">
                <a class='w-full border-3 mt-5 shadow-lg flex p-4' :href="'/blank/' + nak.id"><p>Накладная №{{nak.id}} от {{ moment(nak.created_at).format("DD-MM-YYYY") }}</p></a>

            </div>
        </div>
    </modal>
</div>
</template>

<script>

import Layout from '@/Shared/Layout'
import NumberInput from '@/Shared/NumberInput'
import TextInput from '@/Shared/TextInput'
import SearchInput from '@/Shared/SearchInput'
import SelectInput from '@/Shared/SelectInput'
import axios from 'axios'
import $ from 'jquery'
import Vue from "vue";
import JsonExcel from "vue-json-excel";
import moment from "moment";
import LoadingButton from '@/Shared/LoadingButton'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
 
Vue.component("downloadExcel", JsonExcel);


export default {
    metaInfo: {
        title: 'Зарплата'
    },
    
    layout: Layout,

    props: {
        owes2: Array,
        owes1: Array,
        owerealiztor: Array,
        oweshop1: Array,
        oweother: Array,
        categories: Array,
        incomes: Array,
        expenses: Array,
        total_expenses: Array,
        milk_expenses: Array,
        users: Array,
        zarplata: Array,
        workers: Array,
        days: Array,
        saldo: Array,
        income: Number,
        left: Number,
        totalreports: Array,
        ostatok1: Number
    },
    data(){
        console.log("expenses", this.expenses);
        console.log("total", this.total_expenses);

        return {
            myowes1: this.owes1,
            myostatok: this.ostatok1,
            ostatok: 0,
            priem_moloka:false,
            return_expenses:false,
            overage_expenses:true,
            mytotalreport:this.expenses,
            // total_expenses: this.total_expenses,
            // milk_expenses: this.milk_expenses,
            moment: moment,
            totalreport: 
            {
                kassa: 0,
                bank_amount: 0,
                freezer: 0,
                store: 0,
                owesrealization: 0,
                workers: 0,
                actives: 0,
                tetrapack: 0,
                fuel: 0,
                salary: 0
            },
            oweshop: this.oweshop1,
            magazine_dolg: null,
            dolgi_magazin: 0,
            time: true,
            from: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
            to: new Date(),
            
            salary:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            days1:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            income1:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            OSMS:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            IPN:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            OPV:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            total_income:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            initial_saldo:[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            end_saldo: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],

            years:[2022,2021,2020,2019,2018],
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
            rashod_month:new Date().getMonth()+1,
            rashod_year:new Date().getFullYear(),
            salary_month: new Date().getMonth()+1,
            salary_year: new Date().getFullYear(),
            salary_month1: new Date().getMonth()+1,
            salary_year1: new Date().getFullYear(),
            work_users: this.users,
            //create worker form
            form: this.$inertia.form({
                first_name: null,
                last_name: null,
                salary: null
            }),
            //end worker form
            //excell
            json_fields1: 
            {
                "Реализаторы" : "realizator",
                "Сумма долга на сегодня" : "amount"
            },
            json_data1: this.owerealizator ,

            json_fields2: 
            {
                "Магазин" : "shop",
                "Сумма долга на сегодня" : "amount"
            },
            json_data2: this.oweshop ,


            json_fields3: 
            {
                "Другое" : "name",
                "Сумма долга на сегодня" : "amount"
            },
            json_data3: this.oweother ,
            json_fields4:
            {
                "Магазин":"name",
                "Начальный долг":"dolg_start",
                "Продано":"sold",
                "Оплачено":"paid",
                "Остальной долг":"owe",
                "Реализаторы":"realizator"
            },
            json_data4: this.owes2,

            json_fields: 
            {
                 "Сотрудник": "worker.name",
                 "Оклад": "worker.salary",
                 "Налог" : "nalog",
                 //"Взносы ОСМС": "OSMS",
                 //"ИПН": "IPN",
                 //"ОПВ": "OPV",
                 "На руки": "result_oklad",
                 "Дни": "days",
                 "К оплате":"total_income",
                 "Начальное сальдо":"initial_saldo",
                 "Конечное сальдо":"end_saldo",
                 "Роспись":"rospis"
            },

            json_data: this.export_zarplata,

            //json_data: this.zarplata ,//this.realization_report,


            json_meta: [
                {
                    key : 'charset',
                    value : 'utf-8'
                }
            ],
            //end excel
            myworkers: this.workers,
            mydays: this.days,
            mysaldo: this.saldo,
            mysalary: this.zarplata,
            income_user: null,
            rashod_user: null,
            income_sotrudnik: "",
            rashod_sotrudnik: "",
            income_sum: null,
            income_description: null,
            rashod_sum: null,
            rashod_category: null,
            rashod_description: null,
            myexpenses: this.expenses,
            myincomes: this.incomes,
            kassa: true,
            dolgi: false,
            worker: '',
            worker_salary: 0,
            mysalary: this.zarplata,
            export_zarplate: this.export_zarplate,
            owes: false,
            salary: false,
            reports: false,
            salary: false,
            rashod_index: 4,
            report_save: true,
            vedomost: false,
            sum_rashod: true,
            myindex: 0,
            company_naks: [],
        }
    },
    mounted(){

 
    },
    created() {
        console.log(this.mysalary);
        console.log(this.myostatok);

        if(this.$page.props.auth.user.position_id == 6){
            this.vedomost = false;
            this.kassa = false;
            this.dolgi = true;
            this.reports = false;
            this.salary = false;
        }
    },
    components: {
        NumberInput,
        SelectInput,
        TextInput,
        SearchInput,
        JsonExcel,
        LoadingButton,
        Datepicker
    },
    watch: {
        salary_month1:function(val){
            axios.post('get-salary-month',{month:val,year:this.salary_year1}).then(response => {
                this.mysalary = response.data;
                if(this.salary_month1 - 1 == new Date().getMonth() && this.salary_year1 == new Date().getFullYear()){
                    this.myworkers = this.workers;
                }
                else{
                    this.myworkers = [];
                }
            });
        },
        salary_year1:function(val){
            axios.post('get-salary-month',{month:this.salary_month1,year:val}).then(response => {
                this.mysalary = response.data;
                 if(this.salary_month1 - 1 == new Date().getMonth() && this.salary_year1 == new Date().getFullYear()){
                    this.myworkers = this.workers;
                }
                else{
                    this.myworkers = [];
                }
            });
        },
        salary_month:function(val){
            axios.post('get-owes-month',{month:val,year:this.salary_year}).then(response => {
                this.myowes1 = response.data;
              
            });
        },
        salary_year:function(val){
            axios.post('get-owes-month',{month:this.salary_month,year:val}).then(response => {
                this.myowes1 = response.data;
            });
        },
        rashod_index:function(val){

            if(val == 1){

                
                this.mytotalreport = this.milk_expenses;
                /*this.expenses.forEach(element => {
                    if(element.category_id == 4 && element.kassa == 0){
                        this.mytotalreport.push(element);
                    }
                });*/
            }else if(val == 2){
                
                this.mytotalreport = [];
                this.expenses.forEach(element => {
                    if(element.category_id == 5){
                        this.mytotalreport.push(element);
                    }
                });
            } else if(val == 3){
                
                this.mytotalreport = [];
                this.expenses.forEach(element => {
                    if(element.category_id != 5 && element.category_id != 4)
                        this.mytotalreport.push(element);
                    
                });
            }
        },
        rashod_category:function(val){
            if(val == 4){
                this.sum_rashod = true;
                axios.get('get-work-users').then(response => {
                    this.work_users = response.data;
                });
            }else if(val == 2){
                this.sum_rashod = true;
                axios.get('get-workers').then(response => {
                    this.work_users = response.data;
                });
            }
            else if(val == 1){
                this.sum_rashod = false;
                axios.get('get-workers').then(response => {
                    this.work_users = response.data;
                });
                this.rashod_sum = 0;
            }
            else{
                this.sum_rashod = true;
                this.work_users = this.users;
            }
        }
    },
    computed: {

    },
    methods: {

        
        changeButton(){
            this.report_save = true;
            axios.post('update-totalreport', this.totalreport);
            this.totalreport =
            {
                kassa: null,
                bank_amount: null,
                freezer: null,
                store: null,
                owesrealization: null,
                workers: null,
                actives: null,
                tetrapack: null,
                fuel: null,
                salary: null
            };
            alert('отчет обновлен');
        },
        updateTotalReport(report){
            this.report_save = false;
            this.totalreport = report;
        },
        showRashodReports(index){
            if(index == 1){
                this.priem_moloka = true;
                this.return_expenses = false;
                this.overage_expenses = false;
                this.myindex = 4;
            }
            else if(index == 2){
                this.priem_moloka = false;
                this.return_expenses = true;
                this.overage_expenses = false;
                this.myindex = 5;
            }
            else{
                this.priem_moloka = false;
                this.return_expenses = false;
                this.overage_expenses = true;
                this.myindex = 0;
            }
            
            this.rashod_index = index;    
            
            
            //this.$modal.show('rashod_reports');
        },
        saveTotalReport(){
            if(confirm("Сохранить отчет?")){
                axios.post('save-total-report', this.totalreport);
                alert('отчет сохранен');
            }
        },
        changeDolgStart(shop){
            axios.post('dolg-start',{id: shop.id, amount: shop.dolg_start});
        },
        payOwe(){
            axios.post("pay-owe",{shop: this.magazine_dolg, amount: this.dolgi_magazin});
            this.$modal.hide('add-money');
            alert('сумма добавлена!');
        },
        showAddMoney(){
            this.$modal.show('add-money');
        },
        hozrashod(){
            alert("not ready yet!");
        },
        vozvrat(){
            alert("not ready yet!");
        },
        others(){
            alert("not ready yet!");
        },
        moloko(){
            alert("not ready yet!");
        },

        addWorker(){
            this.form.post(this.route('add-worker'));
            this.$modal.hide('addWorker');
        },
        showAddWorkerForm(){
            this.$modal.show('addWorker');
        },
        countOklad(){
            var oklad = 0;
            this.mysalary.forEach(function(r,a){
                oklad += parseInt(r.worker.salary);
            });
            return oklad;
        },
        countIncome(){
            var income = 0;
            this.mysalary.forEach(function(r,a){
                income += parseInt(r.income);
            });
            return income;
        },
        countOSMS(){
            var osms = 0;
            this.mysalary.forEach(function(r,a){
                osms += parseInt(r.OSMS);
            });
            return osms;
        },
        countIPN(){
            var IPN = 0;
            this.mysalary.forEach(function(r,a){
                IPN += parseInt(r.IPN);
            });
            return IPN;
        },
        countOPV(){
            var OPV = 0;
            this.mysalary.forEach(function(r,a){
                OPV += parseInt(r.OPV);
            });
            return OPV;
        },
        countTotalIncome(){
            var total = 0;
            this.mysalary.forEach(function(r,a){
                total += parseInt(r.total_income);
            });
            return total;
        },
        countSaldo(){
            var saldo = 0;
            this.mysalary.forEach(function(r,a){
                saldo += parseInt(r.initial_saldo);
            });
            return saldo;
        },
        countEndSaldo(){
            var oklad = 0;
            this.mysalary.forEach(function(r,a){
                if(parseInt(r.total_income)-parseInt(r.initial_saldo) >= 0)
                    oklad += parseInt(r.total_income)-parseInt(r.initial_saldo);
            });
            return oklad;
        },
        countNegativeSaldo(){
            var oklad = 0;
            this.mysalary.forEach(function(r,a){
                if(parseInt(r.end_saldo) < 0)
                    oklad += parseInt(r.end_saldo);
            });
            return oklad;  
        },
        giveSalary(id, key){
            axios.post('give-salary',{worker: id, days: this.mydays[id-1], saldo: this.mysaldo[id-1]}).then(response => {
                this.myworkers.splice(key,1);
            });
        },
        showSalaryForm(){
            this.$modal.show('salaryForm');
        },
        showSalary(){
            this.kassa = false;
            this.dolgi = false;
            this.reports = false;
            this.salary = true;
            this.vedomost = false;
        },
        showReports(){
            this.kassa = false;
            this.dolgi = false;
            this.reports = true;
            this.salary = false;
            this.vedomost = false;
        },
        showKassa(){
            this.kassa = true;
            this.dolgi = false;
            this.reports = false;
            this.salary = false;
            this.vedomost = false;
        },
        showOwes(){
            this.vedomost = false;
            this.kassa = false;
            this.dolgi = true;
            this.reports = false;
            this.salary = false;
        },
        getIncomesTotal(){
            var total = 0;
            for (let i = 0; i < this.incomes.length; i++) {
                total += parseInt(this.incomes[i].sum);
            }
            return total;
        },
        getExpensesTotal(){
            var total = 0;
            for (let i = 0; i < this.expenses.length; i++) {
                total += parseInt(this.expenses[i].sum);
            }
            return total;
        },
        showRashod(){
            this.rashod_sum = null;
            this.rashod_category = null;
            this.rashod_user = null;

            this.$modal.show('rashod');
        },
        showPrihod(){
            this.income_sum = null;
            this.income_user = null;
            this.income_description = null;
            this.$modal.show('prihod');
        },
        addIncome(){
            axios.post('send-income',{
                sum: this.income_sum,
                description: this.income_description,
                user: this.income_user
            }).then(response => {
                this.myincomes.unshift(response.data);
                if(response.data.error){
                        alert(response.data.error);
                }else{
                    alert("приход добавлен");
                    location.reload();
                }
            });
            this.$modal.hide('prihod');
        },
        addExpense(){
            if(confirm("Сохранить расход?")){
                axios.post('send-expense',{
                    sum: this.rashod_sum,
                    category: this.rashod_category,
                    description: this.rashod_description,
                    user: this.rashod_user
                }).then(response => {
                        if(response.data.error){
                            alert(response.data.error);
                        }else{
                            this.mysalary = response.data.zarplata;
                            this.myworkers = response.data.workers;
                            this.myexpenses.unshift(response.data.expense);

                            alert("расход добавлен");

                            location.reload();
                        }
                });
                this.$modal.hide('rashod');
            }

        },
        downloadReport(report){

        },
        saveSalary(){
            if(confirm("Сохранить зарплату?")){
                axios.post('save-salary',{
                workers:this.myworkers,
                salary: this.salary,
                days: this.days,
                total_incomes: this.total_income
    /*            income: this.income1,
                OSMS: this.OSMS,
                IPN: this.IPN,
                OPV: this.OPV,
                total_income: this.total_income,
                end_saldo:  this.end_saldo*/
                }).then(response => {
                    console.log(response.data);

                    this.mysalary = response.data.zarplata;
                    this.myworkers = response.data.workers;
                    alert(response.data.message);
                });
            }
        },
        endMonth(){
            if(confirm("Вы точно хотите завершить месяц?")){   
                axios.get('end-month').then(response => {
                    alert(response.data);
                    location.reload();
                });
            }
        },
        showAddOstatok(){
            this.$modal.show('ostatok');
        },
        addOstatok(){
            this.$modal.hide('ostatok');
            axios.post('add-ostatok',{ostatok: this.ostatok}).then(response => {
                alert(response.data.message);
                this.myostatok = response.data.ostatok;
            })
        },
        getOSMS(salary){
            return salary*0.02;
        },
        getOPV(salary){
            return salary*0.1;
        },
        getIPN(salary){
            let OSMS = this.getOSMS(salary);
            let OPV = this.getOPV(salary);
            return salary-OSMS-OPV;
        },
        getNalog(salary){
            var osms = salary * 0.02;
            var opv = salary * 0.1;
            var ipn = (salary - 42882 - opv - osms) * 0.1; 

            return osms + opv + ipn;

            //return (salary/50) + (salary - 42500 - (salary/10) - (salary/50))*0.1 + (salary/10);
        },
        addTotalIncome(key,income){
            this.total_income[key] = income;
        },
        getOwesByMonth(){
            alert(this.salary_month+' '+this.salary_year);
        },

        pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        },

        getPositiveEndSaldo(sal) {
            var saldo = (((sal.worker.salary)-this.getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0) - sal.initial_saldo;

            if (saldo >= 0) return saldo;

            return 0;
        },


        getNegativeEndSaldo(sal) {
            var saldo = (((sal.worker.salary)-this.getNalog(sal.worker.salary).toFixed(0))/26*sal.days).toFixed(0) - sal.initial_saldo;

            if (saldo < 0) return saldo;

            return 0;
        },

        onCompanyClicked(owe, key) {
            console.log("clicked", owe, key);

            axios.get('get-company-naks', {company: owe.name}).then(response => {
                this.company_naks = response.data;

                this.$modal.show('company_naks');
            });
        },

        showVedomost(){
            this.vedomost = true;
            this.kassa = false;
            this.dolgi = false;
            this.reports = false;
            this.salary = false;

            showRashodReports(3);
        },
    }
}
</script>