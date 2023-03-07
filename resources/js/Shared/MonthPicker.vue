<template>
<div class="relative mr-4">
    <div class="flex space-x-2 items-center justify-center border p-2">
        <button @click="prevMonth" class="hover:bg-white hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
            </svg>
        </button>
        <span class="w-40 text-center">{{ getMonthName(month) }} {{ year }}</span>
        <button @click="nextMonth" class="hover:bg-white hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
            </svg>
        </button>
    </div>
</div>
</template>

<script>
export default {
  name: 'MonthPicker',
  props: {
    month: Number,
    year: Number
  },
  data() {
    return {
        selectMonth: [
            {id: 1,month: 'Январь'},
            {id: 2,month: 'Февраль'},
            {id: 3,month: 'Март'},
            {id: 4,month: 'Апрель'},
            {id: 5,month: 'Май'},
            {id: 6,month: 'Июнь'},
            {id: 7,month: 'Июль'},
            {id: 8,month: 'Август'},
            {id: 9,month: 'Сентябрь'},
            {id: 10,month: 'Октябрь'},
            {id: 11,month: 'Ноябрь'},
            {id: 12,month: 'Декабрь'}
        ],
    }
  },
  methods: {
    getMonthName(month) {
        return this.selectMonth.find(m => m.id == month).month;
    },

    nextMonth() {
        let m = this.month
        let y = this.year

        m += 1;

        if (m > 12) {
            m = 1;
            y += 1;
        }

        this.$emit('monthChanged', m, y)
    },

    prevMonth() {
        let m = this.month
        let y = this.year

        m -= 1;

        if (m <= 0) {
            m = 12;
            y -= 1;
        }

        this.$emit('monthChanged', m, y)
    }
  }
}
</script>
