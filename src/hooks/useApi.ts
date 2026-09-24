import { useState, useCallback } from 'react';

export function getCsrfToken(): string | undefined {
  const match = document.cookie.match(/(?:^|;\s*)hrl_csrf2=([^;]*)/);
  return match ? decodeURIComponent(match[1]) : undefined;
}

export function useApi() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const fetchWithAuth = useCallback(async (url: string, options: RequestInit = {}): Promise<Response> => {
    setLoading(true);
    setError(null);

    try {
      const headers = new Headers(options.headers || {});

      if (options.method && !['GET', 'HEAD'].includes(options.method.toUpperCase())) {
        const csrf = getCsrfToken();
        if (csrf) headers.set('X-CSRF-Token', csrf);
      }

      let response = await fetch(url, {
        ...options,
        headers,
        credentials: 'include',
      });

      if (response.status === 401) {
        try {
          const refreshCsrf = getCsrfToken();
          const refreshRes = await fetch('/api/auth/refresh', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              ...(refreshCsrf ? { 'X-CSRF-Token': refreshCsrf } : {}),
            },
            credentials: 'include',
          });
          if (refreshRes.ok) {
            response = await fetch(url, { ...options, headers, credentials: 'include' });
          }
        } catch {
          // refresh failed
        }
      }

      if (response.status === 401) {
        // Refresh didn't recover the session (expired refresh token, revoked,
        // etc.) - bounce to login instead of leaving the caller to render a
        // confusing "failed to load" / empty state that looks like data loss.
        localStorage.removeItem('auth_user');
        window.location.reload();
        return new Promise<Response>(() => {}); // reload is navigating away
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