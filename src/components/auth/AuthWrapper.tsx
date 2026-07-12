import React, { useState, useEffect, useCallback } from 'react';
import Login from '@/components/auth/Login.tsx';
import ForgotPassword from '@/components/auth/ForgotPassword.tsx';
import { AuthProvider } from '@/components/auth/AuthContext.tsx';

interface User {
  uid: string;
  email: string;
  role: string;
}

export default function AuthWrapper({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [showForgot, setShowForgot] = useState(false);
  const [checking, setChecking] = useState(true);

  useEffect(() => {
    const storedUser = localStorage.getItem('auth_user');
    if (storedUser) {
      try {
        const parsed = JSON.parse(storedUser);
        if (parsed.uid && parsed.email) {
          setUser(parsed);
        }
      } catch {
        localStorage.removeItem('auth_user');
      }
    }
    setChecking(false);
  }, []);

  const handleLogin = useCallback((userData: User) => {
    localStorage.setItem('auth_user', JSON.stringify({ uid: userData.uid, email: userData.email, role: userData.role }));
    setUser(userData);
  }, []);

  const handleLogout = useCallback(() => {
    localStorage.removeItem('auth_user');
    fetch('/api/auth/logout', { method: 'POST', credentials: 'include' }).catch(() => {});
    setUser(null);
  }, []);

  if (checking) return null;

  if (!user) {
    if (showForgot) {
      return <ForgotPassword onBack={() => setShowForgot(false)} />;
    }
    return <Login onLogin={handleLogin} onForgot={() => setShowForgot(true)} />;
  }

  return (
    <AuthProvider onLogout={handleLogout}>
      {children}
    </AuthProvider>
  );
}
