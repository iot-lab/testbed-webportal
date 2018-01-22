
// Return the firmware types allowed for a given node architecture

export const allowedFirmwares4Archi = function (archi) {
  archi = archi.split(':')[0].toLowerCase()
  switch (archi) {
    case 'a8':
      return []
    case 'wsn430':
      return ['hex', 'ihex']
    default:
      return ['elf']
  }
}
