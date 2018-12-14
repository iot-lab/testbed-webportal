import { THREE } from 'three'

var sTestEventType = 'mousedown'

var phi, theta, distance

var mouseX, mouseY
var renderer, scene, camera, projector

var nodeInfo, container

var selectedCallback

var objects

var window3DWidth, window3DHeight

// list of selected nodes
var selectedNodes = []
// node list used in 3D vue
var mapNodes

// var logInfo

/*
 * Fonction qui récupère la liste de tous les noeuds de la vue
 *
 */
function loadNodes (liste) {
  mapNodes = liste
}

/*
 * Fonction d'initialisation de la scene 3D
 *
 */

function init () {
  phi = -100
  theta = 0
  distance = 100

  mouseX = mouseY = 0

  container = document.getElementById('map3d')
  nodeInfo = document.getElementById('nodeInfo')
  container.innerHTML = ''
  nodeInfo.innerHTML = ''

  objects = []

  renderer = new THREE.CanvasRenderer()
  scene = new THREE.Scene()
  camera = new THREE.PerspectiveCamera(75, window3DWidth / window3DHeight, 1, 10000)
  // Projecteur utiliser pour la selection de noeuds.
  projector = new THREE.Projector()

  container.appendChild(renderer.domElement)

  window.addEventListener('resize', set3dsize, false)
  scene.add(camera)
  set3dsize()

  createParticles(mapNodes)

  camera.lookAt(scene.position)

  // création des 3 axes pour s'orienter
  debugAxis(40)

  // gestion des actions de la souris
  container.onmousedown = OnMouseDown
  container.onmouseup = OnMouseUp
  container.onmousemove = displayNodeInfo

  container.addEventListener('DOMMouseScroll', wheel, false) // mozilla
  container.onmousewheel = wheel // IE - Chrome

  // Mise à jour de l'affichage
  render()
}

function set3dsize () {
  window3DWidth = container.offsetWidth
  window3DHeight = container.offsetHeight
  renderer.setSize(window3DWidth, window3DHeight)

  camera.aspect = window3DWidth / window3DHeight
  camera.updateProjectionMatrix()

  render()
}

/*
 * Fonction permettant de paser la liste de noeuds reçue et de créer des particules
 * et de les ajouter à la scene.
 *
 */
function createParticles (nodes) {
  // let's find the center of the nodes
  var xmin, xmax, ymin, ymax, zmin, zmax
  xmin = ymin = zmin = 0
  xmax = ymax = zmax = 0

  for (var i = 0; i < nodes.length; i++) {
    if (parseFloat(nodes[i].x) > xmax) xmax = parseFloat(nodes[i].x)
    if (parseFloat(nodes[i].x) < xmin) xmin = parseFloat(nodes[i].x)
    if (parseFloat(nodes[i].y) > ymax) ymax = parseFloat(nodes[i].y)
    if (parseFloat(nodes[i].y) < ymin) ymin = parseFloat(nodes[i].y)
    if (parseFloat(nodes[i].z) > zmax) zmax = parseFloat(nodes[i].z)
    if (parseFloat(nodes[i].z) < zmin) zmin = parseFloat(nodes[i].z)
  }

  var xcenter = (xmax + xmin) / 2
  var ycenter = (ymax + ymin) / 2
  var zcenter = (zmax + zmin) / 2

  // var nodeTexture = THREE.ImageUtils.loadTexture( 'static/node.png' );
  // var nodeMaterial = new THREE.SpriteMaterial( { map: nodeTexture, useScreenCoordinates: false, color: 0xff0000 } );

  for (i = 0; i < nodes.length; i++) {
    var couleur = 0xffffff
    // var particle = new THREE.Particle(nodeMaterial)
    var particle = new THREE.Particle(new THREE.SpriteCanvasMaterial({

      color: couleur,
      opacity: 0.9,
      program: function (context) {
        context.beginPath()
        context.arc(0, 0, 1, 0, Math.PI * 2, true)
        context.closePath()
        context.fill()
      },
    }))
    particle.node = nodes[i]
    particle.name = nodes[i].network_address
    particle.position.x = (parseFloat(nodes[i].x) - xcenter)
    particle.position.y = (parseFloat(nodes[i].y) - ycenter)
    particle.position.z = (parseFloat(nodes[i].z) - zcenter)
    particle.position.multiplyScalar(10)
    particle.state = nodes[i].state
    // particle.archi = nodes[i].archi.split(':')[0]
    // particle.mobile = nodes[i].mobile
    particle.scale.x = particle.scale.y = 1
    objects.push(particle)
    scene.add(particle)
  }
  initColors()
}

function initColors () {
  for (var i = 0; i < objects.length; i++) {
    objects[i].scale.x = objects[i].scale.y = 1
    if (selectedNodes.findIndex(obj => obj.network_address === objects[i].name) !== -1) {
      // bleu clair
      objects[i].material.color.setHex(0x05a8fd)
      objects[i].scale.x = objects[i].scale.y = 1.4
    } else if (objects[i].state === 'Dead') {
      // gris
      objects[i].material.color.setHex(0x868e96)
    } else if (objects[i].state === 'Busy') {
      // orange
      objects[i].material.color.setHex(0xfd7e14)
    } else if (objects[i].state === 'Alive') {
      // vert
      objects[i].material.color.setHex(0x0ce43d)
    } else {
      // rouge
      objects[i].material.color.setHex(0xFF3030)
    }
  }
}

/*
 * Fonction permettant d'actualiser la scene
 * (fonction d'affichage)
 *
 */
function render (computeCamPos = true) {
  // requestAnimationFrame(render)
  initColors()
  if (computeCamPos) {
    camera.position.x = scene.position.x + distance * Math.sin(theta * Math.PI / 360) * Math.cos(phi * Math.PI / 360)
    camera.position.y = scene.position.y + distance * Math.sin(phi * Math.PI / 360)
    camera.position.z = scene.position.z + distance * Math.cos(theta * Math.PI / 360) * Math.cos(phi * Math.PI / 360)
  }
  camera.lookAt(scene.position)
  camera.updateMatrix()
  renderer.setClearColor(0x343a40, 1)
  // renderer.clear()
  renderer.render(scene, camera)

  console.log(`Cam Pos (x, y, z | theta, phi, dist) = ${camera.position.x}, ${camera.position.y}, ${camera.position.z} | ${theta}, ${phi}, ${distance}`)
  console.log('Scene Pos (x, y, z)', scene.position)
}

/*
 * Fonction permettant de trouver le noeud situé en dessous de la souris
 *
 */

function findNodeUnderMouse (event) {
  var target = event.target
  var posX = event.clientX
  var posY = event.clientY
  var rect = target.getBoundingClientRect()
  var left = posX - rect.left - target.clientLeft + target.scrollLeft
  var top = posY - rect.top - target.clientTop + target.scrollTop
  var deviceX = left / target.clientWidth * 2 - 1
  var deviceY = -top / target.clientHeight * 2 + 1
  var vector = new THREE.Vector3(deviceX, deviceY, 0.5)
  projector.unprojectVector(vector, camera)
  var ray = new THREE.Raycaster(camera.position, vector.sub(camera.position).normalize())
  var intersects = ray.intersectObjects(objects)
  // filter out wrong results
  intersects = intersects.filter(obj => !isNaN(obj.distance))
  // console.log(intersects)
  if (intersects.length > 0) {
    // if a retirer pour un simple test
    // if (isNaN(intersects[0].object.position.x)) {
    //   return null
    // } else {
    //   return intersects[0]
    // }
    return intersects[0]
  } else return null
}

/*
 * Fonction permettant de selectionner un noeud
 *
 */
function toggleNode (obj) {
  var node = obj.object.node
  var i = selectedNodes.findIndex(n => n.network_address === node.network_address)
  if (i === -1) {
    selectedNodes.push(node)
  } else {
    selectedNodes.splice(i, 1)
  }
  initColors()
  // selectedInfo.innerHTML = selectedNodes.length > 0 ? 'Selected nodes' : ''
  // for (let node of selectedNodes) {
  // selectedInfo.innerHTML += ` <span class="badge badge-primary">${node}</span>`
  // }
  if (selectedCallback) selectedCallback(selectedNodes)
}

/*
 * Fonction permettant de créer un vecteur
 *
 */
function v (x, y, z) {
  return new THREE.Vector3(x, y, z)
}

/*
 * Fonction qui crée des axes et les ajoute à la scène
 *
 */
function createAxis (p1, p2, color) {
  var line = new THREE.Geometry()
  var lineGeometry = new THREE.Geometry()
  var lineMat = new THREE.LineBasicMaterial({
    color: color,
    lineWidth: 2,
  })
  lineGeometry.vertices.push(p1, p2)
  line = new THREE.Line(lineGeometry, lineMat)
  scene.add(line)
}

/*
 * Fonction permettant de créer le repère de la figure 3D
 * (en créant 3 axes)
 *
 */
function debugAxis (axisLength) {
  createAxis(v(0, 0, 0), v(axisLength, 0, 0), 0xFF0000)
  createAxis(v(0, 0, 0), v(0, axisLength, 0), 0x00FF00)
  createAxis(v(0, 0, 0), v(0, 0, axisLength), 0x0000FF)
}

/*
 * Fonction permettant une action après un clic
 * traite les deux cas clic droit et clic gauche
 */
function OnMouseDown (e) {
  var clickType = 'LEFT'
  if (e.type !== sTestEventType) return true
  if (e.which) {
    if (e.which === 3) clickType = 'RIGHT'
    if (e.which === 2) clickType = 'MIDDLE'
  }
  mouseX = e.clientX
  mouseY = e.clientY
  if (clickType === 'RIGHT') {
    document.onmousemove = onDocumentMouseMoveRot
  }
  if (clickType === 'LEFT') {
    var obj = findNodeUnderMouse(e)
    if (obj !== null) toggleNode(obj)
    else document.onmousemove = onDocumentMouseMoveTranslate
  }
}

/*
 * Fonction permettant une action lors de l'arrêt d'un clic
 *
 */
function OnMouseUp (e) {
  document.onmousemove = displayNodeInfo
}

function badgeClass (value) {
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

/*
 * Display some info of the node under the mouse
 *
 */
function displayNodeInfo (e) {
  // display some info of the node under the mouse
  var obj = findNodeUnderMouse(e)
  if (obj) {
    var node = obj.object.node
    nodeInfo.innerHTML = `
      <b>${node.network_address}</b>
      <span class="badge badge-primary">${node.shortArchi || node.archi}</span>
      <span class="badge ${badgeClass(node.state)}">${node.state}</span>
    `
    if (node.mobile) {
      nodeInfo.innerHTML += '<span class="badge badge-infomobile</span>'
    }
  } else {
    nodeInfo.innerHTML = ''
  }
  // else nodeInfo.innerHTML ='Node info 2: '+ e.clientX + "," + e.clientY + " - " + offX + "," + offY
}

/*
 * Fonction permettant la rotation de la camera lors d'un clic droit
 *
 */
function onDocumentMouseMoveRot (event) {
  var NewmouseX = event.clientX
  var NewmouseY = event.clientY
  var DeltaX = NewmouseX - mouseX
  var DeltaY = NewmouseY - mouseY

  mouseX = NewmouseX
  mouseY = NewmouseY

  theta -= DeltaX
  phi += DeltaY
  if (phi > 180) phi = 180
  if (phi < -180) phi = -180

  render()
}

/*
 * Fonction permettant la translation de la camera lors d'un clic gauche
 *
 */
function onDocumentMouseMoveTranslate (event) {
  console.log('translate')
  var NewmouseX = event.clientX
  var NewmouseY = event.clientY
  var DeltaX = NewmouseX - mouseX
  var DeltaY = NewmouseY - mouseY

  mouseX = NewmouseX
  mouseY = NewmouseY

  scene.position.x -= DeltaX
  scene.position.y += DeltaY
  camera.position.x -= DeltaX
  camera.position.y += DeltaY
  render(false)
}

/*
 * This function was taken here : http://www.adomas.org/javascript-mouse-wheel/
 * Event handler for mouse wheel event.
 * Permet l'interaction avec le scroll de la souris
 */
function wheel (event) {
  console.log('wheel')
  var delta = 0
  if (!event) { /* For IE */
    event = window.event
  }
  if (event.wheelDelta) { /* IE/Opera */
    delta = event.wheelDelta / 120
  } else if (event.detail) { /** Mozilla case */
    /** In Mozilla, sign of delta is different than in IE
     * Also, delta is multiple of 3
     */
    delta = -event.detail / 3
  }
  /** If delta is nonzero, handle it.
   * Basically, delta is now positive if wheel was scrolled up,
   * and negative, if wheel was scrolled down.
   */
  if (delta) {
    Zoom(delta)
  }
  /** Prevent default actions caused by mouse wheel.
   * That might be ugly, but we handle scrolls somehow
   * anyway, so don't bother here..
   */
  event.preventDefault()
  event.returnValue = false
}

/*
 * Fonction utilisé pour le zoom de la souris
 *
 */
function Zoom (delta) {
  distance += delta * 5
  render()
}

/*
 * Set callback when node selection changes
 *
 */
function setSelectedCallback (func) {
  selectedCallback = func
}

/*
 * Set selected nodes
 *
 */
function setSelectedNodes (nodes) {
  selectedNodes = nodes
  render()
}

/*
 * Set camera position with rectangular coordinates
 *
 */
function setCameraRect (x, y, z) {
  camera.position.x = x
  camera.position.y = y
  camera.position.z = z
  let dx = x - scene.position.x
  let dy = y - scene.position.y
  let dz = z - scene.position.z
  distance = Math.sqrt(dx ** 2 + dy ** 2 + dz ** 2)
  theta = Math.atan2(dx, dz) * 360 / Math.PI
  phi = Math.asin(dy / distance) * 360 / Math.PI
  render()
}

/*
 * Set camera position with spherical coordinates
 *
 */
function setCameraSphere (d, theta_, phi_) {
  distance = d
  theta = theta_
  phi = phi_
  render()
}

/*
 * Set scene origin position
 *
 */
function setScenePosition (x, y, z) {
  scene.position.x = x
  scene.position.y = y
  scene.position.z = z
  render()
}

export const map3d = {
  loadNodes,
  init,
  setSelectedCallback,
  setSelectedNodes,
  setCameraRect,
  setCameraSphere,
  setScenePosition,
}
