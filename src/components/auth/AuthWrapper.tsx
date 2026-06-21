import React, { useState } from 'react';
import { User } from 'firebase/auth';
import Login from '@/components/auth/Login.tsx';

export default function AuthWrapper({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);

  if (!user) {
    return <Login onLogin={setUser} />;
  }

  return <>{children}</>;
}
