export const planetChartData = {
    type: "bar",
    data: {
        labels: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь"],
        datasets: [{
                label: "Выполнено",
                data: [17, 16, 10, 28, 32, 30],
                backgroundColor: "#2196f3",
            },
            {
                label: "План",
                data: [25, 25, 30, 40, 40, 40],
                backgroundColor: "#ffeb3b",
            }
        ]
    },
    options: {
        responsive: true,
        lineTension: 1,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    padding: 25
                }
            }]
        }
    }
};

export default planetChartData;