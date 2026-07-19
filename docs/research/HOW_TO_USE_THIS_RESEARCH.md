# Jak korzystać z tego research'u? (How to Use This Research)

## Quick Start Guide

---

## 1. **Dla decydentów (Product Owners, Tech Leads)**

### Przeczytaj w tej kolejności:

1. **UI_COMPONENT_LIBRARY_RESEARCH_REPORT.md** (sekcje: Executive Summary, The Verdict, Top 5 Libraries)
   - Zrozum która biblioteka jest najlepsza dla Twojego use case
   - Sprawdź licencje i kompatybilność

2. **CRITICAL_COMPONENTS_PRIORITIZATION.md** (sekcje: Prioritization Matrix, Implementation Timeline)
   - Zdecyduj które komponenty customowe są potrzebne
   - Zaplanuj budżet czasowy (24 tygodni dla wszystkich 10)

3. **TECHNICAL_ARCHITECTURE_CUSTOM_COMPONENTS.md** (sekcje: System Overview, Core Technologies)
   - Zrozum architekturę techniczną
   - Zaakceptuj stack (Tailwind + daisyUI + Alpine.js)

---

## 2. **Dla developerów (Frontend, Fullstack)**

### Krok 1: Skopiuj szkielet projektu

```bash
# Skopiuj gotowe pliki z CMLP-DesignSystem/components/html/
cp -r CMLP-DesignSystem/components/html/ ./your-project/components/
cp CMLP-DesignSystem/package.json ./your-project/
cp CMLP-DesignSystem/config/tailwind.config.js ./your-project/config/
cp CMLP-DesignSystem/src/input.css ./your-project/src/
```

### Krok 2: Zainstaluj dependencies

```bash
npm install
```

### Krok 3: Wybierz komponenty do użycia

**Opcja A - Gotowe komponenty (51 sztuk):**
```html
<!-- Przykład: button.html -->
<div class="card bg-base-100 shadow-sm">
  <button class="btn btn-primary">Click me</button>
</div>
```

**Opcja B - Custom komponenty (10 krytyczne):**
- Zgodnie z priorytetyzacją zacznij od Command Palette
- Następnie Kanban Board, Form Builder, Chat

### Krok 4: AI Code Generation

**Użyj szablonów promptów z researchu:**

```
Generate a [component-type] page using CMLP Design System:
- Framework: Alpine.js + Tailwind CSS + daisyUI
- Components needed: [list components]
- Style: [light/dark theme]
- Responsive: yes
```

**Przykłady promptów:**

1. **Landing Page:**
   ```
   Create a SaaS landing page with:
   - Hero section with CTA
   - Features grid (3 columns)
   - Pricing table (3 tiers)
   - Testimonials carousel
   - FAQ accordion
   - Footer with links
   ```

2. **Dashboard:**
   ```
   Build an admin dashboard with:
   - Sidebar navigation
   - Stats cards (4 KPIs)
   - Line chart (weekly revenue)
   - Data table with pagination
   - User profile dropdown
   ```

3. **Blog:**
   ```
   Generate a blog page with:
   - Featured article (large card)
   - Article grid (3 columns)
   - Sidebar with categories
   - Newsletter signup form
   - Recent posts list
   ```

---

## 3. **DlaAI Assistants (Claude, GPT, etc.)**

### Kontekst dla AI:

```markdown
You are a frontend architect using CMLP Design System.

## Foundation Stack:
- Tailwind CSS (utility classes)
- daisyUI (semantic components + themes)
- Alpine.js (interactivity)
- 51 ready-to-use HTML components in /components/html/

## Custom Components (when needed):
- Command Palette (Cmd+K search)
- Kanban Board (drag & drop)
- Form Builder (JSON schema → forms)
- Live Chat Widget
- Audio Player
- Gantt Chart
- Resizable Panels
- Mind Map
- Presentation Editor
- eBook Reader

## Component Pattern:
Each component follows this structure:
- HTML with DaisyUI classes
- Alpine.js x-data for state
- Responsive by default
- Accessible (ARIA labels)
- Dark mode support via data-theme attribute

## Your Task:
[Specific component/page request]
```

---

## 4. **Workflow dla różnych projektów**

### A. Landing Page / Marketing Site

**Biblioteki do użycia:**
- Flowbite (hero, features, pricing, testimonials)
- daisyUI (buttons, cards, forms)
- HyperUI (copy-paste snippets)

**Komponenty customowe:**
- Brak (wszystko dostępne)

**HTML components from CMLP:**
- hero.html
- pricing.html
- feature-sections.html
- testimonial.html
- faq.html
- footer.html

---

### B. SaaS Application / Dashboard

**Biblioteki do użycia:**
- Tabler (admin UI, 20+ pages)
- daisyUI (layout, navigation)
- shadcn/ui (React components jeśli używasz React)

**Komponenty customowe:**
- Kanban Board (Priority 1)
- Command Palette (Priority 1)
- Resizable Panel System (Priority 2)

**HTML components from CMLP:**
- sidebar.html
- navbar.html
- stats.html
- table.html
- chart.html
- auth-forms.html

---

### C. Documentation / Knowledge Base

**Biblioteki do użycia:**
- daisyUI + Fractal (docs-focused)
- shadcn/ui MDX (React docs)

**Komponenty customowe:**
- Command Palette (search) - Priority 1
- eBook Reader ( jeśli potrzebujesz EPUB)

**HTML components from CMLP:**
- breadcrumbs.html
- code-block.html
- callout.html
- toc.html (table of contents)
- search.html

---

### D. E-commerce / Marketplace

**Biblioteki do użycia:**
- daisyUI (product cards, checkout)
- Flowbite (e-commerce patterns)

**Komponenty customowe:**
- Brak krytycznych

**HTML components from CMLP:**
- product-card.html (stwórz z card.html)
- pricing.html
- cart.html (stwórz z form.html)
- gallery.html (product images)
- user-profile.html

---

### E. Music Platform / Podcast

**Biblioteki do użycia:**
- daisyUI (layout)
- Custom Audio Player (Priority 2)

**Komponenty customowe:**
- Audio Player z waveform (Priority 2)
- Playlist management

**HTML components from CMLP:**
- music-player.html (basic)
- gallery.html (album art)
- search.html
- user-profile.html

---

## 5. **Quick Reference: Która biblioteka? Kiedy?**

| Use Case | Wybierz | Dlaczego |
|----------|---------|----------|
| **Szybki prototyp/Landing page** | Flowbite + daisyUI | Gotowe sekcje, Figma integration |
| **Enterprise admin panel** | Tabler + daisyUI | 1000+ komponentów, 2000+ ikon |
| **React SaaS aplikacja** | shadcn/ui + Radix + daisyUI | Najlepsza architektura, accessible |
| **Vue 3 aplikacja** | Naive UI lub PrimeVue | Kompletne biblioteki |
| **HTML-only strona** | Franken UI + daisyUI | HTML-first, zero build step |
| **Marketing site z animacjami** | Magic UI + Flowbite | Animacje out-of-the-box |
| **Dokumentacja** | daisyUI + Fractal | Docs-focused |
| **Mobile-first** | daisyUI + Varlet UI | Responsive + Material Design |

---

## 6. **Kolejność implementacji custom components**

### Tydzień 1-2: Command Palette
**Dlaczego pierwszy?**
- Niski complexity, wysoki impact
- Wszędzie użyteczny (docs, dashboard, app)
- Szybkie zwycięstwo dla zespołu

**Jak użyć:**
```html
<!-- Dodaj do navbar -->
<button @click="$dispatch('open-command-palette')" class="btn btn-ghost">
  <svg>search icon</svg>
</button>

<!-- Na końcu body -->
<div id="command-palette" x-data="commandPalette()">
  <!-- zarejestruj komendy -->
</div>
```

---

### Tydzień 3-4: Kanban Board
**Dlaczego drugi?**
- Wysokie zapotrzebowanie w SaaS/CRM
- Widoczna wartość biznesowa

**Jak użyć:**
```html
<div x-data="kanbanBoard({
  columns: [
    { id: 'todo', title: 'To Do', cards: [...] },
    { id: 'done', title: 'Done', cards: [...] }
  ]
})">
  <!-- render columns -->
</div>
```

---

### Tydzień 5-6: Form Builder
**Dlaczego trzeci?**
- Productivity multiplier
- Uselessness bez schematu JSON

**Jak użyć:**
```javascript
const schema = {
  fields: [
    { type: 'text', name: 'username', label: 'Username', required: true },
    { type: 'email', name: 'email', label: 'Email' }
  ]
}

<div x-data="formBuilder({ schema })">
  <!-- auto-generuje formularz -->
</div>
```

---

## 7. **Best Practices**

### ✅ DO:

1. **Używaj DaisyUI** jako podstawowego стилю
   ```html
   <button class="btn btn-primary">Save</button>
   ```

2. **Dodawaj Alpine.js** dla interaktywności
   ```html
   <div x-data="{ open: false }">
     <button @click="open = !open">Toggle</button>
   </div>
   ```

3. **Kombinuj komponenty**
   ```html
   <div class="card">
     <div class="card-body">
       <h2 class="card-title">Title</h2>
       <button class="btn btn-primary">Action</button>
     </div>
   </div>
   ```

4. **Testuj na mobile first**
   ```html
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
   ```

### ❌ DON'T:

1. **Nie pisaj custom CSS** jeśli daisyUI ma to już
   ```html
   <!-- ❌ -->
   <style>.my-button { background: blue; }</style>
   
   <!-- ✅ -->
   <button class="btn btn-primary">Click</button>
   ```

2. **Nie komplikuj** jeśli Alpine.js wystarczy
   ```html
   <!-- ❌ React dla prostego toggle -->
   <!-- ✅ Alpine.js -->
   <div x-data="{ open: false }">
   ```

3. **Nie zapomnij o accessibility**
   ```html
   <!-- ✅ Zawsze dodawaj alt -->
   <img src="photo.jpg" alt="Description">
   
   <!-- ✅ Labiele dla formularzy -->
   <label>Email</label>
   <input type="email">
   ```

---

## 8. **Troubleshooting**

### Problem: Komponent nie działa
**Rozwiązanie:**
1. Sprawdź czy Alpine.js jest załadowany
2. Sprawdź czy daisyUI CSS jest zaimportowany
3. Sprawdź console na błędy

### Problem: Nie wygląda jak w przykładzie
**Rozwiązanie:**
1. Upewnij się że używasz class `cmlp-container`
2. Sprawdź czy theme jest ustawiony: `<html data-theme="cmlp-light">`
3. Sprawdź czy Tailwind CSS jest zainstalowany

### Problem: Chcę zmodyfikować komponent
**Rozwiązanie:**
1. Skopiuj komponent z `components/html/`
2. Zmodyfikuj w miejscu użycia
3. Lub zmodyfikuj source file jeśli chcesz zmienić globalnie

---

## 9. **Resources**

### Dokumentacja:
- **Tailwind CSS:** https://tailwindcss.com/docs
- **daisyUI:** https://daisyui.com/components
- **Alpine.js:** https://alpinejs.dev/start-here

### Research Reports (w folderze `docs/research/`):
- **UI_COMPONENT_LIBRARY_RESEARCH_REPORT.md** — Porównanie 60+ bibliotek
- **CRITICAL_COMPONENTS_PRIORITIZATION.md** — Plan implementacji na 24 tygodnie
- **TECHNICAL_ARCHITECTURE_CUSTOM_COMPONENTS.md** — Architektura systemu

### Komponenty:
- **51 gotowych komponentów:** `CMLP-DesignSystem/components/html/`
- **10 specyfikacji custom:** W TECHNICAL_ARCHITECTURE

---

## 10. **Następne kroki**

### Natychmiast (Tydzień 1):
1. ✅ Przeczytaj research report
2. ⬜ Wybierz foundation stack dla swojego projektu
3. ⬜ Skonfiguruj Tailwind + daisyUI + Alpine.js
4. ⬜ Skopiuj potrzebne komponenty HTML

### Krótkoterminowo (Tydzień 2-4):
1. ⬜ Zbuduj Command Palette (priority 1)
2. ⬜ Zbuduj Kanban Board (priority 1)
3. ⬜ Stwórz pierwszy prototyp z użyciem komponentów

### Średnioterminowo (Miesiąc 2-3):
1. ⬜ Zbuduj Form Builder
2. ⬜ Zbuduj Live Chat
3. ⬜ Zintegruj z AI code generation pipeline

### Długoterminowo (Miesiąc 4-6):
1. ⬜ Zakończ pozostałe 6 komponentów
2. ⬜ Stwórz Storybook dokumentację
3. ⬜ Udostępnij jako open source

---

## Podsumowanie

**To research daje Ci:**

✅ **Gotową listę** najlepszych bibliotek (60+ przeanalizowanych)
✅ **Rekomendowany stack** (8 foundation libraries)
✅ **51 działających komponentu HTML** do skopiowania
✅ **10 specyfikacji** custom components do zbudowania
✅ **24-tygodniowy plan** implementacji
✅ **Architekturę techniczną** gotową do użycia
✅ **Prompt templates** dla AI code generation

**Co dalej?**

1. Wybierz komponenty z `CMLP-DesignSystem/components/html/`
2. Skopiuj je do swojego projektu
3. Dostosuj do potrzeb
4. Budujcustom components według specyfikacji

**To jest foundation dla world-class HTML Design System.**
**Gotowe do użycia w produkcji.**

---

*Ostatnia aktualizacja: 2026-07-19*
*Wersja: 1.0*