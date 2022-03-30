<template>
  <label class="wrapper flex items-start my-2 mt-3 text-xs">
      <span class="checkmark w-5 h-5" :class="{checked: completed}"></span>
      <div class="flex flex-col flex-auto justify-between rounded-full">
        <p class="text-xs ml-30">{{ subtask }}</p>
        <p class="text-2xs text-gray-300">{{ task }}</p>
      </div>
      <div class="h-5 rounded-full text-indigo-400 flex items-center justify-center font-bold">
        {{ computeDays(remain) }}
      </div>
  </label>
</template>

<script>
export default {
  props: {
    remain: '',
    task: '',
    subtask: '',
    completed: 0,
  },
  created() {
    
  },
  methods: {
    computeDays(deadline){
        var difference = Math.abs(new Date(deadline) - Date.now());
        var days = difference/(1000 * 3600 * 24)
        return Math.round(days) + ' дн';
    },
  }
}
</script>

<style lang="css" scoped>

.wrapper {
  position: relative;
  padding-left: 35px;
  margin-bottom: 6px;
  cursor: pointer;
  line-height: 22px;
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
    top: 0;
    left: 0;
    border-radius: 2px;
    border: 1px solid #e0e0e0;
}
/* On mouse-over, add a grey background color */
.wrapper:hover  .checkmark {
  background-color: #f1f1f1;
}
/* When the checkbox is checked, add a blue background */
.wrapper .checkmark.checked  {
  background-color:#22c55e;
  border: 1.5px solid #22c55e;
}
/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}
/* Show the checkmark when checked */
.wrapper  .checkmark.checked:after {
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

