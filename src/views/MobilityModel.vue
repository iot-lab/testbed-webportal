<template>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <button v-if="name" v-show="!readOnly" class="btn btn-sm btn-outline-danger float-right" type="button" @click="deleteModel">Delete</button>
      <h5 v-if="name && !readOnly"><i class="fa fa-pencil"></i> Edit mobility model</h5>
      <h5 v-else-if="name && readOnly"><i class="fa fa-microchip"></i> View mobility model</h5>
      <h5 v-else><i class="fa fa-pencil"></i> New mobility model</h5>
      <model-form :read-only="readOnly"
                  :mobility-model-file="modelFile"
                  :mobility-model="model" :name="name"></model-form>
    </div>
  </div>
</div> <!-- container -->

</template>

<script>
import ModelForm from '@/components/mobility/ModelForm'
import { iotlab } from '@/rest'

export default {
  name: 'MobilityModel',
  components: {
    ModelForm,
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
      model: {},
      modelFile: '',
      readOnly: true,
    }
  },

  async created () {
    if (this.name) {
      this.model = await iotlab.getMobilityModel(this.name)
      this.readOnly = this.model.type !== 'userdefined'
      this.modelFile = await iotlab.getMobilityModelFile(this.name)
    } else {
      this.readOnly = false
    }
  },

  methods: {
    async deleteModel () {
      if (confirm(`Delete model ${this.name}?`)) {
        await iotlab.deleteMobilityModel(this.name)
          .then(_ => {
            this.$notify({text: `Model ${this.name} deleted`, type: 'success'})
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
