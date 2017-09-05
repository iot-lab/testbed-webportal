import Vue from 'vue'
import moment from 'moment'

Vue.filter('fromTimestamp', function (value) {
  if (value) {
    return moment.unix(value).format('YYYY-MM-DD HH:mm:ss')
  }
})

Vue.filter('formatDate', function (value) {
  if (value) {
    return moment(String(value)).format('YYYY-MM-DD')
  }
})

Vue.filter('formatDateTime', function (value) {
  if (value) {
    return moment(String(value)).format('YYYY-MM-DD HH:mm')
  }
})

Vue.filter('stateBadgeClass', function (value) {
  if (value) {
    switch (value) {
      case 'Terminated':
        return 'badge-secondary'
      case 'Error':
        return 'badge-danger'
      case 'Running':
        return 'badge-success'
      case 'Finishing':
        return 'badge-warning'
      case 'Resuming':
        return 'badge-info'
      case 'toError':
        return 'badge-danger'
      case 'Waiting':
        return 'badge-warning'
      case 'Launching':
        return 'badge-info'
      case 'Hold':
        return 'badge-warning'
      case 'toLaunch':
        return 'badge-info'
      case 'toAckReservation':
        return 'badge-info'
      case 'Suspended':
        return 'badge-dark'
      default:
        return 'badge-secondary'
    }
  }
})
