<template>
<div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto">
    <div class="flex justify-start gap-5">
        <h3>Долги</h3>

        <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded h-8"
            @click="$modal.show('add-money')">
            + Оплата
        </button>
    </div>

    <table class="w-full whitespace-nowrap mt-5 mb-10">
        <tr class="text-left  border-b border-gray-200">
            <th>Магазин</th>
            <th>Начальный долг</th>
            <th>Продано</th>
            <th>Оплачено</th>
            <th>Остальной долг</th>
            <th>Реализатор</th>
        </tr>
        <tr v-for="(market, key) in markets" :key="market.id" class="text-left border-b border-gray-200">
            <td class="cursor-pointer"
                @click="onCompanyClicked(market, key)">
                {{ market.name }}
            </td>
            <td>
                <input type="number"
                    v-model="market.debt_start"
                    @change="changeDebtStart(market)" />
            </td>
            <td>{{ soldTotal(market) }}</td>
            <td>{{ paidTotal(market) }}</td>
            <td>{{ (market.debt_start + soldTotal(market) - paidTotal(market)).toFixed(2) }}</td>
            <td>
                <div v-for="(r, key2) in getRealizators(market)" :key="key2">{{ r }}</div>
            </td>
        </tr>
    </table>

    <!-- Долги клиентов: Оплата долга клиентом -->
    <modal name="add-money">
        <div class="p-5">
            <select-input v-model="debt_branch_id" class="pr-6 pb-8 w-full lg:w-1/1" label="Магазин">
                <option v-for="branch in branches" :key="branch.id" :value="branch.id">{{ branch.name }}</option>
            </select-input>
            <text-input  v-model="debt_amount" class="pr-6 pb-8 w-full lg:w-1/1" label="Сумма" />
            <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" @click="payDebt()">Оплатить долг</button>
        </div>
    </modal>


    <!-- Долги клиентов: Филиалы История долга -->
    <modal name="company_branches">
        <div class="px-6 py-6">
            Филиалы магазина
            <table class="w-full whitespace-nowrap mt-5">
                <tr class="text-left  border-b border-gray-200">
                    <th>Название</th>
                    <th>Продано</th>
                    <th>Оплачено</th>
                    <th>Остальной долг</th>
                </tr>
                <tr v-for="branch in market_branches" :key="branch.id" class="text-left  border-b border-gray-200">
                    <td>{{ branch.name }}</td>
                    <td>{{ branch.sold }}</td>
                    <td>{{ branch.paid }}</td>
                    <td>{{ (branch.sold - branch.paid).toFixed(2) }}</td>
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
    name: 'ContentClientDebts',
    components: {
        SelectInput,
    },
    props: {
        markets: Array,
        branches: Array,
    },
    data() {
        return {
            debt_branch_id: 0,
            debt_amount: 0,
            market_branches: []
        }
    },
    created() {

    },
    watch: {},
    computed: {},
    methods: {
        onCompanyClicked(market, key) {
            this.market_branches = market.branches;
            this.$modal.show('company_branches');
        },
        changeDebtStart(market) {
            axios.post('dolg-start',{
                id: market.id, amount: market.debt_start
            });
        },
        soldTotal(market) {
            let total = 0;
            for (let i = 0; i < market.branches.length; i++) {
                total += market.branches[i]['sold'];
            }
            return total;
        },
        paidTotal(market) {
            let total = 0;
            for (let i = 0; i < market.branches.length; i++) {
                total += market.branches[i]['paid'];
            }
            return total;
        },
        getRealizators(market) {
            const users = new Set();

            market.branches.map((branch) => {
                branch.realizators.map((realizator) => {
                    users.add(realizator.first_name);
                })
            })

            return users;
        },
        payDebt() {
            axios.post("pay-owe",{
                branch_id: this.debt_branch_id,
                amount: this.debt_amount
            }).then((response) => {
                this.$modal.hide('add-money');
                alert('долг оплачен!');
                location.reload();
            });
        },
    }
}
</script>
