<template>
<div class="container my-3">
  <h2><i class="fa fa-fw fa-folder-open" aria-hidden="true"></i> My resources</h2>
  <div class="row">
    <div class="col-md-3 mb-4">
      <div class="list-group" id="list-tab" role="tablist">
        <router-link :to="{name:'listMonitoring'}" class="list-group-item list-group-item-action" active-class="active">
          <i class="fa fa-fw fa-thermometer" aria-hidden="true"></i> Monitoring profiles
        </router-link>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tab-content" id="nav-tabContent">
        <router-view></router-view>
      </div>
    </div>
  </div>
</div> <!-- container -->

</template>
