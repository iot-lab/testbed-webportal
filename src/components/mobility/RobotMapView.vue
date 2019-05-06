<template>
  <div>
    <svg xmlns='http://www.w3.org/2000/svg' class="map" v-if="siteLoaded"
       :viewBox="`0 0 ${svgWidth} ${svgHeight}`"  style="border: 1px solid black;">
      <svg id='svgMap' :width="mapWidth" :height="mapHeight" :viewBox="`0 0 ${realWidth} ${realHeight}`" :x="this.xAxisMargin" y="0">
        <defs>
          <g id="middlePoint">
            <path transform="scale(0.2)" d="M -1,-1 L -1,1 L 1,0 L -1,-1" fill="#000000" stroke="none"></path>
          </g>
          <image width="1" height="1" x="-0.5" y="-0.5" :xlink:href="turtlebot2ImagePath()" id="turtlebot"/>
        </defs>
        <rect id="map" :width="realWidth" :height="realHeight" x="0" y="0" fill="none" stroke="#000000" stroke-width="0.2"/>
        <image :width="realWidth" :height="realHeight" x="0" y="0" :xlink:href="site_image" v-on="!readOnly ? {'mousedown': mouseDown} : {}"/>
        <g v-for="node in nodes" :key="node.network_address" v-if="showNodesOverlay"
           :transform="`translate(${toSvg(node).x}, ${toSvg(node).y}) `">
          <circle r="0.2" fill="#20539d55" v-tooltip:auto=""
                  v-adv-tooltip="{
                    title: node.name,
                    placement: 'auto',
                    trigger: 'hover',
                    html: true,
                    delay: 400,
                    }"
                  v-on="!readOnly ? {'mousedown': (e) => mouseDown(e, {x: node.x, y: node.y})} : {}"/>
        </g>
        <path class="robottraj" :d="pathString"></path>
        <use v-for="(coord, index) in middle_points" :key="`m-${index}`"
            xlink:href="#middlePoint"
            :transform="`translate(${coord.x}, ${coord.y}) rotate(${180 * coord.theta / Math.PI})`"/>
        <g v-for="(point, index) in valid_points" :key="`p-${index}`"
          :transform="`translate(${toSvg(coordinate(point)).x}, ${toSvg(coordinate(point)).y}) `"
          v-on="!readOnly ? {'mousedown': (e) => robotMouseDown(e, index, point) } : {}"
          @touchstart.prevent="(e) => robotMouseDown(e, index, point)"
          >
          <use xlink:href="#turtlebot" :transform="`rotate(${- 180 * coordinate(point).theta / Math.PI})`"/>
          <circle r="0.3" fill="#00FF00DD" v-if="selectedIndex === index ? 'active' : ''"></circle>
          <circle r="0.25" fill="#FF000055" v-if="index === 0"></circle>
          <text class="label" text-anchor="left" font-size="0.5">{{point}}</text>
        </g>
      </svg>
      <g :transform="`translate(${xAxisMargin}, ${mapHeight})`">
        <path stroke-width="0.2" stroke="#000000" class="axis" :d="`M 0,${-mapHeight} L 0,0 L ${mapWidth},0`"></path>
        <g v-for="y in stepArray(0, this.mapHeight / 10, 10)" :key="`Y-${y}`">
          <path class="axis" :d="`M 0,${-y} L ${ -xTickHeight},${-y}`"/>
          <text
              text-anchor="end" dominant-baseline="middle" alignment-baseline="center"
              :x=" -1.5 * xTickHeight"
              :y="-y">{{ toReal({x: 0, y: mapHeight - y}).y.toFixed(1) }}
          </text>
        </g>
        <g v-for="x in stepArray(0, this.mapWidth / 10, 10)" :key="`X-${x}`">
          <path stroke-width="0.2" stroke="#000000" class="axis" :d="`M ${x},0 L ${x},${yTickHeight}`"/>
          <text
              text-anchor="middle" dominant-baseline="middle" alignment-baseline="center"
              :x="x"
              :y="1.5 * yTickHeight">{{ toReal({x: x, y: 0}).x.toFixed(1)}}
          </text>
        </g>
      </g>
      <text font-size="8" x="0" :y="svgHeight - 2" @click="showNodesOverlay = !showNodesOverlay">Nodes {{ showNodesOverlay ? "&#9745;" : "&#9744;" }}</text>
    </svg>
    <div v-if="!readOnly && this.selectedPoint">
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
                <select class="form-control" v-model.lazy="currentPointName">
                  <option v-for="c in Object.keys(coordinates)" :key="c">{{c}}</option>
                </select>
              </td>
              <td><input class="form-control form-control-sm" type="text" v-model="currentPointX" :readOnly="readOnly"></td>
              <td><input class="form-control form-control-sm" type="text" v-model="currentPointY" :readOnly="readOnly"></td>
              <td>
                <angle-picker :value="currentPointTheta" :angles="[previousPointAngle, nextPointAngle]" @input="value => { currentPointTheta = value }"/>
              </td>
              <td>
                <angle-input :value="currentPointTheta" @input="value => { currentPointTheta = value }"/>
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
  </div>

</template>
<script>
import { iotlab } from '@/rest'
import { nextString } from '@/utils'
import AngleInput from '@/components/mobility/AngleInput'
import AnglePicker from '@/components/mobility/AnglePicker'
import $ from 'jquery'

export default {
  name: 'RobotMapView',
  components: {
    AnglePicker,
    AngleInput,
  },
  props: {
    site: {
      type: String,
      required: true,
    },
    loop: {
      type: Boolean,
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
    points: {
      type: Array,
      required: true,
    },
    coordinates: {
      type: Object,
      required: true,
    },
  },

  computed: {
    previousPointAngle () {
      let previousPoint = this.coordinates[this.previousPoint(this.selectedIndex)]
      let thisPoint = this.coordinates[this.selectedPoint]
      if (previousPoint) {
        return {
          value: 180 + (180 / Math.PI) * Math.atan2(previousPoint.y - thisPoint.y, previousPoint.x - thisPoint.x),
        }
      }
    },
    nextPointAngle () {
      let nextPoint = this.coordinates[this.nextPoint(this.selectedIndex)]
      let thisPoint = this.coordinates[this.selectedPoint]
      if (nextPoint && thisPoint) {
        return {
          value: (180 / Math.PI) * Math.atan2(nextPoint.y - thisPoint.y, nextPoint.x - thisPoint.x),
        }
      }
    },
    currentPointDegree () {
      return Math.round((180 / Math.PI) * (1e4 * this.currentPointTheta)) / 1e4
    },
    valid_points () {
      return this.points.filter(p => this.coordinate(p) !== undefined)
    },
    middle_points () {
      let middlePoints = []
      let pointsLength = this.points.length
      for (var i = 0; i < pointsLength; i++) {
        var next = this.nextPoint(i)
        if (!next) break
        let pointCoord = this.toSvg(this.coordinates[this.points[i]])
        let nextCoord = this.toSvg(this.coordinates[next])
        middlePoints.push({
          x: (nextCoord.x + pointCoord.x) / 2,
          y: (nextCoord.y + pointCoord.y) / 2,
          theta: Math.atan2(
            nextCoord.y - pointCoord.y,
            nextCoord.x - pointCoord.x
          ),
        })
      }
      return middlePoints
    },
    yTickHeight () {
      return this.yAxisMargin / 3
    },
    xTickHeight () {
      return this.xAxisMargin / 3
    },
    pathString () {
      let path = ''
      if (!this.points || !this.coordinates) {
        return path
      }
      if (this.points.length === 0) {
        return path
      }

      if (this.points.length >= 1) {
        let firstPoint = this.points[0]
        let firstCoordinate = this.toSvg(this.coordinates[firstPoint])
        if (firstCoordinate) {
          path += `M ${firstCoordinate.x},${firstCoordinate.y} `
        }
        for (let point of this.points) {
          let coordinate = this.toSvg(this.coordinates[point])
          if (coordinate) {
            path += `L ${coordinate.x},${coordinate.y} `
          }
        }
        if (this.loop && firstCoordinate) {
          path += `L ${firstCoordinate.x},${firstCoordinate.y} `
        }
      }

      return path
    },
  },

  data: function () {
    return {
      site_image: '',
      site_image_dimensions: {x: null, y: null},
      map_pos: {left: 20, top: 20},
      mapWidth: 100,
      svgWidth: 100,
      mapHeight: 100,
      svgHeight: 100,
      realWidth: 100,
      realHeight: 100,
      realOrigin: {x: 0, y: 0},
      mapConfig: {},
      showTooltip: false,
      showNodesOverlay: false,
      currentPointName: undefined,
      currentPointTheta: undefined,
      currentPointX: undefined,
      currentPointY: undefined,
      selectedIndex: undefined,
      selectedPoint: undefined,
      nodes: [],
      xAxisMargin: 70, // 30 pixels between map and svg
      yAxisMargin: 30, // 30 pixels between map and svg
      config: {},
      tooltip: {},
      xy: {},
      circle_diameter: { x: 20, y: 20 },
      siteLoaded: false,
    }
  },

  mounted () {
    this.updateSite(this.site)
  },

  methods: {
    nextPoint (i) {
      if (i === this.points.length - 1) {
        if (this.loop) {
          return this.points[0]
        }
      } else {
        return this.points[i + 1]
      }
    },
    previousPoint (i) {
      if (i === 0) {
        if (this.loop) {
          return this.points[this.points.length - 1]
        }
      } else {
        return this.points[i - 1]
      }
    },
    stepArray (start, step, n) {
      let values = []
      let value = start
      for (let i = 0; i <= n; i++) {
        values.push(value)
        value += step
      }
      return values
    },
    toSvg (pos) {
      return {x: pos.x - this.realOrigin.x, y: this.realOrigin.y + this.realHeight - pos.y}
    },
    fromSvg (pos) {
      return {x: pos.x + this.realOrigin.x, y: this.realOrigin.y + this.realHeight - pos.y}
    },
    hideTooltip () {
      $('.tooltip[role=tooltip]').remove()
    },
    deletePoint () {
      this.hideTooltip()
      if (this.selectedPoint) {
        this.$emit('removePoint', this.selectedIndex)
        this.deselectPoint()
      }
    },
    submitModifyPoint () {
      this.hideTooltip()
      this.$emit('setCoordinate', this.currentPointName, {
        x: this.currentPointX,
        y: this.currentPointY,
        theta: this.currentPointTheta,
      })
      this.$emit('setPoint', this.selectedIndex, this.currentPointName)
      this.deselectPoint()
    },
    deselectPoint () {
      this.hideTooltip()
      this.selectedIndex = undefined
      this.selectedPoint = undefined
      this.currentPointName = undefined
      this.currentPointTheta = undefined
      this.currentPointX = undefined
      this.currentPointY = undefined
    },
    coordinate (point) {
      return this.coordinates[point]
    },
    getRealLocation (e) {
      // get position  of the click in the svg element
      var svg = document.getElementById('svgMap')
      var map = document.getElementById('map')

      let pt = svg.createSVGPoint()

      pt.x = e.clientX
      pt.y = e.clientY
      let svgP = this.fromSvg(pt.matrixTransform(map.getScreenCTM().inverse()))

      return {x: svgP.x, y: svgP.y}
    },
    robotMouseDown (evt, pointIndex, pointName) {
      if (pointIndex === this.selectedIndex) {
        this.deselectPoint()
      } else {
        this.selectedIndex = pointIndex
        this.selectedPoint = pointName
        this.currentPointName = pointName
        this.currentPointTheta = this.coordinates[pointName].theta
        this.currentPointX = this.coordinates[pointName].x
        this.currentPointY = this.coordinates[pointName].y
      }
    },
    mouseDown (evt, clickPos) {
      if (evt.buttons !== 1) return // left click only
      let pos = clickPos !== undefined ? clickPos : this.getRealLocation(evt)
      let ptName = this.selectedPoint
      let coord = this.coordinates[ptName]
      pos.theta = coord ? coord.theta : 0
      if (this.selectedIndex === undefined) {
        let lastPoint = this.points.slice(-1)[0]
        let lastCoord = this.coordinates[lastPoint]
        ptName = nextString(lastPoint)
        this.$emit('addPoint', ptName)
        if (lastCoord) {
          pos.theta = Math.atan2(pos.y - lastCoord.y, pos.x - lastCoord.x)
        } else {
          pos.theta = 0
        }
      }
      this.$emit('setCoordinate', ptName, pos)
    },
    toReal: function (pos) {
      // x = 0         left of map    => realOrigin.x
      // x = mapWidth  right of map   => realOrigin.x + realWidth
      // y = 0         top of map     => realOrigin.y + realHeight
      // y = mapHeight bottom of map  => realOrigin.y

      let value = {
        x: this.realOrigin.x + pos.x * this.realWidth / this.mapWidth,
        y: this.realOrigin.y + this.realHeight - pos.y * this.realHeight / this.mapHeight,
      }
      return value
    },
    mousemove: function (evt) {
      this.tooltip = {x: evt.x, y: evt.y}
    },
    mouseenter: function (evt) {
      this.showTooltip = true
    },
    mouseleave: function (evt) {
      this.showTooltip = false
    },
    updateSite (site) {
      this.siteLoaded = false
      Promise.all([iotlab.getRobotsSiteMapImage(site),
        iotlab.getRobotsSiteMapConfig(site),
        iotlab.getNodes(),
      ]).then(async ([b64, cfg, nodes]) => {
        this.site_image = 'data:image/png;base64, ' + b64
        this.site_image_dimensions = await this.getImageDimensions(this.site_image)
        this.mapConfig = cfg
        let func = node => node.x + '_' + node.y
        this.nodes = nodes
          .filter(node => node.site === this.site && node.x !== ' ' && node.mobile === 0)
          .map(node => ({x: Number(node.x), y: Number(node.y), z: Number(node.z), name: `${node.network_address.split('.')[0]} z=${Number(node.z)}`}))
          // groupbyFunc
          .reduce(function (rv, element) {
            if (rv[func(element)]) {
              rv[func(element)].name += ('<br>' + element.name)
            } else {
              rv[func(element)] = element
            }
            return rv
          }, {})

        this.mapWidth = this.site_image_dimensions.width
        this.mapHeight = this.site_image_dimensions.height
        this.svgWidth = this.mapWidth + this.xAxisMargin
        this.svgHeight = this.mapHeight + this.yAxisMargin

        this.realOrigin = {x: this.mapConfig.origin[0], y: this.mapConfig.origin[1]}
        // real dimension = resolut * px
        this.realWidth = this.mapConfig.resolution * this.site_image_dimensions.width
        this.realHeight = this.mapConfig.resolution * this.site_image_dimensions.height

        this.siteLoaded = true
      }).catch(err => {
        this.$notify({ text: 'An error occured while fetching the robot site map image or config', type: 'error' })
        throw err
      })
    },
    turtlebot2ImagePath: function () {
      return require('../../assets/turtlebot2_top.svg')
    },
    getImageDimensions: function (data) {
      return new Promise(function (resolve, reject) {
        let i = new Image()
        i.onload = function () {
          resolve({width: i.width, height: i.height})
        }
        i.src = data
      })
    },
  },
}

</script>
<style scoped>
.robottraj {
  stroke:darkviolet;
  fill: none;
  stroke-width: 0.1;
  }
.axis {
  stroke:black;
  fill: none;
  fill-opacity: 0;
  stroke-width: 2;
}
.label {
  pointer-events: none;
}

svg text {
    -webkit-user-select: none;
       -moz-user-select: none;
        -ms-user-select: none;
            user-select: none;
}
svg text::selection {
    background: none;
}
</style>
