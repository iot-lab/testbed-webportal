<template>
  <form @submit.prevent="updateProfile" class="mt-3">
    <div class="form-group">
      <label class="mr-3 my-label">Name</label>
      <input v-model="profile.profilename" type="text" class="form-control" placeholder="Profile name" ref="name"
        style="max-width: 300px; display: inline-block"
        :class="{'is-invalid': !nameValidation}">
      <div class="invalid-feedback">
        Invalid name. Only alphanumeric characters allowed [0-9A-Za-z_]
      </div>
    </div>
    <div class="form-group">
      <label class="mr-3 my-label">Architecture</label>
      <label class="custom-control custom-radio">
        <input name="radio-archi" type="radio" class="custom-control-input" v-model="profile.nodearch" value="m3">
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">M3</span>
      </label>
      <label class="custom-control custom-radio">
        <input name="radio-archi" type="radio" class="custom-control-input" v-model="profile.nodearch" value="a8">
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">A8</span>
      </label>
      <label class="custom-control custom-radio">
        <input name="radio-archi" type="radio" class="custom-control-input" v-model="profile.nodearch" value="custom">
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">Other</span>
      </label>
    </div>
    <div class="form-group">
      <label class="mr-3 my-label">Monitor</label>
      <label class="custom-control custom-checkbox">
        <input name="checkbox-monitor" type="checkbox" class="custom-control-input" v-model="showConso">
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">Consumption <i class="fa fa-bolt text-muted"></i></span>
      </label>
      <label class="custom-control custom-checkbox">
        <input name="checkbox-monitor" type="checkbox" class="custom-control-input" v-model="showRadio">
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">Radio <i class="fa fa-wifi text-muted"></i></span>
      </label>
    </div>
    <fieldset class="card bg-light pt-2 mb-3" v-if="showConso">
      <div class="form-group">
        <label class="mr-3 my-label"><i class="fa fa-bolt"></i></label>
        <label class="custom-control custom-checkbox">
          <input name="checkbox-conso" type="checkbox" class="custom-control-input" v-model="profile.consumption.current">
          <span class="custom-control-indicator"></span>
          <span class="custom-control-description">Current</span>
        </label>
        <label class="custom-control custom-checkbox">
          <input name="checkbox-conso" type="checkbox" class="custom-control-input" v-model="profile.consumption.voltage">
          <span class="custom-control-indicator"></span>
          <span class="custom-control-description">Voltage</span>
        </label>
        <label class="custom-control custom-checkbox">
          <input name="checkbox-conso" type="checkbox" class="custom-control-input" v-model="profile.consumption.power">
          <span class="custom-control-indicator"></span>
          <span class="custom-control-description">Power</span>
        </label>
      </div>
      <div class="form-group">
        <label class="mr-3 my-label">Period</label>
        <select class="custom-select" v-model="profile.consumption.period">
          <option value="140">140 µs</option>
          <option value="204">204 µs</option>
          <option value="332">332 µs</option>
          <option value="588">588 µs</option>
          <option value="1100">1100 µs</option>
          <option value="2116">2116 µs</option>
          <option value="4156">4156 µs</option>
          <option value="8244">8244 µs</option>
        </select>
      </div>
      <div class="form-group">
        <label class="mr-3 my-label">Average</label>
        <select class="custom-select" v-model="profile.consumption.average" placeholder="avg">
          <option value="1">1</option>
          <option value="4">4</option>
          <option value="16">16</option>
          <option value="64">64</option>
          <option value="256">256</option>
          <option value="128">428</option>
          <option value="512">512</option>
          <option value="1024">1024</option>
        </select>
      </div>
    </fieldset>
    <fieldset class="card bg-light pt-2 mb-3" v-if="showRadio">
      <div class="form-group">
        <label class="mr-3 my-label"><i class="fa fa-wifi"></i></label>
        <label class="custom-control custom-radio">
          <input name="radio-mode" type="radio" class="custom-control-input" v-model="profile.radio.mode" value="rssi">
          <span class="custom-control-indicator"></span>
          <span class="custom-control-description">rssi</span>
        </label>
        <label class="custom-control custom-radio">
          <input name="radio-mode" type="radio" class="custom-control-input" v-model="profile.radio.mode" value="sniffer">
          <span class="custom-control-indicator"></span>
          <span class="custom-control-description">sniffer</span>
        </label>
      </div>
      <div class="form-group">
        <label class="mr-3 my-label">Channels</label>
        <multiselect v-model="profile.radio.channels"
          :placeholder="`select ${profile.radio.mode === 'rssi' ? 'channels' : 'channel'}`"
          :options="[11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26]"
          :allow-empty="true"
          :searchable="true"
          :show-labels="false"
          :close-on-select="profile.radio.mode === 'sniffer'"
          :multiple="profile.radio.mode === 'rssi'"
          :class="{'mymultiselect': true, 'invalid': profile.radio.channels.length === 0}"
          style="max-width: 300px; display: inline-block; z-index: 3">
        </multiselect>
        <!-- <div class="invalid-feedback" :style="{'display': (dirty.category && !profile.category) ? 'block': 'none'}">
          The user category field is required.
        </div> -->
      </div>
      <div class="form-group" v-if="profile.radio.mode === 'rssi'">
        <label class="mr-3 my-label">Period</label>
        <div class="input-group" style="display: inline-flex; max-width: 300px;">
          <input v-model="profile.radio.period" type="number" class="form-control" min="1" max="65535" step="1">
          <span class="input-group-addon">ms</span>
        </div>
      </div>
      <div class="form-group" v-if="profile.radio.mode === 'rssi' && profile.radio.channels && profile.radio.channels.length > 1">
        <label class="mr-3 my-label">Measures</label>
        <div class="input-group" style="display: inline-flex; max-width: 300px;">
          <input v-model="profile.radio.num_per_channel" type="range" max="255" min="1" step="1">
          <span class="xinput-group-addon">{{profile.radio.num_per_channel}} per channel</span>
        </div>
      </div>
    </fieldset>
    <div class="form-group">
      <!-- <button class="btn" type="submit">Back</button> -->
      <button class="btn btn-success" type="submit">Save profile</button>
    </div>

    <!-- <div class="form-group">
      <label class="form-control-label">Architecture</label>
      <input placeholder="Profile name" v-model="profile.profilename" name="profilename"
        class="form-control" type="text" v-validate="'required'"
        :class="{'is-invalid': errors.has('profilename') }">
      <div class="invalid-feedback" v-show="errors.has('profilename')">
        {{ errors.first('profilename') }}
      </div>
    </div> -->

  </form>
</template>

<script>
import Multiselect from 'vue-multiselect'
import { iotlab } from '@/rest'
// import { Validator } from 'vee-validate'

export default {
  name: 'MonitoringForm',
  components: { Multiselect },

  props: {
    monitoringProfile: {
      type: Object,
      default: () => {},
    },
  },

  data () {
    return {
      showConso: false,
      showRadio: false,
      profile: {
        profilename: '',
        power: 'dc',
        consumption: {
          current: false,
          voltage: false,
          power: false,
          period: 8244,
          average: 4,
        },
        radio: {
          mode: 'rssi',
          channels: [11],
          period: 1000,
          num_per_channel: 1,
        },
      },
    }
  },

  mounted () {
    if (!this.monitoringProfile.profilename) {
      this.$refs.name.focus()
    }
  },

  watch: {
    monitoringProfile: function (profile) {
      this.profile = Object.assign({}, this.profile, profile)
      this.showConso = this.profileHasConsumption(profile)
      this.showRadio = profile.radio !== undefined
      this.$refs.name.blur()
    },
  },

  computed: {
    nameValidation () {
      return this.profile.profilename.match(/^[0-9A-Za-z_]*$/)
    },
  },

  methods: {
    async validate () {
      // return this.$validator.validateAll().then((validated) => {
      //   if (!validated || !this.profile.country || !this.profile.category) {
      //     this.dirty.category = this.dirty.country = true
      //     console.log('User form is not valid.')
      //     return false
      //   }
      //   return true
      // })
    },

    profileHasConsumption (profile) {
      if (!profile.consumption) return false
      return profile.consumption.current || profile.consumption.voltage || profile.consumption.power
    },
    async updateProfile () {
      let profile = Object.assign({}, this.profile)
      if (profile.radio.mode === 'sniffer') {
        delete profile.radio.period
        delete profile.radio.num_per_channel
        if (typeof profile.radio.channels === 'object') profile.radio.channels = [profile.radio.channels[0]]
        if (typeof profile.radio.channels === 'number') profile.radio.channels = [profile.radio.channels]
      } else {
        if (profile.radio.channels.length < 2) profile.radio.num_per_channel = 0
      }
      if (!this.showConso) delete profile.consumption
      if (!this.showRadio) delete profile.radio

      if (!profile.profilename) {
        this.$notify({text: 'Name is empty', type: 'warning'})
        this.$refs.name.focus()
        return
      }
      if (!profile.nodearch) {
        this.$notify({text: 'Select Architecture first', type: 'warning'})
        return
      }
      if (!this.profileHasConsumption(profile) && !this.showRadio) {
        // this.$notify({text: `${JSON.stringify(profile)}`, type: 'info', duration: 10000})
        this.$notify({text: 'Select something to monitor', type: 'warning'})
        console.log(profile)
        return
      }

      try {
        if (this.monitoringProfile.profilename) {
          // modify existing profile
          await iotlab.updateMonitoringProfile(this.monitoringProfile.profilename, profile)
        } else {
          // create new profile
          await iotlab.createMonitoringProfile(profile)
        }
        this.$notify({text: `Profile ${profile.profilename} saved`, type: 'success'})
        this.$router.push({name: 'listMonitoring'})
      } catch (err) {
        this.$notify({text: err.response.data.message, type: 'error'})
        // this.$notify({text: `${JSON.stringify(profile)}`, type: 'info', duration: 10000})
        console.log(profile)
      }
    },
  },
}
</script>

<style scoped>

</style>
