# testbed-webportal

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

## Deployment

``` bash
# run tests, build and deploy
fab test build deploy -H root@devwww:2222
```
