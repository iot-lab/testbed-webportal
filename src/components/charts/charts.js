import { Pie, Bar, Line, mixins } from 'vue-chartjs'

function getChart (name, type) {
  return {
    extends: type,
    mixins: [mixins.reactiveProp],
    name: name,

    props: ['chartData', 'options'],

    mounted () {
      this.renderChart(this.chartData, {...this.options, ...{responsive: true, maintainAspectRatio: false}})
    },
  }
}

export const BarChart = getChart('BarChart', Bar)
export const StackedRelativeAreaChart = getChart('StackedRelativeAreaChart', Line)
export const LineChart = getChart('LineChart', Line)
export const PieChart = getChart('PieChart', Pie)
