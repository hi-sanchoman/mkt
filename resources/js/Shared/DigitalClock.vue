<script>

export default {
  name: "DigitalClock",
  data() {
    return {
      hours: 0,
      minutes: 0,
      seconds: 0
    }
  },
  mounted() {
    setInterval(() => this.setTime(), 1000)
  },
  methods: {
    setTime() {
      const date = new Date();
      let hours = date.getHours();
      let minutes = date.getMinutes();
      let seconds = date.getSeconds();
      hours = hours <= 9 ? `${hours}`.padStart(2, 0) : hours;
      minutes = minutes <= 9 ? `${minutes}`.padStart(2, 0) : minutes;
      seconds = seconds <= 9 ? `${seconds}`.padStart(2, 0) : seconds;
      this.hours = hours;
      this.minutes = minutes;
      this.seconds = seconds;
    }
  }
}
</script>

<template>
  <div class="LCD-clock">
    <div class="hours">{{ hours }}</div>
    <div class="divider">:</div>
    <div class="minutes">{{ minutes }}</div>
    <div class="divider">:</div>
    <div class="seconds">{{ seconds }}</div>
  </div>
</template>

<style scoped>
.LCD-clock {
  display: flex;
    padding: 4px 20px;
    background: #fff;
    border-radius: 5px;
    justify-content: center;
    font-weight: 500;
    color: #5f5c71;
    min-width: 240px;
    border: 1px solid #ddd;
}
.LCD-clock > div {
    font-family: 'Zona';
    font-size: 32px;
    text-align: center;
}
@media (max-width: 1100px) {
  .LCD-clock {
    display: none;
  }
}

@media (min-width: 1250px) {
  .LCD-clock {
    min-width: 180px;
  }
}

@media (min-width: 1600px) {
  .LCD-clock {
    padding: 5px 15px;
    min-width: 180px;
  }
  .LCD-clock > div {
    font-size: 32px;
    width: 42px;
  }
  .LCD-clock .divider {
    width: 12px;
  }
}

@media (min-width: 2600px) {
  .LCD-clock {
    padding: 5px 15px;
    min-width: 280px;
  }
  .LCD-clock > div {
    font-size: 60px;
    width: 85px;
  }
  .LCD-clock .divider {
    width: 17px;
  }
}
</style>
