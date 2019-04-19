<template>
  <div>
    <div>
      <a class="cursor" title="Show Table" @click="toggle"><i class="fa fa-fw fa-eye"></i>{{showTable ? 'Hide' : 'Show'}} Table</a>
    </div>
    <div class="with-scrollbar table-wrapper-scroll-y">
      <table v-if="showTable" class="table table-striped table-sm mt-2">
        <thead>
        <tr>
          <th>{{ category_title }}</th>
          <th>{{ value_title }}</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="item in data">
          <tr class="d-table-row" :key="item">
            <td>{{item[0]}}</td><td>{{item[1]}}</td>
          </tr>
        </template>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import { downloadObjectAsCsv } from '@/utils'

export default {
  name: 'ChartTable',

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
  },

  data () {
    return {
      showTable: false,
    }
  },

  methods: {
    toggle () {
      this.showTable = !this.showTable
    },
    async download () {
      downloadObjectAsCsv(this.data, 'iotlab-table-statistics',
        {fields: ['category', 'value'], header: [this.category_title, this.value_title]})
    },
  },
}

</script>
<style scoped>
.with-scrollbar {
  position: relative;
  max-height: 500px;
  overflow: auto;
}
.table-wrapper-scroll-y {
  display: block;
}
</style>
