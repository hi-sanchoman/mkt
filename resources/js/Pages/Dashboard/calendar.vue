<template>
  <vue-calendar
      :show-limit="3"
      :events="events"
      :disable="disabledDays"
      :highlight="highlightDays"

      @show-all="showAll"
      @day-clicked="dayClicked"
      @event-clicked="eventClicked"
      @month-changed="monthChanged"
  ></vue-calendar>
</template>

<script>


  export default {
    name: 'my-calendar',
    methods: {
      showAll(events) {
        // Do something...
      },
      dayClicked(day) {
        // Do something...
      },
      eventClicked(event) {
        // Do something...
      },
      monthChanged(start, end) {
        // Do something...
      }
    },
    created() {
      this.$calendar.eventBus.$on('show-all', events => showAll(events));
      this.$calendar.eventBus.$on('day-clicked', day =>  dayClicked(day));
      this.$calendar.eventBus.$on('event-clicked', event => eventClicked(event));
      this.$calendar.eventBus.$on('month-changed', (start, end) => monthChanged(start, end));
    }
  }
</script>