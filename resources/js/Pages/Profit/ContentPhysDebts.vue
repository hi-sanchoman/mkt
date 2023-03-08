<template>
<div class="w-full bg-white rounded-2xl h-auto  overflow-y-auto ">
    <div class="flex flex-col h-full">
        <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
            <div class="flex justify-start gap-5">
                <h3>Долги</h3>
                <button @click="showPayOtherDebt()"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded h-8" >
                    + Оплата
                </button>
            </div>

            <table class="w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    <th>ФИО</th>
                    <th>Начальный долг</th>
                    <th>Оплачено</th>
                    <th>Сумма долга на сегодня</th>
                </tr>
                <tr v-for="debt in other_debts" class="text-left border-b border-gray-200">
                    <td class="cursor-pointer"
                        @click="onOtherDebtClicked(debt)">
                        {{ debt.fio }}
                    </td>
                    <td>{{ formatNum(debt.debt)}}</td>
                    <td>{{ formatNum(debt.payments.reduce((carry, item) => carry + item.amount, 0)) }}</td>
                    <td>{{ formatNum(debt.debt - debt.payments.reduce((carry, item) => carry + item.amount, 0)) }}</td>
                </tr>
            </table>

        </div>
    </div>

    <!--  Долги (физ): Оплата долга (физ)  -->
    <modal name="pay-other-debt">
        <div class="p-5">
            <select-input v-model="other_debt_id" class="pr-6 pb-8 w-full lg:w-1/1" label="ФИО">
                <option v-for="debt in other_debts" :key="debt.id" :value="debt.id">{{ debt.fio }}</option>
            </select-input>
            <text-input  v-model="other_debt_amount" class="pr-6 pb-8 w-full lg:w-1/1" label="Сумма" />
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="payOtherDebt()">Оплатить долг</button>
        </div>
    </modal>

    <!-- Долги (физ): История одного долга -->
    <modal name="other_debt_history" v-if="selected_other_debt">
        <div class="px-6 py-6">
            История долга

            <table class="w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    <th>ФИО</th>
                    <th>Оплачено</th>
                    <th>Дата</th>
                </tr>
                <tr v-for="payment in selected_other_debt.payments" :key="payment.id" class="text-left border-b border-gray-200">
                    <td>{{ selected_other_debt.fio }}</td>
                    <td>{{ formatNum(payment.amount) }}</td>
                    <td>{{ moment(new Date(payment.created_at)).format('YYYY-MM-DD HH:mm') }}</td>
                </tr>
            </table>
        </div>
    </modal>
</div>
</template>
<script>
import SelectInput from '@/Shared/SelectInput'
import axios from 'axios'

export default {
    name: 'ContentPhysDebts.vue',
    components: {
        SelectInput,
    },
    props: {
        other_debts: Array,
    },
    data() {
        return {
            other_debt_id: 0,
            other_debt_amount: 0,
            selected_other_debt: null
        }
    },
    created() {

    },
    watch: {},
    computed: {},
    methods: {
        showPayOtherDebt(){
            this.$modal.show('pay-other-debt');
        },
        formatNum(num, type) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        },
        onOtherDebtClicked(debt) {
            this.selected_other_debt = debt;
            this.$modal.show('other_debt_history');
        },
        payOtherDebt() {
            axios.post("pay-other-debt",{
                id: this.other_debt_id,
                amount: this.other_debt_amount
            }).then((response) => {
                this.$modal.hide('pay-other-debt');
                alert('долг оплачен!');
                location.reload();
            });
        },
    }
}
</script>
