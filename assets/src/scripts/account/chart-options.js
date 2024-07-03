export default {
    series: [],
    xaxis: {
        type: 'datetime',
        categories: []
    },
    chart: {
        type: 'area',
        height: 'auto',
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 300,
            animateGradually: {
                enabled: false,
            },
        },
        toolbar: {
            show: false,
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        curve: 'smooth',
        colors: [ window.rz_vars.chart.colors.main ],
        width: 4,
    },
    tooltip: {
        z: {
            show: true,
        },
        marker: {
            show: false,
        },
    },
    fill: {
        colors: [ window.rz_vars.chart.colors.main ],
        opacity: 0.9,
        type: 'gradient',
        gradient: {
            type: "vertical",
            opacityFrom: .5,
            opacityTo: 0,
            colorStops: []
        },
    },
    markers: {
        size: 5,
        colors: [ window.rz_vars.chart.colors.main ],
        strokeColors: '#fff',
        strokeWidth: 3,
        strokeOpacity: 0.7,
        strokeDashArray: 0,
        fillOpacity: 1,
        discrete: [],
        shape: "circle",
        radius: 5,
        showNullDataPoints: false,
        hover: {
            size: undefined,
            sizeOffset: 3
        }
    },
    grid: {
        borderColor: '#f1f1f1',
        xaxis: {
            lines: {
                show: false,
            }
        },
        yaxis: {
            lines: {
                show: false,
            }
        },
    }
}