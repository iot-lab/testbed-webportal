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
      <div class="form-group">
        <label>Model Script file</label>
        <input
          placeholder="file name"
          v-model="modelForm.script" class="form-control" type="text">
        <editor
          class="mt-3"
          v-model="modelFileForm.data"
          @init="editorInit"
          :options="cmOptions"
          lang="python" theme="chrome" width="600" height="500"></editor>
      </div>
      <div class="form-group">
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
      modelForm: {},
      modelFileForm: {
        data: '',
      },
      cmOptions: {
        displayIndentGuides: true,
        fontSize: '16pt',
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
  },

  methods: {
    editorInit: function () {
      require('brace/ext/language_tools')
      require('brace/mode/python')
      require('brace/theme/chrome')
    },
    async updateMobilityModel () {
      let model = Object.assign({}, this.modelForm)
      let modelFile = this.modelFileForm

      if (!model.name) {
        this.$notify({text: 'Name is empty', type: 'warning'})
        this.$refs.name.focus()
        return
      }

      try {
        if (this.mobilityModel.name) {
          // modify existing model
          console.log('modify model ' + this.mobilityModel.name)
          console.log(model)
          await iotlab.updateMobilityModel(this.mobilityModel.name, model, modelFile.data)
        } else {
          console.log('create model ' + model.name)
          console.log(model)
          // create new model
          await iotlab.createMobilityModel(model, modelFile.data)
        }
        this.$notify({ text: `model ${model.name} saved`, type: 'success' })

        this.$router.push({ name: 'listMobilityModel' })
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
