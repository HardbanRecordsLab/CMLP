import { useState, useCallback } from 'react';

export function useApi() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const fetchWithAuth = useCallback(async (url: string, options: RequestInit = {}): Promise<Response> => {
    setLoading(true);
    setError(null);

    try {
      const token = localStorage.getItem('auth_token');
      if (!token) {
        throw new Error('Not authenticated');
      }

      const headers = new Headers(options.headers || {});
      headers.set('Authorization', `Bearer ${token}`);

      let response = await fetch(url, { ...options, headers });

      if (response.status === 401) {
        const refresh = localStorage.getItem('refresh_token');
        if (refresh) {
          try {
            const refreshRes = await fetch('/api/auth/refresh', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ refreshToken: refresh })
            });
            if (refreshRes.ok) {
              const data = await refreshRes.json();
              localStorage.setItem('auth_token', data.accessToken);
              localStorage.setItem('refresh_token', data.refreshToken);
              headers.set('Authorization', `Bearer ${data.accessToken}`);
              response = await fetch(url, { ...options, headers });
            }
          } catch {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('refresh_user');
            localStorage.removeItem('refresh_token');
          }
        }
      }

      if (!response.ok) {
        throw new Error(`API Error: ${response.statusText}`);
      }

      setLoading(false);
      return response;
    } catch (err: any) {
      setLoading(false);
      setError(err?.message || 'Unknown network error');
      throw err;
    }
  }, []);

  return { fetchWithAuth, loading, error };
}