import { Router } from 'express';
import bcrypt from 'bcryptjs';
import { signToken } from '../../lib/jwt.js';
import { db } from '../../db/index.js';
import { users } from '../../db/schema.js';
import { eq } from 'drizzle-orm';

const router = Router();

router.post('/login', async (req, res) => {
  const { email, password } = req.body;
  
  if (!email || !password) {
    return res.status(400).json({ error: 'Email and password required' });
  }
  
  try {
    const userRecords = await db.select().from(users).where(eq(users.email, email));
    const user = userRecords[0];
    
    if (!user) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }
    
    const isValidPassword = await bcrypt.compare(password, user.pin || '');
    if (!isValidPassword) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }
    
    const token = signToken({
      uid: user.uid,
      email: user.email,
      role: user.role
    });
    
    res.cookie('hrl_cmlp_jwt', token, {
      httpOnly: true,
      secure: true,
      sameSite: 'lax',
      domain: '.hardbanrecordslab.online',
      maxAge: 7 * 24 * 60 * 60 * 1000
    });
    
    res.json({
      uid: user.uid,
      email: user.email,
      role: user.role,
      token
    });
  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

router.post('/register', async (req, res) => {
  const { email, password } = req.body;
  
  if (!email || !password) {
    return res.status(400).json({ error: 'Email and password required' });
  }
  
  try {
    const existingUsers = await db.select().from(users).where(eq(users.email, email));
    if (existingUsers.length > 0) {
      return res.status(409).json({ error: 'User already exists' });
    }
    
    const hashedPassword = await bcrypt.hash(password, 10);
    const uid = `user_${Date.now()}_${Math.random().toString(36).substring(2, 11)}`;
    
    await db.insert(users).values({
      uid,
      email,
      pin: hashedPassword,
      role: 'user'
    });
    
    const token = signToken({
      uid,
      email,
      role: 'user'
    });
    
    res.cookie('hrl_cmlp_jwt', token, {
      httpOnly: true,
      secure: true,
      sameSite: 'lax',
      domain: '.hardbanrecordslab.online',
      maxAge: 7 * 24 * 60 * 60 * 1000
    });
    
    res.status(201).json({
      uid,
      email,
      role: 'user',
      token
    });
  } catch (error) {
    console.error('Register error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

export default router;