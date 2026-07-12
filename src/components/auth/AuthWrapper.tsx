import React, { useState, useEffect } from 'react';
import Login from '@/components/auth/Login.tsx';
import ForgotPassword from '@/components/auth/ForgotPassword.tsx';
import { AuthProvider } from '@/components/auth/AuthContext.tsx';

interface User {
  uid: string;
  email: string;
  role: string;
  accessToken: string;
  refreshToken?: string;
}

export default function AuthWrapper({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [showForgot, setShowForgot] = useState(false);

  useEffect(() => {
    const storedToken = localStorage.getItem('auth_token');
    const storedUser = localStorage.getItem('auth_user');
    if (storedToken && storedUser) {
      try {
        setUser(JSON.parse(storedUser));
      } catch {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
      }
    }
  }, []);

  const handleLogin = (userData: User) => {
    localStorage.setItem('auth_token', userData.accessToken);
    localStorage.setItem('auth_user', JSON.stringify(userData));
    if (userData.refreshToken) {
      localStorage.setItem('refresh_token', userData.refreshToken);
    }
    setUser(userData);
  };

  const handleLogout = () => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    localStorage.removeItem('refresh_token');
    setUser(null);
  };

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
