<template>
<div class="w-full bg-white rounded-2xl h-auto  overflow-y-auto ">
    <div class="flex flex-col h-full">
        <div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto ">
            <div class="flex justify-start gap-5 items-center">
                <h3 class="font-bold">Долги</h3>
                <button @click="$modal.show('pay-other-debt')"
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
                <tr v-for="debt in debts" class="text-left border-b border-gray-200">
                    <td class="cursor-pointer"
                        @click="onOtherDebtClicked(debt)">
                        {{ debt.fio }}
                    </td>
                    <td>{{ formatNum(debt.debt)}}</td>
                    <td>{{ formatNum(debt.paid) }}</td>
                    <td>{{ formatNum(debt.debt_for_today) }}</td>
                </tr>
            </table>

        </div>
    </div>

    <!--  Долги (физ): Оплата долга (физ)  -->
    <modal name="pay-other-debt">
        <div class="p-5">
            
            <div class="flex items-center">
                <label class="form-label font-medium w-2/12">ФИО:</label>
     
                <select-input v-model="other_debt_id" class="pr-6 pb-8 w-10/12 flex" >
                    <option v-for="debt in debts" :key="debt.id" :value="debt.id">{{ debt.fio }}</option>
                </select-input>
            </div>

            <div class="flex mb-3">
                <label class="form-label font-medium w-2/12">Остаток долга:</label>
                <span class="w-10/12">{{ debts.find(el => el.id == other_debt_id) ? debts.find(el => el.id == other_debt_id).debt_for_today : '' }}</span>
            </div>

            <div class="flex">
                <label class="form-label font-medium w-2/12">Сумма:</label>
                <text-input v-model="other_debt_amount" class="pr-6 pb-8 w-10/12" />
            </div>

            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded"
                @click="payOtherDebt()">
                Оплатить долг
            </button>
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
import TextInput from '@/Shared/TextInput'
import axios from 'axios'
import moment from "moment";

export default {
    name: 'ContentPhysDebts',
    components: {
        SelectInput,
        TextInput,
    },
    props: {
        other_debts: Array,
    },
    data() {
        return {
            other_debt_id: 0,
            other_debt_amount: 0,
            selected_other_debt: null,
            moment: moment,
            debts: []
        }
    },
    created() {

        let arr = [];
        this.other_debts?.forEach(d => {
            let item = d;

            let paid = d.payments.reduce((carry, c) => carry + c.amount, 0);
            item.paid = paid;
            item.debt_for_today = Number(d.debt) - Number(paid);

            console.log('A', item.debt_for_today)
            if(Number(item.debt_for_today) > 0) arr.push(item);

        });

        this.debts = arr;
    },
    watch: {},
    computed: {},
    methods: {
        formatNum(num, type) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        },
        onOtherDebtClicked(debt) {
            this.selected_other_debt = debt;
            this.$modal.show('other_debt_history');
        },
        payOtherDebt() {

            let d = this.debts.find(el => el.id == this.other_debt_id);

            console.log(this.other_debt_amount > Number(d.debt_for_today));
            if(d && this.other_debt_amount > Number(d.debt_for_today)) {
                alert('Сумма не должна превышать размер долга');
                return;
            }

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
