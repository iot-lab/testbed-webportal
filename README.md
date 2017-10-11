# Legacy testbed webportal

A new webportal is being developed with Vue.js in branch `vueapp`

## Deployment

Legacy webportal should be deployed as follow:
* branch `release` should be deployed to prod (`www`) (uses user field `structure`)
* branch `master` should be deployed to dev (`devwww`) (uses user field `organization`)

```bash
# deploy with install_lib

# dev
$ git checkout master
$ fab dev deploy_testbed_webportal:"../parts/testbed-webportal/" -H root@devwww.iot-lab.info:2222

# prod
$ git checkout release
$ fab prod deploy_testbed_webportal:"../parts/testbed-webportal/" -H root@www.iot-lab.info:2222
```