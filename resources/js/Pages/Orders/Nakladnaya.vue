<template>
<div>   
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
            <tr v-for="(row, r) in nakladnaya.table" :key="'row' + r" class="border">
                <td>{{ row[0] }}</td>
                <td class="py-1">{{ row[1] }}</td>
                <td class="py-1">{{ row[2] }}</td>
                <td class="py-1">{{ row[3] }}</td>
                <td class="py-1">{{ row[4] }}</td>
                <td class="py-1">{{ row[5] }}</td>
            </tr>
        </table>
    </template>
    <template v-else-if="loading">
        <div class="flex flex-col items-center p-4">
                <img class="w-8 h-8 bg-white" src="/img/loading.gif" alt="">
                <p class="mt-4 text-center">Подождите, накладная загружается...</p>
            </div>
    </template>

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
            loading: true
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
                    this.loading = false;
                });
            } catch(e) {
                this.loading = false; 
            }
        }
    }
}
</script>