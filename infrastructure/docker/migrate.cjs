const fs = require('fs');
const path = require('path');
const { Client } = require('pg');

const sqlPath = path.resolve(__dirname, '../../drizzle/0000_wet_mauler.sql');
const sql = fs
  .readFileSync(sqlPath, 'utf8')
  .split('--> statement-breakpoint')
  .map(statement => statement.trim())
  .filter(Boolean);

async function main() {
  const client = new Client({
    host: process.env.SQL_HOST || 'localhost',
    port: Number(process.env.SQL_PORT || 5432),
    user: process.env.SQL_USER || 'postgres',
    password: process.env.SQL_PASSWORD || 'postgres',
    database: process.env.SQL_DB_NAME || 'postgres',
    ssl: process.env.SQL_SSL === 'true' ? { rejectUnauthorized: false } : false
  });

  await client.connect();

  try {
    for (const statement of sql) {
      await client.query(statement);
    }

    console.log(`Applied ${sql.length} migration statements`);
  } finally {
    await client.end();
  }
}

main().catch(error => {
  console.error(error);
  process.exit(1);
});
