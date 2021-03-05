// const { ready } = require('jquery');

// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = 'Nunito'),
	'-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

$(function() {
	let labels = [],
		datasets = [];
        $.get(
            '/api/charts/comments',
            {randomId: Math.random()},
            function (data) {
                labels = data.labels;
                let obj = data.datasets;
                $.each(obj, function (idx, val) {
                    const
                        r = Math.round(Math.random()*255),
                        g = Math.round(Math.random()*255),
                        b = Math.round(Math.random()*255);

                    datasets.push({
                        label                    : obj[idx].name,
                        lineTension              : 0.3,
                        backgroundColor          : `rgba(${r}, ${g}, ${b}, 0.05)`,
                        borderColor              : `rgba(${r}, ${g}, ${b}, 1)`,
                        pointRadius              : 3,
                        pointBackgroundColor     : `rgba(${r}, ${g}, ${b}, 1)`,
                        pointBorderColor         : `rgba(${r}, ${g}, ${b}, 1)`,
                        pointHoverRadius         : 3,
                        pointHoverBackgroundColor: `rgba(${r}, ${g}, ${b}, 1)`,
                        pointHoverBorderColor    : `rgba(${r}, ${g}, ${b}, 1)`,
                        pointHitRadius           : 10,
                        pointBorderWidth         : 2,
                        data                     : obj[idx].values
                    });
                });
                // Area Chart Example
                var ctx = document.getElementById('myAreaChart');
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0
                            }
                        },
                        scales: {
                            xAxes: [
                                {
                                    time: {
                                        unit: 'date'
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 7
                                    }
                                }
                            ],
                            yAxes: [
                                {
                                    ticks: {
                                        maxTicksLimit: 5,
                                        padding: 10,
                                        callback: function(value, index, values) {
                                            return value;
                                        }
                                    },
                                    gridLines: {
                                        color: 'rgb(234, 236, 244)',
                                        zeroLineColor: 'rgb(234, 236, 244)',
                                        drawBorder: false,
                                        borderDash: [ 2 ],
                                        zeroLineBorderDash: [ 2 ]
                                    }
                                }
                            ]
                        },
                        legend: {
                            display: false
                        },
                        tooltips: {
                            backgroundColor: 'rgb(255,255,255)',
                            bodyFontColor: '#858796',
                            titleMarginBottom: 10,
                            titleFontColor: '#6e707e',
                            titleFontSize: 14,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                    return datasetLabel + ': ' + tooltipItem.yLabel;
                                }
                            }
                        }
                    }
                });
            }
        );
});
