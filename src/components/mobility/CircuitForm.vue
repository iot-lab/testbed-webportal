<template>
  <form @submit.prevent="updateMobilityCircuit" class="mt-3">
  <div class="form-group row">
    <div class="col-6">
      <label>Name</label>
      <input v-model="circuitForm.name"
             class="form-control"
             type="text"
             name="name"
             placeholder="Circuit name"
             ref="circuitName"
             :readOnly="readOnly"
             :class="{'is-invalid': !nameValidation}">
      <div class="invalid-feedback">
        Invalid name. Only alphanumeric characters allowed [0-9A-Za-z_]
      </div>
    </div>
    <div class="col-6">
      <label>Site</label>
      <multiselect v-model="circuitForm.site"
                   v-if="!readOnly"
                   placeholder="Site"
                   :options="sites"
                   :allow-empty="false"
                   :searchable="false"
                   :show-labels="false">
      </multiselect>
      <input v-else
             v-model="circuitForm.site"
             class="form-control"
             type="text"
             :readOnly="readOnly">
    </div>
  </div>
    <div v-if="circuitForm.site">
    <label class="custom-control custom-checkbox">
      <input v-model="circuitForm.loop" type="checkbox" :readOnly="readOnly" class="custom-control-input">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Loop</span>
    </label>
    <robot-map-view
      v-if="circuitForm.site"
      :site="circuitForm.site"
      :loop="circuitForm.loop"
      :points="circuitForm.points"
      :readOnly="readOnly"
      :mode="`circuit`"
      :coordinates="circuitForm.coordinates"
      :selected="{index: selected.index, name: selected.name, x: selected.x, y:selected.y, theta:selected.theta}"
      @setCoordinate="(ptName, pos) => setCoordinate(ptName, pos)"
      @addPoint="(ptName) => circuitForm.points.push(ptName)"
      @selectPoint="selectPoint"
      @deselectPoint="deselectPoint"/>
    <div v-if="!readOnly && this.selected.name">
      <div class="form-group">
        <table class="table table-sm">
          <thead>
          <tr>
            <th>Point name</th>
            <th>X</th>
            <th>Y</th>
            <th colspan="2">Direction (°)</th>
          </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <select class="form-control" v-model.lazy="selected.name">
                  <option v-for="c in Object.keys(circuitForm.coordinates)" :key="c">{{c}}</option>
                </select>
              </td>
              <td><input class="form-control form-control-sm" type="text" v-model="selected.x" :readOnly="readOnly"></td>
              <td><input class="form-control form-control-sm" type="text" v-model="selected.y" :readOnly="readOnly"></td>
              <td>
                <angle-picker :value="selected.theta" :angles="[previousPointAngle, nextPointAngle]" @input="value => { selected.theta = value }"/>
              </td>
              <td>
                <angle-input :value="selected.theta" @input="value => { selected.theta = value }"/>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div>
        <i class="btn btn-success fa fa-check-circle float-right" v-tooltip.right="'Modify the point'" @click="submitModifyPoint"></i>
        <i class="btn btn-info fa fa-window-close float-right" v-tooltip.right="'Close'" @click="deselectPoint"></i>
        <i class="btn btn-danger fa fa-trash float-right" v-tooltip.right="'Delete'" @click="deletePoint"></i>
      </div>
    </div>
    <div class="btn btn-sm btn-secondary" v-on:click="showDetails=!showDetails">{{showDetails ? 'Hide ' : 'Show '}} Details</div>
    <div v-if="showDetails">
    <div class="form-group">
      <h3 class="text-muted">Points coordinates</h3>
      <table class="table table-sm">
        <thead>
        <tr>
          <th>Point name</th>
          <th>X</th>
          <th>Y</th>
          <th colspan="2">Direction (°)</th>
          <th v-if="!readOnly"></th>
        </tr>
        </thead>
        <tbody>
          <tr v-for="(coordinate, coordinate_name) in circuitForm.coordinates" :key="coordinate_name">
            <td><input class="form-control form-control-sm" type="text" :value="coordinate_name" :readOnly="readOnly" @change="changeCoordinateName(coordinate_name, coordinate, $event.target.value)"></td>
            <td><input class="form-control form-control-sm" type="text" v-model="coordinate.x" :readOnly="readOnly"></td>
            <td><input class="form-control form-control-sm" type="text" v-model="coordinate.y" :readOnly="readOnly"></td>
            <td>
              <angle-picker :value="coordinate.theta" :readOnly="readOnly" @input="value => { coordinate.theta = value }"/>
            </td>
            <td>
              <angle-input :value="coordinate.theta" :readOnly="readOnly" @input="value => { coordinate.theta = value }"/>
            </td>
            <td v-if="!readOnly">
              <div class="btn-group">
                <i class="btn btn-outline-danger fa fa-trash" v-tooltip:top="'Delete'" @click="hideTooltip(); removeCoordinate(coordinate_name)"></i>
              </div>
            </td>
          </tr>
          <tr v-if="circuitForm.coordinates && circuitForm.coordinates !== {} && !readOnly">
            <td colspan="4"></td>
            <td class="text-right"><i class="btn btn-outline-success fa fa-plus" @click="addCoordinate()"></i></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="form-group">
      <h3 class="text-muted">Points ordering</h3>
      <table class="table table-sm">
        <thead>
        <tr>
          <th>Point name</th>
          <th v-if="!readOnly"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(_, index) in circuitForm.points" :key="index">
          <td><input class="form-control form-control-sm" type="text" v-model="circuitForm.points[index]" :readOnly="readOnly"></td>
          <td class="text-right" v-if="!readOnly">
            <div class="btn-group">
              <i class="btn btn-outline-success fa fa-arrow-up" v-tooltip:top="'Move Up'" :class="{disabled: index === 0}" @click="swap(index - 1, index)"></i>
              <i class="btn btn-outline-success fa fa-plus" v-tooltip:top="'Add before'" @click="addPoint(index)"></i>
              <i class="btn btn-outline-danger fa fa-trash" v-tooltip:top="'Delete'" @click="hideTooltip(); removePoint(index)"></i>
              <i class="btn btn-outline-success fa fa-plus" v-tooltip:top="'Add after'" @click="addPoint(index+1)"></i>
              <i class="btn btn-outline-success fa fa-arrow-down" v-tooltip:top="'Move Down'" :class="{disabled: index === circuitForm.points.length - 1}" @click="swap(index, index + 1)"></i>
            </div>
          </td>
        </tr>
        <tr v-if="!readOnly && circuitForm.points && circuitForm.points.length ===0">
          <td></td>
          <td class="text-right"><i class="btn btn-outline-success fa fa-plus" @click="addPoint(0)"></i></td>
        </tr>
        </tbody>
      </table>
    </div>
    </div>
    <div class="form-group" v-if="!readOnly">
      <button class="btn btn-success" type="submit">Save</button>
    </div>
    </div>
  </form>
</template>

<script>
import Multiselect from 'vue-multiselect'
import { iotlab } from '@/rest'
import AngleInput from '@/components/mobility/AngleInput'
import AnglePicker from '@/components/mobility/AnglePicker'
import RobotMapView from '@/components/mobility/RobotMapView'
import { nextInArray, previousInArray } from '@/utils'
import $ from 'jquery'
import Vue from 'vue'

// import { Validator } from 'vee-validate'

export default {
  name: 'CircuitForm',
  components: {Multiselect, RobotMapView, AngleInput, AnglePicker},

  props: {
    mobilityCircuit: {
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
      sites: [],
      circuitForm: {
        points: [],
        loop: true,
        coordinates: {},
      },
      showDetails: false,
      selected: {},
    }
  },

  watch: {
    mobilityCircuit: function (circuit) {
      this.circuitForm = Object.assign({}, this.circuitForm, this.mobilityCircuit)
      this.$refs.circuitName.blur()
    },
  },

  async created () {
    iotlab.getSitesDetails().then(data => {
      this.sites = data.filter(site => {
        return site.archis.some(archi => archi.mobile > 0)
      }).map(site => site.site).sort()
    })
  },

  computed: {
    nameValidation () {
      if (this.circuitForm.name) {
        return this.circuitForm.name.match(/^[0-9A-Za-z_]*$/)
      } else {
        return true
      }
    },
    previousPointAngle () {
      let previousPoint = this.circuitForm.coordinates[previousInArray(this.circuitForm.points, this.selected.index, this.circuitForm.loop)]
      let thisPoint = this.circuitForm.coordinates[this.selected.name]
      if (previousPoint) {
        return {
          value: 180 + (180 / Math.PI) * Math.atan2(previousPoint.y - thisPoint.y, previousPoint.x - thisPoint.x),
        }
      }
    },
    nextPointAngle () {
      let nextPoint = this.circuitForm.coordinates[nextInArray(this.circuitForm.points, this.selected.index, this.circuitForm.loop)]
      let thisPoint = this.circuitForm.coordinates[this.selected.name]
      if (nextPoint && thisPoint) {
        return {
          value: (180 / Math.PI) * Math.atan2(nextPoint.y - thisPoint.y, nextPoint.x - thisPoint.x),
        }
      }
    },
  },

  methods: {
    deletePoint () {
      this.hideTooltip()
      if (this.selected.name) {
        this.removePoint(this.selected.index)
        this.deselectPoint()
      }
    },
    submitModifyPoint () {
      this.hideTooltip()
      this.setCoordinate(this.selected.name, {
        x: this.selected.x,
        y: this.selected.y,
        theta: this.selected.theta,
      })
      this.setPoint(this.selected.index, this.selected.name)
      this.deselectPoint()
    },
    selectPoint (select) {
      select.theta = this.circuitForm.coordinates[select.name].theta
      select.x = this.circuitForm.coordinates[select.name].x
      select.y = this.circuitForm.coordinates[select.name].y

      this.selected = select
    },
    deselectPoint () {
      this.hideTooltip()
      this.selected = {}
    },
    setCoordinate (coordinateName, coordinate) {
      Vue.set(this.circuitForm.coordinates, coordinateName, coordinate)
      if (this.selected.name === coordinateName) {
        this.selectPoint({index: this.selected.index, name: coordinateName})
      }
    },
    changeCoordinateName (coordinateName, coordinate, newCoordinateName) {
      Vue.set(this.circuitForm.coordinates, newCoordinateName, coordinate)
      Vue.delete(this.circuitForm.coordinates, coordinateName)
    },
    hideTooltip () {
      $('.tooltip[role=tooltip]').remove()
    },
    addCoordinate () {
      Vue.set(this.circuitForm.coordinates, '', { theta: 0, degree: 0 })
    },
    swap (i, j) {
      if (i > 0 && j < this.circuitForm.points.length) {
        let tmp = this.circuitForm.points[j]
        Vue.set(this.circuitForm.points, j, this.circuitForm.points[i])
        Vue.set(this.circuitForm.points, i, tmp)
      }
    },
    addPoint (index) {
      this.circuitForm.points.splice(index, 0, '')
    },
    setPoint (index, value) {
      Vue.set(this.circuitForm.points, index, value)
    },
    removeCoordinate (coordinateName) {
      Vue.delete(this.circuitForm.coordinates, coordinateName)
      this.circuitForm.points = this.circuitForm.points.filter(p => p !== coordinateName)
    },
    removePoint (index) {
      this.circuitForm.points.splice(index, 1)
    },
    async validate () {
    },
    async updateMobilityCircuit () {
      let circuit = Object.assign({}, this.circuitForm)

      if (!circuit.name) {
        this.$notify({text: 'Name is empty', type: 'warning'})
        this.$refs.name.focus()
        return
      }

      try {
        if (this.mobilityCircuit.name) {
          // modify existing circuit
          console.log('modify circuit ' + this.mobilityCircuit.name)
          console.log(circuit)
          await iotlab.updateMobilityCircuit(this.mobilityCircuit.name, circuit)
        } else {
          console.log('create circuit ' + circuit.name)
          console.log(circuit)
          // create new circuit
          await iotlab.createMobilityCircuit(circuit)
        }
        this.$notify({ text: `Circuit ${circuit.name} saved`, type: 'success' })

        this.$router.push({ name: 'listMobility' })
      } catch (err) {
        this.$notify({ text: err.response.data.message, type: 'error' })
        // this.$notify({text: `${JSON.stringify(profile)}`, type: 'info', duration: 10000})
        console.log(circuit)
      }
    },
  },
}
</script>

<style scoped>
td {
  text-align: center;
  vertical-align: middle;
}
</style>
