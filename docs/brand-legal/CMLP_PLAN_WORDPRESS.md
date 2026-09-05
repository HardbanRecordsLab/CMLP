# CMLP — Plan zmian na WordPress/stronie (v1.0, robocza)

*Plan strukturalny — CO ma się zmienić na stronie. Techniczne wykonanie
tekstowej części brandingu: patrz `CMLP_AI_BUILDER_REBRAND_PROMPT.md`.*

## 1. Struktura stron docelowych

| Strona | Zawartość | Status |
|---|---|---|
| Strona główna | Wprowadzenie marki "Collective Music Licensing Project", nowe logo, hasło pozycjonujące (patrz koncepcja biznesowa pkt 1), CTA do katalogu i do kontaktu B2B | do aktualizacji tekstu i grafiki |
| Katalog / przeglądarka utworów | Bez zmian funkcjonalnych — tylko branding (logo, nagłówek) | do aktualizacji brandingu |
| O nas / About | Krótki opis modelu (kolektyw, pełne prawa, niezależność od OZZ jako atut) — BEZ ujawniania tożsamości konkretnych producentów | nowa treść do napisania |
| Licencjonowanie / Pricing | Opis modeli (subskrypcja / per utwór / white-label), cennik | wymaga decyzji cenowej (koncepcja biznesowa pkt 6) przed publikacją |
| Regulamin licencji (Terms) | Publikacja regulaminu B2B na bazie wzoru w `CMLP_WZORY_DOKUMENTOW.md` (dok. 5) — PO zatwierdzeniu przez prawnika | zablokowane do czasu finalizacji prawnej |
| Kontakt / For Business | Formularz kontaktowy pod nazwą formalną "Creative Music Licensing Partners", dane kontaktowe | do aktualizacji nazwy/treści |
| Stopka (globalna) | Nazwa formalna + rok + "All rights reserved", link do regulaminu i polityki prywatności | do aktualizacji tekstu |

## 2. Elementy wizualne

- Podmiana logo w nagłówku i stopce (po wygenerowaniu z `CMLP_LOGO_PROMPT.md`).
- Favicon (wariant "ikona/monogram" z promptu logo).
- Meta title/description na wszystkich podstronach — nowe nazewnictwo, SEO pod frazy typu "royalty-free music for business", "B2B music licensing catalog" (do doprecyzowania w ramach SEO, jeśli chcecie priorytetyzować ten kanał pozyskania klientów).

## 3. Kolejność wdrożenia (rekomendowana)

1. Wygenerować i zatwierdzić logo (prompt gotowy — `CMLP_LOGO_PROMPT.md`).
2. Puścić `CMLP_AI_BUILDER_REBRAND_PROMPT.md` na repo — bezpieczna zmiana tekstów/nazw w README, docs, WordPress, e-mailach.
3. Ręcznie zweryfikować i dopracować treść stron "O nas" i "Licencjonowanie" (to wymaga decyzji biznesowych z `CMLP_KONCEPCJA_BIZNESOWA.md`, nie da się w pełni zautomatyzować).
4. Podmienić logo/favicon w motywie WordPress.
5. Opublikować regulamin licencji B2B — dopiero po akceptacji prawnika finalnej wersji dokumentu 5 z `CMLP_WZORY_DOKUMENTOW.md`.
6. Test end-to-end: przejść stronę jako nowy odwiedzający i jako potencjalny klient B2B, sprawdzić spójność nazewnictwa wszędzie (grep pomaga, ale wzrokowa weryfikacja UX też potrzebna).

## 4. Czego ten plan NIE obejmuje

- Zmian w panelu administracyjnym / logice aplikacji (poza zakresem
  bezpiecznego rebrandingu — patrz ograniczenia w
  `CMLP_AI_BUILDER_REBRAND_PROMPT.md`).
- Ustalenia ostatecznego cennika (decyzja biznesowa Kamila).
- Finalizacji prawnej regulaminu (wymaga prawnika).
