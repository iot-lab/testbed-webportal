import Vue from 'vue'
import moment from 'moment'
import humanizeDuration from 'humanize-duration'
import { pluralize } from '@/utils'

export const fromTimestamp = function (value) {
  if (value) {
    return moment.unix(value).format('YYYY-MM-DD HH:mm:ss')
  }
}

export const formatDate = function (value) {
  if (value) {
    return moment(String(value)).format('YYYY-MM-DD')
  }
}

export const formatDateTime = function (value, duration = 0) {
  if (value) {
    return moment(String(value)).add(duration, 'minutes').format('YYYY-MM-DD HH:mm')
  }
}

export const formatDateTimeSec = function (value) {
  if (value) {
    return moment(String(value)).format('YYYY-MM-DD HH:mm:ss')
  }
}

export const humanDuration = function (value) {
  if (value) {
    let ms = value * 60 * 1000
    return humanizeDuration(ms, {
      units: ['y', 'd', 'h', 'm'],
      largest: 2,
      round: true,
    })
  }
}

export const stripDomain = function (value) {
  if (value) {
    return value.replace('.iot-lab.info', '')
  }
}

export const stateBadgeClass = function (value) {
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
}

export const formatArchi = function (value) {
  if (value) {
    return value.split(':')[0].replace('_', ' ')
  }
}

export const formatRadio = function (value) {
  if (value && value.includes(':')) {
    return value.split(':')[1]
  }
  return value
}

export const formatArchiRadio = function (value) {
  if (value) {
    if (value.split(':')[1]) {
      return value.split(':')[0].replace('_', ' ') + ' (' + value.split(':')[1] + ')'
    }
    return value.split(':')[0].replace('_', ' ')
  }
}

export const md5Tag = function (value) {
  let firmwareWithMD5 = /([0-9a-f]{32})_(.+)/g
  let groups = firmwareWithMD5.exec(value)
  if (groups) {
    return `${groups[2]} <i class="fa fa-sm fa-tag text-muted" title="MD5: ${groups[1]}"></i>`
  }
  return value
}

Vue.filter('pluralize', function (qty, str) {
  if (str) {
    return pluralize(qty, str)
  }
})
Vue.filter('fromTimestamp', fromTimestamp)
Vue.filter('formatDate', formatDate)
Vue.filter('formatDateTime', formatDateTime)
Vue.filter('formatDateTimeSec', formatDateTimeSec)
Vue.filter('humanizeDuration', humanDuration)
Vue.filter('stripDomain', stripDomain)
Vue.filter('stateBadgeClass', stateBadgeClass)
Vue.filter('formatArchi', formatArchi)
Vue.filter('formatRadio', formatRadio)
Vue.filter('formatArchiRadio', formatArchiRadio)
Vue.filter('md5Tag', md5Tag)
