---
STATUS: gotowy do wklejenia w narzędzie AI-buildera (np. Claude Code lokalnie,
Cursor, Windsurf itp.) pracujące bezpośrednio na repo G:\CMLP HardbanRecordsLab.
ZAKRES: WYŁĄCZNIE branding widoczny na zewnątrz (dokumentacja, WordPress,
e-maile, meta). ŻADNYCH zmian w kodzie funkcjonalnym, bazie danych, API,
zmiennych środowiskowych, JWT, configach infra/Docker.
---

# PROMPT: Rebranding CMLP — zmiana nazewnictwa i identyfikacji marki (bezpieczny zakres)

Jesteś asystentem AI pracującym na repozytorium projektu HRL/CMLP
(`G:\CMLP HardbanRecordsLab`). Twoje zadanie to zaktualizowanie **widocznego
brandingu** projektu do nowej koncepcji nazewniczej. To jest zmiana
**wyłącznie kosmetyczna/treściowa** — NIE zmieniasz działania aplikacji.

## 1. Kontekst zmiany

Dotychczas skrót "CMLP" bywał rozwijany w dokumentacji jako "Custom Music
Licensing Platform" / tagline "Private B2B Music Licensing Platform". Marka
przechodzi na nowy, docelowy model: katalog muzyczny z pełnymi prawami
autorskimi (cesja, nie licencja), niezależny od OZZ, w którym tożsamość
konkretnych producentów pozostaje ukryta — na zewnątrz widoczna jest
wyłącznie marka **CMLP**.

## 2. Docelowe nazewnictwo (źródło prawdy)

| Kontekst użycia | Pełna nazwa |
|---|---|
| Nazwa kreatywna / katalogowa (publiczna, strona z muzyką, materiały promocyjne) | **Collective Music Licensing Project** |
| Nazwa formalna / biznesowa (umowy, faktury, kontakt z klientami B2B, stopki prawne) | **Creative Music Licensing Partners** |
| Skrót używany wszędzie na wierzchu (nagłówki, logo, adresy) | **CMLP** (bez zmian) |
| Firma-matka (bez zmian) | Hardban Records Lab (HRL) |

Zasada rozstrzygania, której pełnej nazwy użyć w danym miejscu: jeśli tekst
mówi o katalogu/muzyce/marce dla odbiorcy zewnętrznego → "Collective Music
Licensing Project". Jeśli tekst jest w kontekście umowy, faktury, danych
firmy, regulaminu, stopki prawnej → "Creative Music Licensing Partners".
W razie wątpliwości zostaw sam skrót "CMLP" i zaznacz miejsce jako `[DO
POTWIERDZENIA PRZEZ KAMILA]`.

## 3. CO WOLNO zmieniać (zakres bezpieczny)

- `README.md` — wyłącznie tekst narracyjny: tytuł, opis/tagline w linii 3
  ("Private B2B Music Licensing Platform" → nowy opis oparty o nową
  koncepcję). **NIE dotykaj** poleceń bash, nazw skryptów npm, przykładów
  zmiennych środowiskowych ani struktury projektu w tym pliku.
- Pliki w `docs/*.md` — akapity opisowe/wprowadzające, nagłówki, wzmianki o
  "Custom Music Licensing Platform" lub podobnych starych rozwinięciach.
  **NIE dotykaj** fragmentów kodu, nazw endpointów, schematów bazy danych
  cytowanych w tych plikach.
- Zawartość WordPress: teksty stron, nagłówek/stopka motywu, meta title/
  description, tagline strony w ustawieniach WP, treści w `wordpress/` i
  widoczne stringi UI we `wordpress-plugin/` (etykiety, komunikaty dla
  użytkownika — NIE nazwy funkcji/hooków PHP).
- Szablony e-maili w `mails/` — treść, stopki, podpisy.
- Pliki w `assets/` i `public/` — wyłącznie treści tekstowe/marketingowe
  (np. opisy, alt-texty), nie nazwy plików technicznych ani konfiguracje.
- Logo — po wygenerowaniu grafik na podstawie `docs/brand-legal/
  CMLP_LOGO_PROMPT.md`, podmień pliki logo/favicon w motywie WordPress i w
  `public/`.

## 4. CZEGO NIE WOLNO ruszać (zakres wykluczony — twarde ograniczenie)

- `package.json` → pole `"name": "hardban-records-lab-cmlp"` — zostaje bez zmian.
- Wszystkie zmienne środowiskowe i ich przykłady (`.env*`, `DATABASE_URL`,
  nazwa bazy `cmlp`, `HMAC_SECRET`, `JWT_SECRET` itd.).
- Subdomeny/adresy techniczne, np. `api.cmlp.hardbanrecordslab.online`.
- Kod aplikacji: `src/`, `server.ts`, `database/`, `drizzle/`,
  `hrl-jwt-auth-bridge/`, `infrastructure/`, `config/`, `scripts/`,
  `vps-deploy/`, `tests/`, `wordpress-plugin/**/*.php` (logika — tylko
  literalne stringi UI, patrz punkt 3).
- Endpointy API, nazwy tabel/kolumn w bazie danych, nazwy paczek npm.
- Wszystko w `node_modules/`, `dist/`, `coverage/`, `.git/`.

Jeśli w trakcie pracy natrafisz na niejasny przypadek (np. nazwa zmiennej
zawierająca "cmlp" w kontekście, który wygląda na tekst, nie kod) — **zatrzymaj
się i zapytaj**, zamiast zgadywać.

## 5. Konkretne wyszukaj-i-zamień (przykłady, nie wyczerpująca lista)

Szukaj w plikach z zakresu bezpiecznego (punkt 3) fraz:
- `"Custom Music Licensing Platform"` → zamień kontekstowo na jedną z dwóch
  nowych pełnych nazw wg tabeli z punktu 2.
- `"Private B2B Music Licensing Platform"` (tagline w README) → zaproponuj
  nowy opis, np.: *"Private B2B music catalog under full copyright ownership
  — licensed directly to businesses under the CMLP brand"* (dopracuj styl,
  zachowaj sens: własny katalog, pełne prawa, model B2B bez OZZ).
- Wszelkie wzmianki "platforma" w kontekście marketingowym/opisowym →
  rozważ zamianę na "katalog" / "kolektyw" / "project", zgodnie z nowym
  pozycjonowaniem (marka, nie tylko technologia).

Po każdej zamianie zachowaj listę zmienionych plików i dokładnych miejsc
(plik + numer linii) do raportu końcowego.

## 6. Checklist weryfikacyjny po zakończeniu

- [ ] `grep -rn "Custom Music Licensing Platform"` w repo (poza
      `node_modules`, `.git`) — zero wyników albo tylko świadomie
      pozostawione (np. w historycznych raportach audytowych, gdzie zmiana
      byłaby fałszowaniem historii — oznacz `[HISTORYCZNE — nie zmieniano]`).
- [ ] `package.json` → pole `name` niezmienione.
- [ ] Żaden plik w `src/`, `database/`, `drizzle/`, `infrastructure/`,
      `config/` nie został zmodyfikowany.
- [ ] `npm run lint && npm run type-check` przechodzi bez błędów (dowód, że
      nic w kodzie się nie zepsuło — powinno przejść bez zmian, bo nic w
      kodzie nie ruszałeś).
- [ ] Strona WordPress wizualnie sprawdzona (nagłówek, stopka, meta title w
      źródle strony) — nowe nazewnictwo widoczne, stare zniknęło.
- [ ] Raport końcowy: lista wszystkich zmienionych plików + krótki opis co
      zmieniono w każdym.

## 7. Po zakończeniu

Zwróć zwięzły raport: (a) listę zmienionych plików, (b) listę miejsc
oznaczonych `[DO POTWIERDZENIA PRZEZ KAMILA]`, (c) wynik checklisty z
punktu 6. Nie commituj zmian do gita bez wyraźnej prośby — zostaw jako
zmiany robocze do przejrzenia.
