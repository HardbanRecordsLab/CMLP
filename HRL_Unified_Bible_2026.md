# HRL_Unified_Bible_2026
## Strategiczny Podręcznik Operacyjny Ekosystemu HardbanRecords Lab

> **WERSJA:** 4.5 (Sync Hub CORS + iframe fix)
> **DATA:** 2026-05-15
> **STATUS:** LIVING DOCUMENT (Źródło Prawdy)
> **ZAKTUALIZOWANO:** Poprawka CORS dla domeny app.hrl-sync-hub (Sync Hub API + Access Manager), naprawa blokady iframe (CSP frame-ancestors), naprawa vercel.json (API rewrite → hrl-sync-hub), zamrożenie OmniPost i Community (FROZEN).

---

## 1. ARCHITEKTURA SYSTEMU (CORE)

**Żelazna zasada: FRONTEND → Vercel | BACKEND → VPS 84.247.162.167**

- **Frontend**: 9 aplikacji React/Vite na Vercel — `app-*.hardbanrecordslab.online`
- **Backend**: VPS Ubuntu 22.04 — 9 mikroserwisów PM2 + Metadata Engine Docker
- **SSO**: Login na WordPress → cookie `jwt_token` na `.hardbanrecordslab.online` → wszystkie aplikacje
- **DB**: PostgreSQL `hbrl_master` (42 tabele) na kontenerze `hbrl-postgres`

### Przepływ SSO (v3 — Hybrid Cookie + Auth Header):
```
1. Użytkownik loguje się na WordPress
2. mu-plugin hrl-sso-redirect.php (v1.3) wymusza redirect do:
   https://app.hrl-sync-hub.hardbanrecordslab.online?token=<jwt>
3. Frontend aplikacji (AuthContext) wyłapuje token z URL, zapisuje go w localStorage i cookie.
4. Każdy request do API Access Managera zawiera nagłówek 'Authorization: Bearer <token>'.
5. Access Manager na VPS (main.py) weryfikuje nagłówek Bearer (rozwiązuje błąd 401 w Incognito).
```

---

## 2. DOSTĘPY I INFRASTRUKTURA (ADMIN)

| Serwis | Adres / Dostęp | Dane |
| :--- | :--- | :--- |
| **VPS** | `84.247.162.167` | SSH: `ssh -i C:\Users\HRL\.ssh\id_ed25519 root@84.247.162.167` |
| **PostgreSQL** | `localhost:5433` | `hrlsync` / (container) / db: `hrlsync` |
| **WordPress** | `hardbanrecordslab.online/wp-admin` | `hardbanrecordslab` / `HRL_Admin_2026!` |
| **Vercel** | `vercel.com/hardbanrecordslabs-projects` | Token: `***REDACTED***` |
| **GitHub** | `github.com/HardbanRecordsLab` | SSH: `***REDACTED***` / PAT: `***REDACTED***` |

---

## 3. MAPA DOMEN I PORTÓW (KANONICZNE)

| Moduł | Backend API | Frontend (Vercel) | Port | PM2 | Status |
| :--- | :--- | :--- | :---: | :--- | :--- |
| **Access Manager** | `hrl-access.hardbanrecordslab.online` | — | 9107 | `access-manager` | ✅ ONLINE |
| **User Hub** | `user-hub.hardbanrecordslab.online` | `app-user-hub.hardbanrecordslab.online` | 9101 | `user-hub` | ✅ ONLINE |
| **WriteMuse** | `writemuse.hardbanrecordslab.online` | `app-writemuse.hardbanrecordslab.online` | 9102 | `writemuse` | ✅ ONLINE |
| **MasterPro** | `masterpro.hardbanrecordslab.online` | `app-masterpro.hardbanrecordslab.online` | 9103 | `masterpro` | ✅ ONLINE |
| **Metadata Engine** | `metadata.hardbanrecordslab.online` | `app-metadata.hardbanrecordslab.online` | 8888 | Docker | ✅ ONLINE |
| **Course Hub** | `course-hub.hardbanrecordslab.online` | `app-course-hub.hardbanrecordslab.online` | 9104 | `course-hub` | ✅ ONLINE |
| **Community** | `hrl-community.hardbanrecordslab.online` | `app-community.hardbanrecordslab.online` | 9106 | `community` | ❄️ FROZEN |
| **Sync Hub** | `hrl-sync-hub.hardbanrecordslab.online` | `app.hrl-sync-hub.hardbanrecordslab.online` | 9108 | `sync-hub` | ✅ ONLINE |
| **Webook Studio** | `webook.hardbanrecordslab.online` | `app-webook.hardbanrecordslab.online` | 5000 | `webook-studio` | ✅ ONLINE |
| **OmniPost** | `omnipost.hardbanrecordslab.online` | `app-omnipost.hardbanrecordslab.online` | 3004 | `omnipost` | ❄️ FROZEN |
| **WordPress** | `hardbanrecordslab.online` | `hardbanrecordslab.online` | 80/443 | Docker | ✅ ONLINE |

---

## 4. CENTRALNA BAZA POSTGRESQL (hbrl_master)
*(Bez zmian)*

---

## 5. SSO — SYSTEM LOGOWANIA (v3)

### WordPress mu-plugins (aktywne):
| Plugin | Plik | Funkcja |
|--------|------|---------|
| HRL SSO Redirect | `hrl-sso-redirect.php` v1.3 | **NOWOŚĆ**: Hardcoded URL fallback, token-in-url |

---

## 14. MODUŁY ZAMROŻONE (FROZEN)

Zgodnie z decyzją z dnia 2026-05-16, poniższe moduły zostały wyłączone z aktywnego ekosystemu (nieużywane, niewidoczne, ale zachowane na serwerze):

1. **OmniPost** — Wszystkie funkcje social media wstrzymane. PM2 process `omnipost` zatrzymany.
2. **Community** — Moduł społecznościowy wstrzymany. PM2 process `community` zatrzymany.

---

**KONIEC BIBLII v4.5 (Zaktualizowano SSO v3 + Frozen Modules)**
