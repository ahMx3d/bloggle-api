// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = 'Nunito'),
	'-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

$(function() {
	let labels = [],
		datasets = [];
	$.get('/api/charts/users', { randomId: Math.random() }, function(data) {
        let baseColors = [];
        let dynamicColors = function () {
            const
                r = Math.round(Math.random()*255),
                g = Math.round(Math.random()*255),
                b = Math.round(Math.random()*255);
            return `rgb(${r},${g},${b})`;
        };
        for (let idx in data.labels) {
            baseColors.push(dynamicColors());
            $('#js-pie-chart-names').append(`
                <span class="mr-2">
                    <i class="fas fa-circle" style="color:${baseColors[idx]};"></i> ${data.labels[idx]}
                </span>`
            );
        }
        labels = data.labels;
        datasets.push({
            data                : data.datasets.values,
            backgroundColor     : baseColors,
            hoverBackgroundColor: baseColors,
            hoverBorderColor    : 'rgba(234, 236, 244, 1)'
        });
        // Pie Chart Example
        var ctx = document.getElementById('myPieChart');
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels  : labels,
                datasets: datasets
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyFontColor  : '#858796',
                    borderColor    : '#dddfeb',
                    borderWidth    : 1,
                    xPadding       : 15,
                    yPadding       : 15,
                    displayColors  : false,
                    caretPadding   : 10
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80
            }
        });
    });
});
