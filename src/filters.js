import Vue from 'vue'
import moment from 'moment'
import humanizeDuration from 'humanize-duration'

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

Vue.filter('formatDateTime', function (value, duration = 0) {
  if (value) {
    return moment(String(value)).add(duration, 'minutes').format('YYYY-MM-DD HH:mm')
  }
})

Vue.filter('formatDateTimeSec', function (value) {
  if (value) {
    return moment(String(value)).format('YYYY-MM-DD HH:mm:ss')
  }
})

Vue.filter('humanizeDuration', function (value) {
  if (value) {
    let ms = value * 60 * 1000
    return humanizeDuration(ms, {
      units: ['y', 'd', 'h', 'm'],
      largest: 2,
      round: true,
    })
  }
})

Vue.filter('stripDomain', function (value) {
  if (value) {
    return value.replace('.iot-lab.info', '')
  }
})

Vue.filter('stateBadgeClass', function (value) {
  if (value) {
    switch (value) {
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
      case 'Busy':
        return 'badge-warning'
      case 'Resuming':
      case 'Launching':
      case 'toLaunch':
      case 'toAckReservation':
        return 'badge-info'
      case 'Suspected':
      case 'Suspended':
      case 'Stopped':
        return 'badge-dark'
      case 'Terminated':
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

Vue.filter('formatRadio', function (value) {
  if (value && value.includes(':')) {
    return value.split(':')[1]
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
