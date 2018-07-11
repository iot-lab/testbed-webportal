import Vue from 'vue'

const vm = new Vue().$mount()

describe('Filter "fromTimestamp".', () => {
  it('fromTimestamp convert unix timestamp to date string.', () => {
    expect(vm.$options.filters.fromTimestamp(1531336988)).to.equal('2018-07-11 21:23:08')
  })
})

describe('Filter "formatDate".', () => {
  it('formatDate convert date to date string.', () => {
    expect(vm.$options.filters.formatDate('2018-07-04T20:04:56Z')).to.equal('2018-07-04')
    expect(vm.$options.filters.formatDate('2018-07-04T22:04:56Z')).to.equal('2018-07-05')
  })
})

describe('Filter "formatDateTime".', () => {
  it('formatDateTime convert date to date string.', () => {
    expect(vm.$options.filters.formatDateTime('2018-07-04T22:04:56Z')).to.equal('2018-07-05 00:04')
  })
})

describe('Filter "formatDateTimeSec".', () => {
  it('formatDateTimeSec convert date to date string.', () => {
    expect(vm.$options.filters.formatDateTimeSec('2018-07-04T22:04:56Z')).to.equal('2018-07-05 00:04:56')
  })
})

describe('Filter "humanizeDuration".', () => {
  it('humanizeDuration convert N minutes into human readable duration.', () => {
    expect(vm.$options.filters.humanizeDuration(0)).to.equal(undefined)
    expect(vm.$options.filters.humanizeDuration(1)).to.equal('1 minute')
    expect(vm.$options.filters.humanizeDuration(60)).to.equal('1 hour')
    expect(vm.$options.filters.humanizeDuration(179)).to.equal('2 hours, 59 minutes')
    expect(vm.$options.filters.humanizeDuration(123456)).to.equal('85 days, 18 hours')
  })
})

describe('Filter "stripDomain".', () => {
  it('stripDomain removes .iot-lab.info to server name.', () => {
    expect(vm.$options.filters.stripDomain('toto')).to.equal('toto')
    expect(vm.$options.filters.stripDomain('toto.iot-lab.info')).to.equal('toto')
  })
})

describe('Filter "stateBadgeClass".', () => {
  it('stateBadgeClass convert an iotlab state into boostrap class', () => {
    expect(vm.$options.filters.stateBadgeClass('Alive')).to.equal('badge-success')
    expect(vm.$options.filters.stateBadgeClass('Running')).to.equal('badge-success')
    expect(vm.$options.filters.stateBadgeClass('Busy')).to.equal('badge-warning')
    expect(vm.$options.filters.stateBadgeClass('Waiting')).to.equal('badge-warning')
    expect(vm.$options.filters.stateBadgeClass('Stopped')).to.equal('badge-dark')
    expect(vm.$options.filters.stateBadgeClass('toto')).to.equal('badge-secondary')
  })
})

describe('Filter "formatArchi".', () => {
  it('formatArchi extract archi from "archi:radio" string', () => {
    expect(vm.$options.filters.formatArchi('a8')).to.equal('a8')
    expect(vm.$options.filters.formatArchi('a8:ble')).to.equal('a8')
    expect(vm.$options.filters.formatArchi('a8_toto:ble')).to.equal('a8 toto')
  })
})

describe('Filter "formatRadio".', () => {
  it('formatRadio extract radio from "archi:radio" string', () => {
    expect(vm.$options.filters.formatRadio('a8')).to.equal('a8')
    expect(vm.$options.filters.formatRadio('a8:radio')).to.equal('radio')
    expect(vm.$options.filters.formatRadio('a8_toto:ble')).to.equal('ble')
  })
})

describe('Filter "formatArchiRadio".', () => {
  it('formatArchiRadio extract archi & radio from "archi:radio" string', () => {
    expect(vm.$options.filters.formatArchiRadio('a8')).to.equal('a8')
    expect(vm.$options.filters.formatArchiRadio('a8:radio')).to.equal('a8 (radio)')
    expect(vm.$options.filters.formatArchiRadio('a8_toto:ble')).to.equal('a8 toto (ble)')
  })
})
