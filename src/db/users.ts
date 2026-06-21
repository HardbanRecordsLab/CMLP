import { db } from './index.ts';
import { users } from './schema.ts';

export async function getOrCreateUser(uid: string, email: string, name?: string) {
  const result = await db.insert(users)
    .values({
      uid,
      email,
      name: name || '',
    })
    .onConflictDoUpdate({
      target: users.uid,
      set: {
        email,
        name: name || '',
      },
    })
    .returning();

  return result[0];
}
