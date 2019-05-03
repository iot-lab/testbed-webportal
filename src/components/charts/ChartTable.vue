<template>
  <div>
    <div>
      <a class="cursor" title="Show Table" @click="toggle"><i class="fa fa-fw fa-eye"></i>{{showTable ? 'Hide' : 'Show'}} Table</a>
      <a class='cursor float-right' @click.prevent='download'>download <span class='badge badge-pill badge-info'>CSV</span></a>
    </div>
    <div class="with-scrollbar table-wrapper-scroll-y">
      <table v-if="showTable" class="table table-striped table-sm mt-2">
        <thead>
        <tr>
          <th v-for="header in headers">{{ header }}</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="item in data">
          <tr class="d-table-row">
            <td v-for="element in item">{{element}}</td>
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
    },
    value_title: {
      type: String,
    },
    value_titles: {
      type: Array,
    },
    data: {
      type: Array,
    },
  },

  data () {
    return {
      showTable: false,
    }
  },

  computed: {
    headers () {
      if (this.value_titles) {
        return [this.category_title, ...this.value_titles]
      } else {
        return [this.category_title, this.value_title]
      }
    },
    csvOpts () {
      return { fields: this.headers.map((header, index) => { return { label: header, value: String(index) } }) }
    },
  },

  methods: {
    toggle () {
      this.showTable = !this.showTable
    },
    async download () {
      downloadObjectAsCsv(this.data, 'iotlab-table-statistics', this.csvOpts)
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
