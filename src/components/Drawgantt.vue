<template>
  <div>
    <div class="form-group mb-2 center col-5">
      <label class="form-control-label" for="timezone">Timezone: </label>
      <multiselect v-model="timezone" placeholder="select timezone"
                :options="tzNames"
                :allow-empty="false"
                style="z-index: 10" name="timezone"
                id="timezoneSelect"/>
    </div>
    <div class="text-center time-header">
      <div>
        <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_WEEK)">&lt;1w</button>
        <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_DAY)">&lt;1d</button>
        <button class="btn mr-2" type="button" v-on:click="shift(-6 * S_PER_HOUR)">&lt;6h</button>
        <button class="btn mr-2" type="button" v-on:click="shift(-S_PER_HOUR)">&lt;1h</button>
        <button class="btn mr-2" type="button" v-on:click="prev()">&lt;&lt;</button>
        <button class="btn mr-2" type="button" v-on:click="zoomout()">-</button>
        <button class="btn mr-2" type="button" v-on:click="refresh()">refresh</button>
        <button class="btn mr-2" type="button" v-on:click="reset()">reset</button>
        <button class="btn mr-2" :aria-disabled="cant_zoom" :class="{disabled: cant_zoom}" type="button" v-on:click="zoom()">zoom</button>
        <button class="btn mr-2" type="button" v-on:click="zoomin()">+</button>
        <button class="btn mr-2" type="button" v-on:click="next()">&gt;&gt;</button>
        <button class="btn mr-2" type="button" v-on:click="shift(S_PER_HOUR)">&gt;1h</button>
        <button class="btn mr-2" type="button" v-on:click="shift(6 * S_PER_HOUR)">&gt;6h</button>
        <button class="btn mr-2" type="button" v-on:click="shift(S_PER_DAY)">&gt;1d</button>
        <button class="btn mr-2" type="button" v-on:click="shift(S_PER_WEEK)">&gt;1w</button>
      </div>
    </div>
    <gantt ref='gantt' :timezone="timezone" :nodes="nodes" :gantt_relative_window="relative_window" @set_zoom="set_zoom"/>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import { S_PER_DAY, S_PER_WEEK, S_PER_HOUR } from '@/constants'
import moment from 'moment-timezone'
import Gantt from '@/components/Gantt'

const DEFAULT_RELATIVE_WINDOW = {start: -S_PER_DAY, stop: S_PER_DAY}
const MAX_WINDOW = 50 * S_PER_WEEK

export default {
  name: 'Drawgantt',

  components: {
    Multiselect,
    Gantt,
  },

  data () {
    return {
      active: 0,
      relative_window: Object.assign({}, DEFAULT_RELATIVE_WINDOW),
      timezone: 'UTC',
      tzUser: moment.tz.guess(),
      zoom_window: {},

      S_PER_DAY: S_PER_DAY,
      S_PER_WEEK: S_PER_WEEK,
      S_PER_HOUR: S_PER_HOUR,
    }
  },

  props: {
    nodes: {
      type: Array,
    },
  },

  computed: {
    cant_zoom () {
      return Object.keys(this.zoom_window).length === 0
    },
    relative_start () {
      return this.relative_window.start
    },
    relative_stop () {
      return this.relative_window.stop
    },
    tzNames () {
      // filtered tz names
      let tzNames = moment.tz.names()
        .filter(z => z !== 'UTC' && z !== this.tzUser)
        .sort()
      tzNames.unshift(this.tzUser)
      tzNames.unshift('UTC')
      return tzNames
    },
    resource_filter () {
      return {
        site: n => this.currentSite !== 'all' ? n.site === this.currentSite.site : true,
        archi: n => this.currentArchi !== 'all' ? n.archi === this.currentArchi : true,
        node: this.nodeFilter,
      }
    },
  },

  methods: {

    set_zoom (window) {
      this.zoom_window = window
    },

    update (window) {
      if (window.stop - window.start < MAX_WINDOW) {
        this.relative_window = window
      } else {
        this.$notify({text: 'can\'t zoom out more than ', type: 'error'})
      }
    },

    refresh () {
      this.$refs.gantt.refresh()
    },

    errorHandler (type, err) {
      this.$notify({text: err.response.data.message || 'Failed to fetch ' + type, type: 'error'})
    },

    sleep (millis, callback) {
      setTimeout(() => callback(), millis)
    },

    reset () {
      this.update(DEFAULT_RELATIVE_WINDOW)
    },

    shift (time) {
      this.update({ start: this.relative_start + time, stop: this.relative_stop + time })
    },

    next () {
      var t = this.relative_stop + (this.relative_stop - this.relative_start)
      this.update({ start: this.relative_stop, stop: t })
    },

    prev () {
      var t = this.relative_start - (this.relative_stop - this.relative_start)
      this.update({ start: t, stop: this.relative_start })
    },

    zoom () {
      if (!this.cant_zoom) {
        this.update(this.zoom_window)
        this.zoom_window = {}
        this.$refs.gantt.clear_zoom()
      }
    },

    zoomin () {
      var t = this.relative_start + (this.relative_stop - this.relative_start) / 4
      this.update({ start: t, stop: this.relative_stop - (this.relative_stop - this.relative_start) / 4 })
    },

    zoomout () {
      var t = (this.relative_stop - this.relative_start) / 2
      this.update({ start: this.relative_start - t, stop: this.relative_stop + t })
    },
  },
}
</script>
<style scoped>
.time-header {
  position: sticky;
  top: 0px;
  background: white;
  z-index: 2;
  margin-bottom: 0;
  height: 40px;
}
</style>
