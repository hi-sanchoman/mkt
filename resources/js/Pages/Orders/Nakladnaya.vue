<template>
<div class="relative">   
    <p class=" text-lg font-bold mb-3">Накладная {{ id }}</p>

    <template v-if="nakladnaya">
        <p class="font-normal mb-3">
            {{ nakladnaya.consegnation }} 
            <span class="font-bold" v-if="nakladnaya.shop !== ''">для {{ nakladnaya.shop }}</span>
        </p> 
        <p class="font-bold mb-3">Сумма: <span class="font-normal">{{ nakladnaya.sum }}</span></p> 
        <p class="font-bold mb-3">Реализатор: <span class="font-normal">{{ nakladnaya.realizator }}</span></p> 
        <p class="font-bold mb-6">Дата: <span class="font-normal">{{ nakladnaya.date }}</span></p> 

        <table class="w-full mb-6">
            <tr class="">
                <th class="text-left pb-3 py-1" v-for="(header, h) in nakladnaya.headers" :key="'header' + h">
                    {{ header }}
                </th>
            </tr>
            <tr v-for="(row, r) in items" :key="'row' + r" class="border">
                <td class="pl-1">{{ row.index }}</td>
                <td class="py-1">{{ row.name }}</td>
                <td class="py-1">
                    <input type="number" v-model="row.amount" class="w-8" >
                </td>
                <td class="py-1">
                    <input type="number" v-model="row.brak" class="w-8" >
                </td>
                <td class="py-1">{{ row.price }}</td>
                <td class="py-1">{{ +row.price * (+row.amount - +row.brak) }}</td>
            </tr>
            <tr>
                <td class="pl-1"></td>
                <td class="py-1"></td>
                <td class="py-1"></td>
                <td class="py-1"></td>
                <td class="py-1">ИТОГ</td>
                <td class="py-1">{{ total }}</td>
            </tr>
        </table>
    </template>
    <template v-else-if="loading">
        <div class="flex flex-col items-center p-4">
            <img class="w-8 h-8 bg-white" src="/img/loading.gif" alt="">
            <p class="mt-4 text-center">Подождите, накладная загружается...</p>
        </div>
    </template>

    <div class="flex flex-col  p-4 absolute top-0 left-0 justify-center w-full h-full z-10 bg-white bottom" v-if="saving">
        <img class="w-8 h-8 bg-white" src="/img/loading.gif" alt="">
        <p class="mt-4 text-center">Подождите, накладная сохраняется...</p>
    </div>

</div>
</template>

<script>
import axios from 'axios'

export default {
    metaInfo: {title: 'Накладная'},
    props: {
        id: Number,
    },
    data() {
        return {
            nakladnaya: null,
            items: [],
            loading: true,
            saving: false
        }
    },
    computed: {
        total() {

            let sum = 0;
            this.items.forEach(i => {
                let s = +i.price * (+i.amount - +i.brak);
                sum += isNaN(s) ? 0 : +s;
                console.log(sum)
            })

            return sum;
        }
    },
    created() {
        this.get();
    },
    methods: {
        get() {
            try {
                axios.get(`nakladnaya/${this.id}`).then(response => {
                    this.nakladnaya = response.data;
                    this.items = this.parse(response.data.table);
                    this.loading = false;
                });
            } catch(e) {
                this.loading = false; 
            }
        },

        parse(data) {
            let arr = [];

            data.forEach(i => {
                arr.push({
                    'index': i[0],
                    'name': i[1],
                    'amount': i[2],
                    'brak': i[3],
                    'price': i[4],
                    'sum': i[5],
                    'store_id': i[6],
                })
            });

            return arr;
        },

        update() {
             try {
                this.saving = true;
                axios.post(`nakladnaya/update`, {
                    id: this.id,
                    items: this.items
                })
                .then(response => {
                    alert('Накладная обновлена');
                    this.loading = false;
                    this.saving = false;
                });
            } catch(e) {
                this.loading = false; 
                this.saving = false;
            }
        }

    }
}
</script>

<style scoped>
.bottom {
    justify-content: flex-end;
    align-items: center;
    padding-bottom: 150px;
    background: rgba(255,255,255,.7);
}
</style>
