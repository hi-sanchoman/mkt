<template>
<div class="flex flex-col h-full">

    <!-- Кнопки в первом ряду -->
    <div class="panel flex justify-start gap-5 mb-4">
        <button v-if="userIsNot([ACCOUNTANT])"
            @click="currentTab = 1"
            class="text-white font-bold py-2 px-4 rounded"
            :class="currentTab == 1 ? 'bg-green-500' : 'bg-blue-500'">
            Касса
        </button>

        <button v-if="userIsNot([ACCOUNTANT])"
            @click="currentTab = 2"
            class="text-white font-bold py-2 px-4 rounded"
            :class="currentTab == 2 ? 'bg-green-500' : 'bg-blue-500'">
            Зарплата
        </button>

        <button
            @click="currentTab = 3"
            class="text-white font-bold py-2 px-4 rounded"
            :class="currentTab == 3 ? 'bg-green-500' : 'bg-blue-500'">
            Долги клиентов
        </button>

        <button
            @click="currentTab = 4"
            class="text-white font-bold py-2 px-4 rounded"
            :class="currentTab == 4 ? 'bg-green-500' : 'bg-blue-500'">
            Долги (физ)
        </button>
    </div>

    <content-kassa v-if="currentTab == 1"
        :myincomes="incomes"
        :myexpenses="expenses"
        :categories="categories"
        :users="users"
        :myostatok="ostatok1"
        :dbOtherDebts="db_other_debts"
        :realizators="realizators"
        :pivotPrices="pivotPrices"
    ></content-kassa>

    <content-salary v-if="currentTab == 2"
        :workers="workers"
        :days="days"
        :dbMonth="month1"
    ></content-salary>

    <content-client-debts v-if="currentTab == 3"
    ></content-client-debts>

    <content-phys-debts v-if="currentTab == 4"
        :other_debts="db_other_debts"
    ></content-phys-debts>

</div>
</template>

<script>
import Layout from '@/Shared/Layout'
import axios from 'axios'
import LoadingButton from '@/Shared/LoadingButton'
import ContentSalary from '@/Pages/Profit/ContentSalary'
import ContentKassa from '@/Pages/Profit/ContentKassa'
import ContentClientDebts from '@/Pages/Profit/ContentClientDebts'
import ContentPhysDebts from '@/Pages/Profit/ContentPhysDebts'

export default {
    metaInfo: {
        title: 'Зарплата / Расходы / Долги'
    },
    layout: Layout,
    components: {
        LoadingButton,
        ContentKassa,
        ContentSalary,
        ContentClientDebts,
        ContentPhysDebts
    },
    props: {
        categories: Array,
        incomes: Array,
        expenses: Array,
        users: Array,
        workers: Array,
        days: Array,
        ostatok1: Number,
        month1: Object,
        db_other_debts: Array,
        realizators: Array,
        pivotPrices: Array,
    },
    data() {
        return {
            currentTab: 1,
            clientDebtsFetched: false
        }
    },
    created() {
        if(this.userIs([this.ACCOUNTANT])){
            this.currentTab = 3
        }
    },
}
</script>
