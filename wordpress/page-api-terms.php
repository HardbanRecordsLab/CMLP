<?php
/**
 * Template Name: Regulamin API Enterprise
 * @package HRL_Theme
 *Last updated: 2026-07-12
 */
get_header();
?>
<div class="legal-page-wrapper">
<div class="legal-content">
    <h1><?php esc_html_e( 'Regulamin API Enterprise', 'hrl-theme' ); ?></h1>
    <p class="legal-date"><?php esc_html_e( 'Ostatnia aktualizacja: 12 lipca 2026 r.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '1. Wprowadzenie i zakres', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Niniejszy Regulamin API Enterprise określa zasady dostępu, wykorzystania oraz ograniczenia dotyczące interfejsu programistycznego API REST v3 udostępnianego przez HardbanRecords Lab w ramach platformy Commercial Music Licensing Platform CMLP. Regulamin stanowi część umowy licencyjnej między HardbanRecords Lab a Klientem pakietu Enterprise i określa szczegółowe warunki techniczne, prawne oraz bezpieczeństwa związane z integracją odtwarzacza CMLP z systemami Klienta. Korzystanie z API oznacza akceptację niniejszego regulaminu.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '2. Dostęp do API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Dostęp do API Enterprise jest dostępny wyłącznie dla klientów posiadających aktywną licencję pakietu Enterprise. API udostępniane jest przez HTTPS pod adresem docs.cmlp.hardbanrecordslab.online. Dostęp wymaga autoryzacji za pomocą tokenów JWT, które można wygenerować w Panelu B2B w zakładce Ustawienia integracje. Tokeny JWT mają ograniczony czas ważności i muszą być odnawiane regularnie. HardbanRecords Lab zastrzega sobie prawo do wprowadzania limitów zapytań rate limiting w celu ochrony stabilności infrastruktury. Domyślny limit dla planów Enterprise wynosi 1000 zapytań na godzinę na konto.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '3. Ograniczenia API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Klient nie ma prawa do udostępniania tokenów API osobom trzecim, wykorzystywania API do celów nieautoryzowanych, tworzenia konkurencyjnych serwisów na bazie API, ani wykonywania requestów masowych bulk requests mogących obciążyć infrastrukturę. Naruszenie warunków API może skutkować natychmiastowym zablokowaniem dostępu API bez zwrotu opłaty. Klient zobowiązuje się do przestrzegania polityki rate limiting i nie przekroczenia dozwolonej liczby zapytań. W przypadku przekroczenia limitów, API zwróci odpowiedź z kodem 429 Too Many Requests.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '4. Wersjonowanie API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'HardbanRecords Lab prowadzi wersjonowanie API zgodnie z semantykiem wersji semver. Wersja API jest określana w nagłówku żądania Accept: application/vnd.hrl.v3+json. Administrator zobowiązuje się do powiadamiania klientów o planowanych zmianach w API z co najmniej 30-dniowym wyprzedzeniem. W przypadku wprowadzenia zmian powodujących utratę kompatybilności wstecznej, nowa wersja API zostanie wydana równolegle z poprzednią przez okres nie krótszy niż 90 dni.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '5. Reverse Engineering', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Klient zobowiązuje się do niepodejmowania żadnych działań mających na celu reverse engineering, dekompilację, montaż, tłumaczenie ani w jakikolwiek sposób modyfikowanie API, dokumentacji API, lub powiązanego oprogramowania. Klient nie ma prawa do tworzenia konkurencyjnych produktów na bazie API lub dokumentacji. HardbanRecords Lab zastrzega sobie wszystkie prawa własności intelektualnej do API, dokumentacji i powiązanego oprogramowania.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '6. Monitorowanie i logi', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'HardbanRecords Lab prowadzi monitoring użycia API dla wszystkich klientów Enterprise. Logi obejmują: identyfikator klienta, czas żądania, endpoint, kod odpowiedzi, czas odpowiedzi, adres IP żądającego. Logi są przechowywane przez okres 12 miesięcy i mogą być wykorzystane do analizy bezpieczeństwa, diagnostyki problemów oraz planowania rozwoju infrastruktury. Klient ma dostęp do własnych logów API przez Panel B2B.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '7. Rozwiązanie umowy API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Umowa dotycząca dostępu do API Enterprise rozstrzyga się automatycznie wraz z rozwiązaniem umowy licencyjnej. Po rozwiązaniu umowy, klient zobowiązany jest do natychmiastowego zaprzestania korzystania z API i usunięcia wszystkich skopiowanych danych, tokenów autoryzacyjnych oraz powiązanego oprogramowania z systemów. HardbanRecords Lab zastrzega sobie prawo do natychmiastowego zablokowania dostępu API w przypadku naruszenia warunków regulaminu.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '8. Dokumentacja API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Pełna dokumentacja API Enterprise dostępna jest pod adresem docs.cmlp.hardbanrecordslab.online po zalogowaniu się do Panelu B2B. Dokumentacja obejmuje: opis wszystkich endpointów, formaty żądań i odpowiedzi, kody błędów, przykłady użycia w różnych językach programowania, politykę rate limiting, autoryzację OAuth2 oraz JWT, obsługę błędów, paginację, filtrowanie oraz sortowanie. Dokumentacja aktualizowana jest co najmniej raz na kwartał i klient otrzymuje powiadomienie o każdej istotnej zmianie.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '9. Wsparcie techniczne API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Klienci Enterprise otrzymują priorytetowe wsparcie techniczne dotyczące API. Czas reakcji na zapytania standardowe wynosi do 8 godzin roboczych, a na incydenty krytyczne do 1 godziny roboczej. Wsparcie świadczane jest przez ticket system w Panelu B2B oraz dedykowanego opiekuna konta. HardbanRecords Lab oferuje również szkolenia z integracji API dla zespołów technicznych klienta, dostępne w formie sesji zdalnych lub na miejscu.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '10. Bezpieczeństwo API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'HardbanRecords Lab stosuje kompleksowe środki bezpieczeństwa w zakresie API. Wszystkie połączenia API wymagają TLS 1.2 lub nowszego. Tokeny JWT muszą być przechowywane bezpiecznie i nie mogą być udostępniane osobom trzecim. Klient zobowiązuje się do przestrzegania zasad bezpieczeństwa API, w tym nieużywania tokenów w kodzie klienta dostępnym publicznie, regularnej rotacji tokenów, monitorowania użycia API pod kątem nieautoryzowanego dostępu. W przypadku podejrzenia nieautoryzowanego dostępu do API, klient zobowiązany jest do natychmiastowego wycofania skompromitowanych tokenów i powiadomienia zespołu bezpieczeństwa HardbanRecords Lab.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '11. Integracje z systemami zewnętrznymi', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Klient Enterprise może integrować API CMLP z dowolnymi systemami zewnętrznymi, w tym systemami POS, aplikacjami mobilnymi, panelami zarządzania, systemami automatyki budynku oraz narzędziami BI. HardbanRecords Lab dostarcza dokumentację techniczną, przykłady kodu oraz wsparcie podczas procesu integracji. Klient zobowiązuje się do przestrzegania standardów branżowych przy implementacji integracji, w tym bezpiecznego przechowywania tokenów, obsługi błędów, logowania oraz monitorowania. HardbanRecords Lab nie ponosi odpowiedzialności za problemy wynikające z nieprawidłowej implementacji integracji po stronie Klienta.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '12. SLA i dostępność API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'HardbanRecords Lab gwarantuje dostępność API na poziomie 99,9% w ciągu każdego miesiąca kalendarzowego, z wyłączeniem planowanych przerw konserwacyjnych ogłaszanych z 7-dniowym wyprzedzeniem. Planowane przerwy konserwacyjne przeprowadzane są zwykle w godzinach nocnych w weekendy, aby minimalizować wpływ na działanie biznesową Klienta. W przypadku niezrealizowania gwarantowanego poziomu dostępności, Klient ma prawo do żądania odszkodowania w formie kredytu na kolejny okres rozliczeniowy, proporcjonalnego do czasu niedostępności API.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '13. Zmiany w regulaminie API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'HardbanRecords Lab zastrzega sobie prawo do modyfikacji niniejszego Regulaminu API Enterprise w dowolnym momencie z przyczyn uzasadnionych, w szczególności zmianami w przepisach prawa, rozwojem technologii, wprowadzaniem nowych funkcji API lub dostosowaniem regulaminu do wytycznych organów nadzorczych. O każdej zmianie regulaminu API administrator poinformuje Klientów z 14-dniowym wyprzedzeniem na adres e-mail powiązany z kontem. Kontynuowanie korzystania z API po wprowadzeniu zmian oznacza akceptację nowych postanowień regulaminu.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '14. Odpowiedzialność i ograniczenia', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'HardbanRecords Lab nie ponosi odpowiedzialności za szkody wynikające z nieprawidłowego wykorzystania API przez Klienta lub osoby trzecie, w tym za utratę danych, utracone zyski, straty pośrednie lub szkody następcze. Maksymalna odpowiedzialność Administratora wobec Klienta nie przekroczy kwoty opłaty licencyjnej zapłaconej w bieżącym okresie rozliczeniowym. Klient zobowiązuje się do odszkodowania HardbanRecords Lab w przypadku szkód wynikających z naruszenia regulaminu API, w tym nieautoryzowanego wykorzystania API, przekroczenia limitów zapytań lub wykorzystania API do celów nieautoryzowanych.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '15. Kontakt w sprawach API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'W sprawach dotyczących dostępu do API, integracji, wsparcia technicznego lub zgłaszania problemów z API, Klient może kontaktować się z HardbanRecords Lab pod adresem e-mail api@hardbanrecordslab.online, telefonicznie pod numerem +48 726 651 384 lub przez ticket system w Panelu B2B w zakładce Wsparcie. Administrator udziela odpowiedzi w ciągu 8 godzin roboczych dla klientów Enterprise. W przypadku incydentów krytycznych blokujących działanie systemu Klienta, prosimy o oznaczenie tematu jako Pilne.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '16. Postanowienia końcowe', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'W sprawach nieuregulowanych niniejszym Regulaminem API Enterprise zastosowanie mają przepisy prawa polskiego, w szczególności Kodeksu Cywilnego, ustawy o świadczeniu usług drogą elektroniczną, ustawy o prawach konsumentów oraz inne obowiązujące akty prawne. Wszelkie spory wynikłe z związane z korzystaniem z API lub wykonaniem regulaminu rozstrzygane będą w pierwszej kolejności na drodze negocjacji między stronami. Jeśli strony nie dojdą do porozumienia przez okres 30 dni, sprawa zostanie skierowana do sądu powszechnego właściwego dla siedziby administratora HardbanRecords Lab.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '17. Wsparcie przy integracji', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'HardbanRecords Lab oferuje kompleksowe wsparcie przy integracji API dla klientów Enterprise. Wsparcie obejmuje: sesje onboardingowe zdalne lub na miejscu, dostosowanie dokumentacji API do specyfiki systemu Klienta, wsparcie w trakcie procesu integracji, testy integracji w środowisku testowym przed wdrożeniem na produkcję, szkolenia dla zespołów technicznych Klienta oraz stałe wsparcie konsultingowe. Klient Enterprise może również zamówić usługi developmentu custom modułów integracyjnych w ramach umowy o świadczenie usług dodatkowych. Wszystkie usługi dodatkowe są wliczane do raportu miesięcznego i widoczne w Panelu B2B.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '18. Zarządzanie wersjami i wdrożenia', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Klient Enterprise ma możliwość zarządzania wieloma wersjami API w środowiskach testowym i produkcyjnym. HardbanRecords Lab dostarcza narzędzia do monitorowania wersji API, automatycznego testowania kompatybilności oraz powiadamiania o nowych wersjach. Klient może wybrać strategię wdrożenia: natychmiastowe wdrożenie nowej wersji, wdrożenie w określonym terminie lub wdrożenie po zatwierdzeniu przez zespół techniczny Klienta. Administrator zobowiązuje się do utrzymania poprzednich wersji API przez okres co najmniej 90 dni od wydania nowej wersji, co pozwala na płynne przejście bez zakłóceń w działaniu systemu Klienta.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '19. Testy i środowiska', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'HardbanRecords Lab udostępnia klientom Enterprise dostęp do środowiska testowego sandbox API, które replikuje funkcjonalności API produkcyjnego w izolowanym środowisku. Środowisko testowe umożliwia klientom testowanie integracji, eksperymentowanie z nowymi funkcjami API oraz przygotowanie wdrożenia bez ryzyka zakłócenia działania systemu produkcyjnego. Środowisko testowe jest dostępne 24/7 i ma te same limity rate limiting co środowisko produkcyjne. Klient może generować nieograniczoną liczbę tokenów testowych do środowiska sandbox.', 'hrl-theme' ); ?></p>    <h2><?php esc_html_e( '20. Kontakt w sprawach API', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'W sprawach dotyczących API Enterprise, integracji, wsparcia technicznego lub zgłaszania problemów z API, Klient może kontaktować się z HardbanRecords Lab pod adresem e-mail api@hardbanrecordslab.online, telefonicznie pod numerem +48 726 651 384 lub przez ticket system w Panelu B2B w zakładce Wsparcie. Administrator udziela odpowiedzi w ciągu 8 godzin roboczych dla klientów Enterprise. W przypadku incydentów krytycznych blokujących działanie systemu Klienta, prosimy o oznaczenie tematu jako Pilne. Dla spraw ogólnych dotyczących API można również skorzystać z adresu contact@hardbanrecordslab.online. Jesteśmy do dyspozycji i pomożemy Ci w każdej kwestii dotyczącej integracji API z systemami Twojej firmy.', 'hrl-theme' ); ?></p>    <p><?php esc_html_e( 'Dziękujemy za korzystanie z API Enterprise HardbanRecords Lab. Nasz zespół jest gotów odpowiedzieć na wszystkie pytania i pomóc Ci wdrożyć integrację API w systemach Twojej firmy. Skontaktuj się z nami pod adresem api@hardbanrecordslab.online lub +48 726 651 384. Jesteśmy do dyspozycji i wspieramy naszych klientów Enterprise na każdym etapie współpracy.', 'hrl-theme' ); ?></p>    <p><?php esc_html_e( 'Niniejszy regulamin API Enterprise wchodzi w życie z dniem 12 lipca 2026 roku i zastępuje wszystkie wcześniejsze wersje regulaminu API.', 'hrl-theme' ); ?></p>



</div>
</div>
<?php get_footer(); ?>