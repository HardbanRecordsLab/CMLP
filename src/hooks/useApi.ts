import { useState, useCallback } from 'react';
import { auth } from '../lib/firebase';

export function useApi() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const fetchWithAuth = useCallback(async (url: string, options: RequestInit = {}, retries = 2): Promise<Response> => {
    setLoading(true);
    setError(null);
    let lastError: any;

    for (let attempt = 0; attempt <= retries; attempt++) {
      try {
        const user = auth.currentUser;
        if (!user) {
          throw new Error('Not authenticated');
        }
        
        // Force token refresh on retry attempt to handle potential invalidation
        const token = await user.getIdToken(attempt > 0);
        
        const headers = new Headers(options.headers || {});
        headers.set('Authorization', `Bearer ${token}`);

        const response = await fetch(url, { ...options, headers });
        
        // If 401, token might have just expired, try to retry once more with forced refresh
        if (response.status === 401 && attempt < retries) {
          continue;
        }

        if (!response.ok) {
          throw new Error(`API Error: ${response.statusText}`);
        }

        setLoading(false);
        return response;
      } catch (err: any) {
        lastError = err;
        if (attempt === retries) {
           break;
        }
        // Brief wait before retry
        await new Promise(resolve => setTimeout(resolve, 500 * Math.pow(2, attempt)));
      }
    }
    
    setLoading(false);
    setError(lastError?.message || 'Unknown network error');
    throw lastError;
  }, []);

  return { fetchWithAuth, loading, error };
}
