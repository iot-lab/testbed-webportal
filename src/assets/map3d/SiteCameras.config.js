// Camera views for IoT-LAB sites

const SiteCameras = {
  grenoble: [
    {
      name: 'Default',
      camera: {x: 11, y: -216, z: 176},
      origin: {x: 11, y: -6, z: 0},
    },
  ],
  lille: [
    {
      name: 'Default',
      origin: {x: -470, y: -270, z: 270},
      camera: {x: -458, y: -263, z: 176},
    },
    {
      name: 'lecube',
      origin: {x: 590, y: -303, z: -100},
      camera: {x: 687, y: -185, z: 176},
    },
  ],
  saclay: [
    {
      name: 'Default',
      camera: {x: 6, y: -376, z: 130},
      origin: {x: 10, y: -212, z: 0},
    },
    {
      name: 'Basement',
      camera: {x: 14, y: -313, z: 189},
      origin: {x: 10, y: -193, z: 0},
    },
    {
      name: 'Room 1',
      camera: {x: -107, y: 262, z: 41},
      origin: {x: -107, y: 342, z: 0},
    },
    {
      name: 'Room 2',
      camera: {x: -116, y: 110, z: 75},
      origin: {x: -115, y: 160, z: 0},
    },
  ],
  devgrenoble: [
    {
      name: 'Default',
      camera: {x: 0, y: -75, z: 63},
      origin: {x: 0, y: 0, z: 0},
    },
    {
      name: 'My camera',
      camera: {x: 12, y: 23, z: 12},
      origin: {x: 0, y: 0, z: 0},
    },
    {
      name: 'My camera 2',
      camera: {x: -65, y: 37, z: 65},
      origin: {x: -25, y: 6, z: 0},
    },
  ],
  devlille: [],
  devsaclay: [
    {
      name: 'Default',
      camera: {x: -107, y: 262, z: 41},
      origin: {x: -107, y: 342, z: 0},
    },
    {
      name: 'Room 2',
      camera: {x: -116, y: 110, z: 75},
      origin: {x: -115, y: 160, z: 0},
    },
    {
      name: 'Basement',
      camera: {x: 14, y: -313, z: 189},
      origin: {x: 10, y: -193, z: 0},
    },
  ],
  devstrasbourg: [],
}

export default SiteCameras
