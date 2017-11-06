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
      case 'Alive':
      case 'Running':
        return 'badge-success'
      case 'Error':
      case 'toError':
      case 'Absent':
        return 'badge-danger'
      case 'Waiting':
      case 'Hold':
      case 'Finishing':
        return 'badge-warning'
      case 'Resuming':
      case 'Launching':
      case 'toLaunch':
      case 'toAckReservation':
        return 'badge-info'
      case 'Suspected':
      case 'Suspended':
        return 'badge-dark'
      default:
        return 'badge-secondary'
    }
  }
})

Vue.filter('formatArchi', function (value) {
  if (value) {
    return value.split(':')[0].replace('_', ' ')
  }
})

Vue.filter('formatArchiRadio', function (value) {
  if (value) {
    if (value.split(':')[1]) {
      return value.split(':')[0].replace('_', ' ') + ' (' + value.split(':')[1] + ')'
    }
    return value.split(':')[0].replace('_', ' ')
  }
})
