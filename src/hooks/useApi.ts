import { useState, useCallback } from 'react';
import { refreshAccessToken } from '@/lib/jwt.ts';

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
          const tokens = refreshAccessToken(refresh);
          if (tokens) {
            localStorage.setItem('auth_token', tokens.accessToken);
            localStorage.setItem('refresh_token', tokens.refreshToken);
            headers.set('Authorization', `Bearer ${tokens.accessToken}`);
            response = await fetch(url, { ...options, headers });
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