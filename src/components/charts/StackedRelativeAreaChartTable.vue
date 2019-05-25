<template>
  <div>
    <label><h3 class="mt-3">{{label}}</h3></label>
    <chart ref="chart" type="area" :options="options" :chartData="chartdata"/>
    <chart-table :category_title="category_title" :value_title="value_title" :value_titles="value_titles" :data="data_table"/>
  </div>
</template>
<script>
import { StackedRelativeAreaChart } from '@/components/charts/charts.js'
import ChartTable from '@/components/charts/ChartTable'
import { colorPalette } from '@/utils'
import zoomPlugin from 'chartjs-plugin-zoom'

export default {
  name: 'StackedRelativeAreaChartTable',

  components: {
    ChartTable, chart: StackedRelativeAreaChart,
  },

  props: {
    category_title: {
      type: String,
      default: () => '',
    },
    categories: {
      type: Array,
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
      plugins: [zoomPlugin],
    }
  },

  computed: {
    value_titles () {
      return [...this.categories, 'total']
    },
    data_table () {
      return this.data.map(el => [el[0].format('YYYY-MM-DD'), ...this.categories.map(category => el[1].values[category]), el[1].total])
    },
    chartdata () {
      let dataSeries = []
      let colors = colorPalette(this.categories.length)
      this.categories.forEach((category, index, array) => {
        dataSeries[index] = {
          data: [],
          backgroundColor: colors[index],
          label: category,
          fill: '-1',
        }
        if (index === 0) {
          dataSeries[index].fill = 'start'
        }
      })

      this.data.map(el => {
        let time = el[0].toDate()
        let values = el[1].values
        if (values) {
          let total = el[1].total
          let running = 0
          this.categories.forEach((category, index, array) => {
            dataSeries[index].running = running
            running += values[category] ? 100 * values[category] / total : 0
            dataSeries[index].data.push({x: time, y: running})
          })
        }
      })
      return {
        datasets: dataSeries,
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
        tooltips: {
          callbacks: {
            title: function (tooltipItem, data) {
              return data.datasets[tooltipItem[0].datasetIndex].data[tooltipItem[0].index].x.toISOString().split('T')[0]
            },
            label (tooltipItem, data) {
              let item = data.datasets[tooltipItem.datasetIndex]
              let label = item.label || ''

              if (label) {
                label += ': '
              }
              label += Math.round(100 * (tooltipItem.yLabel - item.running)) / 100
              label += '%'

              return label
            },
          },
        },
        elements: {
          line: {
            tension: 0, // disables bezier curves
          },
        },
        scales: {
          xAxes: [{
            type: 'time',
          }],
          yAxes: [{
            type: 'linear',
            labelString: this.value_title,
            ticks: {
              min: 0,
              max: 100,
            },
          }],
        },
      }
    },
  },
}

</script>
