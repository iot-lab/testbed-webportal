<template>
  <div>
    <router-link :to="{name: 'newMonitoring'}" class="btn btn-sm btn-success float-right"><i class="fa fa-plus"></i> New profile</router-link>
    <h5>Monitoring profiles</h5>
    <table class="table table-striped table-sm mt-3">
      <thead>
        <tr>
          <th class="cursor" title="sort by name" @click="sortBy(p => p.profilename)">Profile</th>
          <th class="cursor" title="sort by archi" @click="sortBy(p => nodeArchi(p))">Archi</th>
          <th>Monitor</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="profile in store.profiles" v-if="filterByArchi(profile)">
          <td>
            <a href="#" @click.prevent="select(profile)">{{profile.profilename}}</a>
          </td>
          <td>{{nodeArchi(profile)}}</td>
          <td>
            <template v-if="profile.consumption && (profile.consumption.current || profile.consumption.voltage || profile.consumption.power)">
              <i class="fa fa-bolt" title="consumption"></i>
              <span v-if="profile.consumption.current"> current </span>
              <span v-if="profile.consumption.voltage"> voltage </span>
              <span v-if="profile.consumption.power"> power </span>
              <span class="text-muted" title="period, average">({{profile.consumption.period}}µs, {{profile.consumption.average}})</span>
            </template>
            <template v-if="profile.radio">
              <i class="fa fa-wifi" title="radio"></i> {{profile.radio.mode}}
              <span class="text-muted" title="channel">({{profile.radio.channels.join(',')}})</span>
            </template>
          </td>
        </tr>
        <tr v-if="store.profiles.length === 0">
          <td colspan="3" class="font-italic bg-light">
            <router-link :to="{name: 'newMonitoring'}" class="fsont-italic">create a monitoring profile</router-link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { iotlab } from '@/rest'
import store from '@/store'

export default {
  name: 'MonitoringList',

  props: {
    archi: {
      // Display monitoring profiles filtered by archi
      type: String,
      default: '',
    },
  },

  data () {
    return {
      store: store,
    }
  },

  async created () {
    try {
      this.store.profiles = (await iotlab.getMonitoringProfiles()).sort((a, b) => a.profilename.localeCompare(b.profilename))
    } catch (err) {
      this.$notify({text: 'Failed to load profiles', type: 'error'})
    }
  },

  methods: {
    sortBy (func) {
      // sort by func() then by profile name
      this.store.profiles = this.store.profiles.sort((a, b) => func(a) === func(b) ? a.profilename.localeCompare(b.profilename) : func(a).localeCompare(func(b)))
    },
    nodeArchi (profile) {
      return profile.nodearch.replace('custom', 'other')
    },
    filterByArchi (profile) {
      switch (this.archi) {
        case '': return true
        case 'a8': return profile.nodearch === 'a8'
        case 'm3': return profile.nodearch === 'm3'
        default: return profile.nodearch === 'custom'
      }
    },
    select (profile) {
      this.$emit('select', profile.profilename)
    },
  },
}
</script>

<style scoped>

</style>
