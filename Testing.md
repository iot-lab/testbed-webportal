# IoT-LAB testbed frontend testing

| Tooling versions |  before |     after     |
|------------------|:-------:|:-------------:|
| vue              |  2.5.2  |     2.5.16    |
| vue-cli          |  2.8.1  |   3.0.0-rc3   |
| node             |  6.10.2 |   8.11.3 LTS  |
| npm              | 3.10.10 |     5.6.0     |
| vue-test-utils   |    -    | 1.0.0-beta.15 |

| Test Frameworks         |                        before                       |                  after                  |
|-------------------------|:---------------------------------------------------:|:---------------------------------------:|
| Unit tests              | Karma + Mocha + Sinon + Chai (on browser PhantomJS) | Vue-test-utils + Jest (on top of JSDOM) |
| Integration tests (e2e) |            Nightwatch (Selenium + Chrome)           |          Cypress (runs Chrome)          |

## Fewer dependencies

* many devDependencies replaced by one
* ensure coherent dependencies versions
* easier to upgrade dependencies

## Modern tools

* state of the art tech, batteries included
* faster test runs
* get rid of obsolete tech (PhantomJS, Selenium)

## Vue-test-utils

It's the official testing tool for Vue.js projects

## Jest (unit testing)

Jest is a new full featured unit test framework from Facebook

* Fast (run tests in parallel)
* Interactive mode (run changes tests only)
* Snapshot testing
* Coverage
* Mock

`npm run test:unit` -> run unit tests

`npm run test:unit -- --watch` -> run unit tests in interactive mode (great when writing tests)

`npm run test:unit -- -u` -> update all snapshots

## Cypress (integration testing)

Free open source test runner with an optional dashboard service (free for public projects)

* Fast (runs inside the browser as a node process)
* Easy to write, run and debug tests
* Time travel test execution
* Record screenshots or videos

`npm run test:e2e` -> launch dev server and run cypress GUI

`npm run test:e2e -- --headless` -> launch dev server and run tests without GUI (for CI)

`npm run test:e2e -- --url` -> run tests against given url instead of starting dev server

`npm run test:e2e -- --mode` -> specify the mode the dev server should run in (default: production)

### Test runner / dashboard

https://docs.cypress.io/guides/core-concepts/test-runner.html#Overview
https://docs.cypress.io/guides/core-concepts/dashboard-service.html

### Screenshots / videos

https://docs.cypress.io/guides/guides/screenshots-and-videos.html

Cypress will automatically capture screenshots when a failure happens but only during a headless run.
Cypress also records videos when running headlessly from the CLI.

### Ajax testing strategies

https://docs.cypress.io/guides/guides/network-requests.html

It's possible to issue real ajax API requests to the backend server for real end to end testing, hence you test exactly what the end user gets.

Or it's possible to stub API requests or responses with fixtures. So that it runs faster and it's easier to test edge cases.
