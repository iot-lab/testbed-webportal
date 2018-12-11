##  1.1.0 (2018-12-11)

### New features

* Resources
  * add user & preset firmwares store
* Experiment details
  * add per-node actions
  * add node uids
  * add update monitoring to running nodes
  * add flash firmwares from store
  * view node camera
  * access node serial port in web terminal
* New experiment
  * handle firmware upload collision (auto add md5 to the filename)
* User account
  * delete user account is now functional
  * updated mailing list link

### Enhancements

* Various bug fixes and UI improvements

### Internal

* Remove old api
* Update nodes properties to both apis
* Prevent signup to devwww

##  1.0.2 (2018-10-12)

### Enhancements

* Synced with wordpress menu
* Dashboard experiment list is now full width
* Experiment details:
  * add per-node actions
  * add node uids

### Internal

* Cleaned repo (fabfile, beta envs)
* Tweaked linter config

##  1.0.1 (2018-09-27)

### Enhancements

* [New experiment] Click-to-fill available node ids
* Autofocus search users input
* Some UI polish (spinner, margins)

### Bug fixes

* Fix edition of radio monitoring profile with sniffer mode
* Fix running experiment double polling
* Fix experiment details polling

##  1.0.0 (2018-09-19)

### Enhancements

* Rewrite from scratch from legacy php webportal
* Use new version of IoT-LAB rest api
* Modern looking ergonomic UI
* Faster experiments dashboard with live refresh
* Vastly improved UI for experiment submission
* Added user accounts (edit profile, delete)
* Added user groups management
* Added Testbed status page
  * running experiments
  * node properties
* Removed monika
* Removed stats

### Internal

* [project]
  * Single page application
  * Javascript Framework (Vue.js)
  * Static files only (no backend)
  * Unit tests (vue-test-utils and Jest)
  * Integration tests (Cypress)
  * Code linter (standard)
  * Local development server
  * Build step

* [versions]
  * Vue.js 2.5.17
  * Bootstrap 4.0.0-beta.2
  * Three.js 0.68.0
  * Node.js 8.11.3 LTS
  * npm 5.6.0
  * vue-cli 3.0.3
  