# testbed-webportal

[![Build Status](https://ci.inria.fr/iot-lab/buildStatus/icon?job=testbed-webportal)](https://ci.inria.fr/iot-lab/job/testbed-webportal/)

> A Vue.js webportal for the FIT IoT-LAB testbed

## Requirements

* [Node.js](https://nodejs.org), for development
* Python & Fabric, for deployment


## Development

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

# build for production and view the bundle analyzer report
npm run build --report

# run unit tests
npm run unit

# run e2e tests
npm run e2e

# run all tests
npm test
```

For detailed explanation on how things work, checkout the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).

Note: project structure created with vue-cli `vue init webpack testbed-webportal`

## Deployment

``` bash
# run tests, build and deploy
fab test build deploy -H root@devwww:2222
```

## IoT-LAB API

New API url is `https://devwww|www.iot-lab.info/api/`
[Documentation](https://devapi.iot-lab.info)

``` bash
# test API
curl https://devwww.iot-lab.info/api/sites | python -m json.tool
# with authentification
curl -u <login> https://devwww.iot-lab.info/api/<...> | python -m json.tool
```