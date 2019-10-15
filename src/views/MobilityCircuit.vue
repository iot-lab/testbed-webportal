<template>
<div class="container">
    <div class="row">
      <div class="col-md-12">
        <button v-if="name" v-show="!readOnly" class="btn btn-sm btn-outline-danger float-right" type="button" @click="deleteCircuit">Delete</button>
        <h5 v-if="name && !readOnly"><i class="fa fa-pencil"></i> Edit mobility circuit</h5>
        <h5 v-else-if="name && readOnly"><i class="fa fa-eye"></i> View mobility circuit</h5>
        <h5 v-else><i class="fa fa-pencil"></i> New mobility circuit</h5>
        <circuit-form :read-only="readOnly" :mobility-circuit="circuit" :name="name"></circuit-form>
      </div>
    </div>
</div> <!-- container -->

</template>

<script>
import CircuitForm from '../components/mobility/CircuitForm'
import { iotlab } from '@/rest'

export default {
  name: 'MobilityCircuit',
  components: {
    CircuitForm,
  },

  props: {
    mode: {
      type: String,
      default: () => 'view',
    },
    name: {
      type: String,
      default: () => '',
    },
  },

  data () {
    return {
      circuit: {},
      readOnly: true,
    }
  },

  async created () {
    if (this.name) {
      this.circuit = await iotlab.getMobilityCircuit(this.name)
      this.readOnly = this.circuit.type !== 'userdefined'
    } else {
      this.readOnly = false
    }
  },

  methods: {
    async deleteCircuit () {
      if (confirm(`Delete circuit ${this.name}?`)) {
        await iotlab.deleteMobilityCircuit(this.name)
          .then(_ => {
            this.$notify({text: `Circuit ${this.name} deleted`, type: 'success'})
            this.$router.push({name: 'listMobility'})
          })
          .catch(err => {
            this.$notify({text: err.response.data.message, type: 'error'})
          })
      }
    },
  },
}
</script>
