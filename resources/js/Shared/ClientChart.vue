<template>
  <div class="h-full w-1/2 relative">
   <canvas id="client-chart" ></canvas>
  </div>
</template>

<script>

import Chart from 'chart.js'
import clientChartData from '@/Shared/client-data.js'

export default {
  name: 'ClientChart',
  data() {
    return {
      clientChartData: {
      type: 'horizontalBar',
      data: {
        labels: [1500,1600,1700,1750,1800,1850],
        datasets: [{ 
            data: [86,114,106,106,107,111],
            label: "Africa",
            borderColor: "#3e95cd",
            fill: false
          }, { 
            data: [282,350,411,502,635,809],
            label: "Asia",
            borderColor: "#8e5ea2",
            fill: false
          }
        ]
      },
        options: {
          title: {
            display: true,
            text: 'Количество клиентов'
          }
        }
      }
    }
  },
  mounted() {
    const ctx = document.getElementById('client-chart');
    new Chart(ctx, this.clientChartData);

  }
}
</script>