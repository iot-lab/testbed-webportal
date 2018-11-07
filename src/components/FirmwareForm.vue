<template>
  <form @submit.prevent="saveFirmware" class="mt-3">
    <div class="form-group">
      <label>File</label>
      <div v-if="firmware.fileName" class="mb-2">
        <a href="#" @click.prevent="download"><i class="fa fa-download"></i> {{firmware.fileName}}</a>
      </div>
      <label class="custom-file">
        <input type="file" id="file" ref="firmwareFile" class="custom-file-input" @change="uploadFirmware">
        <span class="custom-file-control">{{firmwareFile && firmwareFile.name}}</span>
      </label>
    </div>
    <div class="form-group">
      <label>Name</label>
      <input v-model="firmwareForm.name" class="form-control" type="text" name="name" placeholder="Custom name">
    </div>
    <div class="form-group">
      <label>Description <span class="text-muted">(optional)</span></label>
      <input v-model="firmwareForm.description"  class="form-control" type="text" name="description" placeholder="Description">
    </div>
    <div class="form-group">
      <label>Architecture</label>
      <multiselect v-model="firmwareForm.archi"
        placeholder="Select architecture"
        :options="archis"
        :class="{'mymultiselect': true}">
      </multiselect>
    </div>
    <div class="form-group">
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
        downloadAsFile(this.firmware.fileName, await iotlab.getFirmwareFile(this.firmware.name))
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

          var res = await iotlab.checkFirmware(e.target.result)
          vm.$notify({text: `firmware format ${res.format}`, type: res.format === 'unknown' ? 'error' : 'info'})
          file.bin = e.target.result
          vm.firmwareFile = file
          vm.firmwareForm.fileName = file.name
          if (!vm.firmwareForm.name) {
            vm.firmwareForm.name = file.name
            vm.$forceUpdate()
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
          await iotlab.createFirmware(this.firmwareForm, this.firmwareFile.bin)
        }
        this.$notify({text: `firmware ${this.firmwareForm.name} saved`, type: 'success'})
        this.$router.push({name: 'listFirmware'})
      } catch (err) {
        this.$notify({text: err.response.data.message || 'An error occured', type: 'error'})
      }
    },
  },
}
</script>

<style scoped>

</style>
