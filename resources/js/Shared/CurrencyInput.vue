<template>
  <input 
    ref="inputRef" 
    :value="formattedValue"
  >
</template>

<script>
  import { watch } from '@vue/composition-api'
import useCurrencyInput from 'vue-currency-input'

export default {
  name: 'CurrencyInput',
  props: {
    value: Number, // Vue 2: value
    options: Object
  },
  setup (props) {
    const {
      inputRef,
      formattedValue,
      setOptions,
      setValue
    } = useCurrencyInput(props.options)

    watch(() => props.modelValue, (value) => { // Vue 2: props.value
      setValue(value)
    })

    watch(() => props.options, (options) => {
      setOptions(options)
    })

    return { inputRef, formattedValue }
  }
}
</script>