import jwt from 'jsonwebtoken';

const secret = 'hrl_jwt_production_secret_2026_change_me';

const payload = {
  uid: 'wp-cmlp-bridge',
  email: 'wp-bridge@hardbanrecordslab.online',
  name: 'WordPress CMLP Bridge',
  role: 'admin'
};

const token = jwt.sign(payload, secret, { expiresIn: '365d' });
console.log(token);
