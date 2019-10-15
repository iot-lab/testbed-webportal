<template>
    <form @submit.prevent="updateMobilityModel" class="mt-3">
      <div class="form-group">
        <label>Name</label>
        <input v-model="modelForm.name"
               class="form-control"
               type="text"
               name="name"
               placeholder="Model name"
               ref="modelName"
               :readOnly="readOnly"
               :class="{'is-invalid': !nameValidation}">
        <div class="invalid-feedback">
          Invalid name. Only alphanumeric characters allowed [0-9A-Za-z_]
        </div>
      </div>
      <div class="form-group" v-if="!readOnly">
        <label>Upload a Script file</label>
        <input type="file" id="modelfile" ref="modelfile" name="modelfile" class="form-control" v-on:change="modelFileUpload">
      </div>
      <div class="form-group" v-if="!readOnly">
        <label>Model Script file</label>
        <input
          placeholder="file name"
          v-model="modelForm.script" class="form-control" type="text">
      </div>
      <div class="form-group">
        <label>Model Script content</label>
        <editor
          v-model="modelFileForm.data"
          @init="editorInit"
          :options="cmOptions"
          height="800"
          lang="python" theme="chrome"></editor>
      </div>
      <div class="form-group" v-if="!readOnly">
        <button class="btn btn-success" type="submit">Save Mobility model
        </button>
      </div>
    </form>
</template>

<script>
import { iotlab } from '@/rest'
// import { Validator } from 'vee-validate'

export default {
  name: 'ModelForm',
  components: {
    editor: require('vue2-ace-editor'),
  },

  props: {
    mobilityModel: {
      type: Object,
      default: () => {},
    },
    mobilityModelFile: {
      type: String,
      default: '',
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
  },

  data () {
    return {
      sites: [],
      modelForm: {
        script: 'script.py',
      },
      modelFileForm: {
        data: `#!/usr/bin/env python
# Here, you enter some calls to the robot API to make it move
# You can look at the script for the \`random\` model for inspiration
# the server is at robot:8081
# Content-type header should be application/json
#
# GET /robot/position
# GET /robot/is_reachable?x=<...>&y=<...>
# POST /robot/go_to_point   JSON body {x: <...>, y: <...>}`,
      },
    }
  },

  watch: {
    mobilityModel: function (model) {
      this.modelForm = Object.assign({}, this.modelForm, this.mobilityModel)
      this.$refs.modelName.blur()
    },
    mobilityModelFile: function (modelFile) {
      this.modelFileForm = { data: this.mobilityModelFile }
    },
  },

  async created () {
  },

  computed: {
    nameValidation () {
      if (this.modelForm.name) {
        return this.modelForm.name.match(/^[0-9A-Za-z_]*$/)
      } else {
        return true
      }
    },
    cmOptions () {
      return {
        displayIndentGuides: true,
        fontSize: '16pt',
        readOnly: this.readOnly,
      }
    },
  },

  methods: {
    modelFileUpload () {
      let reader = new FileReader()
      reader.onload = (function (vm) {
        return async function (e) {
          vm.modelFileForm.data = e.target.result
          console.log(e)
          vm.modelForm.script = e.target.name
        }
      })(this)
      reader.onerror = (function (vm) {
        return async function (e) {
          vm.$notify({text: 'error reading that file', type: 'error'})
        }
      })(this)
      reader.readAsText(this.$refs.modelfile.files[0])
    },
    editorInit () {
      require('brace/ext/language_tools')
      require('brace/mode/python')
      require('brace/theme/chrome')
    },
    async updateMobilityModel () {
      let model = Object.assign({}, this.modelForm)
      let modelFile = this.modelFileForm.data

      if (!model.name) {
        this.$notify({text: 'Name is empty', type: 'warning'})
        this.$refs.modelName.focus()
        return
      }

      try {
        if (this.mobilityModel.name) {
          // modify existing model
          console.log('modify model ' + this.mobilityModel.name)
          console.log(model)
          console.log(modelFile)
          await iotlab.updateMobilityModel(this.mobilityModel.name, model, modelFile)
        } else {
          console.log('create model ' + model.name)
          console.log(model)
          // create new model
          await iotlab.createMobilityModel(model, modelFile)
        }
        this.$notify({ text: `model ${model.name} saved`, type: 'success' })

        this.$router.push({ name: 'listMobility' })
      } catch (err) {
        this.$notify({ text: err.response.data.message, type: 'error' })
        // this.$notify({text: `${JSON.stringify(profile)}`, type: 'info', duration: 10000})
        console.log(model)
      }
    },
  },
}
</script>

<style scoped>
.table tbody > tr > td.vert-aligned {
  vertical-align: middle;
}
</style>
