# Bolt.new — Integracja z Istniejącą Platformą CMLP
## Jak użyć Bolt.new do rozbudowy istniejącego systemu

**Data:** 2026-07-04  
**Cel:** Praktyczne instrukcje integracji Bolt.new z istniejącą platformą CMLP

---

## 1. CO TO JEST BOLT.NEW?

Bolt.new to AI-powered full-stack generator, który tworzy kompletne aplikacje z promptu. Dla istniejącej platformy CMLP możemy go używać jako **generatora nowych features, komponentów i modułów**, które następnie integrujemy z istniejącym kodem.

**Link:** https://bolt.new  
**Koszt:** Free tier (wystarczy dla CMLP)

---

## 2. STRATEGIA INTEGRACJI — 3 SCENARIUSZE

### Scenario A: Nowy Moduł/Feature w CMLP
**Przykład:** Chcesz dodać moduł "Brand Brief Matcher" (AI matching utworów do briefów kampanii)

**Krok 1:** Wygeneruj kod w Bolt.new
```
Prompt do Bolt.new:
"Build a React component for brand brief matching:
- Textarea for pasting brand brief
- Button to trigger AI matching
- Display top 10 track matches with confidence scores
- Use Tailwind CSS + TypeScript
- Fetch data from /api/ai/match endpoint
- Show loading state + error handling"

Bolt.new wygeneruje:
- Komponent React (BrandBriefMatcher.tsx)
- Styling (Tailwind)
- API integration code
```

**Krok 2:** Eksport kodu z Bolt.new
- Kliknij "Export" → "Download ZIP"
- Lub skopiuj kod bezpośrednio z edytora

**Krok 3:** Integracja z CMLP
```bash
# 1. Rozpakuj ZIP do tymczasowego folderu
# 2. Skopiuj wygenerowane komponenty do src/components/content/
cp BrandBriefMatcher.tsx src/components/content/

# 3. Dodaj importy do istniejących plików
# 4. Dostosuj backend API jeśli needed
# 5. Test integracji
```

**Krok 4:** Dostosowanie do CMLP
- Może być potrzeba zmiany importów (dostosuj do struktury CMLP)
- Dostosuj API endpoints (CMLP używa /api/*)
- Dodaj autoryzację (JWT + Firebase)
- Testy

---

### Scenario B: Prototypowanie Nowej Feature (Proof of Concept)
**Przykład:** Chcesz przetestować czy Multi-Location Management da się zrobić w 2 dni

**Krok 1:** Generuj standalone prototype w Bolt.new
```
Prompt:
"Build a multi-location management dashboard:
- List of locations per company
- Each location has: name, address, type, playlist assignment
- Bulk operations: assign playlist, change volume, schedule
- Analytics per location
- Use React + TypeScript + Tailwind
- Mock data for 3 companies, 10 locations
- Export as standalone React app"

Bolt.new wygeneruje pełną aplikację React z mockami
```

**Krok 2:** Testuj prototype
- Deploy na Vercel (1 kliknięcie z Bolt.new)
- Pokaż stakeholderom
- Zbierz feedback

**Krok 3:** Implementacja w CMLP
- użyj wygenerowanego kodu jako reference
- implementuj w CMLP z użyciem Cursor + Claude
- integruj z prawdziwą bazą danych

**Korzyść:** Zamiast tracić 2 dni na implementację w CMLP, masz działający prototype w 30 minut.

---

### Scenario C: Generowanie UI Komponentów
**Przykład:** Chcesz nowy admin dashboard dla license management

**Krok 1:** Generuj UI w Bolt.new
```
Prompt:
"Create a license management dashboard with:
- Sidebar navigation
- Stats cards (total licenses, active, expired, revenue)
- Data table with filters (company, status, date range)
- Modal for creating/editing licenses
- PDF preview panel
- Export to CSV button
- Dark mode support
- Use React + TypeScript + Tailwind + shadcn/ui
- Mock data: 20 licenses"

Bolt.new wygeneruje kompletny dashboard
```

**Krok 2:** Eksport i integracja
```bash
# Skopiuj tylko potrzebne komponenty:
- AdminDashboard.tsx → src/components/admin/
- LicenseTable.tsx → src/components/admin/
- LicenseModal.tsx → src/components/admin/
- StatsCards.tsx → src/components/admin/

# Usuń pliki konfiguracyjne Bolt.new (package.json, tailwind.config.ts)
# Zostaw tylko komponenty
```

**Krok 3:** Podmień mock data na prawdziwe API
- Zamień `const [licenses, setLicenses] = useState(mockData)` 
- Na: `const { data } = useSWR('/api/licenses')`
- Dodaj JWT auth headers
- Testuj z prawdziwym backendem

---

## 3. KROTKI PRZEWODNIK INTEGRACJI

### Krok 1: Przygotuj Prompt dla Bolt.new

**Zasady dobrego promptu:**
1. **Specyfikuj stack:** React + TypeScript + Tailwind
2. **Opisz UI:** Komponenty, layout, styling
3. **Określ integrację:** API endpoints, auth, data fetching
4. **Podaj kontekst:** "This will be integrated into existing CMLP platform"

**Przykład dobrego promptu:**
```
Build a React component for audio file upload:
- Drag & drop zone + file picker
- Upload to /api/tracks (POST, multipart/form-data)
- Show upload progress (percentage)
- Display file info (name, size, duration)
- Cancel button
- Error handling + retry
- Use React + TypeScript + Tailwind
- Return component code only (no app wrapper)
```

---

### Krok 2: Generuj i Eksportuj

1. Wklej prompt do Bolt.new
2. Poczekaj na generowanie (30-60 sekund)
3. Preview w prawym panelu
4. Jeśli OK → kliknij **"Export"** → **"Download Code"**
5. Rozpakuj ZIP

---

### Krok 3: Integracja z CMLP

**Opcja A: Podstawowa integracja (szybka)**
```bash
# 1. Skopiuj pliki
cp -r bolt-new-output/src/components/* src/components/

# 2. Zainstaluj dependencies (jeśli needed)
npm install [nowe dependencies z Bolt.new]

# 3. Import w App.tsx
import { NewComponent } from './components/NewComponent';

# 4. Test
npm run dev
```

**Opcja B: Zaawansowana integracja (z Cursor AI)**
```bash
# 1. Otwórz wygenerowany plik w Cursor
# 2. Zapytaj Claude:
"Integrate this component into existing CMLP codebase:
- Replace mock data with real API calls to /api/tracks
- Add JWT token from localStorage
- Match existing error handling patterns
- Match existing TypeScript types from src/types.ts
- Ensure compatibility with existing Tailwind config"

# 3. Claude zintegruje automatycznie
```

---

### Krok 4: Testowanie

```bash
# Unit tests
npm run test src/components/NewComponent.tsx

# E2E tests (jeśli needed)
npx playwright test

# Lint + format
npm run lint
npm run format
```

---

## 4. PRZYKŁADY UŻYCIA BOLT.NEW DLA CMLP

### 4.1 White-Label Player Component

```
Prompt:
"Build a customizable white-label audio player:
- Play/Pause button
- Progress bar (seekable)
- Volume control
- Time display (current / total)
- Playlist sidebar (collapsible)
- Branding support: primaryColor, logoUrl
- React + TypeScript + Tailwind
- Props interface for customization
- Use Howler.js for audio playback"

Output: Komponent gotowy do użycia w CMLP
```

---

### 4.2 Admin Dashboard dla Licensing

```
Prompt:
"Create an admin dashboard for music licensing:
- Sidebar: Dashboard, Tracks, Playlists, Licenses, Payments, Settings
- Dashboard page: Stats cards (total tracks, licenses, revenue, active users)
- Licenses page: Table with filters, search, pagination
- Payments page: Transaction history, refund button, export CSV
- Use React + TypeScript + Tailwind + shadcn/ui
- Dark mode support
- Responsive design"

Output: Cały admin panel (podstawa do integracji)
```

---

### 4.3 Payment Flow (Stripe Checkout)

```
Prompt:
"Build a Stripe checkout flow:
- Plan selection (Starter $199, Business $499, Enterprise $999)
- Form: company name, email, VAT ID
- Stripe Elements integration
- Success/cancel pages
- Webhook handler stub
- React + TypeScript + Tailwind
- Use @stripe/react-stripe-js"

Output: Kompletny flow płatności
```

---

### 4.4 WordPress Sync UI

```
Prompt:
"Create a WordPress sync configuration page:
- Input fields: WP URL, username, app password
- Test connection button
- Sync direction: bidirectional, CMLP→WP, WP→CMLP
- Sync logs table (timestamp, action, status, message)
- Manual sync trigger button
- React + TypeScript + Tailwind"

Output: UI do zarządzania WordPress sync
```

---

### 4.5 AI Track Tagging UI

```
Prompt:
"Build an AI tagging interface for audio tracks:
- Track list with columns: title, artist, BPM, key, genre, mood, energy
- Bulk tagging button (triggers AI analysis)
- Progress bar for batch processing
- Inline editing for tags
- Filter by genre/mood/BPM
- Export tags as JSON
- React + TypeScript + Tailwind"

Output: UI do tagowania utworów
```

---

## 5. INTEGRACJA Z CURSEM (AI-Assisted)

### Workflow: Bolt.new → Cursor → CMLP

**Krok 1:** Wygeneruj kod w Bolt.new
```
Prompt: "Create a playlist manager component..."
→ [Wygenerowano kod]
```

**Krok 2:** Otwórz w Cursor
```bash
# Skopiuj wygenerowane pliki do projektu
cp -r bolt-output/* src/components/

# Otwórz w Cursor
cursor src/components/PlaylistManager.tsx
```

**Krok 3:** Poproś Claude o integrację
```
Cursor chat:
"Integrate this PlaylistManager component into CMLP:

Requirements:
1. Replace mock data with real API calls to /api/playlists
2. Add JWT auth headers (use getToken() from src/lib/auth.ts)
3. Match existing error handling (use AppError from src/utils/errors.ts)
4. Replace TypeScript types with CMLP types from src/types.ts
5. Add loading states with existing LoadingSpinner component
6. Ensure compatibility with existing Tailwind config

Existing patterns:
- API calls use axios with baseURL from src/utils.ts
- Auth: const token = await getToken(); axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
- Error handling: try/catch with AppError
- Components: functional components with hooks, no class components"
```

**Krok 4:** Claude automatycznie dostosuje kod
```typescript
// Before (Bolt.new):
const [playlists, setPlaylists] = useState(mockData);

// After (Claude):
const [playlists, setPlaylists] = useState<Playlist[]>([]);
const [loading, setLoading] = useState(true);
const [error, setError] = useState<string | null>(null);

useEffect(() => {
  fetchPlaylists();
}, []);

const fetchPlaylists = async () => {
  try {
    const token = await getToken();
    const response = await axios.get('/api/playlists', {
      headers: { Authorization: `Bearer ${token}` }
    });
    setPlaylists(response.data);
  } catch (err) {
    setError(err.message);
  } finally {
    setLoading(false);
  }
};
```

**Krok 5:** Review + testy
- CodeRabbit: automatyczny review
- Testy manualne
- Commit

---

## 6. ZAWRTOŚĆ BOLT.NEW — CO MOŻNA GENEROWAĆ

### ✅ DOBRE DO GENEROWANIA W BOLT.NEW:
1. **UI Komponenty** — player, dashboard, modale, tabele
2. **Prototypy** — szybkie proof-of-concept
3. **Formularze** — upload, edycja, konfiguracja
4. **Statyczne strony** — landing pages, pomoc, FAQ
5. **Mockupy** — prezentacje dla klientów
6. **Testy** — unit tests, E2E tests (Playwright)

### ❌ NIE GENERUJ W BOLT.NEW:
1. **Backend logic** — npm run dev nie zadziała bez backendu
2. **Baza danych** — schema, migrations
3. **Authentication** — używa własnego auth, nie Firebase/JWT
4. **Complex business logic** — lepiej zrobić ręcznie z pomocą Cursor
5. **Existing CMLP files** — nie generuj z istniejących plików, tylko nowe

---

## 7. TYPICAL WORKFLOW — TYDZIEŃ Z BOLT.NEW

### Monday: Prototypowanie
```
09:00 — Bolt.new: Generujesz nowy feature (np. "Brand Brief Matcher")
10:00 — Testujesz prototype w przeglądarce
11:00 — Zbierasz feedback
12:00 — Lunch

13:00 — Cursor: Otwierasz wygenerowany kod
14:00 — Claude: Integruje z CMLP (API, auth, types)
15:00 — CodeRabbit: Review
16:00 — Testing
17:00 — GitHub: PR
```

### Tuesday-Thursday: Implementacja
```
09:00 — Cursor: Development na podstawie prototypu
10:30 — CodeRabbit: Review
11:00 — Naprawki
12:00 — Lunch

13:00 — Cursor: Kontynuacja
15:00 — Bolt.new: Generujesz kolejny komponent (np. "Schedule Builder UI")
16:00 — Integracja
17:00 — Testing
```

### Friday: Deploy
```
09:00 — Final testing
10:00 — GitHub Actions: Deploy
11:00 — Sentry: Sprawdź błędy
12:00 — Lunch

13:00 —Monitoring (UptimeRobot)
14:00 — Documentation (Mintlify)
15:00 — Sprint review
16:00 — Plan następnego tygodnia
```

---

## 8. TROUBLESHOOTING

### Problem: "Bolt.new generuje zły stack"
**Rozwiązanie:**
- Zawsze podawaj stack: "React + TypeScript + Tailwind"
- Jeśli generuje Next.js, dodaj: "No Next.js, just React"
- Jeśli generuje Python, dodaj: "No Python, backend only"

### Problem: "Kod z Bolt.new nie pasuje do CMLP"
**Rozwiązanie:**
- Użyj Cursor + Claude do refactoringu
- Podaj Claude istniejące wzorce z CMLP (src/components/AdminDashboard.tsx)
- Claude automatycznie dostosuje styl

### Problem: "Bolt.new ma zbyt dużo dependencies"
**Rozwiązanie:**
- Wygeneruj tylko potrzebne komponenty
- Usuń pliki: package.json, tsconfig.json, tailwind.config.ts
- Zostaw tylko .tsx/.ts files
- Zintegruj ręcznie z istniejącą konfiguracją

---

## 9. PRZYKŁAD: COMPLETE INTEGRATION

### Zadanie: Dodaj "AI Brand Brief Matcher" do CMLP

**Krok 1: Bolt.new — Generuj UI**
```
Prompt:
"Build a React component for AI-powered music matching:
- Textarea: 'Paste your brand brief here...'
- Button: 'Find Matching Tracks'
- Results grid: track cards with cover art, title, artist, match score
- Loading spinner during analysis
- Error message if no matches
- Use React + TypeScript + Tailwind
- Component name: BrandBriefMatcher
- Props: onMatchSelect(trackId: string)"

Output: BrandBriefMatcher.tsx (150 lines)
```

**Krok 2: Eksport**
```bash
# Pobierz ZIP z Bolt.new
# Rozpakuj
cp BrandBriefMatcher.tsx src/components/content/
```

**Krok 3: Cursor — Integracja z CMLP**
```bash
# Otwórz w Cursor
cursor src/components/content/BrandBriefMatcher.tsx

# Claude chat:
"Integrate BrandBriefMatcher into CMLP:

1. Replace mock data with API call to POST /api/ai/match
   Request body: { brief: string }
   Response: { tracks: Array<{id, title, artist, coverUrl, score}> }

2. Add auth: import { getToken } from '../../lib/auth'
   Headers: Authorization: Bearer ${token}

3. Add error handling using AppError from '../../utils/errors'

4. Add loading state using existing LoadingSpinner component

5. onMatchSelect should navigate to /tracks/:id

6. Match existing CMLP patterns (see src/components/content/PlaylistManager.tsx as reference)"
```

**Krok 4: Backend — API endpoint (jeśli nie istnieje)**
```bash
# W Cursor, zapytaj Claude:
"Create POST /api/ai/match endpoint:

1. Route: src/routes/ai.routes.ts (new file)
2. Controller: src/controllers/ai.controller.ts
3. Service: src/services/ai-matching.service.ts
4. Use Google GenAI to analyze brief
5. Query track_tags table for matching tracks
6. Return top 10 matches with scores
7. Auth: requireAuth + requireRole(['client', 'admin'])"
```

**Krok 5: Test + Deploy**
```bash
npm run test
npm run build
git add .
git commit -m "feat: add AI Brand Brief Matcher"
git push origin main
# GitHub Actions automatycznie deploy
```

**Result:** Feature gotowa w 4-6 godzin zamiast 2-3 dni

---

## 10. BEST PRACTICES

### DO:
✅ Używaj Bolt.new do generowania UI i prototypów
✅ Eksportuj tylko potrzebne komponenty
✅ Integruj z Cursor + Claude do dostosowania
✅ Testuj każdy wygenerowany kod
✅ Review'uj przez CodeRabbit przed merge

### NIE:
❌ Nie generuj backend logic w Bolt.new (użyj Cursor)
❌ Nie generuj plików konfiguracyjnych (nie potrzebujesz ich)
❌ Nie ufaj 100% generowanemu kodowi — zawsze review
❌ Nie skip'uj testów
❌ Nie commituj kodu bez CI/CD check

---

## 11. KOSZTY

| Narzędzie | Koszt | Użycie w CMLP |
|-----------|-------|---------------|
| **Bolt.new** | $0 (free tier) | Generowanie features, UI, prototypy |
| **Cursor** | $0 (2000/miesiąc) | Integracja, refactoring, debugging |
| **CodeRabbit** | $0 (10 PR/miesiąc) | Code review |
| **RAZEM** | **$0/miesiąc** | |

**Ograniczenia free tier:**
- Bolt.new: ~50 generacji/miesiąc (wystarczy)
- Cursor: 2000 completions/miesiąc (wystarczy na 1-2 devs)
- CodeRabbit: 10 PR/miesiąc (wystarczy na mały zespół)

---

## 12. QUICK REFERENCE

### Przykładowe Prompts dla CMLP:

**1. Upload Component:**
```
"Build a drag & drop audio upload component with:
- File validation (MP3, FLAC, WAV, max 100MB)
- Progress bar
- File metadata display (name, size, duration)
- Cancel button
- Error handling
- React + TypeScript + Tailwind"
```

**2. Player Component:**
```
"Build a white-label audio player:
- Play/Pause, Next/Previous
- Progress bar (seekable)
- Volume control
- Time display
- Playlist sidebar
- PropTypes for customization (colors, logo)
- React + TypeScript + Tailwind"
```

**3. Admin Table:**
```
"Build a data table component for license management:
- Columns: ID, Company, Status, Issued Date, Expiry Date, Actions
- Filters: status, date range, company search
- Sort by columns
- Pagination (20 items/page)
- Actions: view, edit, revoke, renew
- React + TypeScript + Tailwind + shadcn/ui"
```

**4. Dashboard:**
```
"Build an admin dashboard with:
- Sidebar navigation
- Stats cards (total, active, revenue)
- Line chart (revenue over time)
- Recent activity feed
- Quick actions
- React + TypeScript + Tailwind + Recharts"
```

---

## 13. FAQ

**Q: Czy mogę używać Bolt.new z istniejącą bazą danych?**  
A: Nie bezpośrednio. Bolt.new generuje kod z mockami. Musisz podmienić na prawdziwe API calls.

**Q: Co jeśli wygenerowany kod używa innych bibliotek?**  
A: Cursor + Claude pomogą zastąpić biblioteki na te używane w CMLP.

**Q: Ile czasu to zajmuje?**  
A: Generowanie: 30-60s. Integracja: 1-2h na komponent, 4-6h na feature.

**Q: Czy warto?**  
A: Tak, dla prototypów i UI komponentów zaoszczędzasz 50-70% czasu.

---

## 14. NEXT STEPS

1. **Dzisiaj:** Zarejestruj się na https://bolt.new
2. **Jutro:** Wygeneruj pierwszy komponent (np. upload UI)
3. **Tydzień 1:** Wygeneruj 3-4 komponenty, zintegruj z CMLP
4. **Tydzień 2:** Prototypuj nowe features w Bolt.new, testuj, implementuj
5. **Miesiąc 1:** Masz gotowe ~5-6 features wygenerowanych przez Bolt.new

**Powodzenia! 🚀**