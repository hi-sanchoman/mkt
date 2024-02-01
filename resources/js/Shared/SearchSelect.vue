<template>
  <div class="relative">
    <div class="relative rounded shadow">
      <input type="text" v-model="searchText" :placeholder="placeholder" class="w-full py-1 px-1 rounded" @focus="show = true" />

      <template v-if="value">
        <div class="absolute top-0 w-full z-10 bg-white rounded h-full flex items-center px-1">
          {{ value_text }}
        </div>
        <div class="absolute top-1 z-20 right-2 cursor-pointer hover:bg-indigo-500" @click="$emit('change', null)"><i class="fas fa-times"></i></div>
      </template>
    </div>

    <div v-if="show" class="absolute h-40 md:h-60 overflow-x-hidden overflow-y-auto z-10 top-full left-0 w-full">
      <div v-for="option in filteredOptions" :key="option" @click="() => selectItem(option)" class="bg-white hover:bg-blue-100 cursor-pointer px-1 md:py-1 border border-gray-100">
        {{ option.name }}
      </div>
    </div>
  </div>
</template>
 
<script>
export default {
  name: 'SearchSelect',
  props: {
    options: {
      type: Array,
      required: true,
    },
    value: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      default: 'Выберите...',
    },
  },
  data() {
    return {
      searchText: '',
      show: '',
    }
  },
  computed: {
    filteredOptions() {
      if (this.searchText === '') {
        return this.options
      }
      return this.options.filter((option) => option.name?.toLowerCase().includes(this.searchText.toLowerCase()))
    },
    value_text() {
      const option = this.options.find((option) => option.id === this.value)
      return option?.name
    },
  },
  methods: {
    selectItem(item) {
      this.show = ''
      this.searchText = ''
      this.$emit('change', item.id)
    },
  },
}
</script>
