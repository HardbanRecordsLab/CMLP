import { createContext, useContext, ReactNode } from 'react';

interface AuthContextValue {
  onLogout: () => void;
}

const AuthContext = createContext<AuthContextValue>({ onLogout: () => {} });

export function AuthProvider({ children, onLogout }: { children: ReactNode; onLogout: () => void }) {
  return (
    <AuthContext.Provider value={{ onLogout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useLogout() {
  return useContext(AuthContext).onLogout;
}
