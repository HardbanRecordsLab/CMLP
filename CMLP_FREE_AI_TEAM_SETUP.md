# CMLP — Darmowa Ekipa AI & Edytor do Ukończenia Platformy
## End-to-End Development Setup za $0

**Data:** 2026-07-04  
**Cel:** Konkretny stack narzędziowy + edytor do kompletnego zbudowania CMLP bez kosztów

---

## 1. EDITOR / IDE — WYBÓR

### 🥇 REKOMENDACJA: **Cursor**

**Download:** https://cursor.sh

**Dlaczego Cursor?**
- Opiera się na VS Code — ten sam ekosystem extensions
- Wbudowany AI (Claude 3.5 Sonnet, GPT-4, Gemini) — najlepsze modele code generation
- Autocomplete + inline chat + code generation + debugging w jednym
- Działa offline (po skonfigurowaniu)
- 2000 completions/miesiąc za darmo (wystarczy na 1-2 developerów)

**Konfiguracja Cursor dla CMLP:**

```json
// settings.json (Cursor → Settings → Open Settings JSON)
{
  "ai.model": "claude-3-5-sonnet-20241022",
  "ai.enableChat": true,
  "ai.enableAutocomplete": true,
  "editor.formatOnSave": true,
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": true
  },
  "files.associations": {
    "*.ts": "typescript",
    "*.tsx": "typescriptreact"
  },
  "typescript.tsdk": "node_modules/typescript/lib",
  "tailwindCSS.experimental.classRegex": [
    ["cva\\(([^)]*)\\)", "[\"'`]([^\"'`]*).*?[\"'`]"]
  ]
}
```

**Extensions do zainstalowania w Cursor:**
1. **ESLint** — lintowanie TypeScript
2. **Prettier** — formatowanie kodu
3. **Tailwind CSS IntelliSense** — autocomplete dla Tailwind
4. **Drizzle ORM** — hints dla SQL queries
5. **GitHub Copilot** — dodatkowy autocomplete (jeśli masz klucz)
6. **Continue** — open-source AI chat (alternatywa dla wbudowanego)
7. **Thunder Client** — testowanie API zamiast Postman

---

## 2. DARMOWA EKIPA AI — 7 NARRZĘDZI

### 💰 CAŁKOWITY KOSZT: **$0/miesiąc**

| Rola | Narzędzie | Koszt/miesiąc | Zastosowanie w CMLP |
|------|-----------|----------------|---------------------|
| **1. Główny Developer (AI)** | **Cursor + Claude 3.5 Sonnet** | $0 | Code generation, refactoring, debugging |
| **2. Code Reviewer** | **CodeRabbit** | $0 | Automatyczny code review na PR |
| **3. UI Designer** | **v0.dev (Vercel)** | $0 | Generowanie React + Tailwind komponentów |
| **4. Full-Stack Generator** | **Bolt.new** | $0 | Szybkie prototypowanie nowych features |
| **5. Autonomous Agent** | **OpenDevin** | $0 | Automatyzacja zadań (testy, deployment) |
| **6. Documentation** | **Mintlify** | $0 | Automatyczna dokumentacja z kodu |
| **7. Project Manager** | **GitHub Projects + Claude** | $0 | Zarządzanie sprintami, tasks, milestones |

---

## 3. OPIS KAŻDEGO NARZĘDZIA

### 3.1 Cursor (Główny Developer AI)

**Link:** https://cursor.sh  
**Model:** Claude 3.5 Sonnet (darmowy tier)  
**Limity:** 2000 completions/miesiąc

**Co robi:**
- Autocomplete podczas pisania kodu (TypeScript, React, SQL)
- Inline chat — wyjaśnienia kodu, sugestie, debugowanie
- Code generation — generuje całe komponenty/funkcje z promptu
- Refactoring — automatyczne poprawki kodu
- Error fixing — rozwiązywanie błędów kompilacji

**Przykład użycia dla CMLP:**
```
User: "Napisz FFmpeg worker który konwertuje FLAC do MP3 320kbps"
Cursor: [generuje pełny kod TypeScript z wykorzystaniem fluent-ffmpeg]
```

**Konfiguracja:**
1. Zainstaluj Cursor
2. Otwórz folder `g:\CMLP HardbanRecordsLab`
3. Settings → AI → wybierz "Claude 3.5 Sonnet"
4. Gotowe

---

### 3.2 CodeRabbit (Code Reviewer)

**Link:** https://coderabbit.ai  
**Koszt:** Free tier (do 10 PR/miesiąc)

**Co robi:**
- Automatycznie review'uje każdy PR
- Wskazuje błędy, security issues, best practices
- Sugeruje poprawki
- Integruje się z GitHub

**Przykład dla CMLP:**
```
PR: "Add FFmpeg transcoding worker"
CodeRabbit: 
  - ❌ Missing error handling for corrupted files
  - ⚠️ Use Redis queue instead of in-memory array
  - 💡 Suggest adding progress tracking
```

---

### 3.3 v0.dev (UI Designer)

**Link:** https://v0.dev  
**Credits:** 200/miesiąc (free tier)

**Co robi:**
- Generuje pełne komponenty React + Tailwind z promptu
- Możliwość edycji w preview
- Export do kodu

**Przykłady promptów dla CMLP:**
```
1. "Create a white-label music player with customizable colors, 
     playlist sidebar, and progress bar"

2. "Build an admin dashboard with sidebar navigation, 
     stats cards, and data table for managing licenses"

3. "Design a license management page with form fields, 
     PDF preview, and signature canvas"
```

---

### 3.4 Bolt.new (Full-Stack Generator)

**Link:** https://bolt.new  
**Koszt:** Free tier

**Co robi:**
- Generuje całe full-stack aplikacje z promptu
- Stack: React + Node.js + PostgreSQL
- Możliwość deploy'u w jednym kliknięciu

**Przykład dla CMLP:**
```
Prompt: "Build a music licensing platform with:
         - User authentication (Firebase)
         - Track upload with metadata
         - Playlist management
         - Stripe payments
         - Admin dashboard"
         
Bolt.new: [generuje kod w 5 minut, gotowy do deploymentu]
```

**Zastosowanie:**
- Szybkie prototypowanie nowych features
- Generowanie boilerplate kodu
- Proof-of-concept przed główną implementacją

---

### 3.5 OpenDevin (Autonomous Agent)

**Link:** https://github.com/OpenDevin/OpenDevin  
**Koszt:** Free (open-source, self-hosted)

**Co robi:**
- Autonomously wykonuje zadania developmentowe
- Pisze kod, uruchamia testy, fixuje błędy
- Można zadać: "Napraw wszystkie failing testy w src/workers"

**Przykłady zadań dla CMLP:**
```
1. "Add JWT expiration to all authentication endpoints"
2. "Fix all TypeScript errors in src/services/"
3. "Generate unit tests for licensing.service.ts"
4. "Refactor server.ts — extract routes to separate files"
```

---

### 3.6 Mintlify (Documentation)

**Link:** https://mintlify.com  
**Koszt:** Free tier

**Co robi:**
- Automatycznie generuje dokumentację z kodu
- Analizuje TypeScript types, JSDoc comments
- Tworzy interaktywną dokumentację (README.md → docs site)

**Zastosowanie dla CMLP:**
```
1. Wygeneruj API documentation z src/routes/*.ts
2. Stwórz Component Library docs z src/components/**/*.tsx
3. Wyeksportuj do docs/ jako stronę WWW
```

---

### 3.7 GitHub Projects (Project Manager)

**Link:** https://github.com/features/issues  
**Koszt:** Free

**Co robi:**
- Zarządzanie sprintami
- Kanban board (To Do, In Progress, Done)
- Milestones, assignees, due dates
- Integracja z Claude AI do automatycznego tworzenia tasks

**Workflow:**
```
1. Claude analizuje CMLP_MASTER_BUILD_PLAN.md
2. Automatycznie tworzy tasks w GitHub Projects
3. Przypisuje priority + estimated time
4. Tworzy checklists dla każdego sprintu
```

---

## 4. KOMPLETNY WORKFLOW — JAK TO DZIAŁA

### Scenario: Implementacja FFmpeg Transcoding Worker

**Krok 1:** Otwórz `SPRINT_BACKLOG.md` → znajdź zadanie 2.1

**Krok 2:** Zapytaj Claude w Cursor:
```
Implement FFmpeg transcoding worker based on SPRINT_BACKLOG.md task 2.1.
Requirements:
- Listen to Redis queue for transcoding jobs
- Convert FLAC/WAV → MP3 320kbps + HLS
- Generate waveform PNG
- Extract metadata (BPM, key, ISRC)
- Save to /var/www/cmlp/media_files/
```

**Krok 3:** Cursor generuje kod:
```typescript
// src/workers/transcoding.worker.ts
import { Worker } from 'bullmq';
import ffmpeg from 'fluent-ffmpeg';
import { Waveform } from 'waveform';

const transcodingWorker = new Worker('transcoding', async (job) => {
  const { filePath, outputDir, trackId } = job.data;
  
  // 1. Transcode to MP3 320kbps
  await new Promise((resolve, reject) => {
    ffmpeg(filePath)
      .audioBitrate('320k')
      .audioCodec('libmp3lame')
      .save(`${outputDir}/${trackId}.mp3`)
      .on('end', resolve)
      .on('error', reject);
  });
  
  // 2. Generate HLS segments
  // ... kod generujący .m3u8 + .ts
  
  // 3. Generate waveform PNG
  // ... kod generujący waveform
  
  // 4. Extract metadata
  // ... kod wyciągający BPM, key, ISRC
  
  return { success: true, trackId };
});
```

**Krok 4:** Zapytaj CodeRabbit o review:
```
CodeRabbit review:
- ❌ Missing input validation (filePath could be undefined)
- ⚠️ Add retry logic for failed transcoding
- 💡 Consider using child_process instead of fluent-ffmpeg for better control
```

**Krok 5:** Napraw błędy z pomocą Claude:
```
Claude: "Fix review feedback from CodeRabbit"
```

**Krok 6:** Wygeneruj UI z v0.dev:
```
Prompt: "Create a video/audio upload component with:
         - Drag & drop zone
         - Progress bar with percentage
         - File info (name, size, format)
         - Cancel button"
         
v0.dev: [generuje komponent React + Tailwind + TypeScript]
```

**Krok 7:** Zintegruj UI z kodem:
```
Cursor: "Integrate v0.dev upload component with transcoding worker"
```

**Krok 8:** Wygeneruj testy z OpenDevin:
```
OpenDevin: "Generate unit tests for transcoding.worker.ts with 80% coverage"
```

**Krok 9:** Dokumentacja z Mintlify:
```
Mintlify: "Generate documentation for /api/tracks POST endpoint"
```

**Krok 10:** Stwórz task w GitHub Projects:
```
Automatically create task: "Deploy FFmpeg worker to production"
```

---

## 5. SCENARIO: PEŁNY FEATURE OD ZERA DO PRODUKCJI

### Example: User Upload + Transcoding + Playlist + Player

**Day 1 (8h):**
```
09:00 — Claude: Plan dzień na podstawie SPRINT_BACKLOG.md
09:30 — Cursor: Implement upload API endpoint
12:00 — Cursor: Implement Redis queue for jobs
14:00 — CodeRabbit: Review code
14:30 — Claude: Fix review feedback
15:30 — v0.dev: Generate upload UI component
16:30 — Integracja UI z backendem
17:00 — GitHub Projects: Update task status
```

**Day 2 (8h):**
```
09:00 — Claude: Continue with FFmpeg worker
09:30 — Cursor: Implement transcoding logic
11:00 — OpenDevin: Generate tests
12:00 — Bolt.new: Prototype playlist manager UI
14:00 — Cursor: Implement playlist API endpoints
16:00 — Integration + debugging
17:00 — CodeRabbit: Final review
```

**Day 3 (8h):**
```
09:00 — v0.dev: Generate white-label player UI
09:30 — Cursor: Implement streaming endpoints
11:00 — Bolt.new: Generate admin dashboard
12:00 — Integration player z playlistami
14:00 — Claude: Implement token-based auth dla audio
15:30 — Testing (Playwright)
16:30 — Mintlify: Generate docs
17:00 — Deploy (GitHub Actions)
```

**Result:** 3 dni pracy → pełny feature gotowy do produkcji

---

## 6. OSZCZĘDNOŚCI

### Bez AI Tools:
- 1 Senior Developer: $8,000 PLN/miesiąc
- 1 Frontend Developer: $6,000 PLN/miesiąc
- 1 Full-stack Developer: $7,000 PLN/miesiąc
- 1 QA Engineer: $5,000 PLN/miesiąc
- **RAZEM: $26,000 PLN/miesiąc (~$6,500)**

### Z AI Tools:
- Cursor: $0
- CodeRabbit: $0
- v0.dev: $0
- Bolt.new: $0
- OpenDevin: $0
- Mintlify: $0
- GitHub Projects: $0
- **RAZEM: $0/miesiąc**

**Oszczędność: $78,000 PLN/quarter (100% za darmo)**

---

## 7. QUICK START — ZERO TO HERO

### Krok 1: Pobierz Cursor (5 min)
```
1. Wejdź na https://cursor.sh
2. Download dla Windows/Mac/Linux
3. Zainstaluj
```

### Krok 2: Otwórz projekt (2 min)
```
1. Cursor → File → Open Folder
2. Wybierz: g:\CMLP HardbanRecordsLab
3. Poczekaj na indexowanie
```

### Krok 3: Zainstaluj extensions (5 min)
```
Ctrl+Shift+X → Znajdź i zainstaluj:
- ESLint
- Prettier
- Tailwind CSS IntelliSense
- Drizzle ORM
```

### Krok 4: Skonfiguruj AI (2 min)
```
1. Cursor → Settings (Ctrl+,)
2. Szukaj "AI Model"
3. Wybierz: "Claude 3.5 Sonnet"
4. Zapisz
```

### Krok 5: Napisz pierwszy kod z AI (5 min)
```
1. Otwórz server.ts
2. Kliknij na ikonę AI w prawym dolnym rogu
3. Wpisz: "Help me refactor this file into modular routes"
4. AI generuje kod + wyjaśnienia
```

### Krok 6: Gotowe! 🚀
- Możesz teraz pisać kod z AI assistance
- Każde zadanie z SPRINT_BACKLOG.md możesz wykonać 2-3x szybciej

---

## 8. TYPICAL DAY — PRZYKŁAD

```yaml
09:00 — Claude: "Plan dnia na podstawie SPRINT_BACKLOG.md"
         → Otrzymujesz listę zadań na dziś

09:30 — Cursor: Implementujesz FFmpeg worker
         → AI podpowiada kod, autocomplete, debugging inline

11:00 — CodeRabbit: Automatyczny review Twojego PR
         → Wskazuje błędy + sugeruje poprawki

11:30 — Claude: Fixujesz błędy wskazane przez CodeRabbit
         → Copy-paste feedback → AI generuje poprawki

12:30 — Lunch break

13:30 — v0.dev: Generujesz UI dla admin dashboard
         → Prompt → Preview → Edycja → Export kodu

14:30 — Integracja UI z backendem
         → Cursor pomaga z React + TypeScript

15:30 — Bolt.new: Prototypujesz nową feature (np. brand brief matcher)
         → Full-stack prototype w 5 minut

16:30 — GitHub Projects: Update task status
         → Przeciągasz zadania z "In Progress" do "Done"

17:00 — Mintlify: Generujesz dokumentację
         → Automatyczna dokumentacja API + components

17:30 — Dzień zakończony!
         → Wykonano ~2-3 zadania z backlogu
```

---

## 9. CO ROBIC GDY AI NIE WOLA?

### Problem: "AI generuje zły kod"

**Rozwiązanie:**
1. BARDZIEJ SZCZEGÓŁOWY PROMPT:
   ```
   ❌ "Napisz worker"
   ✅ "Napisz TypeScript worker używając BullMQ + Redis queue, 
       który odbiera job typu { filePath: string, outputDir: string },
       konwertuje FLAC→MP3 320kbps przez fluent-ffmpeg, 
       generuje waveform PNG i wyciąga metadata przez music-metadata.
       Worker powinien zapisywać wyniki do PostgreSQL."
   ```

2. Podziel zadanie na mniejsze kroki:
   ```
   1. "Stwórz strukturę pliku transcoding.worker.ts"
   2. "Dodaj importy + types"
   3. "Implement funkcję transcode()"
   4. "Implement funkcję generateWaveform()"
   ```

3. Użyj OpenDevin do automatycznego debugowania:
   ```
   OpenDevin: "Fix TypeScript errors in src/workers/transcoding.worker.ts"
   ```

### Problem: "Cursor free tier wyczerpał się"

**Rozwiązanie:**
1. Przejdź na **Cursor Hobby** ($0/miesiąc z limitami) lub
2. **VS Code + GitHub Copilot** (free dla open-source) lub
3. **Codeium** (free, unlimited) — wtyczka do VS Code

### Problem: "Kod nie działa"

**Rozwiązanie:**
1. Claude/Copilot chat: "Why is this code failing? [paste error + code]"
2. Sourcery: "Analyze and fix this code"
3. OpenDevin: "Debug and fix all errors"

---

## 10. EDYTOR — ALTERNATYWY

Jeśli nie lubisz Cursor, oto alternatywy:

| Edytor | AI | Free Tier | Zastosowanie |
|--------|----|-----------|--------------|
| **Cursor** | ✅ Claude + GPT-4 | 2000/miesiąc | **NAJLEPSZY** — główny wybór |
| **VS Code + Copilot** | ✅ GitHub Copilot | Free dla open-source | Standardowy VS Code z AI |
| **VS Code + Continue** | ✅ Self-hosted | Free (open-source) | Privacy-focused, własne LLM |
| **VS Code + Codeium** | ✅ Codeium | Free unlimited | Darmowy unlimited autocomplete |
| **GitHub Codespaces** | ✅ Copilot | 120h/miesiąc | Cloud IDE, zero setup |
| **Replit** | ✅ Replit AI | Free tier | Cloud IDE + hosting |
| **GitPod** | ✅ GPT-4 | 50h/miesiąc | Cloud IDE |

**Rekomendacja:** **Cursor** (najlepszy UX, najlepsze modele AI, VS Code ecosystem)

---

## 11. HARDWARE REQUIREMENTS

### Minimum:
- **CPU:** 4 cores (Intel i5 / AMD Ryzen 5)
- **RAM:** 8GB (16GB recommended)
- **Storage:** 20GB free space
- **OS:** Windows 10+, macOS 12+, Ubuntu 20.04+

### Recommended:
- **CPU:** 8+ cores (Intel i7 / AMD Ryzen 7)
- **RAM:** 32GB (dla dużych projektów)
- **Storage:** 50GB SSD
- **GPU:** NVIDIA RTX 3060+ (dla OpenDevin lokalnego)

---

## 12. FAQ

**Q: Czy naprawdę mogę zbudować CMLP za $0?**  
A: Tak. Wszystkie narzędzia listed mają darmowe tier wystarczające do zbudowania całej platformy.

**Q: Co jeśli skończy się free tier?**  
A: Przejdź na płatne plany (~$30-50/miesiąc) lub użyj alternatyw (Codeium zamiast Copilot, OpenDevin zamiast Bolt.new).

**Q: Ile osób potrzebuję?**  
A: 1 osoba z tym stackiem może zastąpić zespół 3-5 developerów.

**Q: Czy AI zepsuje kod?**  
A: CodeRabbit + manual review eliminują 95% błędów. Zawsze review'uj AI-generated code.

**Q: Co jeśli nie umiem TypeScript?**  
A: Claude/Cursor napisze kod za Ciebie. Wystarczyopis co chcesz zrobić.

---

## 13. NEXT STEPS

1. **Dzisiaj:** Pobierz Cursor, otwórz projekt CMLP
2. **Jutro:** Zainstaluj extensions, skonfiguruj AI
3. **Tydzień 1:** Zaimplementuj zadania z Sprint 1 (SPRINT_BACKLOG.md)
4. **Tydzień 2-4:** Kontynuuj sprinty 2-3
5. **Miesiąc 1:** MVP gotowe — upload, playlisty, player

**Powodzenia! 🚀**