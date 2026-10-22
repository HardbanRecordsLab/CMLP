import {StrictMode} from 'react';
import {createRoot} from 'react-dom/client';
import './i18n.ts';
import { initHrlI18n } from './lib/hrl/hrl-i18n';
import { PAIRS } from './lib/hrl/dict';
import App from './App.tsx';
import './index.css';

// Shared EN/PL runtime: translates the strings that are still hardcoded in components (i18next covers the rest).
initHrlI18n({ pairs: PAIRS, storageKey: 'cmlp_lang' });

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <App />
  </StrictMode>,
);
