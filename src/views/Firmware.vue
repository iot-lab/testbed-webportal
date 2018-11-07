<template>
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <button v-if="name" class="btn btn-sm btn-outline-danger float-right" type="button" @click="deleteFirmware">Delete</button>
      <h5 v-if="name"><i class="fa fa-pencil"></i> Edit firmware</h5>
      <h5 v-else><i class="fa fa-pencil"></i> New firmware</h5>
      <firmware-form :firmware="firmware"></firmware-form>
    </div>
  </div>
</div> <!-- container -->

</template>

<script>
import FirmwareForm from '@/components/FirmwareForm'
import { iotlab } from '@/rest'

export default {
  name: 'Firmware',
  components: { FirmwareForm },
  props: {
    name: {
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      firmware: {},
    }
  },

  async created () {
    if (this.name) {
      this.firmware = await iotlab.getFirmware(this.name)
    }
  },

  methods: {
    async deleteFirmware () {
      if (confirm(`Delete firmware ${this.name}?`)) {
        await iotlab.deleteFirmware(this.name)
          .then(_ => {
            this.$notify({text: `Firmware ${this.name} deleted`, type: 'success'})
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
