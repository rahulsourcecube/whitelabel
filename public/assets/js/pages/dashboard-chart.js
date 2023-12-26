$(document).ready(function () {
    var ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
        // The type of chart we want to create
        type: 'line', // also try bar or other graph types
        // The data for our dataset
        data: {
            labels: chartdata.day,
            // Information about the dataset
            datasets: [{
                label: "Reward",
                backgroundColor: 'lightblue',
                borderColor: 'royalblue',
                data: chartdata.reward,
            }]
        },
        // Configuration options
        options: {
            layout: {
                padding: 10,
            },
            legend: {
                position: '',
            },
            title: {
                display: true,
                text: 'Reward earn by user'
            },
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Reward ' + currency
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: chart_title
                    }
                }]
            }
        }
    });
});
