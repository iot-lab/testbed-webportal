<template>
  <div class="container mt-3">
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
          <text class="label" text-anchor="middle" font-size="1">{{point}}</text>
        </g>
      </svg>
      <g :transform="`translate(${xAxisMargin}, ${mapHeight})`">
        <path stroke-width="0.2" stroke="#000000" class="axis" :d="axisPathString"></path>
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
    </svg>
    <div v-if="!readOnly" :style="{'visibility': this.selectedPoint ? '': 'hidden'}">
      <div class="form-group">
        <div class="row">
          <div class="col">
            <label>Select existing coordinate point:</label>
            <select class="form-control" v-model.lazy="currentPointName">
              <option v-for="c in Object.keys(this.coordinates)" :key="c">{{c}}</option>
            </select>
          </div>
          <div class="col">
            <label>Enter name for new coordinate point:</label>
            <input class="form-control" type="text" v-model.lazy="currentPointName"/>
          </div>
          <div class="col">
            <label>Point rotation:</label>
            <angle-input v-model="currentPointTheta"></angle-input>
          </div>
        </div>
      </div>
      <i class="btn btn-success fa fa-check-circle" v-tooltip:top="'Modify the point'" @click="submitModifyPoint"></i>
      <i class="btn btn-info fa fa-window-close" v-tooltip:top="'Close'" @click="deselectPoint"></i>
      <i class="btn btn-danger fa fa-trash float-right" v-tooltip:top="'Delete the point'" @click="deletePoint"></i>
    </div>
    <div :style="{'visibility': this.selectedPoint ? '': 'hidden'}">
    </div>
    <div class="pull-right">
      <img height=50 src="../../assets/turtlebot2_top.svg"> Robot front
    </div>
  </div>

</template>
<script>
import { iotlab } from '@/rest'
import { nextString } from '@/utils'
import AngleInput from '@/components/mobility/AngleInput'

export default {
  name: 'CircuitMapView',
  components: {
    AngleInput,
  },
  props: {
    site: {
      type: String,
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
    valid_points () {
      return this.points.filter(p => this.coordinate(p) !== undefined)
    },
    middle_points () {
      let middlePoints = []
      let pointsLength = this.points.length
      for (var i = 0; i < pointsLength; i++) {
        var next
        if (i === pointsLength - 1) {
          if (this.loop) {
            next = this.points[0]
          } else {
            break
          }
        } else {
          next = this.points[i + 1]
        }
        let point = this.points[i]
        let pointCoord = this.toSvg(this.coordinates[point])
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
    axisPathString () {
      let path = `
      M 0,${-this.mapHeight} 
      L 0,0
      L ${this.mapWidth},0
      `
      return path
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
      currentPointName: undefined,
      currentPointTheta: undefined,
      selectedIndex: undefined,
      selectedPoint: undefined,
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

  watch: {
    site: function (site) {
      this.updateSite(site)
    },
  },

  methods: {
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
    deletePoint () {
      if (this.selectedPoint) {
        this.$emit('removePoint', this.selectedIndex)
        this.deselectPoint()
      }
    },
    submitModifyPoint () {
      let coord = this.coordinates[this.selectedPoint]
      coord.theta = this.currentPointTheta
      this.$emit('setCoordinate', this.currentPointName, coord)
      this.$emit('setPoint', this.selectedIndex, this.currentPointName)
      this.deselectPoint()
    },
    deselectPoint () {
      this.selectedIndex = undefined
      this.selectedPoint = undefined
      this.currentPointName = undefined
      this.currentPointTheta = undefined
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
      console.log('robotMouseDown ' + evt)
      if (pointIndex === this.selectedIndex) {
        this.deselectPoint()
      } else {
        this.selectedIndex = pointIndex
        this.selectedPoint = pointName
        this.currentPointName = pointName
        this.currentPointTheta = this.coordinates[pointName].theta
      }
    },
    mouseDown (evt) {
      console.log('mouseDown ' + evt)
      if (evt.buttons !== 1) return // left click only
      let pos = this.getRealLocation(evt)
      console.log(pos)
      let ptName = this.selectedPoint
      let coord = this.coordinates[ptName]
      pos.theta = coord ? coord.theta : 0
      if (this.selectedIndex === undefined) {
        let lastPoint = this.points.slice(-1)[0]
        ptName = nextString(lastPoint)
        this.$emit('addPoint', ptName)
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
      console.log('mobility map for site ' + site)
      Promise.all([iotlab.getRobotsSiteMapImage(site),
        iotlab.getRobotsSiteMapConfig(site),
      ]).then(async ([b64, cfg]) => {
        this.site_image = 'data:image/png;base64, ' + b64
        this.site_image_dimensions = await this.getImageDimensions(this.site_image)
        this.mapConfig = cfg

        this.mapWidth = this.site_image_dimensions.width
        this.mapHeight = this.site_image_dimensions.height
        this.svgWidth = this.mapWidth + this.xAxisMargin
        this.svgHeight = this.mapHeight + this.yAxisMargin
        console.log(this.mapConfig)

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
