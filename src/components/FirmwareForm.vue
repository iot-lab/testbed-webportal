<template>
  <form @submit.prevent="saveFirmware" class="mt-3">
    <div class="form-group">
      <label>File</label>
      <div v-if="firmware.filename" class="mb-2">
        <a href="#" @click.prevent="download"><i class="fa fa-download"></i> {{firmware.filename}}</a>
      </div>
      <div class="custom-file" v-if="!readOnly">
        <input type="file" id="file" ref="firmwareFile" class="custom-file-input" @change="uploadFirmware">
        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
      </div>
    </div>
    <div class="form-group">
      <label>Identifier</label>
      <input v-model="firmwareForm.name" v-validate="'required|alpha_dash'" class="form-control" type="text" name="name" placeholder="Unique identifier name" :class="{'is-invalid': errors.has('name') }" :disabled="readOnly">
      <div class="invalid-feedback" v-show="errors.has('name')">
        {{ errors.first('name') }}
      </div>
    </div>
    <div class="form-group">
      <label>Description <span class="text-muted">(optional)</span></label>
      <textarea v-model="firmwareForm.description" class="form-control" rows="3" name="description" placeholder="Description" :disabled="readOnly"></textarea>
    </div>
    <div class="form-group">
      <label>Operating system <span class="text-muted">(optional)</span></label>
      <input v-model="firmwareForm.os" class="form-control" type="text" name="os" placeholder="OS" :disabled="readOnly">
    </div>
    <div class="form-group">
      <label>Architecture <span class="text-muted">(optional)</span></label>
      <multiselect v-model="firmwareForm.archi"
        :disabled="readOnly"
        placeholder="Select architecture"
        :options="archis"
        :class="{'mymultiselect': true}">
      </multiselect>
    </div>
    <div class="form-group" v-if="!readOnly">
      <button class="btn btn-success" type="submit">Save</button>
    </div>
  </form>
</template>

<script>
import Multiselect from 'vue-multiselect'
import { downloadAsFile } from '@/utils'
import { extractArchi } from '@/assets/js/iotlab-utils'
import { iotlab } from '@/rest'

export default {
  name: 'FirmwareForm',
  components: { Multiselect },

  props: {
    firmware: {
      type: Object,
      default: () => {},
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
  },

  data () {
    return {
      firmwareForm: {},
      firmwareFile: undefined,
      archis: [],
    }
  },

  async created () {
    try {
      this.archis = Array.from((await iotlab.getSitesDetails()).reduce((acc, site) => {
        site.archis.map(archi => acc.add(extractArchi(archi.archi)))
        return acc
      }, new Set()))
        .sort((a, b) => a.localeCompare(b))
    } catch (err) {
      this.$notify({text: err.response.data.message || 'Failed to fetch archis', type: 'error'})
    }
  },

  mounted () {
    if (!this.firmware.name) {
      this.$refs.firmwareFile.focus()
    }
  },

  watch: {
    firmware: function (firmware) {
      this.firmwareForm = Object.assign({}, this.firmwareForm, this.firmware)
      this.$refs.firmwareFile.blur()
    },
  },

  methods: {
    async download () {
      try {
        downloadAsFile(this.firmware.filename, await iotlab.getFirmwareFile(this.firmware.name))
      } catch (err) {
        this.$notify({text: err.response.data.message || 'Failed to download file', type: 'error'})
      }
    },
    uploadFirmware () {
      this.$notify({text: 'Uploading file...', type: 'info', duration: -1})

      this.firmwareFile = this.$refs.firmwareFile.files[0]
      var reader = new FileReader()

      reader.onload = (function (file, vm) {
        return async function (e) {
          vm.$notify({clean: true}) // close pending notification
          file.bin = e.target.result
          vm.firmwareFile = file
          vm.firmwareForm.filename = file.name
          if (/^[0-9A-Za-z_-]+(\.[0-9A-Za-z_-]+)?$/.test(file.name)) {
            vm.$notify({text: 'File uploaded', type: 'info'})
            if (!vm.firmwareForm.name) {
              vm.firmwareForm.name = file.name.replace(/\.[^.$]+$/, '')
              vm.$forceUpdate()
            }
          } else {
            vm.$notify({text: `Invalid file name format. Only alphanumeric characters allowed [0-9A-Za-z_]`, type: 'error'})
          }
        }
      })(this.firmwareFile, this)

      reader.readAsDataURL(this.firmwareFile)
    },
    async saveFirmware () {
      try {
        if (this.firmware.name) {
          // update existing firmware
          if (this.firmwareFile && this.firmwareFile.name) {
            this.$notify({ text: 'Updating firmware...', type: 'info', duration: -1 })
            await iotlab.updateFirmware(this.firmware.name, this.firmwareForm, this.firmwareFile.bin)
          } else {
            await iotlab.updateFirmware(this.firmware.name, this.firmwareForm)
          }
        } else {
          // create new firmware
          if (!this.firmwareFile || !this.firmwareFile.name) {
            this.$notify({text: 'Select firmware file first', type: 'warning'})
            return
          }
          this.$notify({ text: 'Creating firmware...', type: 'info', duration: -1 })
          await iotlab.createFirmware(this.firmwareForm, this.firmwareFile.bin)
        }
        this.$notify({clean: true})
        this.$notify({text: `firmware ${this.firmwareForm.name} saved`, type: 'success'})
        this.$router.push({name: 'listFirmware'})
      } catch (err) {
        this.$notify({clean: true})
        this.$notify({text: err.response.data.message || 'An error occured', type: 'error'})
      }
    },
  },
}
</script>

<style scoped>

</style>
