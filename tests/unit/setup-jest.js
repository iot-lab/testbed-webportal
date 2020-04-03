import $ from 'jquery'
import moment from 'moment'
import localStorage from 'jest-localstorage-mock'

global.$ = global.jQuery = $
global.moment = moment

const localStorageMock = {
  getItem: jest.fn(),
  setItem: jest.fn(),
  clear: jest.fn(),
  removeItem: jest.fn()
};
global.localStorage = localStorageMock;
