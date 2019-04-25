<template>
  <div>
    <label>{{label}}:</label>
    <chart-table :category_title="category_title" :value_title="value_title" :data="Object.entries(data)"/>
    <div>
      <p >
        <a><span class="badge badge-pill mr-1 cursor"  @click="type = 'bar'" :class="{'badge-primary': type === 'bar', 'badge-secondary': type !== 'bar'}">Bars</span></a>
        <a><span class="badge badge-pill mr-1 cursor"  @click="type = 'pie'" :class="{'badge-primary': type === 'pie', 'badge-secondary': type !== 'pie'}">Pie</span></a>
      </p>
    </div>
    <vue-apex-charts v-if="type === 'bar'" ref="chart" width="900" type="bar" :options="options" :series="data_series"/>
    <vue-apex-charts v-if="type === 'pie'" ref="chart" width="900" type="pie" :options="pie_options" :series="data_values"/>
  </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts'
import ChartTable from '@/components/charts/ChartTable'

export default {
  name: 'BarChartTable',

  components: {
    VueApexCharts, ChartTable,
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
      type: Object,
      default: () => {},
    },
    label: {
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      type: 'bar',
    }
  },

  computed: {
    options () {
      return {
        xaxis: {
          categories: Object.keys(this.data),
        },
        dataLabels: {
          enabled: false,
        },
      }
    },
    pie_options () {
      return {
        labels: Object.keys(this.data),
      }
    },
    data_values () {
      return Object.values(this.data)
    },
    data_series () {
      return [{
        name: this.value_title,
        data: this.data_values,
      }]
    },
  },
}

</script>
