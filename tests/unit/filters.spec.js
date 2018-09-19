import * as Filters from '@/filters'

describe('Filter "fromTimestamp".', () => {
  it('fromTimestamp convert unix timestamp to date string.', () => {
    expect(Filters.fromTimestamp(1531336988)).toEqual('2018-07-11 21:23:08')
  })
})

describe('Filter "formatDate".', () => {
  it('formatDate convert date to date string.', () => {
    expect(Filters.formatDate('2018-07-04T20:04:56Z')).toEqual('2018-07-04')
    expect(Filters.formatDate('2018-07-04T22:04:56Z')).toEqual('2018-07-05')
  })
})

describe('Filter "formatDateTime".', () => {
  it('formatDateTime convert date to date string.', () => {
    expect(Filters.formatDateTime('2018-07-04T22:04:56Z')).toEqual('2018-07-05 00:04')
  })
})

describe('Filter "formatDateTimeSec".', () => {
  it('formatDateTimeSec convert date to date string.', () => {
    expect(Filters.formatDateTimeSec('2018-07-04T22:04:56Z')).toEqual('2018-07-05 00:04:56')
  })
})

describe('Filter "humanizeDuration".', () => {
  it('humanizeDuration convert N minutes into human readable duration.', () => {
    expect(Filters.humanDuration(0)).toEqual(undefined)
    expect(Filters.humanDuration(1)).toEqual('1 minute')
    expect(Filters.humanDuration(60)).toEqual('1 hour')
    expect(Filters.humanDuration(179)).toEqual('2 hours, 59 minutes')
    expect(Filters.humanDuration(123456)).toEqual('85 days, 18 hours')
  })
})

describe('Filter "stripDomain".', () => {
  it('stripDomain removes .iot-lab.info to server name.', () => {
    expect(Filters.stripDomain('toto')).toEqual('toto')
    expect(Filters.stripDomain('toto.iot-lab.info')).toEqual('toto')
  })
})

describe('Filter "stateBadgeClass".', () => {
  it('stateBadgeClass convert an iotlab state into boostrap class', () => {
    expect(Filters.stateBadgeClass('Alive')).toEqual('badge-success')
    expect(Filters.stateBadgeClass('Running')).toEqual('badge-success')
    expect(Filters.stateBadgeClass('Busy')).toEqual('badge-warning')
    expect(Filters.stateBadgeClass('Waiting')).toEqual('badge-warning')
    expect(Filters.stateBadgeClass('Stopped')).toEqual('badge-dark')
    expect(Filters.stateBadgeClass('toto')).toEqual('badge-secondary')
  })
})

describe('Filter "formatArchi".', () => {
  it('formatArchi extract archi from "archi:radio" string', () => {
    expect(Filters.formatArchi('a8')).toEqual('a8')
    expect(Filters.formatArchi('a8:ble')).toEqual('a8')
    expect(Filters.formatArchi('a8_toto:ble')).toEqual('a8 toto')
  })
})

describe('Filter "formatRadio".', () => {
  it('formatRadio extract radio from "archi:radio" string', () => {
    expect(Filters.formatRadio('a8')).toEqual('a8')
    expect(Filters.formatRadio('a8:radio')).toEqual('radio')
    expect(Filters.formatRadio('a8_toto:ble')).toEqual('ble')
  })
})

describe('Filter "formatArchiRadio".', () => {
  it('formatArchiRadio extract archi & radio from "archi:radio" string', () => {
    expect(Filters.formatArchiRadio('a8')).toEqual('a8')
    expect(Filters.formatArchiRadio('a8:radio')).toEqual('a8 (radio)')
    expect(Filters.formatArchiRadio('a8_toto:ble')).toEqual('a8 toto (ble)')
  })
})
