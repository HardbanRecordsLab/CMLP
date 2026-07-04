const jwt = require('/opt/cmlp/node_modules/jsonwebtoken');
const http = require('http');

const payload = {
  uid: 'wp-cmlp-bridge',
  email: 'wp-bridge@hardbanrecordslab.online',
  name: 'WordPress CMLP Bridge',
  role: 'admin'
};

const token = jwt.sign(payload, 'hrl_jwt_production_secret_2026_change_me', { expiresIn: '365d' });
console.log('TOKEN=' + token);

const options = {
  hostname: 'localhost',
  port: 3007,
  path: '/api/tracks',
  method: 'GET',
  headers: {
    'Authorization': 'Bearer ' + token
  }
};

const req = http.request(options, (res) => {
  let data = '';
  res.on('data', (chunk) => { data += chunk; });
  res.on('end', () => {
    console.log('TRACKS_STATUS=' + res.statusCode);
    console.log('TRACKS_BODY=' + data.substring(0, 500));
  });
});

req.on('error', (e) => { console.error('ERROR=' + e.message); });
req.end();
