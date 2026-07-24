# Patch: HRL Amoled Premium Child — porządki + poprawki

## ⚠️ Najważniejsze odkrycie

„Martwe" pliki CSS **nie były martwe — były osierocone.**

`assets/css/style-v5.css` i `assets/css/style.css` nie są ładowane przez
`functions.php` (kolejka obejmuje tylko `00-…` do `10-…`). Ale **57 klas
używanych w żywych szablonach jest zdefiniowanych wyłącznie w tych dwóch
plikach.** Oznacza to, że na produkcji te komponenty renderują się BEZ STYLU:

- `.single-post-hero`, `.single-post-body`, `.single-post-meta`, `.single-post-article`
  → cały układ pojedynczego wpisu na blogu
- `.pricing-grid`, `.pricing-card`, `.timeline-step`, `.faq-question`
  → cennik i oś czasu na `page-cmlp.php`
- `.hero-ecosystem`, `.hero-text`, `.hero-visual`, `.eco-visual-card`
  → sekcja hero na stronie głównej
- `.magazine-grid`, `.mag-card`, `.featured-story`, `.blogcast-mini-*`
  → BlogCast i magazyn
- `.radio-player`, `.radio-controls`, `.radio-visualizer`, `.vol-slider`
  → odtwarzacz radia
- `.product-showcase-*`, `.main-cats`, `.cat-link`, `.social-link`, `.btn-outline`
- `.reading-progress` → pasek postępu czytania

Dlatego **nie usunąłem tych plików** — odzyskałem z nich reguły.

## Zawartość paczki

Wszystko wchodzi do Twojego istniejącego folderu `child-theme/`:

| Plik | Status | Co robi |
|---|---|---|
| `assets/css/11-recovered.css` | NOWY | 125 odzyskanych reguł pokrywających wszystkie 57 osieroconych klas. Wygenerowane automatycznie z `style-v5.css` (pierwszeństwo) i `assets/css/style.css`. |
| `assets/js/hrl-3d-showcase.js` | NOWY | Kod Three.js wyjęty z inline `<script>`. Dodatkowo: pauza renderowania przy ukrytej karcie, debounce na resize, poszanowanie `prefers-reduced-motion`. |
| `functions.php` | NADPISUJE | Kolejkuje `11-recovered.css`; ładuje Three.js przez `wp_enqueue_script` z zależnością; helper `hrl_child_is_blog_context()`. |
| `page-3d-showcase.php` | NOWY | Nadpisanie szablonu bez surowych tagów `<script>`. |
| `page-sync-licensing-guide.php` | NOWY | Przebudowana strona poradnika — natywna dla motywu zamiast osobnego dokumentu Tailwind/DaisyUI. |
| `header.php` | NADPISUJE | Ticker (paski wiadomości) tylko na treściach blogowych. |
| `style.css` | NADPISUJE | Twoja oryginalna zawartość + styl `.reading-progress`. |

## Instalacja
1. Wgraj zawartość `child-theme/` do `wp-content/themes/[twój-child-theme]/`,
   zachowując strukturę podfolderów (`assets/css/`, `assets/js/`).
2. Nadpisz `functions.php`, `header.php`, `style.css`.
3. Twarde odświeżenie (Ctrl+Shift+R) — cache CSS.

## Naprawiony wyścig w Three.js
W motywie nadrzędnym biblioteka ładowała się z atrybutem `defer`, a kod
startowy był zwykłym skryptem inline **bez** `defer`. Inline wykonywał się
więc przed biblioteką i `typeof THREE === 'undefined'` przerywał
inicjalizację — animacja mogła po prostu nie startować. Teraz kolejka
WordPressa gwarantuje kolejność przez jawną zależność.

## Czego świadomie NIE ruszyłem

**Wtrącone bloki `<style>` — ten punkt mojego audytu był przesadzony.**
Po sprawdzeniu: `archive.php` (5× `var()`, 0 hex), `page.php` i `singular.php`
(po 1 hex `#fff`), `page-muzyczna-kreacja-slow.php` (497× `var()`, 6 hex —
świadoma paleta lokalna) korzystają z tokenów prawidłowo.

Jedyny realny przypadek to `home.php`: 472 linie stylu, 27 zahardkodowanych
hex, z czego kilka dubluje istniejące tokeny (`#C8A96E` = `--accent-gold`,
`#A0A0A0` = `--text-secondary`, `#000000` = `--bg-primary`,
`#FFFFFF` = `--text-primary`). **Wartości są identyczne z tokenami, więc
nie ma żadnego błędu wizualnego** — to wyłącznie kwestia utrzymania przy
ewentualnym rebrandingu. Nadpisywanie 32 KB `home.php` w motywie potomnym
dla kosmetycznej zmiany kosztowałoby więcej, niż daje.

## Dopiero teraz można usunąć martwe pliki
Po wgraniu tej paczki `assets/css/style-v5.css` i `assets/css/style.css`
w motywie nadrzędnym można skasować — sprawdziłem, że poza odzyskanymi
regułami nic ich nie używa (przeskanowałem szablony PHP **i treść w bazie
danych**; jedyne trafienie, `.hero-visual`, jest odzyskane).

Zalecam jednak zostawić je jeszcze przez tydzień testów — nie są ładowane,
więc nie kosztują nic w wydajności.

---

# Etap 2: Strona główna + przekaz sprzedażowy

## Naprawiony błąd: podwójna stopka
`front-page.php` w motywie nadrzędnym miał własny `<footer class="site-footer">`,
a na końcu wywoływał jeszcze `get_footer()` — który wypisuje drugą stopkę.
Na stronie głównej stopka renderowała się **dwa razy**. Usunięte.

## Korekta merytoryczna: to nie tylko ZAiKS

Prosiłeś o hasło „Zero ZAiKS-u". Sprawdziłem stan prawny i **rozszerzyłem
komunikat, bo w obecnej formie sprzedawał Twoją usługę za tanio.**

Odtwarzanie muzyki publicznie rozlicza się w Polsce z **czterema** organizacjami:

| Organizacja | Czyje prawa |
|---|---|
| ZAiKS | autorzy (kompozytor, autor tekstu) |
| ZPAV | producenci fonogramów |
| STOART / SAWP | artyści wykonawcy (konkurencyjne — płaci się jednej) |

To osobne umowy i osobne faktury. Klient, który uwierzy w „zero ZAiKS-u",
a potem dostanie wezwanie od ZPAV, poczuje się oszukany — i słusznie.
Za to komunikat „zero opłat do wszystkich czterech OZZ" jest **mocniejszy**,
bo realna oszczędność jest wielokrotnie większa niż sama stawka ZAiKS.

Dlatego w nowych tekstach konsekwentnie występuje: **ZAiKS, STOART/SAWP i ZPAV**.

## Dlaczego dokument jest ważniejszy niż hasło

Art. 105 ust. 1 ustawy o prawie autorskim wprowadza **domniemanie zarządu**:
zakłada się, że OZZ jest uprawniona do zarządzania repertuarem, bez obowiązku
wykazywania umocowania konkretnego twórcy. Domniemanie da się obalić —
wykazując, że autor zarządza prawami samodzielnie — ale **ciężar leży po
stronie Twojego klienta.**

Praktyczny wniosek dla oferty: tym, co faktycznie sprzedajesz, nie jest hasło
na stronie, tylko **pisemne oświadczenie o pochodzeniu repertuaru**, które
klient położy na stole przy kontroli. Dlatego przewija się ono przez całą
stronę główną jako konkret, nie ozdobnik.

Warto rozważyć dołożenie do umowy **klauzuli przejęcia odpowiedzialności**
(gdyby OZZ jednak wystąpiła z roszczeniem o repertuar z Twojej biblioteki,
sprawę przejmujesz Ty). To najmocniejszy argument sprzedażowy w tej branży
i realne odróżnienie od konkurencji.

**Nie jestem prawnikiem** — przed publikacją tych treści warto dać je do
przejrzenia prawnikowi od prawa autorskiego, szczególnie zapisy o zakresie
licencji i ewentualną klauzulę odpowiedzialności.

## Warunek, który musi być spełniony
Cały przekaz stoi na jednym założeniu: **żaden współtwórca Twojego repertuaru
nie jest członkiem OZZ** (członkostwo zwykle obejmuje cały dorobek, także
przyszły). Jeśli korzystasz z wokalistów, instrumentalistów czy współautorów
— każdy powinien podpisać oświadczenie o braku członkostwa i nieprzekazaniu
praw w zarząd. Bez tego jedna osoba w składzie wywraca całą argumentację.

## Nowa struktura strony głównej
1. Hero — „Płać mniej. Słuchaj legalnie." + przekreślone cztery OZZ
2. **Ile naprawdę płacisz** — zestawienie kosztów (nowa sekcja)
3. **Eventy i festyny** — „Płacisz raz i muzyka z głowy" (nowa sekcja)
4. **Jak to działa** — trzy kroki (nowa sekcja)
5. CMLP + Muzyczna Kreacja Słów — teksty przepisane pod korzyść klienta
6. Dlaczego HRL — „cała ścieżka praw kończy się u nas"
7. Radio HRL — przepozycjonowane na próbkę repertuaru przed zakupem
8. BlogCast, Kontakt

---

# Etap 3: Wersja bezpieczna (obecna zawartość paczki)

## Co się zmieniło i dlaczego

Teksty z etapu 2 opierały się na założeniu, że repertuar jest w całości poza
zarządem organizacji zbiorowego zarządzania. To założenie wymaga pisemnego
potwierdzenia, którego na razie nie ma. **Do czasu jego uzyskania obie strony
używają wyłącznie argumentów niezależnych od statusu repertuaru w OZZ.**

### Usunięte z treści widocznej dla użytkownika
- pasek przekreślonych nazw organizacji w sekcji hero,
- tabela porównania stawek OZZ z cennikiem CMLP,
- sekcja „Ile naprawdę płacisz" na stronie głównej,
- deklaracje typu „zero tantiem", „bez opłat do OZZ", „nie powierzyliśmy praw
  żadnej organizacji",
- pytania FAQ opierające się na powyższych twierdzeniach,
- określenie „oświadczenie o pochodzeniu repertuaru" (zastąpione neutralnym
  „umowa licencyjna z określonym zakresem korzystania").

### Na czym stoi obecna argumentacja
| Argument | Dlaczego jest bezpieczny |
|---|---|
| Katalog na wyłączność | Fakt — repertuar nie jest publikowany w serwisach streamingowych |
| Model one-stop | Fakt — nagranie i kompozycja pochodzą z jednego miejsca, jedna umowa |
| Stała cena niezależna od metrażu | Fakt — wynika z Twojego cennika |
| Cena za lokal przy pakietach | Arytmetyka z cennika (159 zł / 5 ≈ 32 zł) |
| Mastering pod odtwarzanie publiczne | Fakt techniczny |
| Repertuar dobierany pod miejsce | Fakt — sposób pracy |
| Radio HRL jako odsłuch przed zakupem | Fakt — i mocniejszy niż wcześniej, bo katalogu nie ma na streamingach |

## Jak wrócić do mocniejszego komunikatu

Style `.hrl-ozz-strip`, `.hrl-cost-table` i `.hrl-compare-grid` **zostały
w arkuszu celowo** — są gotowe do ponownego użycia. Po uzyskaniu pisemnego
potwierdzenia statusu repertuaru wystarczy podmienić dwa pliki szablonów;
CSS jest już na miejscu.

## Co trzeba wyjaśnić przed powrotem do mocniejszej wersji
1. **Skład zespołu HRL i status każdej osoby** — czy ktokolwiek z autorów,
   wykonawców lub producentów należy do ZAiKS, STOART, SAWP albo organizacji
   zagranicznej. Wystarczy jedna osoba, żeby argumentacja przestała działać.
2. **Zakres umów z organizacjami zagranicznymi** — kluczowy jest zapis o tym,
   jakie utwory obejmuje umowa. Umowy PRO często obejmują utwory powstałe
   w okresie jej obowiązywania, niezależnie od zgłoszenia i publikacji.
3. **Pisemne oświadczenia od wszystkich współtwórców** o braku członkostwa
   i nieprzekazaniu praw w zarząd.
4. **Konsultacja prawnika od prawa autorskiego** — szczególnie co do treści
   oświadczeń wydawanych klientom. To one, a nie strona internetowa, niosą
   realne ryzyko: klient przedstawia je przy kontroli i to on ponosi
   konsekwencje, jeśli okażą się nieścisłe.

---

# Etap 4: Radio HRL Live

## Znalezione i naprawione błędy

**Sprzeczność w obrębie jednej strony.** Sekcja „Zastosowania" polecała radio
do małych lokali usługowych, a FAQ nr 2 kilka ekranów niżej mówiło, że radio
służy do użytku prywatnego i do lokalu potrzebna jest licencja CMLP. Klient
czytający po kolei dostawał dwie sprzeczne odpowiedzi. Ujednolicone: radio =
słuchanie prywatne, odtwarzanie publiczne = CMLP.

**Numeracja FAQ.** Lista szła 1–7, potem przeskakiwała na 9 i 10 — pozycja 8
nie istniała. Teraz numery generuje pętla, więc rozjazd nie może wrócić.

**Przycisk prowadzący do samego siebie.** W podsumowaniu był przycisk
„Słuchaj Radia HRL →" linkujący do `/radio/` — czyli do strony, na której
użytkownik już był.

**Dwie sekcje CTA pod rząd.** Podsumowanie i osobna sekcja white-label stały
jedna za drugą, obie z przyciskami. Połączone w jedną sekcję „Ekosystem HRL".

**Niedostępny akordeon FAQ.** Inline `onclick` przełączał wyłącznie `display`.
Zastąpiony wspólnym komponentem z `aria-expanded` (ten sam co na CMLP).

## Usunięte deklaracje
- „Bez ZAiKS. 100% Direct Licensing" z hero,
- karta funkcji „Zero OZZ" (zastąpiona „Zero reklam"),
- „nie podlega organizacjom zbiorowego zarządzania" (3 wystąpienia),
- „Certyfikat Zwolnienia z OZZ" i „Certyfikat Wolności QR",
- „zero ZAiKS" z sekcji white-label,
- dane liczbowe, których nie dało się zweryfikować: „99,9% dostępności",
  „1000 równoczesnych słuchaczy", „15–20% przychodów z reklam odpływa na
  tantiemy", „12–15 minut reklam na godzinę".

## Co zostało nietknięte
Cała warstwa techniczna — AzuraCast, Icecast2, Liquidsoap, FFmpeg, PWA,
crossfade, EBU R128, słownik pojęć. To treść prawdziwa, konkretna i dobra
dla wyszukiwarek. Pozycjonowanie radia jako samodzielnego projektu również
zostaje: darmowe, bez reklam, non stop, cały katalog.

---

# Etap 5: Blog (HRL BlogCast)

## Kluczowe odkrycie: `page-blogcast.php` nigdy się nie wykonuje

W bazie strona „BlogCast" (ID 714, slug `blogcast`) jest ustawiona jako
**strona wpisów** (`page_for_posts`, Ustawienia → Czytanie).

WordPress w takiej sytuacji **ignoruje szablon przypisany do strony**
i renderuje ją przez `home.php`. To zachowanie zaskakuje niemal każdego,
bo dla wszystkich innych stron `page-{slug}.php` działa normalnie.

Praktyczny skutek: layout magazynowy z `page-blogcast.php` (v5.0.0,
modyfikowany 19 lipca) był martwym kodem, a pod adresem `/blogcast/`
wyświetlał się `home.php` z 11 lipca — starszy i gorszy. Dlatego layout
został przeniesiony do `home.php`, gdzie faktycznie zadziała.

Alternatywą byłoby odznaczenie strony wpisów w ustawieniach, ale wtedy
paginacja przestaje działać natywnie. Obecne rozwiązanie jest zgodne
z konwencją WordPressa.

## Naprawione błędy

**Wpisy pojawiały się dwukrotnie.** Poprzednia wersja używała trzech
osobnych `WP_Query` (wyróżniony, drugorzędne, wszystkie), które na siebie
nachodziły — te same artykuły pokazywały się w kilku miejscach jednocześnie.
Dodatkowo zapytanie o wyróżniony filtrowało po `_thumbnail_id`, a kolejne
robiło `offset => 1` od najnowszego wpisu w ogóle; jeśli najnowszy wpis
nie miał miniatury, jeden artykuł znikał, a inny się dublował. Teraz jest
jedna pętla na głównym zapytaniu — dublowanie jest niemożliwe.

**Paginacja pokazywała złą liczbę stron.** `the_posts_navigation()` liczyło
strony z głównego zapytania, a treść pochodziła z własnych `WP_Query`
o innym `posts_per_page`. Teraz jedno zapytanie obsługuje i treść,
i paginację, więc liczby zawsze się zgadzają.

**Chmura tagów prowadziła donikąd.** Osiem tagów zakodowanych na sztywno,
każdy z `href="#"`. Zastąpiona nawigacją po realnych kategoriach z licznikami
wpisów, generowaną z bazy.

**Czas czytania był zaniżony.** Liczony przez `str_word_count()`, który nie
obsługuje UTF-8 — polskie znaki diakrytyczne rozbijały wyrazy na części.
Zastąpione dopasowaniem `\p{L}+` z flagą `/u` w `hrl_child_reading_time()`.

**Nagłówek używał nieładowanego fontu.** `font-family:'Cinzel',serif` —
motyw ładuje Inter, Playfair Display i JetBrains Mono, Cinzela nie ma
w kolejce. Przeglądarka schodziła do zwykłego szeryfowego. Teraz używany
jest `--font-serif` z systemu tokenów.

## Zmiany w układzie (przejrzystość i oddech)
- trzy ciasne kolumny (`3fr 2fr 1.7fr`) → dwie: treść + sidebar 320 px,
- odstępy w siatce zwiększone z 1,8 rem do wartości płynnej `--space-8`,
- setki linii stylów inline przeniesione do klas CSS,
- sidebar przyklejony przy przewijaniu na szerokich ekranach,
- sensowny stan pusty zamiast surowego komunikatu o braku wpisów,
- paginacja w stylu reszty serwisu.

---

# Etap 6: Stopka — poprawka obejmująca całą witrynę

## Dlaczego to było pilniejsze niż kolejne strony

Stopka renderuje się na **każdej** podstronie. Deklaracje o organizacjach
zbiorowego zarządzania siedziały w niej w trzech miejscach — co oznacza, że
strony wyczyszczone w etapach 3–5 (główna, CMLP, radio, blog) i tak
wyświetlały je kilkanaście centymetrów niżej. Cała wcześniejsza praca była
przez to podważona.

## Usunięte

| Miejsce | Było |
|---|---|
| `footer.php` — opis | „Suwerenny ekosystem B2B Audio. Muzyka komercyjna bez opłat ZAiKS, OZZ i pośredników." |
| `footer.php` — menu | „CMLP — Muzyka bez ZAiKS" |
| `site-info.php` — dół każdej strony | „100% Direct Licensing — Zero ZAiKS / OZZ / ZPAV / STOART" |

Zastąpione odpowiednio: opisem oferty („Autorska muzyka dla biznesu. Katalog
na wyłączność, licencje bezpośrednio od twórcy."), pozycją „CMLP — Muzyka do
lokalu" oraz podpisem „Autorski katalog · Licencje bezpośrednio od twórcy".

## Przy okazji
- link „Panel CMLP" prowadził do `cmlp.hardbanrecordslab.online` bez ścieżki —
  poprawiony na `/cmlp/b2b` i przemianowany na „Panel B2B",
- „Radio HRL" ujednolicone do „Radio HRL Live",
- style inline z `site-info.php` przeniesione do klas CSS.

## Pozostałe pliki z deklaracjami o OZZ (do zrobienia)

| Plik | Trafień | Charakter |
|---|---|---|
| `page-about.php` | 27 | marketing |
| `page-sale-terms.php` | 13 | **dokument sprzedażowy** |
| `page-faq.php` | 8 | marketing |
| `page-terms.php` | 6 | **regulamin** |
| `page-license-agreement.php` | 1 | **umowa licencyjna** |
| `page-newsletter.php` | 1 | marketing |
| `page-muzyczna-kreacja-slow.php` | 1 | marketing |
| `sidebar-blogcast.php` | 1 | widoczne na blogu |
| `patterns/hero-amoled.php` | 1 | wzorzec bloku |
| `patterns/product-grid.php` | 1 | wzorzec bloku |
| `customizer.php` | 1 | wartość domyślna |

**Kolejność priorytetów:** dokumenty prawne (regulamin, warunki sprzedaży,
umowa licencyjna) przed stronami marketingowymi. Marketing to obietnica,
którą można wycofać; dokument prawny wiąże strony i to on trafia na stół
przy sporze.

---

# Etap 9: Pozostałe strony i elementy globalne (domknięcie)

## page-faq.php (było 8 deklaracji OZZ)
- „Certyfikat Zwolnienia z OZZ" → „Certyfikat Licencyjny" (2 miejsca),
- odpowiedź „Czy naprawdę nie muszę płacić ZAiKS/STOART/ZPAV/SAWP?" (oparta
  w całości na deklaracji o OZZ) → przebudowana na „Na czym polega
  licencjonowanie u HRL?" z komunikatem o licencjonowaniu bezpośrednim
  i Certyfikacie Licencyjnym,
- **317 → 197 linii.** 27 przycisków z inline `onclick` i powielonym stylem
  w każdym → jeden dostępny akordeon zarządzany danymi (wspólny `hrl-faq.js`),
- **spójność cenowa:** z FAQ usunięto twarde kwoty (roczne rabaty, 999 zł za
  event, liczby utworów w próbie), które kłóciły się z ustaleniami na CMLP.
  Zastąpione odesłaniem „aktualną cenę podajemy na CMLP" — cena żyje teraz
  w jednym miejscu i nie będzie się rozjeżdżać.

## page-muzyczna-kreacja-slow.php (2 trafienia, w tym konflikt)
- „Utwory MKS (…) nie podlegają OZZ, bez dodatkowych opłat" → opis oparty
  na umowie i zakresie praw,
- **naprawiony konflikt:** stara wersja deklarowała „Publikujemy utwór na
  Spotify, Apple Music, Tidal (…) 100% tantiem" — wprost sprzeczne z „katalog
  nie jest na streamingach", które powtarzamy na wszystkich pozostałych
  stronach. Ponieważ MKS to utwór na zamówienie klienta (inny przypadek niż
  katalog CMLP), przeformułowane na „na życzenie klienta możemy przygotować
  do dystrybucji, zakres praw ustalamy w umowie" — bez deklaracji o tantiemach.

## Elementy globalne
- `sidebar-blogcast.php`: „CMLP — Muzyka bez ZAiKS" → „CMLP — Muzyka do lokalu",
- `page-newsletter.php`: „bez ZAiKS" usunięte z opisu,
- `patterns/hero-amoled.php`: nagłówek „Muzyka bez ZAiKS" → „Autorska muzyka
  na wyłączność", opis bez „wyłączeniem opłat OZZ",
- `patterns/product-grid.php`: „certyfikat wolności od OZZ" → „Certyfikat
  Licencyjny z kodem QR",
- `customizer.php`: domyślny sticky-CTA „uwolnić biznes od ZAiKS" nadpisany
  filtrem `theme_mod_hrl_sticky_cta_text` (nie da się nadpisać samego pliku —
  wartość jest w motywie nadrzędnym).

### Uwaga techniczna do wzorców
Motyw nadrzędny rejestruje wzorce z `get_template_directory()` na sztywno,
więc podmiana pliku w child-theme nie wystarcza. Dodano
`hrl_child_override_patterns()` (priorytet 20), który wyrejestrowuje oba
wzorce i rejestruje ich wersje z child-theme.

## Stan końcowy: deklaracje o OZZ w treści widocznej dla użytkownika

**Zero** we wszystkich przebudowanych plikach (zweryfikowane automatycznie,
z pominięciem komentarzy w kodzie).

Świadomie NIETKNIĘTE — do osobnej decyzji (dokumenty prawne):
`page-sale-terms.php`, `page-terms.php`, `page-license-agreement.php`.
Tu podmiana treści bez przeglądu prawnego byłaby ryzykowna — te dokumenty
wiążą strony, więc powinny zostać zmienione świadomie, najlepiej po
konsultacji.

---

# Etap 10: Dokumenty prawne (projekty do przeglądu prawnika)

## ⚠ Ważne zastrzeżenie
Wszystkie trzy dokumenty otrzymały widoczny baner „Dokument w wersji roboczej —
treść przygotowana do przeglądu przez radcę prawnego przed publikacją".
To projekty ujednolicające treść z resztą serwisu, NIE gotowe dokumenty
do wdrożenia. Wiążą strony, więc przed publikacją wymagają przeglądu prawnego.

## Wspólny problem, który został naprawiony

Dokumenty opisywały **zupełnie inną ofertę niż strona CMLP** — inne pakiety,
ceny i limity utworów. Gdyby klient kupił „cały katalog" ze strony, a w umowie
miał „10 utworów", umowa by wygrała i powstałby spór. Wszystko ujednolicono
do CMLP jako źródła prawdy.

| | Było w umowach | Jest (= CMLP) |
|---|---|---|
| Pakiety | Starter/Business/Premium/**Enterprise**/Seasonal | Starter/Business/Premium/**Custom**/Event |
| Ceny | 199 / 399 / 799 / 4999 zł | 69 / 159 / 499 zł + Event 600 zł |
| Limit utworów | 10 / 50 / 200 | brak limitu (cały katalog) |
| Sieć | „min. 3 lokale / Enterprise" | Business do 5, Premium do 15 |
| Metraż lokalu | „do 500 m² gastro / 1000 m² inne" | bez limitu metrażu |

## page-terms.php (Regulamin, było 6 deklaracji OZZ)
- sekcja 2: cel „największa biblioteka wolna od tantiem i opłat zbiorowych"
  → „rozbudowana biblioteka oryginalnej muzyki, licencjonowana bezpośrednio",
- sekcja 5: „wszystkie licencje wolne od opłat ZAiKS/STOART/ZPAV/SAWP, co
  stanowi główną przewagę" → „utwory oryginalne, licencjonowane bezpośrednio;
  zakres uprawnień wynika z umowy",
- „pakiet Enterprise" → „pakiet Custom" (SLA).

## page-sale-terms.php (Warunki Sprzedaży B2B, było 13 deklaracji)
- definicja licencji: usunięte „z wyłączeniem opłat ZAiKS/STOART/ZPAV/SAWP",
- definicja lokalu: usunięte limity metrażu (500/1000 m²),
- definicja sieci: „min. 3 lokale / Enterprise" → pakiety Business/Premium,
- **sekcja 5 przepisana w całości**: „Certyfikat Zwolnienia z OZZ" →
  „Certyfikat Licencyjny". Kluczowa zmiana treści: stara wersja stwierdzała,
  że certyfikat „stanowi dokument ochronny podczas kontroli, upoważnia do
  odrzucenia żądań opłat" i kazała go wywiesić w lokalu — czyli wiążąco
  obiecywała klientowi skutek prawny wobec OZZ. Nowa wersja potwierdza tylko
  fakt posiadania aktywnej licencji i wprost zaznacza, że „nie przesądza
  o roszczeniach osób trzecich i nie zastępuje oceny prawnej",
- sekcja 2 (ceny): pełna lista pakietów ujednolicona do CMLP (patrz tabela).

## page-license-agreement.php (Regulamin Licencji, było 1 deklaracja + usterki)
- sekcja 3 (pakiety): przepisana do CMLP, bez limitów utworów, Enterprise
  i Seasonal → Custom i Event; „Certyfikat Zwolnienia z OZZ" → „Certyfikat
  Licencyjny",
- **naprawione usterki po AI builderze**: chińskie znaki „采样" → „samplowania",
  „SLA 99,9% kapowności" → „dostępności",
- definicje: usunięty metraż i „min. 3 lokale / Enterprise",
- 7× „Enterprise" → „Custom" (white-label, API, SLA, kontakt, definicje),
- nagłówek sekcji 6 „API Enterprise" → „API — pakiet Custom".

## Stan końcowy całej witryny
Zero deklaracji o OZZ w treści widocznej dla użytkownika — na wszystkich
przebudowanych stronach oraz we wszystkich trzech dokumentach prawnych
(zweryfikowane automatycznie, z pominięciem komentarzy w kodzie).
Ceny i pakiety spójne wszędzie: źródłem prawdy jest strona CMLP.

---

# Etap 11: Finalizacja

- **Banery „Dokument w wersji roboczej" usunięte** ze wszystkich trzech
  dokumentów prawnych — po przeglądzie prawnym. Usunięto również powiązany
  styl `.hrl-legal-draft-notice` z arkusza (nieużywany) oraz uporządkowano
  komentarze w nagłówkach plików.
- **Potwierdzono obecność pakietu Event** we wszystkich miejscach: strona
  główna, CMLP (cennik + FAQ + CTA), Warunki Sprzedaży, Regulamin Licencji.

## Walidacja końcowa (całość)
- znaczniki PHP zbilansowane we wszystkich plikach (pliki zwracające array
  celowo bez zamykającego `?>`),
- zero deklaracji o OZZ w treści widocznej dla użytkownika,
- ceny i pakiety spójne: Starter 69 / Business 159 / Premium 499 / Event 600 zł,
  źródło prawdy = strona CMLP,
- bilans nawiasów CSS = 0.

Paczka jest kompletna i gotowa do wgrania jako motyw potomny.
