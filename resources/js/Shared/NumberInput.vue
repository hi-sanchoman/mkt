<template>
  <div class="flex" :class="{ 'flex-col' : !col }">
    <div :class="{ 'w-3/12' : col }">
      <label v-if="label" class="form-label font-medium" :for="id">{{ label }}:</label>
    </div>
    <div :class="{ 'w-9/12' : col }">
      <input :id="id" ref="input" v-bind="$attrs" class="w-full block  pb-1 border-b-2 border-gray-200" :class="{ error: error }" :type="type" :value="value" @input="$emit('input', $event.target.value)" />
      <div class="w-full mt-1">
        <div v-if="error" class="form-error">{{ error }}</div>
      </div>
    </div>
    
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `number-input-${this._uid}`
      },
    },
    type: {
      type: Number,
      default: 'number',
    },
    col: {
      type: Boolean,
      default: true,
    },
    value: String,
    label: String,
    error: String,
  },
  methods: {
    focus() {
      this.$refs.input.focus()
    },
    select() {
      this.$refs.input.select()
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end)
    },
  },
}
</script>
<style scoped>
</style>