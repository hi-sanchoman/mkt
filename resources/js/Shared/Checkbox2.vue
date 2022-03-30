<template>
  <label class="lbl ml-3 leading-8 h-8 font-light wrapper flex items-center  text-sm leading-6 py-1 px-2 rounded-full" :class="{'checked':isChecked}">
    {{label}}
    <input class="checkbox" type="checkbox" :checked="isChecked" :value="value" @change="updateInput"/>
    <span class="checkmark w-5 h-5"></span>
  </label>
</template>

<script>
export default {
  model: {
    prop: 'modelValue', 
    event: 'change'
  },
  props: {
    "value": { type: String },
    "modelValue": { default: "" },
    "label": { type: String, required: true, default: ''},
    "trueValue": { default: true },
    "falseValue": { default: false }
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
.lbl {
  background: #e9e9e9;
}
.lbl.checked {
  background-image: linear-gradient(to right, #e33232, #ff7220);
  color: #fff;
}
.wrapper {
  display: block;
  position: relative;
  padding-right: 35px;
  cursor: pointer;
  line-height: 24px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
/* Hide the browser's default checkbox */
.wrapper input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}
/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 5px;
  right: 5px;
  border-radius: 100%;
  border: 1.5px solid #fff;
}
/* On mouse-over, add a grey background color */
.wrapper:hover input ~ .checkmark {
  background-color: #e33232;
}
/* When the checkbox is checked, add a blue background */
.wrapper input:checked ~ .checkmark {
  /* background-color:#33cc33;
  border: 1.5px solid #33cc33; */
}
/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}
/* Show the checkmark when checked */
.wrapper input:checked ~ .checkmark:after {
  display: block;
}
/* Style the checkmark/indicator */
.wrapper .checkmark:after {
    left: 5px;
    top: 2px;
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 1.5px 1.5px 0;
    transform: rotate(41deg);
}
</style>

