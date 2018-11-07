<template>
<div class="container">
  <div class="row">
    <div class="col-md-7">
      <button v-if="name" class="btn btn-sm btn-outline-danger float-right" type="button" @click="deleteProfile">Delete</button>
      <h5 v-if="name"><i class="fa fa-pencil"></i> Edit monitoring profile</h5>
      <h5 v-else><i class="fa fa-pencil"></i> New monitoring profile</h5>
      <monitoring-form :monitoring-profile="profile"></monitoring-form>
    </div>
  </div>
</div> <!-- container -->

</template>

<script>
import MonitoringForm from '@/components/MonitoringForm'
import { iotlab } from '@/rest'

export default {
  name: 'Monitoring',
  components: { MonitoringForm },
  props: {
    name: {
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      profile: {},
    }
  },

  async created () {
    if (this.name) {
      this.profile = await iotlab.getMonitoringProfile(this.name)
    }
  },

  methods: {
    async deleteProfile () {
      if (confirm(`Delete profile ${this.name}?`)) {
        await iotlab.deleteMonitoringProfile(this.name)
          .then(_ => {
            this.$notify({text: `Profile ${this.name} deleted`, type: 'success'})
            this.$router.push({name: 'resources'})
          })
          .catch(err => {
            this.$notify({text: err.response.data.message, type: 'error'})
          })
      }
    },
  },
}
</script>
