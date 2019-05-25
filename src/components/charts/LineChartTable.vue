<template>
  <div>
    <label>{{label}}:</label>
    <line-chart ref="chart" type="line" :options="options" :chartData="chartdata" :plugins="plugins"/>
    <chart-table :category_title="category_title" :value_title="value_title" :data="data_table"/>
  </div>
</template>
<script>
import ChartTable from '@/components/charts/ChartTable'
import { LineChart } from '@/components/charts/charts.js'
import zoomPlugin from 'chartjs-plugin-zoom'

export default {
  name: 'LineChartTable',

  components: {
    ChartTable, LineChart,
  },

  props: {
    category_title: {
      type: String,
      default: () => '',
    },
    value_title: {
      type: String,
      default: () => '',
    },
    data: {
      type: Array,
      default: () => [],
    },
    label: {
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      plugins: [ zoomPlugin ],
    }
  },

  computed: {
    data_table () {
      return this.data.map(el => [el[0].format('YYYY-MM-DD'), el[1]])
    },
    chartdata () {
      return {
        datasets: [
          {
            data: this.data.map(el => { return {x: el[0].toDate(), y: el[1]} }),
            fill: false,
            label: this.value_title,
          },
        ],
      }
    },
    options () {
      return {
        plugins: {
          zoom: {
            zoom: {
              enabled: true,
              drag: true,
              mode: 'x',
              speed: 0.05,
            },
          },
        },
        elements: {
          line: {
            tension: 0, // disables bezier curves
          },
        },
        tooltips: {
          callbacks: {
            title: function (tooltipItem, data) {
              return data.datasets[tooltipItem[0].datasetIndex].data[tooltipItem[0].index].x.toISOString().split('T')[0]
            },
          },
        },
        scales: {
          xAxes: [{
            type: 'time',
            scaleLabel: {
              display: true,
              labelString: this.category_title,
            },
          }],
          yAxes: [{
            type: 'linear',
            scaleLabel: {
              display: true,
              labelString: this.value_title,
            },
          }],
        },
      }
    },
  },
}

</script>
