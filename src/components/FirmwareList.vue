<template>
  <div>
    <router-link :to="{name: 'newFirmware'}" class="btn btn-sm btn-success float-right"><i class="fa fa-plus"></i> New firmware</router-link>
    <h5>Firmwares</h5>
    <table class="table table-striped table-sm mt-3">
      <thead>
        <tr>
          <th class="cursor" title="sort by name" @click="sortBy(f => f.name)">Name</th>
          <th class="cursor" title="sort by archi" @click="sortBy(f => f.archi)">Archi</th>
          <th class="cursor" title="sort by archi" @click="sortBy(f => f.description)">Description</th>
          <th width="15"><i class="fa fa-download" v-tooltip:bottom="'Download'"></i></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="firmware in store.firmwares" v-if="filterByArchi(firmware)">
          <td>
            <a v-if="event" href="#" @click.prevent="select(firmware)">{{firmware.name}}</a>
            <router-link v-else :to="{name: 'firmware', params: {name: firmware.name}}">
              {{firmware.name}}
            </router-link>
          </td>
          <td>{{firmware.archi}}</td>
          <td>{{firmware.description}}</td>
          <td><a href="#" @click.prevent="download(firmware)" v-tooltip:bottom.html="`<i class='fa fa-download'></i> <b>${firmware.fileName}</b>`"><i class="fa fa-file-o"></i></a></td>
        </tr>
        <tr v-if="store.firmwares.length === 0">
          <td colspan="4" class="font-italic bg-light">
            <router-link :to="{name: 'newFirmware'}">upload a firmware</router-link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { iotlab } from '@/rest'
import { downloadAsFile } from '@/utils'
import store from '@/store'

export default {
  name: 'FirmwareList',

  props: {
    archi: {
      // Display Firmware filtered by archi(s) (default no filter)
      type: [String, Array],
      default: '',
    },
    event: {
      // true  -> emits an selected event
      // false -> router link to object
      type: Boolean,
      default: false,
    },
  },

  data () {
    return {
      store: store,
    }
  },

  async created () {
    try {
      store.firmwares = (await iotlab.getFirmwares()).sort((a, b) => a.name.localeCompare(b.name))
    } catch (err) {
      this.$notify({text: 'Failed to load firmwares', type: 'error'})
    }
  },

  methods: {
    sortBy (func) {
      // sort by func() then by firmware name
      store.firmwares = store.firmwares.sort((a, b) => func(a) === func(b) ? a.name.localeCompare(b.name) : func(a).localeCompare(func(b)))
    },
    filterByArchi (firmware) {
      if (this.archi === '') {
        return true
      }
      let archis = (typeof this.archi === 'string') ? Array(this.archi) : this.archi
      return archis.includes(firmware.archi)
    },
    select (firmware) {
      this.$emit('select', firmware.name)
    },
    async download (firmware) {
      try {
        downloadAsFile(firmware.fileName, await iotlab.getFirmwareFile(firmware.name))
      } catch (err) {
        this.$notify({text: err.response.data.message || 'Failed to download file', type: 'error'})
      }
    },
  },
}
</script>

<style scoped>

</style>
