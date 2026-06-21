export function getApiUrl(path: string): string {
  if (path.startsWith('http://') || path.startsWith('https://')) {
    return path;
  }
  const baseUrl = import.meta.env.VITE_API_URL;
  if (!baseUrl) {
    return path;
  }
  const cleanBase = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;
  const cleanPath = path.startsWith('/') ? path : `/${path}`;
  return `${cleanBase}${cleanPath}`;
}

export function getWsUrl(): string {
  const customBaseUrl = import.meta.env.VITE_API_URL;
  if (customBaseUrl) {
    try {
      const url = new URL(customBaseUrl);
      const wsProtocol = url.protocol === 'https:' ? 'wss:' : 'ws:';
      return `${wsProtocol}//${url.host}/`;
    } catch (e) {
      let formatted = customBaseUrl.replace(/^http:/, 'ws:').replace(/^https:/, 'wss:');
      if (!formatted.startsWith('ws:') && !formatted.startsWith('wss:')) {
        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        formatted = `${protocol}//${formatted}`;
      }
      return formatted.endsWith('/') ? formatted : `${formatted}/`;
    }
  }
  const protocol = window.location.protocol === "https:" ? "wss:" : "ws:";
  return `${protocol}//${window.location.host}/`;
}
