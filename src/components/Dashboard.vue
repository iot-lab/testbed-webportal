<template>
<div class="container">
        
    <h2>Platform status</h2>
    <p v-if="stats.nodes">
        <span class="badge label-success">{{stats.nodes.Alive}}</span> nodes available
        <span class="badge label-warning">{{stats.nodes.Busy}}</span> busy
        <span class="badge label-danger">{{stats.nodes.Unavailable}}</span> unavailable
    </p>
    <p v-else>
        <i class="fa fa-spinner fa-spin fa-fw"></i>
    </p>
    <p>
        <a class="btn btn-default">Check future availability</a>
    </p>

    <h2>My experiments</h2>
    <p v-if="total.running != undefined">
        <span class="badge label-success">{{total.running}}</span> running
        <span class="badge label-warning">{{total.upcoming}}</span> upcoming
        <span class="badge label-danger">{{total.terminated}}</span> terminated
    </p>
    <p v-else>
        <i class="fa fa-spinner fa-spin fa-fw"></i>
    </p>
    <p>
        <a class="btn btn-primary">New experiment</a>
    </p>

</div> <!-- container -->

</template>

<script>
import {iotlab} from '@/rest'

export default {
  name: 'Dashboard',

  data () {
    return {
      total: {},
      stats: {},
    }
  },

  created () {
    iotlab.getUserExperiments().then(data => { this.total = data })
    iotlab.getStats().then(data => { this.stats = data })
  },

}
</script>

<style>
.label-default {
  background-color: #777;
}
.label-default[href]:hover,
.label-default[href]:focus {
  background-color: #5e5e5e;
}
.label-primary {
  background-color: #337ab7;
}
.label-primary[href]:hover,
.label-primary[href]:focus {
  background-color: #286090;
}
.label-success {
  background-color: #5cb85c;
}
.label-success[href]:hover,
.label-success[href]:focus {
  background-color: #449d44;
}
.label-info {
  background-color: #5bc0de;
}
.label-info[href]:hover,
.label-info[href]:focus {
  background-color: #31b0d5;
}
.label-warning {
  background-color: #f0ad4e;
}
.label-warning[href]:hover,
.label-warning[href]:focus {
  background-color: #ec971f;
}
.label-danger {
  background-color: #d9534f;
}
.label-danger[href]:hover,
.label-danger[href]:focus {
  background-color: #c9302c;
}
</style>
