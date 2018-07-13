<template>
<div class="container my-3">
  <h2><i class="fa fa-fw fa-folder-open" aria-hidden="true"></i> My resources</h2>
  <div class="row">
    <div class="col-md-3 mb-4">
      <div class="list-group" id="list-tab" role="tablist">
        <!-- <a class="list-group-item list-group-item-action active" id="list-firmwares-list" data-toggle="list" href="#list-firmwares" role="tab" aria-controls="firmwares"><i class="fa fa-fw fa-microchip" aria-hidden="true"></i> Firmwares</a> -->
        <a class="list-group-item list-group-item-action active" id="list-monitoring-list" data-toggle="list" href="#list-monitoring" role="tab" aria-controls="monitoring"><i class="fa fa-fw fa-thermometer" aria-hidden="true"></i> Monitoring profiles</a>
        <!-- <a class="list-group-item list-group-item-action" id="list-mobility-list" data-toggle="list" href="#list-mobility" role="tab" aria-controls="mobility"><i class="fa fa-fw fa-android" aria-hidden="true"></i> Mobility</a> -->
        <!-- <a class="list-group-item list-group-item-action" id="list-scripts-list" data-toggle="list" href="#list-scripts" role="tab" aria-controls="scripts"><i class="fa fa-fw fa-terminal" aria-hidden="true"></i> Site scripts</a> -->
      </div>
    </div>
    <div class="col-md-9">
      <div class="tab-content" id="nav-tabContent">
        <!-- <div class="tab-pane fade" id="list-firmwares" role="tabpanel" aria-labelledby="list-firmwares-list">
          TODO
        </div> -->
        <div class="tab-pane fade show active" id="list-monitoring" role="tabpanel" aria-labelledby="list-monitoring-list">
          <monitoring-list @select="editMonitoring"></monitoring-list>
        </div>
        <!-- <div class="tab-pane fade" id="list-mobility" role="tabpanel" aria-labelledby="list-mobility-list">
          TODO
        </div>
        <div class="tab-pane fade" id="list-scripts" role="tabpanel" aria-labelledby="list-scripts-list">
          TODO
        </div> -->
      </div>
    </div>
  </div>
</div> <!-- container -->

</template>

<script>
import MonitoringList from '@/components/MonitoringList'

export default {
  name: 'Resources',

  components: {
    MonitoringList,
  },

  methods: {
    editMonitoring (profile) {
      this.$router.push({name: 'monitoring', params: { name: profile }})
    },
  },
}
</script>

<style>

</style>
