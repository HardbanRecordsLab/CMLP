const jwt = require('/opt/cmlp/node_modules/jsonwebtoken');

const payload = {
  uid: 'wp-cmlp-bridge',
  email: 'wp-bridge@hardbanrecordslab.online',
  name: 'WordPress CMLP Bridge',
  role: 'admin'
};

const token = jwt.sign(payload, 'hrl_jwt_production_secret_2026_change_me', { expiresIn: '365d' });
console.log('TOKEN=' + token);
