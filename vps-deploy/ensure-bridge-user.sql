-- Ensure wp-cmlp-bridge user exists in hbrl_master database
INSERT INTO users (uid, email, name, role, pmpro_level, mfa_enabled)
VALUES ('wp-cmlp-bridge', 'wp-bridge@hardbanrecordslab.online', 'WordPress CMLP Bridge', 'admin', 1, false)
ON CONFLICT (uid) DO NOTHING;

-- Verify the user was created
SELECT uid, email, name, role FROM users WHERE uid = 'wp-cmlp-bridge';