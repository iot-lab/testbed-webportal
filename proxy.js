// Run a local proxy so that IoT-LAB API can be accessed locally (bypass CORS in browser)
//
// Launch with `$ node proxy.js`
//
// Note: in rest.js API URL must be prefixed (https://devwww.iot-lab.info/api/ => http://localhost:6060/devwww.iot-lab.info:443/api/)


var host = process.env.PORT ? '0.0.0.0' : '127.0.0.1';
var port = process.env.PORT || 6060;
 
var cors_proxy = require('cors-anywhere');
cors_proxy.createServer({
    originWhitelist: [], // Allow all origins 
    // requireHeader: ['origin', 'x-requested-with'],
    removeHeaders: ['cookie', 'cookie2']
}).listen(port, host, function() {
    console.log('Running CORS Anywhere on ' + host + ':' + port);
});