import { drizzle } from 'drizzle-orm/node-postgres';
import pg from 'pg';
import * as schema from './schema.ts';

const { Pool } = pg;

let poolInstance: pg.Pool | null = null;
let drizzleInstance: ReturnType<typeof drizzle> | null = null;

export const getPool = () => {
  if (!poolInstance) {
    const password = process.env.SQL_PASSWORD || '';
    const user = process.env.SQL_USER || '';
    const host = process.env.SQL_HOST || 'localhost';
    const dbName = process.env.SQL_DB_NAME || 'hbrl_master';
    
    poolInstance = new Pool({
      host,
      port: parseInt(process.env.SQL_PORT || '5432', 10),
      user,
      password,
      database: dbName,
      connectionTimeoutMillis: 15000,
      idleTimeoutMillis: 30000,
      max: 20,
    });
    poolInstance.on('error', (err) => {
      console.error('Unexpected error on idle SQL pool client:', err);
    });
  }
  return poolInstance;
};

export const createPool = getPool;

export const db = new Proxy({} as any, {
  get(target, prop) {
    if (!drizzleInstance) {
      drizzleInstance = drizzle(getPool(), { schema });
    }
    return Reflect.get(drizzleInstance, prop);
  }
});
