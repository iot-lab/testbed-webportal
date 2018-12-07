
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

// Extract archi from a string "archi:radio"
export const extractArchi = function (nodeArchi) {
  return nodeArchi.split(':')[0]
}

// Extract archi from a network_address "m3-1.lille.iot-lab.info"
export const extractArchiFromAddress = function (networkAddress) {
  return networkAddress.split('-')[0]
}

// List of existing experiment states
export const experimentStates = {
  all: 'Terminated,Stopped,Error,Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended'.split(','),
  scheduled: 'Running,Finishing,Resuming,toError,Waiting,Launching,Hold,toLaunch,toAckReservation,Suspended'.split(','),
  completed: 'Terminated,Stopped,Error'.split(','),
  stoppable: 'Running,Launching,Waiting'.split(','),
}
