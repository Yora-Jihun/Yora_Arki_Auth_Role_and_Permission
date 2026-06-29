import Chart from 'chart.js/auto';

window.renderEmployeeAttendanceChart = (config) => {
    const canvas = document.getElementById('employeeAttendanceChart');

    if (!canvas) {
        return;
    }

    Chart.getChart(canvas)?.destroy();

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: config.labels,
            datasets: [
                {
                    label: 'Work Hours',
                    data: config.hours,
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.12)',
                    borderWidth: 2,
                    pointBackgroundColor: '#059669',
                    pointBorderColor: '#ffffff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#ffffff',
                    bodyColor: '#e2e8f0',
                    borderColor: 'rgba(255, 255, 255, 0.12)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: (context) => `${context.parsed.y.toFixed(2)} hours`,
                    },
                },
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11,
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.18)',
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11,
                        },
                        callback: (value) => `${value}h`,
                    },
                },
            },
        },
    });
};