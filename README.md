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

# run local dev server with hot reload at localhost:8080
npm run serve

# run local dev server with another REST API host (view .env* files at the root path)
export VUE_APP_IOTLAB_HOST=www.iot-lab.info
npm run serve

# build for production with minification (mode = [dev|dev-beta|prod|prod-beta])
# (eg. 'dev[-beta]' <=> devwww.iot-lab.info/testbed[-beta])
npm run build:<mode>

# run code linter
npm run lint             # Lint code and fix errors
npm run lint -- --no-fix # Run code linter but do not save fixes to disk

# run unit tests
npm run test:unit
npm run test:unit -- --watch # run tests in interactive mode
npm run test:unit -- --u     # update all snapshots

# run end to end tests
npm run test:e2e                # run tests against local dev server with GUI
npm run test:e2e -- --headless  # run tests for CI
npm run test:e2e -- --url <URL> # run tests against given url
```

For detailed explanation on how things work, checkout the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).

Note: project structure created with vue-cli 3.0 `vue create testbed-webportal` with this tooling (Babel, Router, Linter (standard), Unit tests (Jest), e2e tests (cypress))

## Testing

[Testing tools & strategies](Testing.md)

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
