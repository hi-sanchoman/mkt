<template>
  <div class="h-full flex-auto flex flex-col relative">
    <canvas id="sales-chart" class="flex-auto flex flex-col"></canvas>
  </div>
</template>

<script>

import Chart from 'chart.js'
import salesChartData from '@/Shared/sales-data.js'

export default {
  name: 'SalesChart',
  data() {
    return {
      salesChartData: {
        type: "bar",
        data: {
            labels: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь"],
            datasets: [{
                    label: "Выполнено",
                    data: [17, 16, 10, 28, 32, 30],
                    backgroundColor: "#4a32e3",
                },
                {
                    label: "План",
                    data: [25, 25, 30, 40, 40, 40],
                    backgroundColor: "#ffab3b",
                }
            ]
        },
        options: {
            responsive: true,
            lineTension: 1,
              title: {
            display: true,
            text: 'Количество звонков'
          },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        padding: 25
                    }
                }]
            }
        }
    }
    }
  },
  mounted() {
    const ctx = document.getElementById('sales-chart');
    new Chart(ctx, this.salesChartData);

  }
}
</script>