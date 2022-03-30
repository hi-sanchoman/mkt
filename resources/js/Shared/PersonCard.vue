<template>
  <div class="flex cursor-pointer">
    <div class="w-10"  @click="openProfile">
      <img :src="src" alt="" class="w-10 h-10 rounded-full shadow-sm border-2 border-gray-200">
    </div>
    <div class="pl-3" v-if="!hide"  @click="openProfile">
      <p class="text-black font-normal  text-sm pt-1">{{ fullname }}</p>
      <p class="text-3xs font-light text-gray-400">{{ job }}</p>
    </div>
  </div>
</template>

<script>
export default {
  model: {
    prop: 'modelValue', 
    event: 'change'
  },
  props: {
    id: { default: 0 },
    src: { default: "/img/default-user.png" },
    fullname: { default: "Имя Фамилия" },
    job: { default: "Должность" },
    hide: { default: false },
  },
  created() {
    if(this.src == '') this.src = '/img/default-user.png';
  },
  computed: {
    isChecked() {
      if (this.modelValue instanceof Array) {
        return this.modelValue.includes(this.value)
      }
      // Note that `true-value` and `false-value` are camelCase in the JS
      return this.modelValue === this.trueValue
    }
  },
  methods: {
    openProfile(){
      this.$page.props.auth.sidebar_profile = true
      this.$page.props.actions.selected_user = this.id
    },
    updateInput(event) {
      let isChecked = event.target.checked
      if (this.modelValue instanceof Array) {
        let newValue = [...this.modelValue]
        if (isChecked) {
          newValue.push(this.value)
        } else {
          newValue.splice(newValue.indexOf(this.value), 1)
        }
        this.$emit('change', newValue)
      } else {
        this.$emit('change', isChecked ? this.trueValue : this.falseValue)
      }
    }
  }
}
</script>

<style lang="css" scoped>

</style>

