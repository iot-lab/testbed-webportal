<template>
  <div>
    <router-link :to="{name: 'newMobilityCircuit'}" class="btn btn-sm btn-outline-success float-right"><i class="fa fa-plus"></i> New mobility circuit</router-link>
    <h5>Mobility circuits</h5>
    <ul class="nav nav-tabs" style="position: relative; top: 1px">
      <li class="nav-item" v-tooltip:top="'User circuits'">
        <a class="nav-link active" data-toggle="list" href="#userdefined" role="tab" aria-controls="userdefined" @click="filterType = 'userdefined'"> My circuits </a>
      </li>
      <li class="nav-item" v-tooltip:top="'Predefined circuits'">
        <a class="nav-link" data-toggle="list" href="#predefined" role="tab" aria-controls="predefined" @click="filterType = 'predefined'"> Presets </a>
      </li>
    </ul>
    <table class="table table-striped table-sm">
      <thead>
      <tr>
        <th class="cursor" title="sort by name" @click="sortBy(p => p.name)">Circuit</th>
        <th class="cursor" title="sort by site" @click="sortBy(p => p.site)">Site</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="circuit in store.circuits" v-if="filter(circuit)" :key="circuit.name">
        <td>
          <a v-if="select" href="#" @click.prevent="selectItem(circuit)">{{circuit.name}}</a>
          <router-link v-else :to="{name: 'mobilityCircuit', params: {name: circuit.name}}">
            {{circuit.name}}
          </router-link>
        </td>
        <td>{{circuit.site}}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { iotlab } from '@/rest'
import store from '@/store'

export default {
  name: 'CircuitList',

  props: {
    site: {
      // Display mobility circuits filtered by site
      type: String,
      default: '',
    },
    select: {
      // true  -> emits a selected event
      // false -> router link to object
      type: Boolean,
      default: false,
    },
  },

  data () {
    return {
      store: store,
      currentSite: 'all',
      sites: [],
      filterType: 'userdefined',
    }
  },

  async created () {
    iotlab.getSitesDetails().then(data => { this.sites = data.sort((a, b) => a.site.localeCompare(b.site)) }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch sites details', type: 'error'})
    })
    iotlab.getMobilityCircuits().then(data => { this.store.circuits = data.sort((a, b) => a.name.localeCompare(b.name)) }).catch(err => {
      this.$notify({text: err.response.data.message || 'Failed to fetch circuits details', type: 'error'})
    })
  },

  methods: {
    sortBy (func) {
      // sort by func() then by name
      this.store.circuits = this.store.circuits.sort((a, b) => func(a) === func(b) ? a.name.localeCompare(b.name) : func(a).localeCompare(func(b)))
    },
    selectItem (circuit) {
      this.$emit('select', circuit.name)
    },
    filter (circuit) {
      if (circuit.type !== this.filterType) {
        return false
      }
      if (this.currentSite !== 'all') {
        if (circuit.site !== this.currentSite.site) {
          return false
        }
      }
      return true
    },
  },
}
</script>

<style scoped>

</style>
