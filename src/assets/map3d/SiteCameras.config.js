// Camera views for IoT-LAB sites

const SiteCameras = {
  grenoble: [
    {
      name: 'Default',
      camera: {x: 11, y: -216, z: 176},
      origin: {x: 11, y: -6, z: 0},
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
  devsaclay: [],
  devstrasbourg: [],
}

export default SiteCameras
