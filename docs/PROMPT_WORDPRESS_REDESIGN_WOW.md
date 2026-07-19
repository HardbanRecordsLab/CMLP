# PROMPT: Kompletny Redesign WordPress — HardbanRecords Lab
## Nowoczesny, Interaktywny, 3D + Anime + Pełna Responsywność

**Projekt:** hardbanrecordslab.online  
**Motyw obecny:** HRL Amoled Premium v3.0.0  
**Stack:** WordPress + PHP + CSS/JS vanilla  
**Autor promptu:** 2026-07-14

---

## KONTEKST PROJEKTU

Strona to **prywatna platforma B2B licencjonowania muzyki** — HardbanRecords Lab.
Paleta kolorów: czysta czerń AMOLED (`#000000`), złoto (`#C8A96E → #E8D5A3`), neon niebieski (`#38bdf8`), neon fiolet (`#a855f7`).
Fonty: `Playfair Display` (nagłówki), `Cinzel` (akcenty/logo), `DM Sans` (body).

Istniejące strony do zachowania (cała treść musi pozostać):
- **Strona główna** (`front-page.php`) — hero, CMLP, BlogCast, MKS, Radio, CTA
- **CMLP** (`page-cmlp.php`) — platforma B2B, 4 pakiety cenowe (199/399/799/4999 zł)
- **O Nas** (`page-about.php`) — misja, 4 filary (Suwerenność, IP, Technologia, Niezależność)
- **Muzyczna Kreacja Słów** (`page-muzyczna-kreacja-slow.php`) — MKS, pakiety (49/99/199/399+ zł), song tabs, formularz
- **Radio HRL** (`page-radio.php`) — odtwarzacz AzuraCast, stream 128kbps
- **Academy / Muzyka dla Biznesu** (`page-academy.php`) — hotele, fitness, korporacje
- **Kontakt** (`page-contact.php`) — formularz, dane kontaktowe
- **Blogcast** (`home.php`, `archive.php`, `single.php`) — 113 kategorii, artykuły
- **Strony prawne** — privacy, terms, sale-terms, cookies, license-agreement, gdpr-consent, newsletter-terms, security-policy, api-terms, data-retention

---

## ZADANIE GŁÓWNE

Stwórz **kompletny, nowoczesny, wizualnie spektakularny motyw WordPress** dla HardbanRecords Lab.
CAŁA TREŚĆ istniejących stron musi być zachowana. Zmieniamy TYLKO wygląd.


---

## WYMAGANIA WIZUALNE — KIERUNEK ARTYSTYCZNY

### Styl Ogólny
- **Aesthetic:** Dark luxury + cyberpunk B2B + premium record label
- **Nastrój:** Potężny, tajemniczy, artystyczny — jak okładka płyty od drogiego wytwórni
- **NIE:** Prostacki gamer aesthetic. TAK: Artisan dark premium z elementami technologicznymi
- **Inspiracje:** Apple Music dark UI × Spotify Premium × high-end fashion brand × Tokyo night

### 1. ANIMACJE ANIME — Obowiązkowe

Dodaj animacje inspirowane japońskim anime/motion design:

**Particle System (Three.js lub canvas):**
- Tło hero: płynące, świetlne cząsteczki jak nuty muzyczne lub fale audio
- Cząsteczki reagują na ruch myszy (parallax mouse-tracking)
- Kolor: złoto + neon blue gradient, opacity 0.3–0.6
- 200–400 cząsteczek, połączone liniami gdy blisko siebie

**Glitch Effect (anime-style):**
- Logo "HARDBANRECORDS LAB" ma subtelny glitch effect na hover
- Tekst hero ma efekt "typing" lub "scramble" przy załadowaniu (jak w anime intro)
- Opcjonalnie: scanlines overlay na hero section, opacity 0.03–0.05

**Fluid/Morphing Shapes:**
- Za sekcją hero: animowane blob shapes (SVG filter `feTurbulence`)
- Kolory: rgba złoto + neon purple, bardzo subtelne, blur 60px
- Powolna, hipnotyzująca animacja (20–40s loop)

**Stagger Animations (Anime.js lub GSAP-like pure JS):**
- Każda karta produktu wchodzi ze staggerem (delay 100ms per karta)
- Animacja: `translateY(40px) → 0` + `opacity 0 → 1`
- Easing: `cubic-bezier(0.16, 1, 0.3, 1)` (spring feel)

**Number Counter Animation:**
- Liczby statystyk (200+ klientów, 15+ lat, itp.) animują się liczydłem gdy wchodzą w viewport


### 2. EFEKTY 3D — Obowiązkowe

**Tilt Cards (Vanilla Tilt lub pure CSS/JS):**
- Karty produktów (`product-card`, `pakiet`, `manifesto-card`, `opinia`) reagują na ruch myszy
- Efekt: 3D perspective tilt do 15 stopni
- Na hover: złoty reflektor (specular highlight) wędruje po karcie wraz z kursorem
- `transform-style: preserve-3d`, perspektywa 1000px
- Subtelny `box-shadow` zmienia się dynamicznie (głębszy od strony przechylenia)

**3D Hero Text:**
- Główny `<h1>` na stronie głównej ma efekt 3D depth (text-shadow layered, 5–8 warstw)
- Animacja: powolne, pętlowe unoszenie się (float) 0 → -10px → 0, 6s ease
- Na hover: tytuł jeszcze bardziej "wychodzi" w stronę użytkownika

**Dedicated 3D Showcase Page (`page-3d-showcase.php`):**
- Strona istnieje w kodzie — wypełnij ją spektakularnym Three.js scene
- Scena: rotujący, goldowy "vinyl record" (torus/disc geometry)
- Materiał: MeshStandardMaterial, emissive gold, environment map reflection
- Oświetlenie: PointLight gold + AmbientLight dark, tone mapping ACES
- Audio waveform 3D bars w tle (animowane sine wave, złote słupki)
- Tekst "HRL Audio Universe" unosi się nad sceną
- Fallback: CSS 3D animation gdy WebGL niedostępny

**CSS 3D Cards Flip:**
- Karty cennikowe na stronie CMLP i MKS: klikalne/hover flip 3D
- Przód: cena + nazwa pakietu
- Tył: szczegółowa lista features + przycisk CTA
- `transform: rotateY(180deg)`, płynne 0.6s


### 3. HERO SECTION — Przeprojektowanie

**Strona główna hero (`front-page.php`):**

```
Układ: Full viewport, pure black
├── Tło: canvas particle system (nuty/fale)
├── Centered content z max-width 1000px
│   ├── Eyebrow: "HARDBANRECORDS LAB 2.0" — font Cinzel, złoto, letter-spacing 6px
│   │            + animowany underscore cursor migający jak terminal
│   ├── H1 główny (DUŻY — clamp(3.5rem, 8vw, 6rem)):
│   │   "Muzyka Komercyjna"  ← biały
│   │   "bez ZAiKS"          ← gradient złoto, 3D float effect
│   │   "w Twoim Biznesie"   ← biały
│   ├── Tagline: opis platformy — font DM Sans, 1.15rem, text-secondary
│   ├── CTA buttons:
│   │   [Platforma CMLP →]  ← btn-primary, złoty gradient, glow shadow
│   │   [Muzyczna Kreacja Słów]  ← btn-outline, border złoto
│   └── Hero Stats bar (4 liczby):
│       [15+ lat] [200+ klientów] [100% Direct License] [Zero ZAiKS]
│       Animowane liczniki, podziały złotą kreską
└── Scroll indicator: animowana strzałka ↓ lub zlatująca nuty
```

**Scroll-triggered Section Transitions:**
- Każda sekcja wchodzi z efektem fade + slide
- Sekcje z lewej/prawej na przemian (nie tylko z dołu)
- Intersection Observer API, threshold 0.15

### 4. NAWIGACJA — Upgrade

**Header Glassmorphic V2:**
- `backdrop-filter: blur(20px) saturate(150%)`
- Border bottom: gradient line `linear-gradient(90deg, transparent, gold, transparent)`
- Logo: "HARDBANRECORDS LAB" font Cinzel z animowanym złotym underscore
- Na scroll: header shrinks (padding 16px → 8px), płynna animacja
- Active link: golden underline z `scaleX` animation od center
- Mobile: hamburger menu z animacją X ↔ ☰, full-screen overlay menu

**Announcement Bar (gdy aktywny):**
- Scrolling marquee tekst, gradient background (neon blue → neon purple)
- Zamykalne (X button), stan zapamiętany w sessionStorage


---

## KOMPONENTY UI — SZCZEGÓŁOWE SPECYFIKACJE

### Karty Produktów (`product-card`)

```css
/* UPGRADE do wersji Premium */
.product-card {
  background: rgba(10, 8, 6, 0.7);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(200, 169, 110, 0.15);
  border-radius: 16px;
  padding: 40px 32px;
  
  /* 3D Tilt przygotowanie */
  transform-style: preserve-3d;
  will-change: transform;
  
  /* Gradient border on hover via pseudo-element */
}
.product-card::before {
  /* animated gradient border — rainbow → gold */
  content: '';
  position: absolute;
  inset: -1px;
  border-radius: inherit;
  background: conic-gradient(from var(--angle), transparent 70%, #C8A96E, transparent);
  animation: rotateBorder 4s linear infinite paused;
  opacity: 0;
}
.product-card:hover::before { opacity: 1; animation-play-state: running; }

/* Icon upgrade: 3D floating icon box */
.product-card-icon {
  width: 64px; height: 64px;
  background: linear-gradient(135deg, rgba(200,169,110,0.15), rgba(56,189,248,0.1));
  border: 1px solid rgba(200,169,110,0.25);
  border-radius: 16px;
  font-size: 1.8rem;
  /* subtelny drop shadow neonowy */
  filter: drop-shadow(0 0 8px rgba(200,169,110,0.3));
}
```

**Hover state kart:**
- Transform: `translateY(-8px) rotateX(3deg)`
- Border glow: `box-shadow: 0 20px 60px rgba(200,169,110,0.15), 0 0 0 1px rgba(200,169,110,0.3)`
- Icon: `filter: drop-shadow(0 0 16px rgba(200,169,110,0.6))`
- Animowany gradient border (conic-gradient obracający się)

### Pakiety Cenowe — 3D Flip Cards

Każdy `.pakiet` / `.pakiet-card` → flip card:

```
[Karta przód]                    [Karta tył — po hover/click]
━━━━━━━━━━━━━━━━━━               ━━━━━━━━━━━━━━━━━━━━━━━━━━━
STARTER                          ✓ 1 lokal
199 zł/mies.                     ✓ Podstawowa biblioteka
"Idealne do..."                  ✓ Certyfikat QR
[Obróć →]                        ✓ MP3 320kbps
                                 ✓ Basic Support
                                 ────────────────
                                 [Zamów teraz →]
```

Pakiet **Business** ma badge "NAJPOPULARNIEJSZY" (featured).

### Sekcja Radio — Upgrade

```
Glassmorphic audio player card, max-width: 800px, centered
┌─────────────────────────────────────────────────┐
│  📻  RADIO HRL LIVE                      [LIVE] │  ← pulsujące czerwone dot
│  ──────────────────────────────────────────────  │
│  Hardban Records Lab · 128kbps · AzuraCast       │
│                                                  │
│     ████████████████░░░░░░░░  [▶ PLAY]          │  ← animowany waveform
│                                                  │
│  Teraz gra: [Auto-DJ · HRL Catalog]              │
│  Słuchaczy online: [Live counter]                │
│                                                  │
│  ┌──────┐ ┌──────┐ ┌──────────────────────────┐ │
│  │ Vol  │ │ Mute │ │ stream.hardbanrecords... │ │
│  └──────┘ └──────┘ └──────────────────────────┘ │
└─────────────────────────────────────────────────┘
```

Animowany pseudo-waveform (CSS animated bars, 20–30 słupków, gold color).


---

## PEŁNA RESPONSYWNOŚĆ — BREAKPOINTS

```
Desktop XL:  1400px+ → 4 kolumny, pełne animacje
Desktop:     1024–1399px → 3–4 kolumny
Tablet:      768–1023px → 2 kolumny, tilt disabled
Mobile L:    480–767px → 1 kolumna, uproszczone animacje
Mobile S:    < 480px → 1 kolumna, bez 3D transforms, bez particles
```

**Mobile-specific:**
- Hamburger menu: animowane (3 kreski → X), full-screen overlay z blur
- Touch-friendly: min tap target 48px
- Tilt cards: wyłączone na touch devices (`window.matchMedia('(hover: none)')`)
- Particle system: wyłączony na mobile (performance)
- Font sizes: `clamp()` na wszystkich nagłówkach
- Pakiety cenowe: 1 kolumna, scroll horizontal NIE — pełna wysokość
- Hero height: `100svh` (safe viewport height dla mobile browsers)
- Bottom navigation sticky nie jest wymagana

**CSS Container Queries (nowoczesne):**
```css
@container (max-width: 600px) {
  .product-card { padding: 24px 20px; }
}
```

---

## STRUKTURA PLIKÓW — MOTYW WORDPRESS

Stwórz/zaktualizuj kompletny motyw w strukturze:

```
wordpress/
├── style.css                    ← Theme header + base vars
├── functions.php                ← Enqueue, hooks, customizer
├── front-page.php               ← Strona główna (zachowaj treść)
├── header.php                   ← Glassmorphic nav V2
├── footer.php                   ← Multi-column footer
├── page-cmlp.php                ← CMLP z flip cards
├── page-about.php               ← O Nas
├── page-muzyczna-kreacja-slow.php ← MKS (zachowaj całą treść)
├── page-radio.php               ← Radio z premium playerem
├── page-academy.php             ← Muzyka dla Biznesu
├── page-contact.php             ← Kontakt (zachowaj cały formularz)
├── page-3d-showcase.php         ← Three.js vinyl record scene
├── home.php                     ← BlogCast listing
├── single.php                   ← Single post
├── assets/
│   ├── css/
│   │   ├── 00-variables.css     ← CSS Custom Properties
│   │   ├── 01-reset.css
│   │   ├── 02-typography.css
│   │   ├── 03-layout.css
│   │   ├── 04-components.css    ← Cards, buttons, forms
│   │   ├── 05-animations.css    ← Keyframes, transitions
│   │   ├── 06-3d-effects.css    ← Tilt, flip, perspective
│   │   ├── 07-header.css
│   │   ├── 08-footer.css
│   │   └── 10-responsive.css   ← Media queries
│   └── js/
│       ├── hrla-theme.js        ← Main JS (particles, tilt, counters, etc.)
│       └── hrla-three.js        ← Three.js scene (tylko na page-3d-showcase)
```


---

## SZCZEGÓŁOWY PLAN ZMIAN — STRONA PO STRONIE

### `front-page.php` — Strona Główna

**Zachować:** całą polską treść, linki, sektory (CMLP, BlogCast, MKS, Radio, CTA)

**Dodać/zmienić:**
1. Canvas particle background w `<section class="hero">`
2. H1 → 3D floating text z layered text-shadow
3. "Bez ZAiKS" span → animowany gradient gold + glow pulse
4. Hero stats bar — 4 metryki z number counter
5. Section scroll animations (Intersection Observer)
6. Product cards → tilt 3D + animated border
7. Radio section → premium player card z waveform

### `page-cmlp.php` — CMLP Platform

**Zachować:** hero copy, 6 feature cards, 4 pakiety (199/399/799/4999 zł), CTA

**Dodać:**
1. Feature cards → 3D tilt + animated border glow
2. Pakiety → CSS 3D flip cards (przód: cena, tył: features + CTA)
3. "Featured" badge na pakiecie Business animowany shimmer
4. Stats section: "100+ firm", "Zero ZAiKS", "24/7 Support", "99.9% SLA"
5. Trust badges row: logotypy branż (gastronomia, hotel, fitness, retail)

### `page-muzyczna-kreacja-slow.php` — MKS

**WAŻNE:** Ta strona ma ogromny kod CSS inline (`#mks-page-root`). 
**Zadanie:** Przenieś style do zewnętrznego CSS, zachowaj wszystkie klasy i funkcje.
Zachowaj: tabs, song panels z lyrics, formularz z sliderem budżetu, FAQ accordion, sticky CTA

**Dodać:**
1. Animację otwierania song tabs (slide + fade, nie tylko display toggle)
2. Lyrics card → subtelny animowany gradient border
3. Formularz: focus states z glow effect
4. FAQ: płynny accordion (CSS `max-height: 0 → auto` z transition)

### `page-radio.php` — Radio HRL

**Zachować:** player audio, linki do streamu, 4 feature cards, CTA

**Dodać:**
1. Premium audio player card (specyfikacja powyżej)
2. Animowany waveform (CSS bars za play button)
3. Pulsujące "LIVE" badge
4. Visualizer bars animacja

### `page-3d-showcase.php` — 3D Showcase

**Stwórz od zera:**
```php
<?php /* Template Name: 3D Showcase */ get_header(); ?>
<div id="three-canvas-container" style="width:100vw;height:100vh;"></div>
<div class="showcase-overlay">
  <h1>HRL Audio Universe</h1>
  <p>Commercial Music Licensing Platform</p>
</div>
<?php get_footer(); ?>
```

Three.js scene:
- Rotujący vinyl record (TorusGeometry lub własna disc geometry)
- Material: MeshPhysicalMaterial, roughness 0.1, metalness 0.8, color gold
- Środowisko: HDR environment map (three/examples/jsm)
- Audio bars: 64 BoxGeometry słupki wokół vinyl w kole, animowane sine wave
- Post-processing: UnrealBloomPass z strength 0.8, threshold 0.4
- Sterowanie: OrbitControls z auto-rotate


---

## JAVASCRIPT — `hrla-theme.js` — Pełna Specyfikacja

```javascript
// ═══════════════════════════════════════════════
// HRL THEME — MAIN JAVASCRIPT MODULE
// ES6+, vanilla JS, zero dependencies (opcjonalnie CDN Three.js)
// ═══════════════════════════════════════════════

// 1. PARTICLE SYSTEM
class HRLParticles {
  // canvas fullscreen w background hero
  // 300 particles: x,y,vx,vy,radius,opacity,color
  // Kolory: złoto lub neon blue (random 70/30)
  // Mouse tracking: particles flee lub attract (configurable)
  // Linie między particlami gdy distance < 120px
  // FPS limit: requestAnimationFrame + timestamp delta
  // Disable jeśli prefers-reduced-motion lub isMobile
}

// 2. TILT 3D CARDS
class HRLTilt {
  // Działa na: .product-card, .pakiet, .manifesto-card, .opinia
  // mousemove → calculateTilt(e, el) → requestAnimationFrame
  // mouseleave → resetTilt() z smooth spring
  // Reflection highlight: pseudo-element background radial-gradient
  //   śledzi pozycję kursora przez CSS vars --mx, --my
  // Disabled jeśli: matchMedia('(hover: none)') lub prefers-reduced-motion
}

// 3. SCROLL ANIMATIONS
class HRLScrollReveal {
  // IntersectionObserver, threshold: 0.1, rootMargin: '0px 0px -50px 0px'
  // Klasy: .reveal-left, .reveal-right, .reveal-up, .reveal-fade
  // Stagger: data-delay="100" → delay w ms
  // Animacja: CSS transitions uruchamiane przez dodanie klasy .revealed
}

// 4. NUMBER COUNTERS
function animateCounters() {
  // querySelectorAll('[data-count]')
  // Każda liczba: od 0 do target wartości
  // Czas: 2000ms, easing: easeOutCubic
  // Trigger: IntersectionObserver gdy element widoczny
}

// 5. RADIO PLAYER
class HRLRadioPlayer {
  // Audio element control
  // Play/Pause z animacją waveform bars
  // Volume slider
  // Status: 'Gotowy' | 'Łączenie...' | 'Odtwarza' | 'Błąd połączenia'
  // Error handling: retry po 3s, max 3 próby
  // Waveform bars: 24 div elementy, animowane CSS gdy playing
}

// 6. FLIP CARDS (pakiety cenowe)
function initFlipCards() {
  // .flip-card-inner toggle .is-flipped na click/hover
  // Touch: tap toggles flip
  // Desktop: hover = flip dla pakietów
}

// 7. HEADER SCROLL BEHAVIOR
function initStickyHeader() {
  // Scroll > 80px → header klasa .scrolled
  // .scrolled: zmniejszony padding, enhanced backdrop-filter
  // Progress bar: szerokość = scrollY / (scrollHeight - viewportHeight) * 100
}

// 8. MOBILE NAV
function initMobileNav() {
  // .nav-toggle click → .nav-menu toggle .open
  // body.overflow-hidden gdy menu open
  // Escape key → close
  // Click outside → close
  // Animacja: menu items wchodzą z staggerem
}

// 9. TYPING/SCRAMBLE EFFECT (hero eyebrow)
class TextScramble {
  // Na load: tekst "HardbanRecords Lab 2.0" scramble → reveal
  // Characters: '!<>-_\/[]{}—=+*^?#@0123456789ABCDEFabcdef'
  // Czas reveal: 1.5s, delay start: 500ms
}

// 10. CUSTOM CURSOR (desktop only)
function initCustomCursor() {
  // Dot: 8px, złoty, podąża za kursorem z lag (lerp)
  // Ring: 32px border, podąża z większym lag
  // Na hover linku/buttona: dot rośnie, ring zanika
  // Cursor: none na body
}

// ═══════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  initStickyHeader();
  initMobileNav();
  initFlipCards();
  if (!isMobile() && !prefersReducedMotion()) {
    new HRLParticles('#hero-canvas');
    new HRLTilt();
    initCustomCursor();
  }
  new HRLScrollReveal();
  animateCounters();
  // Radio player tylko jeśli element istnieje
  if (document.getElementById('radioPlayBtn')) {
    new HRLRadioPlayer();
  }
  // Text scramble tylko na front-page
  if (document.querySelector('.hero-eyebrow.scramble')) {
    new TextScramble(document.querySelector('.hero-eyebrow.scramble'));
  }
});
```


---

## CSS — NOWE ZMIENNE I EFEKTY KLUCZOWE

```css
/* ═══════════════════════════
   NOWE VARIABLES
   ═══════════════════════════ */
:root {
  /* Istniejące zachowaj */
  --bg-main: #000000;
  --gold: #C8A96E;
  --gold-light: #E8D5A3;
  --neon-blue: #38bdf8;
  --neon-purple: #a855f7;

  /* Nowe */
  --glow-gold: 0 0 20px rgba(200, 169, 110, 0.4), 0 0 40px rgba(200, 169, 110, 0.2);
  --glow-blue: 0 0 20px rgba(56, 189, 248, 0.4);
  --glow-purple: 0 0 20px rgba(168, 85, 247, 0.4);
  --border-animated: rgba(200, 169, 110, 0.4);
  --radius-card: 16px;
  --radius-btn: 8px;

  /* 3D */
  --perspective: 1000px;
  --tilt-max: 15deg;
}

/* ═══════════════════════════
   ANIMATED GRADIENT BORDER
   ═══════════════════════════ */
@property --angle {
  syntax: '<angle>';
  initial-value: 0deg;
  inherits: false;
}

@keyframes rotateBorder {
  to { --angle: 360deg; }
}

/* Użycie na kartach: */
.product-card.border-animated::before {
  background: conic-gradient(
    from var(--angle),
    transparent 0%,
    transparent 70%,
    #C8A96E 80%,
    #38bdf8 90%,
    transparent 100%
  );
  animation: rotateBorder 3s linear infinite;
}

/* ═══════════════════════════
   3D HERO TEXT
   ═══════════════════════════ */
.hero-content h1 .gold-text {
  text-shadow:
    0 1px 0 rgba(200,169,110,0.9),
    0 2px 0 rgba(180,149,90,0.8),
    0 3px 0 rgba(160,130,70,0.7),
    0 4px 0 rgba(140,110,50,0.5),
    0 5px 0 rgba(120,90,30,0.3),
    0 8px 20px rgba(0,0,0,0.8),
    0 15px 35px rgba(200,169,110,0.2);
  animation: floatText 6s ease-in-out infinite;
}

@keyframes floatText {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-12px); }
}

/* ═══════════════════════════
   WAVEFORM BARS
   ═══════════════════════════ */
.waveform-bars {
  display: flex;
  align-items: center;
  gap: 3px;
  height: 32px;
}

.waveform-bar {
  width: 3px;
  background: var(--gold);
  border-radius: 2px;
  transform-origin: bottom;
  animation: waveBar var(--duration, 0.8s) ease-in-out infinite alternate;
  animation-play-state: paused; /* uruchamiane przez .playing klasa */
}

@keyframes waveBar {
  from { transform: scaleY(0.2); opacity: 0.5; }
  to { transform: scaleY(1); opacity: 1; }
}

.is-playing .waveform-bar { animation-play-state: running; }

/* ═══════════════════════════
   CUSTOM CURSOR
   ═══════════════════════════ */
.cursor-dot {
  width: 8px; height: 8px;
  background: var(--gold);
  border-radius: 50%;
  position: fixed;
  pointer-events: none;
  z-index: 99999;
  transition: transform 0.15s ease, opacity 0.2s;
  transform: translate(-50%, -50%);
}

.cursor-ring {
  width: 32px; height: 32px;
  border: 1px solid rgba(200,169,110,0.6);
  border-radius: 50%;
  position: fixed;
  pointer-events: none;
  z-index: 99998;
  transition: transform 0.08s linear, width 0.2s, height 0.2s, opacity 0.2s;
  transform: translate(-50%, -50%);
}

body:has(a:hover) .cursor-dot { transform: translate(-50%, -50%) scale(2.5); }
body:has(a:hover) .cursor-ring { opacity: 0; }

/* ═══════════════════════════
   GLASSMORPHIC LOADER
   ═══════════════════════════ */
#hrl-preloader {
  position: fixed; inset: 0; z-index: 99999;
  background: #000;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  transition: opacity 0.5s ease 0.2s, visibility 0.5s ease 0.2s;
}

#hrl-preloader .loader-logo {
  font-family: var(--font-accents);
  font-size: 1.4rem;
  letter-spacing: 6px;
  color: var(--gold);
  animation: pulse 1.5s ease-in-out infinite;
}

#hrl-preloader .loader-bar {
  width: 200px; height: 2px;
  background: rgba(255,255,255,0.1);
  border-radius: 1px;
  margin-top: 24px;
  overflow: hidden;
}

#hrl-preloader .loader-bar::after {
  content: '';
  display: block; height: 100%;
  background: var(--gradient-gold);
  animation: loadBar 1.2s ease-in-out forwards;
}

@keyframes loadBar { from { width: 0; } to { width: 100%; } }

#hrl-preloader.hidden { opacity: 0; visibility: hidden; }
```


---

## `functions.php` — WYMAGANE ZMIANY

```php
<?php
// ═══════════════════════════════════════
// HRL AMOLED PREMIUM — functions.php
// ═══════════════════════════════════════

// 1. Enqueue — PRAWIDŁOWA KOLEJNOŚĆ
function hrl_enqueue_assets() {
  // Fonts z Google (preconnect + display=swap)
  wp_enqueue_style('hrl-fonts',
    'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Cinzel:wght@400;700&family=DM+Sans:wght@400;500;600&display=swap',
    [], null
  );

  // CSS modules — ładuj w kolejności!
  $css_files = ['00-variables','01-reset','02-typography','03-layout',
                '04-components','05-animations','06-3d-effects',
                '07-header','08-footer','10-responsive'];
  $prev = 'hrl-fonts';
  foreach ($css_files as $file) {
    wp_enqueue_style("hrl-{$file}", get_template_directory_uri() . "/assets/css/{$file}.css", [$prev], '3.1.0');
    $prev = "hrl-{$file}";
  }

  // Three.js CDN (tylko na stronie 3D Showcase)
  if (is_page_template('page-3d-showcase.php')) {
    wp_enqueue_script('threejs',
      'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
      [], '128', true
    );
    wp_enqueue_script('hrl-three',
      get_template_directory_uri() . '/assets/js/hrla-three.js',
      ['threejs'], '3.1.0', true
    );
  }

  // Main JS — defer dla performance
  wp_enqueue_script('hrl-theme',
    get_template_directory_uri() . '/assets/js/hrla-theme.js',
    [], '3.1.0', true
  );
  wp_script_add_data('hrl-theme', 'defer', true);

  // Inline JS vars
  wp_localize_script('hrl-theme', 'HRLConfig', [
    'ajaxurl' => admin_url('admin-ajax.php'),
    'homeUrl'  => home_url('/'),
    'themeUrl' => get_template_directory_uri(),
    'isRTL'    => is_rtl(),
    'nonce'    => wp_create_nonce('hrl_nonce'),
  ]);
}
add_action('wp_enqueue_scripts', 'hrl_enqueue_assets');

// 2. Theme Support
function hrl_theme_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['search-form','comment-form','gallery','caption','script','style']);
  add_theme_support('responsive-embeds');
  add_theme_support('wp-block-styles');
  add_theme_support('custom-logo', [
    'height' => 60, 'width' => 300, 'flex-height' => true, 'flex-width' => true,
  ]);
  // Rejestracja menu
  register_nav_menus([
    'primary'  => 'Główna Nawigacja',
    'footer_1' => 'Footer — Platforma',
    'footer_2' => 'Footer — Dokumenty',
    'mobile'   => 'Menu Mobilne',
  ]);
}
add_action('after_setup_theme', 'hrl_theme_setup');

// 3. Customizer — nowe opcje
function hrl_customizer(WP_Customize_Manager $wp_customize) {
  // Panel HRL Settings
  $wp_customize->add_panel('hrl_panel', ['title' => 'HRL Theme Settings', 'priority' => 30]);

  // Animations Section
  $wp_customize->add_section('hrl_animations', ['panel' => 'hrl_panel', 'title' => 'Animacje & Efekty']);
  $wp_customize->add_setting('hrl_particles_enabled', ['default' => true, 'sanitize_callback' => 'rest_sanitize_boolean']);
  $wp_customize->add_control('hrl_particles_enabled', ['section' => 'hrl_animations', 'type' => 'checkbox', 'label' => 'Particle system hero']);

  $wp_customize->add_setting('hrl_custom_cursor', ['default' => true, 'sanitize_callback' => 'rest_sanitize_boolean']);
  $wp_customize->add_control('hrl_custom_cursor', ['section' => 'hrl_animations', 'type' => 'checkbox', 'label' => 'Custom cursor (desktop)']);

  $wp_customize->add_setting('hrl_preloader_toggle', ['default' => false, 'sanitize_callback' => 'rest_sanitize_boolean']);
  $wp_customize->add_control('hrl_preloader_toggle', ['section' => 'hrl_animations', 'type' => 'checkbox', 'label' => 'Preloader']);

  // Header
  $wp_customize->add_section('hrl_header_opts', ['panel' => 'hrl_panel', 'title' => 'Header']);
  $wp_customize->add_setting('hrl_announcement_bar_text', ['default' => '', 'sanitize_callback' => 'wp_kses_post']);
  $wp_customize->add_control('hrl_announcement_bar_text', ['section' => 'hrl_header_opts', 'type' => 'textarea', 'label' => 'Announcement Bar']);
}
add_action('customize_register', 'hrl_customizer');
?>
```


---

## FOOTER — UPGRADE

```
┌────────────────────────────────────────────────────────────────────┐
│  NEWSLETTER BAR (jeśli aktywny w Customizer)                       │
│  [Gradient neon blue → purple | "HRL Intel" | form inline]         │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  HARDBANRECORDS LAB    Platforma      Kontakt        Dokumenty    │
│  ────────────────────  ────────────   ────────────   ─────────── │
│  "Suwerenny ekosystem  CMLP           📧 contact@    Privacy     │
│  B2B Audio..."         MKS            📞 +48 726...  Terms       │
│                        BlogCast       Dostępny po    GDPR        │
│  [Social icons row]    Radio HRL      umówieniu      Cookies     │
│  🐦 𝕏  💼 LinkedIn    Panel CMLP                    API Terms   │
│                                                                    │
├────────────────────────────────────────────────────────────────────┤
│  Divider: animated gradient line                                   │
├────────────────────────────────────────────────────────────────────┤
│  © 2026 HardbanRecords Lab · Wszelkie Prawa Zastrzeżone           │
│  Muzyka bez ZAiKS · Direct Licensing · B2B Audio Ecosystem        │
│                      [↑ Wróć na górę]                              │
└────────────────────────────────────────────────────────────────────┘
```

**"Wróć na górę" button:**
- Pojawia się po scroll > 400px
- Animacja: fade + slide up
- Click: smooth scroll to top z `behavior: 'smooth'`
- Styl: circle 48px, gradient złoto, shadow glow

---

## DOSTĘPNOŚĆ (WCAG 2.1 AA — wymagania)

- Wszystkie animacje: `@media (prefers-reduced-motion: reduce)` → wyłączone
- Wszystkie interactive elements: widoczny focus ring (`:focus-visible`)
- Kontrast tekstu: minimum 4.5:1 (sprawdzić gold na black)
- ARIA labels na wszystkich ikonach i buttonach bez tekstu
- Skip-to-content link (ukryty, widoczny na :focus)
- Alt text na wszystkich obrazach
- Keyboard navigation na flip cards i tabs

---

## PERFORMANCE — WYMAGANIA

- Lazy loading: `loading="lazy"` na wszystkich obrazach poniżej fold
- Preload: krytyczne fonty `<link rel="preload">`
- CSS: krytyczne style inline w `<head>`, reszta defer
- JS: wszystkie skrypty z `defer` lub `async`
- Particles: wyłączone gdy `navigator.hardwareConcurrency <= 2` lub `window.matchMedia('(max-width: 768px)')`
- Three.js: ładowany tylko na page-3d-showcase (code split)
- Obrazy: format WebP z fallback JPEG
- Core Web Vitals target: LCP < 2.5s, CLS < 0.1, FID < 100ms

---

## KOLEJNOŚĆ IMPLEMENTACJI (priorytet)

1. **[P0]** `00-variables.css` + `01-reset.css` — fundament
2. **[P0]** `functions.php` — enqueue + theme support
3. **[P0]** `header.php` + `07-header.css` — nawigacja V2
4. **[P1]** `front-page.php` + particle canvas hero
5. **[P1]** `04-components.css` — karty, przyciski, forms
6. **[P1]** `05-animations.css` + `06-3d-effects.css`
7. **[P1]** `hrla-theme.js` — JS interactivity
8. **[P2]** `page-cmlp.php` — flip cards cennik
9. **[P2]** `page-radio.php` — premium player
10. **[P2]** `footer.php` + `08-footer.css`
11. **[P3]** `page-3d-showcase.php` + `hrla-three.js`
12. **[P3]** `10-responsive.css` — mobile polish
13. **[P3]** Wszystkie pozostałe strony — style audit

---

## OGRANICZENIA TECHNICZNE

- **NIE używaj:** jQuery (vanilla JS only), Bootstrap, żadnych UI frameworków
- **NIE dodawaj:** nowych PHP dependencies, plugin dependencies
- **Używaj:** CDN tylko dla Three.js (tylko na 1 stronie) i Google Fonts
- **PHP wersja:** 8.0+ compatible
- **WordPress wersja:** 6.5+
- **Brak:** page builder (Elementor, Gutenberg blocks theme) — custom theme PHP

---

## WERYFIKACJA KOŃCOWA — CHECKLIST

Przed dostarczeniem sprawdź:
- [ ] Cała treść polska zachowana na każdej stronie
- [ ] Linki działają (cmlp.hardbanrecordslab.online, /contact/, /radio/, itp.)
- [ ] Radio player działa (src="https://radio.hardbanrecordslab.online/...")
- [ ] Formularze mają prawidłowe `action` + `wp_nonce_field`
- [ ] Wszystkie animacje mają `prefers-reduced-motion` fallback
- [ ] Mobile menu działa bez JS errors
- [ ] Three.js ładuje się TYLKO na page-3d-showcase
- [ ] No console errors na żadnej stronie
- [ ] CSS variables poprawnie dziedziczone
- [ ] Flip cards dostępne z klawiatury


---

## PRZYKŁADOWY OUTPUT — CO POWINNO WYGLĄDAĆ

Po zaimplementowaniu redesignu, strona powinna dawać takie wrażenia:

**Pierwsze 3 sekundy:**
1. Czarny ekran → preloader z logo "HARDBANRECORDS LAB" + złoty loading bar
2. Preloader zanika → hero section z falującymi złotymi cząsteczkami w tle
3. Tekst hero pojawia się z efektem "scramble/type" → finalna forma z 3D float
4. Stats bar wjeżdża z dołu z animowanymi licznikami

**Scrollowanie:**
- Sekcje wchodzą z wygładzonymi reveal animations (nie "dziurkowata" animacja)
- Karty reagują na kursor (3D tilt) jak premium landing page Stripe/Apple
- Golden animated border pojawia się na kartach przy hover
- Czuć physics spring w animacjach (cubic-bezier)

**Ogólne wrażenie:**
> "To wygląda jak strona dużej, europejskiej wytwórni muzycznej 2027"
> Nie: "wordpress landing page z darmowego motywu"

---

*Prompt wygenerowany na podstawie rzeczywistego kodu źródłowego projektu HardbanRecords Lab.*  
*Wszystkie nazwy klas, zmienne CSS i linki są zgodne z aktualnym kodem w `/wordpress/`.*  
*Data: 2026-07-14*
