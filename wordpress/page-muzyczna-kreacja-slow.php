<?php
/**
 * Template Name: Muzyczna Kreacja Słów
 * Description: Pełna strona docelowa usługi personalizowanych utworów muzycznych.
 * Pricing: Mini 49 zł | Standard 99 zł | Premium 199 zł | Firmowy od 399 zł
 * Version: 3.2.0 — encapsulated inline styles, zero external dependencies
 */
get_header();
?>
<style>
/* ═══════════════════════════════════════════════════════════
   MKS ENCAPSULATED STYLES — Completely isolated from theme cascade
   Overrides any WP container styles with explicit rules.
   ═══════════════════════════════════════════════════════════ */
#mks-page-root {
  --mks-bg-main: #000000;
  --mks-bg-card: rgba(18, 15, 12, 0.75);
  --mks-border-glow: rgba(200, 169, 110, 0.2);
  --mks-gold: #C8A96E;
  --mks-gold-light: #E8D5A3;
  --mks-gold-dark: #8B6914;
  --mks-gold-neon: #FFCA61;
  --mks-text-primary: #F5F0E6;
  --mks-text-secondary: #a3998a;
  --mks-white: #FFFFFF;
  --mks-bg-dark: #050505;
  --mks-wine: #6B2737;
  --mks-forest: #1F3D2F;
  --mks-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.82);
  --mks-font-headings: 'Playfair Display', Georgia, serif;
  --mks-font-sans: 'DM Sans', 'Inter', sans-serif;

  all: unset;
  display: block;
  background: #000000 !important;
  color: var(--mks-text-primary);
  font-family: var(--mks-font-sans);
  font-size: 16px;
  line-height: 1.7;
  overflow-x: hidden;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
#mks-page-root *,
#mks-page-root *::before,
#mks-page-root *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: inherit;
}
#mks-page-root a { color: var(--mks-gold); text-decoration: none; transition: all 0.3s; }
#mks-page-root a:hover { color: var(--mks-gold-light); }
#mks-page-root img { max-width: 100%; height: auto; }
#mks-page-root h1,#mks-page-root h2,#mks-page-root h3,#mks-page-root h4,#mks-page-root h5,#mks-page-root h6 { font-family: var(--mks-font-headings); line-height: 1.2; }
#mks-page-root section { padding: 8rem 2rem; position: relative; display: block; background: transparent; }
#mks-page-root .container { max-width: 1200px; margin: 0 auto; }

/* Progress Bar */
#mks-page-root .progress-container { position: fixed; top: 0; left: 0; width: 100%; height: 3px; z-index: 2000; background: transparent; }
#mks-page-root .progress-bar { height: 100%; background: linear-gradient(90deg, var(--mks-gold-dark), var(--mks-gold), var(--mks-gold-light)); width: 0%; }

/* Hero */
#mks-page-root .mks-hero { min-height: 100vh; background: radial-gradient(circle at 50% 35%, rgba(35,28,20,0.7) 0%, #000000 75%) !important; display: flex; align-items: center; justify-content: center; text-align: center; padding: 10rem 2rem 6rem; position: relative; overflow: hidden; }
#mks-page-root .mks-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 15% 50%, rgba(107,39,55,0.18) 0%, transparent 55%), radial-gradient(ellipse at 85% 50%, rgba(31,61,47,0.18) 0%, transparent 55%); z-index: 0; }
#mks-page-root .hero-ornament { position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 1px; height: 120px; background: linear-gradient(to bottom, transparent, var(--mks-gold)); opacity: 0.6; z-index: 1; }
#mks-page-root .hero-content { position: relative; max-width: 900px; z-index: 2; background: transparent !important; }
#mks-page-root .hero-eyebrow { color: var(--mks-gold) !important; font-size: 0.8rem; letter-spacing: 0.35em; text-transform: uppercase; margin-bottom: 1.5rem; font-weight: 600; font-family: var(--mks-font-sans); }
#mks-page-root .hero-content h1 { font-family: var(--mks-font-headings) !important; font-size: clamp(2.8rem, 6.8vw, 5rem) !important; color: var(--mks-text-primary) !important; line-height: 1.15 !important; margin-bottom: 1.2rem !important; font-weight: 700 !important; background: transparent !important; }
#mks-page-root .hero-content h1 em { color: var(--mks-gold) !important; font-style: italic; text-shadow: 0 0 20px rgba(200,169,110,0.25); }
#mks-page-root .hero-sub { font-family: var(--mks-font-headings) !important; font-size: clamp(1.15rem, 2.8vw, 1.5rem) !important; color: var(--mks-gold-light) !important; font-style: italic; margin-bottom: 2.5rem; }
#mks-page-root .hero-desc { color: var(--mks-text-secondary) !important; font-size: 1.05rem; max-width: 720px; margin: 0 auto 3.5rem; line-height: 1.8; }
#mks-page-root .hero-actions { display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap; }

#mks-page-root .btn-primary { background: linear-gradient(135deg, var(--mks-gold) 0%, var(--mks-gold-light) 100%) !important; color: #000000 !important; padding: 1rem 2.4rem; font-weight: 700; font-size: 0.9rem; letter-spacing: 0.08em; text-decoration: none; border-radius: 4px; transition: all 0.3s; display: inline-block; box-shadow: 0 4px 22px rgba(200,169,110,0.3); border: none !important; }
#mks-page-root .btn-primary:hover { background: var(--mks-white) !important; color: #000 !important; transform: translateY(-2px); box-shadow: 0 6px 28px rgba(200,169,110,0.5); }
#mks-page-root .btn-secondary { border: 1px solid var(--mks-border-glow) !important; color: var(--mks-gold) !important; padding: 1rem 2.4rem; font-size: 0.9rem; letter-spacing: 0.08em; text-decoration: none; border-radius: 4px; transition: all 0.3s; display: inline-block; background: rgba(200,169,110,0.03) !important; }
#mks-page-root .btn-secondary:hover { border-color: var(--mks-gold) !important; background: rgba(200,169,110,0.09) !important; transform: translateY(-2px); color: var(--mks-gold) !important; }

/* Hero Badges */
#mks-page-root .hero-badges { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 5.5rem; border-top: 1px solid var(--mks-border-glow); padding-top: 3rem; }
#mks-page-root .hero-badge { background: var(--mks-bg-card); border: 1px solid var(--mks-border-glow); padding: 1.75rem 1rem; border-radius: 6px; backdrop-filter: blur(10px); text-align: center; }
#mks-page-root .hero-badge-num { font-family: var(--mks-font-headings) !important; font-size: 2.2rem; color: var(--mks-gold) !important; font-weight: 700; margin-bottom: 0.3rem; }
#mks-page-root .hero-badge-label { font-size: 0.75rem; color: var(--mks-text-secondary) !important; letter-spacing: 0.12em; text-transform: uppercase; }

/* Section shared */
#mks-page-root .section-eyebrow { text-align: center; color: var(--mks-gold) !important; font-size: 0.8rem; letter-spacing: 0.35em; text-transform: uppercase; margin-bottom: 0.8rem; font-weight: 600; }
#mks-page-root .section-title { font-family: var(--mks-font-headings) !important; font-size: clamp(2.2rem, 4.5vw, 3.2rem) !important; text-align: center; margin-bottom: 1.2rem; line-height: 1.25; color: var(--mks-text-primary) !important; background: transparent !important; }
#mks-page-root .section-lead { text-align: center; color: var(--mks-text-secondary) !important; max-width: 750px; margin: 0 auto 4.5rem; font-size: 1.1rem; line-height: 1.8; }
#mks-page-root .divider { width: 80px; height: 2px; background: linear-gradient(90deg, transparent, var(--mks-gold), transparent); margin: 2rem auto 3rem; border: none; display: block; }

/* Manifesto */
#mks-page-root .manifesto { background: var(--mks-bg-dark) !important; border-top: 1px solid var(--mks-border-glow); border-bottom: 1px solid var(--mks-border-glow); }
#mks-page-root .manifesto-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-top: 3.5rem; }
#mks-page-root .manifesto-card { background: var(--mks-bg-card); border: 1px solid var(--mks-border-glow); padding: 3.5rem 2.8rem; border-radius: 8px; backdrop-filter: blur(10px); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
#mks-page-root .manifesto-card:hover { border-color: var(--mks-gold); transform: translateY(-4px); box-shadow: var(--mks-shadow); background: rgba(28,22,17,0.55); }
#mks-page-root .manifesto-icon { font-size: 2.2rem; margin-bottom: 1.5rem; display: inline-block; filter: drop-shadow(0 0 12px rgba(200,169,110,0.35)); }
#mks-page-root .manifesto-card h3 { font-family: var(--mks-font-headings) !important; color: var(--mks-gold) !important; font-size: 1.4rem; margin-bottom: 1.2rem; background: transparent !important; }
#mks-page-root .manifesto-card p { color: var(--mks-text-secondary) !important; font-size: 0.98rem; line-height: 1.8; }

/* Song Tabs */
#mks-page-root .utwory { background: var(--mks-bg-main) !important; }
#mks-page-root .song-tabs { display: flex; gap: 0.6rem; justify-content: center; border-bottom: 1px solid var(--mks-border-glow); margin-bottom: 4.5rem; padding-bottom: 0.75rem; overflow-x: auto; }
#mks-page-root .song-tab { padding: 1rem 2.2rem; font-size: 0.9rem; letter-spacing: 0.06em; font-family: var(--mks-font-sans); font-weight: 500; border: none !important; background: none !important; cursor: pointer; color: var(--mks-text-secondary) !important; border-bottom: 3px solid transparent !important; transition: all 0.3s; white-space: nowrap; border-radius: 0 !important; }
#mks-page-root .song-tab:hover { color: var(--mks-gold) !important; }
#mks-page-root .song-tab.active { color: var(--mks-gold) !important; border-bottom-color: var(--mks-gold) !important; text-shadow: 0 0 12px rgba(200,169,110,0.35); font-weight: 700; background: none !important; }
#mks-page-root .song-panel { display: none; grid-template-columns: 1.2fr 1fr; gap: 4.5rem; align-items: start; animation: mksFadeIn 0.6s ease-out; }
#mks-page-root .song-panel.active { display: grid; }
@keyframes mksFadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
#mks-page-root .song-occasion { display: inline-block; background: rgba(200,169,110,0.12); color: var(--mks-gold-light) !important; font-size: 0.75rem; letter-spacing: 0.18em; padding: 0.4rem 1.2rem; border-radius: 4px; margin-bottom: 1.5rem; text-transform: uppercase; border: 1px solid rgba(200,169,110,0.2); }
#mks-page-root .song-title-text { font-family: var(--mks-font-headings) !important; font-size: 2.4rem; margin-bottom: 0.5rem; line-height: 1.2; color: var(--mks-white) !important; background: transparent !important; }
#mks-page-root .song-artist { color: var(--mks-gold) !important; font-size: 0.95rem; margin-bottom: 1.5rem; font-style: italic; }
#mks-page-root .song-desc { color: var(--mks-text-secondary) !important; font-size: 1rem; line-height: 1.8; margin-bottom: 2.5rem; }
#mks-page-root .song-tags { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-bottom: 2.5rem; }
#mks-page-root .song-tag { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: var(--mks-text-primary) !important; font-size: 0.8rem; padding: 0.4rem 1.1rem; border-radius: 20px; }
#mks-page-root .song-price-box { border: 1px solid var(--mks-border-glow); padding: 1.75rem; border-radius: 6px; background: var(--mks-bg-card); backdrop-filter: blur(10px); display: inline-block; min-width: 280px; }
#mks-page-root .song-price-box .price { font-family: var(--mks-font-headings) !important; font-size: 1.9rem; color: var(--mks-gold-neon) !important; font-weight: 700; }
#mks-page-root .song-price-box .price-note { font-size: 0.8rem; color: var(--mks-text-secondary) !important; margin-top: 0.3rem; }
#mks-page-root .lyrics-card { background: linear-gradient(135deg, rgba(200,169,110,0.04) 0%, rgba(0,0,0,0) 100%); border: 1px solid var(--mks-border-glow); border-radius: 8px; padding: 3.5rem; position: relative; box-shadow: var(--mks-shadow); }
#mks-page-root .lyrics-card::before { content: '\266A'; position: absolute; top: 1.5rem; right: 2rem; color: rgba(200,169,110,0.12); font-size: 3rem; font-family: var(--mks-font-headings); }
#mks-page-root .lyrics-label { font-size: 0.75rem; letter-spacing: 0.25em; color: var(--mks-gold) !important; margin-bottom: 1.8rem; text-transform: uppercase; font-weight: 600; border-bottom: 1px solid var(--mks-border-glow); padding-bottom: 0.6rem; }
#mks-page-root .lyrics-text { font-family: var(--mks-font-headings) !important; font-style: italic; color: var(--mks-text-primary) !important; font-size: 1.1rem; line-height: 2.2; white-space: pre-line; }

/* Cennik */
#mks-page-root .cennik { background: var(--mks-bg-dark) !important; border-top: 1px solid var(--mks-border-glow); border-bottom: 1px solid var(--mks-border-glow); }
#mks-page-root .pakiety-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 2.5rem; }
#mks-page-root .pakiet { background: var(--mks-bg-card) !important; border: 1px solid var(--mks-border-glow); border-radius: 8px; padding: 3.5rem 2rem; position: relative; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); backdrop-filter: blur(10px); display: flex; flex-direction: column; }
#mks-page-root .pakiet:hover { border-color: var(--mks-gold) !important; transform: translateY(-6px); box-shadow: var(--mks-shadow); }
#mks-page-root .pakiet.featured { border-color: var(--mks-gold) !important; background: linear-gradient(180deg, rgba(35,28,20,0.45) 0%, rgba(18,15,12,0.75) 100%) !important; box-shadow: 0 0 35px rgba(200,169,110,0.12); }
#mks-page-root .pakiet-badge { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: linear-gradient(90deg, var(--mks-gold-dark), var(--mks-gold)); color: #000000 !important; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.15em; padding: 0.4rem 1.2rem; border-radius: 4px; text-transform: uppercase; white-space: nowrap; }
#mks-page-root .pakiet-name { font-size: 0.75rem; letter-spacing: 0.25em; color: var(--mks-text-secondary) !important; text-transform: uppercase; margin-bottom: 0.4rem; font-weight: 500; font-family: var(--mks-font-sans); }
#mks-page-root .pakiet-title { font-family: var(--mks-font-headings) !important; font-size: 1.75rem; margin-bottom: 0.3rem; color: var(--mks-white) !important; background: transparent !important; }
#mks-page-root .pakiet-price-inline { font-family: var(--mks-font-headings) !important; font-size: 2.1rem; color: var(--mks-gold-neon) !important; margin: 1.2rem 0; font-weight: 700; white-space: nowrap; display: flex; align-items: baseline; gap: 0.3rem; }
#mks-page-root .pakiet-price-inline span { font-size: 1rem; font-family: var(--mks-font-sans); color: var(--mks-text-secondary) !important; font-weight: 400; }
#mks-page-root .pakiet-desc { font-size: 0.9rem; color: var(--mks-text-secondary) !important; margin-bottom: 2rem; line-height: 1.6; min-height: 70px; }
#mks-page-root .pakiet-features { list-style: none !important; margin: 0 0 2.5rem 0 !important; padding: 0 !important; flex-grow: 1; }
#mks-page-root .pakiet-features li { padding: 0.6rem 0; border-bottom: 1px solid rgba(255,255,255,0.03); font-size: 0.88rem; color: var(--mks-text-primary) !important; display: flex; align-items: center; gap: 0.6rem; line-height: 1.4; list-style: none !important; margin: 0 !important; }
#mks-page-root .pakiet-features li::before { content: '\2713'; color: var(--mks-gold) !important; font-weight: 900; flex-shrink: 0; display: inline-block; }
#mks-page-root .btn-pakiet { display: block; text-align: center; padding: 0.9rem 1.2rem; border: 1px solid var(--mks-gold) !important; color: var(--mks-gold) !important; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-decoration: none; border-radius: 4px; transition: all 0.3s; cursor: pointer; background: rgba(0,0,0,0.3) !important; width: 100%; margin-top: auto; }
#mks-page-root .btn-pakiet:hover, #mks-page-root .pakiet.featured .btn-pakiet { background: linear-gradient(135deg, var(--mks-gold) 0%, var(--mks-gold-light) 100%) !important; color: #000000 !important; border-color: transparent !important; box-shadow: 0 4px 18px rgba(200,169,110,0.25); }

/* O mnie */
#mks-page-root .o-mnie { background: var(--mks-bg-main) !important; }
#mks-page-root .o-mnie-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 6.5rem; align-items: center; }
#mks-page-root .o-mnie-visual { position: relative; }
#mks-page-root .o-mnie-img-placeholder { width: 100%; aspect-ratio: 4/5; background: linear-gradient(135deg, var(--mks-bg-card) 0%, #201812 100%) !important; border: 1px solid var(--mks-border-glow); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 5.5rem; color: rgba(200,169,110,0.12); box-shadow: var(--mks-shadow); position: relative; }
#mks-page-root .o-mnie-quote-box { position: absolute; bottom: -2rem; right: -2rem; background: #0a0806 !important; border: 1px solid var(--mks-border-glow); padding: 1.75rem; border-radius: 6px; max-width: 280px; box-shadow: var(--mks-shadow); }
#mks-page-root .o-mnie-quote-text { font-family: var(--mks-font-headings) !important; font-style: italic; font-size: 0.95rem; color: var(--mks-text-primary) !important; line-height: 1.6; }
#mks-page-root .o-mnie-quote-author { font-size: 0.75rem; color: var(--mks-gold) !important; margin-top: 0.8rem; text-transform: uppercase; letter-spacing: 0.06em; }
#mks-page-root .o-mnie-eyebrow { color: var(--mks-gold) !important; font-size: 0.8rem; letter-spacing: 0.32em; text-transform: uppercase; margin-bottom: 1rem; font-weight: 600; }
#mks-page-root .o-mnie h2 { font-family: var(--mks-font-headings) !important; font-size: 2.6rem; margin-bottom: 2rem; line-height: 1.25; color: var(--mks-white) !important; background: transparent !important; }
#mks-page-root .o-mnie p { color: var(--mks-text-secondary) !important; line-height: 1.9; margin-bottom: 1.5rem; font-size: 1.02rem; }
#mks-page-root .o-mnie p strong { color: var(--mks-gold-light) !important; }
#mks-page-root .ai-manifesto-box { background: rgba(107,39,55,0.12) !important; border-left: 3px solid var(--mks-gold); padding: 1.75rem 2.2rem; border-radius: 0 6px 6px 0; margin: 2.8rem 0; border-top: 1px solid rgba(200,169,110,0.06); border-bottom: 1px solid rgba(200,169,110,0.06); }
#mks-page-root .ai-manifesto-box p { font-style: italic; color: var(--mks-text-primary) !important; margin: 0; font-size: 1.05rem; font-family: var(--mks-font-headings) !important; line-height: 1.8; }
#mks-page-root .credentials { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 3.5rem; border-top: 1px solid var(--mks-border-glow); padding-top: 2.5rem; }
#mks-page-root .credential { text-align: center; background: rgba(255,255,255,0.02) !important; padding: 1.2rem 0.8rem; border-radius: 4px; border: 1px solid rgba(255,255,255,0.03); }
#mks-page-root .credential-num { font-family: var(--mks-font-headings) !important; font-size: 1.9rem; color: var(--mks-gold-neon) !important; font-weight: 700; }
#mks-page-root .credential-label { font-size: 0.75rem; color: var(--mks-text-secondary) !important; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 0.3rem; }

/* Opinie */
#mks-page-root .opinie { background: var(--mks-bg-dark) !important; border-top: 1px solid var(--mks-border-glow); border-bottom: 1px solid var(--mks-border-glow); }
#mks-page-root .opinie-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2.5rem; }
#mks-page-root .opinia { background: var(--mks-bg-card) !important; border: 1px solid var(--mks-border-glow); padding: 3rem; border-radius: 8px; backdrop-filter: blur(10px); position: relative; }
#mks-page-root .opinia-stars { color: var(--mks-gold) !important; font-size: 1rem; margin-bottom: 1.5rem; letter-spacing: 3px; }
#mks-page-root .opinia-text { font-family: var(--mks-font-headings) !important; font-style: italic; color: var(--mks-text-primary) !important; font-size: 1.08rem; line-height: 1.8; margin-bottom: 2rem; }
#mks-page-root .opinia-author { font-size: 0.85rem; color: var(--mks-text-secondary) !important; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.2rem; }
#mks-page-root .opinia-author strong { color: var(--mks-gold) !important; display: block; font-size: 0.98rem; font-family: var(--mks-font-sans); font-weight: 600; margin-bottom: 0.2rem; }

/* Formularz */
#mks-page-root .formularz { background: var(--mks-bg-main) !important; }
#mks-page-root .form-container { max-width: 950px; margin: 0 auto; background: var(--mks-bg-card) !important; border: 1px solid var(--mks-border-glow); border-radius: 8px; padding: 4.5rem; box-shadow: var(--mks-shadow); backdrop-filter: blur(15px); }
#mks-page-root .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.8rem; }
#mks-page-root .form-group { margin-bottom: 0; }
#mks-page-root .form-group.full { grid-column: 1 / -1; }
#mks-page-root .form-section-title { font-family: var(--mks-font-headings) !important; font-size: 1.4rem; color: var(--mks-gold) !important; margin: 3rem 0 1.5rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--mks-border-glow); grid-column: 1 / -1; font-weight: 700; background: transparent !important; }
#mks-page-root .form-radio-group { display: flex; flex-wrap: wrap; gap: 0.8rem; grid-column: 1 / -1; }
#mks-page-root .form-radio-label { border: 1px solid var(--mks-border-glow); padding: 0.85rem 1.6rem; border-radius: 4px; font-size: 0.9rem; cursor: pointer; color: var(--mks-text-secondary) !important; transition: all 0.2s; background: rgba(0,0,0,0.4) !important; display: inline-block; }
#mks-page-root .form-radio-label:hover { border-color: var(--mks-gold) !important; color: var(--mks-text-primary) !important; }
#mks-page-root .form-radio-label input { display: none; }
#mks-page-root .form-radio-label.selected { background: linear-gradient(135deg, var(--mks-gold-dark) 0%, var(--mks-gold) 100%) !important; border-color: transparent !important; color: #000000 !important; font-weight: 700; box-shadow: 0 0 15px rgba(200,169,110,0.25); }
#mks-page-root .form-slider-wrapper { grid-column: 1 / -1; background: rgba(255,255,255,0.01) !important; padding: 2.5rem; border-radius: 6px; border: 1px solid rgba(255,255,255,0.04); }
#mks-page-root .form-slider { width: 100%; appearance: none; -webkit-appearance: none; height: 6px; background: rgba(255,255,255,0.12); border-radius: 3px; outline: none; margin: 1.8rem 0; }
#mks-page-root .form-slider::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 22px; height: 22px; background: var(--mks-gold); border-radius: 50%; cursor: pointer; box-shadow: 0 0 12px rgba(200,169,110,0.6); transition: transform 0.1s; }
#mks-page-root .form-slider::-webkit-slider-thumb:hover { transform: scale(1.25); }
#mks-page-root .slider-labels { display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--mks-text-secondary) !important; font-weight: 500; }
#mks-page-root .form-submit { grid-column: 1 / -1; text-align: center; margin-top: 2.5rem; }
#mks-page-root .btn-submit { background: linear-gradient(135deg, var(--mks-gold) 0%, var(--mks-gold-light) 100%) !important; color: #000000 !important; padding: 1.3rem 4rem; font-size: 1rem; font-weight: 700; letter-spacing: 0.12em; border: none !important; border-radius: 4px; cursor: pointer; transition: all 0.3s; box-shadow: 0 5px 28px rgba(200,169,110,0.35); width: 100%; text-transform: uppercase; font-family: var(--mks-font-sans); }
#mks-page-root .btn-submit:hover { background: var(--mks-white) !important; transform: translateY(-2px); box-shadow: 0 7px 35px rgba(200,169,110,0.55); color: #000 !important; }
#mks-page-root .form-note { font-size: 0.85rem; color: var(--mks-text-secondary) !important; margin-top: 1.2rem; }
#mks-page-root .form-gwarancja { display: flex; align-items: center; gap: 1.8rem; background: rgba(31,61,47,0.18) !important; border: 1px solid rgba(31,61,47,0.35); padding: 1.75rem 2.2rem; border-radius: 6px; margin-top: 3rem; grid-column: 1 / -1; }
#mks-page-root .gwarancja-icon { font-size: 2.8rem; filter: drop-shadow(0 0 12px rgba(31,61,47,0.5)); }
#mks-page-root .gwarancja-text { font-size: 0.98rem; color: var(--mks-text-primary) !important; line-height: 1.7; }
#mks-page-root .gwarancja-text strong { color: var(--mks-gold-light) !important; }
#mks-page-root label { display: block; font-size: 0.85rem; font-weight: 600; letter-spacing: 0.06em; color: var(--mks-gold-light) !important; margin-bottom: 0.6rem; text-transform: uppercase; }
#mks-page-root label .req { color: var(--mks-gold-neon) !important; }
#mks-page-root input[type="text"], #mks-page-root input[type="email"], #mks-page-root input[type="tel"], #mks-page-root input[type="date"], #mks-page-root select, #mks-page-root textarea { width: 100%; padding: 0.95rem 1.3rem; border: 1px solid var(--mks-border-glow) !important; border-radius: 4px; font-family: var(--mks-font-sans); font-size: 0.95rem; color: var(--mks-text-primary) !important; background: rgba(0,0,0,0.6) !important; transition: all 0.3s; outline: none; }
#mks-page-root input:focus, #mks-page-root select:focus, #mks-page-root textarea:focus { border-color: var(--mks-gold) !important; background: #080705 !important; box-shadow: 0 0 12px rgba(200,169,110,0.2); }
#mks-page-root textarea { resize: vertical; min-height: 140px; }

/* FAQ */
#mks-page-root .faq { background: var(--mks-bg-dark) !important; border-top: 1px solid var(--mks-border-glow); border-bottom: 1px solid var(--mks-border-glow); }
#mks-page-root .faq-list { max-width: 900px; margin: 0 auto; }
#mks-page-root .faq-list .faq-item { border: none !important; border-bottom: 1px solid var(--mks-border-glow) !important; padding: 0.6rem 0; margin-bottom: 0; border-radius: 0 !important; overflow: visible; background: transparent !important; backdrop-filter: none; }
#mks-page-root .faq-q { width: 100%; text-align: left; background: none !important; border: none !important; padding: 1.6rem 1.2rem; font-size: 1.1rem; font-weight: 600; cursor: pointer; color: var(--mks-text-primary) !important; display: flex; justify-content: space-between; align-items: center; transition: background 0.2s, color 0.2s; border-radius: 4px; }
#mks-page-root .faq-q:hover { background: rgba(255,255,255,0.02) !important; color: var(--mks-gold) !important; }
#mks-page-root .faq-q::after { content: '+'; color: var(--mks-gold) !important; font-size: 1.5rem; font-weight: 300; transition: transform 0.3s ease; }
#mks-page-root .faq-q.open { color: var(--mks-gold) !important; }
#mks-page-root .faq-q.open::after { content: '+'; transform: rotate(45deg); color: var(--mks-white) !important; }
#mks-page-root .faq-a { display: none; padding: 0.6rem 1.2rem 1.8rem; color: var(--mks-text-secondary) !important; font-size: 0.98rem; line-height: 1.8; animation: mksSlideDown 0.35s ease-out; }
#mks-page-root .faq-a.open { display: block; }
@keyframes mksSlideDown { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }

/* Sticky CTA */
#mks-page-root .sticky-cta { position: fixed; bottom: 0; left: 0; right: 0; z-index: 2000; background: rgba(0,0,0,0.95) !important; border-top: 1px solid var(--mks-border-glow); padding: 1.1rem 3.5rem; display: flex; align-items: center; justify-content: space-between; transform: translateY(100%); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); box-shadow: 0 -12px 35px rgba(0,0,0,0.6); }
#mks-page-root .sticky-cta.visible { transform: translateY(0); }
#mks-page-root .sticky-cta-text { color: var(--mks-text-primary) !important; font-size: 1.02rem; }
#mks-page-root .sticky-cta-text strong { color: var(--mks-gold-neon) !important; font-weight: 700; }

/* Responsive */
@media (max-width: 1200px) {
  #mks-page-root .pakiety-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 2rem; }
}
@media (max-width: 968px) {
  #mks-page-root .hero-badges { grid-template-columns: repeat(2, 1fr); gap: 1.2rem; }
  #mks-page-root .manifesto-grid { grid-template-columns: 1fr; }
  #mks-page-root .song-panel { grid-template-columns: 1fr !important; gap: 3rem; }
  #mks-page-root .o-mnie-grid { grid-template-columns: 1fr !important; gap: 4.5rem; }
  #mks-page-root .o-mnie-quote-box { position: static; margin: 2rem auto 0; max-width: 100%; }
  #mks-page-root .opinie-grid { grid-template-columns: 1fr; }
  #mks-page-root .form-container { padding: 3rem 1.8rem; }
  #mks-page-root .form-grid { grid-template-columns: 1fr; }
  #mks-page-root .credentials { grid-template-columns: repeat(2, 1fr); }
  #mks-page-root .sticky-cta { padding: 1.2rem; flex-direction: column; gap: 1.2rem; text-align: center; }
}
@media (max-width: 680px) {
  #mks-page-root .pakiety-grid { grid-template-columns: 1fr !important; gap: 2.5rem; }
}
@media (max-width: 600px) {
  #mks-page-root section { padding: 5rem 1.2rem; }
  #mks-page-root .hero-badges { grid-template-columns: 1fr; }
  #mks-page-root .credentials { grid-template-columns: repeat(2, 1fr); }
  #mks-page-root .form-grid { grid-template-columns: 1fr; }
  #mks-page-root .sticky-cta { padding: 1.2rem; flex-direction: column; gap: 1.2rem; text-align: center; }
  #mks-page-root .song-tabs { gap: 4px; }
  #mks-page-root .song-tab { padding: 8px 14px; font-size: 0.7rem; }
}
</style>

<div id="mks-page-root">

<div class="progress-container"><div class="progress-bar" id="mksProgressBar"></div></div>

<section class="mks-hero" id="hero">
  <div class="hero-ornament"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">♩ Muzyczna Kreacja Słów ♩</p>
    <h1>Twój <em>Muzyczny Azyl</em><br>stworzony dla Ciebie</h1>
    <p class="hero-sub">Spersonalizowane Utwory na Zamówienie — dla każdego, na każdą okazję i na każdą kieszeń</p>
    <p class="hero-desc">
      Tam, gdzie AI tworzy szablony, my projektujemy unikalne przeżycia. Każda piosenka rodzi się z Twojej historii,
      z emocji, których nie da się wpisać w formularz algorytmu. Muzyka z duszą — bo za każdą nutą stoi człowiek.
    </p>
    <div class="hero-actions">
      <a href="#formularz" class="btn-primary">Zamów swój utwór</a>
      <a href="#utwory" class="btn-secondary">Posłuchaj przykładów</a>
    </div>
    <div class="hero-badges">
      <div class="hero-badge"><div class="hero-badge-num">200+</div><div class="hero-badge-label">Zadowolonych klientów</div></div>
      <div class="hero-badge"><div class="hero-badge-num">15+</div><div class="hero-badge-label">Lat doświadczenia</div></div>
      <div class="hero-badge"><div class="hero-badge-num">48h</div><div class="hero-badge-label">Czas realizacji demo</div></div>
      <div class="hero-badge"><div class="hero-badge-num">100%</div><div class="hero-badge-label">Satysfakcji gwarantowanej</div></div>
    </div>
  </div>
</section>

<section class="manifesto" id="manifesto">
  <div class="container">
    <p class="section-eyebrow">Nasza Oś Działania</p>
    <h2 class="section-title">Tworzymy muzykę, która niesie trwałe znaczenie</h2>
    <hr class="divider">
    <p class="section-lead">
      Odrzucamy syntetyczny, plastikowy połysk masowych produkcji na rzecz autentycznych ludzkich doświadczeń.
      Każdy projekt traktujemy indywidualnie, dbając o czysty, mocny i szczery przekaz skupiony na Twojej historii.
    </p>
    <div class="manifesto-grid">
      <div class="manifesto-card">
        <div class="manifesto-icon">🎵</div>
        <h3>Dusza, której AI nie ma</h3>
        <p>Algorytm nie zna historii Twojego związku, nie pamięta zapachu pierwszego spotkania. My pytamy, słuchamy, rozumiemy — i dopiero wtedy piszemy.</p>
      </div>
      <div class="manifesto-card">
        <div class="manifesto-icon">✍️</div>
        <h3>Każde słowo ma znaczenie</h3>
        <p>Tekst tworzony ręcznie, z uwzględnieniem Twojego języka, Twoich wspomnień i Twojej wizji. Nie szablon — opowieść.</p>
      </div>
      <div class="manifesto-card">
        <div class="manifesto-icon">🎸</div>
        <h3>Muzyka dopasowana do emocji</h3>
        <p>Gatunek, tempo, instrumentacja — wszystko dobrane do nastroju, który chcesz przekazać. Od ballady po hip-hop, od akustyki po orkiestrę.</p>
      </div>
      <div class="manifesto-card">
        <div class="manifesto-icon">🤝</div>
        <h3>Partnerstwo, nie usługa</h3>
        <p>Pracujemy razem. Konsultacje, korekty, dialog — dopóki nie poczujesz, że ta piosenka jest naprawdę Twoja.</p>
      </div>
    </div>
  </div>
</section>

<section class="utwory" id="utwory">
  <div class="container">
    <p class="section-eyebrow">Portfolio Emocji</p>
    <h2 class="section-title">Posłuchaj historii zamienionych w dźwięk</h2>
    <hr class="divider">
    <div class="song-tabs">
      <button class="song-tab active" data-tab="wesele" onclick="switchSong('wesele', this)">💍 Wesele</button>
      <button class="song-tab" data-tab="urodziny" onclick="switchSong('urodziny', this)">🎂 Urodziny</button>
      <button class="song-tab" data-tab="narodziny" onclick="switchSong('narodziny', this)">👶 Narodziny</button>
      <button class="song-tab" data-tab="biznes" onclick="switchSong('biznes', this)">🏢 Firmowy</button>
      <button class="song-tab" data-tab="pozegnanie" onclick="switchSong('pozegnanie', this)">💫 Pożegnanie</button>
    </div>

    <div class="song-panel active" id="song-wesele">
      <div class="song-meta">
        <span class="song-occasion">Pierwszy Taniec</span>
        <h3 class="song-title-text">„Właśnie Ty”</h3>
        <p class="song-artist">dla Karoliny i Macieja — Kraków</p>
        <p class="song-desc">Małgorzata poprosiła nas o utwór, który opowie historię jej związku — od przypadkowego spotkania na Kazimierzu, przez trudne miesiące rozstania, aż po powrót i zaręczyny. Ballada pop z elementami jazzu. Realizacja: 6 dni, 2 korekty tekstu.</p>
        <div class="song-tags"><span class="song-tag">Ballada</span><span class="song-tag">Pop</span><span class="song-tag">Pierwszy taniec</span><span class="song-tag">Studio 3 dni</span></div>
        <div class="song-price-box"><div class="price">od 199 zł</div><div class="price-note">Pakiet Premium + dedykowane nagranie wokalne</div></div>
      </div>
      <div class="lyrics-card">
        <div class="lyrics-label">Fragment utworu</div>
        <div class="lyrics-text">Znaleźliśmy się przez przypadek na bruku między kawiarnią a snem,
Twój śmiech rozświetlił tamten zaułek i nie pamiętam już jak żyć bez Ciebie.

Właśnie Ty — przez wszystkie burze,
Właśnie Ty — gdy świat się burzy,
W Twoich dłoniach mam swój azyl,
Właśnie Ty. Zawsze Ty.

Przez noce, gdy milczenie krzyczało, przez mosty, które sami spaliliśmy — wróciłeś i nagle wszystko miało sens, bo bez Ciebie byłam tylko połową siebie.</div>
      </div>
    </div>

    <div class="song-panel" id="song-urodziny">
      <div class="song-meta">
        <span class="song-occasion">50. Urodziny</span>
        <h3 class="song-title-text">„Pół wieku piękna”</h3>
        <p class="song-artist">dla Bożeny — od rodziny, Warszawa</p>
        <p class="song-desc">Dzieci poprosiły o żartobliwy, ciepły utwór dla mamy świętującej 50. urodziny. Chciały, by nawiązywał do jej legendarnego ciasta drożdżowego, miłości do Mazur i wiecznego gubienia okularów. Dynamiczny folk-akustyczny kawałek idealny do tańca.</p>
        <div class="song-tags"><span class="song-tag">Folk Akustyczny</span><span class="song-tag">Żartobliwy</span><span class="song-tag">Prezent</span><span class="song-tag">Gitara + skrzypce</span></div>
        <div class="song-price-box"><div class="price">od 99 zł</div><div class="price-note">Pakiet Standard + aranżacja instrumentów</div></div>
      </div>
      <div class="lyrics-card">
        <div class="lyrics-label">Fragment utworu</div>
        <div class="lyrics-text">Gdzie są okulary? — pyta od rana,
choć na czubku głowy nosi je sama.
W kuchni zapach drożdży, w sercu ciągle maj,
Bożenka na Mazurach ma swój własny raj.

Pięćdziesiąt lat minęło jak jeden krótki dzień,
lecz Ty w ogóle nie rzucasz na nas cienia,
pół wieku piękna, uśmiechu i sił,
chcemy, byś zawsze była tym, kim jesteś dziś!</div>
      </div>
    </div>

    <div class="song-panel" id="song-narodziny">
      <div class="song-meta">
        <span class="song-occasion">Narodziny Córki</span>
        <h3 class="song-title-text">„Małe niebo”</h3>
        <p class="song-artist">dla małej Zosi — od taty, Gdańsk</p>
        <p class="song-desc">Wzruszający list młodego ojca, napisany jeszcze w szpitalu na powitanie córki. Nie znał imienia — wiedział tylko, że będzie dziewczynka. Utwór nagraliśmy w 4 dni, finalna wersja z imionami — 24h po porodzie.</p>
        <div class="song-tags"><span class="song-tag">Kołysanka</span><span class="song-tag">Akustyczna</span><span class="song-tag">Gitara + wokal</span><span class="song-tag">Ekspres 24h</span></div>
        <div class="song-price-box"><div class="price">od 49 zł</div><div class="price-note">Pakiet Mini + ekspresowa wersja</div></div>
      </div>
      <div class="lyrics-card">
        <div class="lyrics-label">Fragment kołysanki</div>
        <div class="lyrics-text">Zosiu mała, witaj tu, czekaliśmy na Ciebie tak długo,
świat jest wielki, pełen barw, a my — Twoi — przy Tobie zawsze.

Śpij, Zosiu, śpij, gwiazdki piszą Twoje imię,
tato trzyma Twoją dłoń — jesteś cała i piękna, i prawdziwa.

Nauczymy Cię wszystkiego, jak się wstaje gdy pada deszcz, jak się śmieje przez łzy szczęścia — dziś jesteś naszym nowym niebem.</div>
      </div>
    </div>

    <div class="song-panel" id="song-biznes">
      <div class="song-meta">
        <span class="song-occasion">Jubileusz Firmy</span>
        <h3 class="song-title-text">„20 lat razem”</h3>
        <p class="song-artist">dla TechStar Sp. z o.o. — Wrocław</p>
        <p class="song-desc">Firma obchodziła 20-lecie i chciała czegoś więcej niż przemówienia. Utwór zaśpiewany przez pracowników na gali — tekst nawiązywał do historii firmy, jej wartości i konkretnych sukcesów. Teraz jest hymnem firmy.</p>
        <div class="song-tags"><span class="song-tag">Rock-pop</span><span class="song-tag">Motywacyjny</span><span class="song-tag">Chóralny</span><span class="song-tag">Produkcja premium</span></div>
        <div class="song-price-box"><div class="price">od 399 zł</div><div class="price-note">Pakiet Firmowy — pełna produkcja na miarę</div></div>
      </div>
      <div class="lyrics-card">
        <div class="lyrics-label">Fragment hymnu firmowego</div>
        <div class="lyrics-text">Dwadzieścia lat temu — biuro, komputer i sen,
dziś nasza marka brzmi w całej Europie,
przeszliśmy przez kryzysy, zmiany i czas,
bo razem jesteśmy silniejsi niż każdy z nas.

Raz, dwa, dwadzieścia lat!
To nie jest liczba — to nasz wspólny dorobek,
TechStar to ludzie, to pasja i plan,
idziemy po więcej, otwieramy drzwi bram!</div>
      </div>
    </div>

    <div class="song-panel" id="song-pozegnanie">
      <div class="song-meta">
        <span class="song-occasion">Pamiątka / Pożegnanie</span>
        <h3 class="song-title-text">„Ślady na wodzie”</h3>
        <p class="song-artist">Piosenka pożegnalna dla taty — Poznań</p>
        <p class="song-desc">Najtrudniejsze, ale i najpiękniejsze zamówienie. Rodzina chciała pożegnać zmarłego tatę utworem pełnym ciepła i osobistych wspomnień, unikając patosu i nadmiernej rozpaczy. Ciepła ballada gitarowa.</p>
        <div class="song-tags"><span class="song-tag">Nostalgiczna</span><span class="song-tag">Gitara klasyczna</span><span class="song-tag">Osobista pamiątka</span></div>
        <div class="song-price-box"><div class="price">od 99 zł</div><div class="price-note">Pakiet Standard — dedykowany aranż akustyczny</div></div>
      </div>
      <div class="lyrics-card">
        <div class="lyrics-label">Fragment piosenki</div>
        <div class="lyrics-text">Tato, wiemy, że siedzisz gdzieś wyżej,
pilnujesz, żebyśmy nie przeklinali przy mamie,
sprawdzasz czy ogród podlany, czy koło naprawione —
zawsze będziesz z nami, choć inaczej niż dawniej.

Jesteś dalej tu — w zapachu kawy rano,
w starym krześle, gdzie siadałeś co niedzielę,
w tym śmiechu, który wszyscy po Tobie mamy —
jesteś dalej tu. I zawsze tak zostanie.</div>
      </div>
    </div>
  </div>
</section>

<section class="cennik" id="cennik">
  <div class="container">
    <p class="section-eyebrow">Przejrzyste Warunki</p>
    <h2 class="section-title">Muzyka rzemieślnicza dopasowana do potrzeb</h2>
    <hr class="divider">
    <p class="section-lead">Przejrzyste stawki oparte na realnym nakładzie pracy twórczej. Bez niespodzianek.</p>
    <div class="pakiety-grid">
      <div class="pakiet">
        <p class="pakiet-name">Pakiet</p>
        <h3 class="pakiet-title">Mini ✦</h3>
        <div class="pakiet-price-inline">49 zł <span>/ utwór</span></div>
        <p class="pakiet-desc">Krótki, słodki utwór idealny na szybki prezent lub małe co nieco.</p>
        <ul class="pakiet-features">
          <li>Krótki tekst (1 zwrotka + refren)</li>
          <li>Melodia + podkład generowany</li>
          <li>1 konsultacja online 15 min</li>
          <li>1 korekta tekstu</li>
          <li>Plik MP3</li>
          <li>Realizacja: 2–3 dni robocze</li>
        </ul>
        <a href="#formularz" class="btn-pakiet">Wybieram Mini</a>
      </div>
      <div class="pakiet">
        <p class="pakiet-name">Pakiet</p>
        <h3 class="pakiet-title">Standard ✦✦</h3>
        <div class="pakiet-price-inline">99 zł <span>/ utwór</span></div>
        <p class="pakiet-desc">Pełniejszy utwór z dłuższym tekstem i lepszym nagraniem.</p>
        <ul class="pakiet-features">
          <li>Pełny tekst (2 zwrotki + refren)</li>
          <li>Nagranie z akompaniamentem</li>
          <li>Konsultacja 30 min + brief</li>
          <li>2 korekty tekstu</li>
          <li>Plik MP3 + notatka</li>
          <li>Realizacja: 3–5 dni roboczych</li>
        </ul>
        <a href="#formularz" class="btn-pakiet">Wybieram Standard</a>
      </div>
      <div class="pakiet featured">
        <span class="pakiet-badge">POLECANY WPIS</span>
        <p class="pakiet-name">Pakiet</p>
        <h3 class="pakiet-title">Premium ✦✦✦</h3>
        <div class="pakiet-price-inline">199 zł <span>/ utwór</span></div>
        <p class="pakiet-desc">Pełna produkcja z wokalem. Dla tych, którzy chcą prawdziwego utworu.</p>
        <ul class="pakiet-features">
          <li>Rozbudowany tekst (3 zwrotki + refren + bridge)</li>
          <li>Nagranie wokalne (profesjonalny wokal)</li>
          <li>Konsultacja 60 min + pogłębiony wywiad</li>
          <li>Nieograniczone korekty</li>
          <li>Plik WAV + MP3 w wysokiej jakości</li>
          <li>Realizacja: 5–7 dni roboczych</li>
        </ul>
        <a href="#formularz" class="btn-pakiet">Wybieram Premium</a>
      </div>
      <div class="pakiet">
        <p class="pakiet-name">Pakiet</p>
        <h3 class="pakiet-title">Firmowy ✦✦✦✦</h3>
        <div class="pakiet-price-inline">od 399 zł</div>
        <p class="pakiet-desc">Dla firm i zespołów — hymn, piosenka integracyjna lub branding audio.</p>
        <ul class="pakiet-features">
          <li>Pełna produkcja na miarę</li>
          <li>Opcja z żywymi instrumentami</li>
          <li>Dedykowany opiekun projektu</li>
          <li>Prawa autorskie w cenie</li>
          <li>Hymn firmowy / event branding</li>
          <li>Realizacja: ustalana indywidualnie</li>
        </ul>
        <a href="#formularz" class="btn-pakiet">Wycena indywidualna</a>
      </div>
    </div>
  </div>
</section>

<section class="o-mnie" id="o-mnie">
  <div class="container">
    <div class="o-mnie-grid">
      <div class="o-mnie-visual">
        <div class="o-mnie-img-placeholder">🎵</div>
        <div class="o-mnie-quote-box">
          <div class="o-mnie-quote-text">„Muzyka to nie algorytmiczna kombinacja zer i jedynek. To transfer doświadczeń człowieka do drugiego człowieka.”</div>
          <div class="o-mnie-quote-author">— Muzyczna Kreacja Słów</div>
        </div>
      </div>
      <div>
        <p class="o-mnie-eyebrow">Kim Jesteśmy</p>
        <h2>Muzyczna Kreacja Słów —<br>Żywa Kreacja kontra Szablony AI</h2>
        <p>Jesteśmy niezależnymi twórcami, którzy przez lata obserwowali ewolucję rynku muzycznego. Kiedy technologia zaczęła zalewać świat masową produkcją, podjęliśmy świadomą decyzję, by pójść pod prąd głównego nurtu.</p>
        <p>Muzyczna Kreacja Słów powstała w celu obrony pełnej autentyczności. Nie zgadzamy się na bezduszne, plastikowe generowanie wspomnień, które brzmią identycznie u każdego klienta.</p>
        <div class="ai-manifesto-box">
          <p>„Sztuczna inteligencja z łatwością zrymuje losowe wyrazy. Nigdy jednak nie zapyta, dlaczego akurat to konkretne wspomnienie wywołuje u Ciebie łzy, ani nie ukryje między wierszami żartu, który zrozumie tylko Wasza dwójka. My to robimy.”</p>
        </div>
        <p>Stojąc za sterami z ponad <strong>15-letnim doświadczeniem studyjnym</strong>, dbamy o to, by każda nuta nosiła ludzki ślad. Wykorzystujemy zaawansowane zaplecze sprzętowe jako narzędzie pracy, lecz sercem każdej kompozycji zawsze pozostaje intuicja żywego kompozytora.</p>
        <div class="credentials">
          <div class="credential"><div class="credential-num">15+</div><div class="credential-label">lat w muzyce</div></div>
          <div class="credential"><div class="credential-num">200+</div><div class="credential-label">kompozycji</div></div>
          <div class="credential"><div class="credential-num">98%</div><div class="credential-label">zadowolonych serc</div></div>
          <div class="credential"><div class="credential-num">8</div><div class="credential-label">gatunków głównych</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="opinie" id="opinie">
  <div class="container">
    <p class="section-eyebrow">Prawdziwe Reakcje</p>
    <h2 class="section-title">Każda piosenka — osobna, prawdziwa historia</h2>
    <hr class="divider">
    <div class="opinie-grid">
      <div class="opinia"><div class="opinia-stars">★★★★★</div><p class="opinia-text">„Jako firma obchodzimy jubileusz. Chciałem czegoś więcej niż tort i przemówienia. Ten hymn śpiewaliśmy razem na gali — emocje były niesamowite. Inwestycja, która skończyła się potężnym zaangażowaniem zespołu.”</p><p class="opinia-author"><strong>Marek T.</strong>Wrocław · Pakiet Firmowy · Jubileusz firmy</p></div>
      <div class="opinia"><div class="opinia-stars">★★★★★</div><p class="opinia-text">„Piosenka pożegnalna dla taty. Najtrudniejsze zamówienie w moim życiu. Oni to zrozumieli — rozmawiali ze mną, pytali o konkretne małe chwile. Utwór wyszedł pełen miłości, bez zbędnej patetyczności.”</p><p class="opinia-author"><strong>Piotr K.</strong>Poznań · Pakiet Standard · Pożegnanie</p></div>
      <div class="opinia"><div class="opinia-stars">★★★★★</div><p class="opinia-text">„Kołysanka dla córeczki była gotowa ekspresowo. Słysząc jej imię wplecione w tak czuły sposób, po prostu się popłakałem. Najwspanialsza pamiątka rodzinna, jaką można sobie wyobrazić.”</p><p class="opinia-author"><strong>Tomasz N.</strong>Gdańsk · Pakiet Mini · Narodziny</p></div>
      <div class="opinia"><div class="opinia-stars">★★★★★</div><p class="opinia-text">„Próbowałam wcześniej darmowych generatorów i dostałam bezduszny plastik. Tutaj poczułam, że ktoś naprawdę przeczytał mój opis i włożył serce w instrumenty. Warte każdej złotówki.”</p><p class="opinia-author"><strong>Marta S.</strong>Łódź · Pakiet Premium · Rocznica związku</p></div>
    </div>
  </div>
</section>

<section class="formularz" id="formularz">
  <div class="container">
    <p class="section-eyebrow">Konfigurator Zamówienia</p>
    <h2 class="section-title">Opowiedz nam swoją historię</h2>
    <hr class="divider">
    <p class="section-lead">Im więcej szczegółów nam powiesz, tym precyzyjniej uderzymy w odpowiednie tony emocjonalne.</p>
    <div class="form-container">
      <form id="zamowienie-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="event.preventDefault(); alert('Formularz wysłany pomyślnie! Skontaktujemy się w ciągu 24 godzin.');">
        <input type="hidden" name="action" value="hrl_mks_order">
        <?php wp_nonce_field( 'hrl_mks_order_action', 'hrl_mks_nonce' ); ?>
        <div class="form-grid">
          <div class="form-section-title">📋 Krok 1: Dane podstawowe i kontaktowe</div>
          <div class="form-group"><label for="client-name">Imię i nazwisko / Nazwa firmy <span class="req">*</span></label><input type="text" id="client-name" name="mks_name" placeholder="Jan Kowalski" required></div>
          <div class="form-email form-group"><label for="client-email">Adres e-mail <span class="req">*</span></label><input type="email" id="client-email" name="mks_email" placeholder="jan@przyklad.pl" required></div>
          <div class="form-group"><label for="client-phone">Numer telefonu kontaktowego</label><input type="tel" id="client-phone" name="mks_phone" placeholder="+48 500 000 000"></div>
          <div class="form-group"><label for="deadline-date">Ostateczny termin odbioru utworu</label><input type="date" id="deadline-date" name="mks_deadline"></div>
          <div class="form-section-title">🎉 Krok 2: Charakterystyka i intencja utworu</div>
          <div class="form-group full">
            <label>Okazja główna <span class="req">*</span></label>
            <div class="form-radio-group" id="okazjaGroup">
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="okazja" value="wesele" required> 💍 Wesele / Rocznica</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="okazja" value="urodziny"> 🎂 Urodziny / Imieniny</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="okazja" value="narodziny"> 👶 Narodziny / Chrzciny</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="okazja" value="pozegnanie"> 💫 Pożegnanie / Pamiątka</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="okazja" value="firmowy"> 🏢 Event Firmowy / Hymn</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="okazja" value="inne"> ✨ Inna wyjątkowa okazja</div>
            </div>
          </div>
          <div class="form-group full"><label for="target-person">Dla kogo dedykowany jest utwór? Opisz tę osobę lub grupę <span class="req">*</span></label><textarea id="target-person" name="mks_target" rows="4" placeholder="Opisz obdarowywaną osobę. Czym się zajmuje, jaka jest, co dla Ciebie znaczy? Np. Utwór dla żony Anny na 10. rocznicę ślubu..." required></textarea></div>
          <div class="form-group full"><label for="emotional-intent">Jakie kluczowe emocje ma wywołać ten utwór?</label><textarea id="emotional-intent" name="mks_emotions" rows="3" placeholder="Głębokie wzruszenie, radość do tańca, nostalgia, motywacja, a może subtelny żart i dystans?"></textarea></div>
          <div class="form-group full"><label for="specific-memories">Wklej unikalne wspomnienia, wewnętrzne żarty, imiona, daty lub miejsca</label><textarea id="specific-memories" name="mks_memories" rows="4" placeholder="To tutaj dzieje się magia personalizacji. Wpisz hasła, miejsca spotkań, ulubione powiedzonka, wspólne podróże..."></textarea></div>
          <div class="form-section-title">🎸 Krok 3: Preferencje muzyczne i techniczne</div>
          <div class="form-group full">
            <label>Preferowany styl / gatunek muzyczny</label>
            <div class="form-radio-group" id="gatunekGroup">
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="gatunek" value="pop"> Klasyczny Pop</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="gatunek" value="ballada"> Kameralna Ballada</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="gatunek" value="folk"> Folk / Brzmienie Akustyczne</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="gatunek" value="rock"> Rockowy Anthem</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="gatunek" value="hiphop"> Świadomy Hip-Hop</div>
              <div class="form-radio-label" onclick="selectRadio(this)"><input type="radio" name="gatunek" value="inne"> Inny / Własna wizja</div>
            </div>
          </div>
          <div class="form-group"><label for="tempo-select">Dynamika utworu (Tempo)</label><select id="tempo-select" name="mks_tempo"><option value="wolne">Wolne i nastrojowe (Kołysanki, Ballady)</option><option value="umiarkowane" selected>Umiarkowane (Klasyczny pop, tło narracyjne)</option><option value="szybkie">Dynamiczne i energiczne (Do tańca, motywacja)</option></select></div>
          <div class="form-group"><label for="express-select">Tryb realizacji zamówienia</label><select id="express-select" name="mks_express"><option value="standard">Tryb standardowy (Zgodnie z pakietem)</option><option value="express48">Ekspres 48 godzin (Dopłata do priorytetu w studio)</option><option value="express24">Ekspres 24 godziny (Najwyższy priorytet realizacyjny)</option></select></div>
          <div class="form-slider-wrapper">
            <label>Przewidywany budżet inwestycji: <span id="budzet-val" style="color: var(--mks-gold-neon); font-weight: 700;">99 zł</span></label>
            <input type="range" class="form-slider" id="budzet" name="mks_budget" min="49" max="1500" step="10" value="99" oninput="document.getElementById('budzet-val').textContent = this.value + ' zł'">
            <div class="slider-labels"><span>49 zł (Mini)</span><span>99 zł (Standard)</span><span>199 zł (Premium)</span><span>399 zł+ (Firmowy)</span></div>
          </div>
          <div class="form-group full"><label for="inspiration-links">Linki do utworów inspiracyjnych (YouTube, Spotify)</label><textarea id="inspiration-links" name="mks_inspiration" rows="2" placeholder="Wklej linki do piosenek, których klimat lub struktura szczególnie Ci się podobają..."></textarea></div>
          <div class="form-gwarancja"><div class="gwarancja-icon">🛡️</div><div class="gwarancja-text"><strong>Gwarancja rzetelności wykonania:</strong> Dbamy o każdy szczegół. Jeśli po przesłaniu wstępnego demo tekstu uznasz, że nie odzwierciedla ono Twojej intencji, bez problemu wprowadzamy korekty.</div></div>
          <div class="form-submit"><button type="submit" class="btn-submit">Prześlij konfigurację do bezpłatnej wyceny →</button><p class="form-note">Wysłanie formularza nie zobowiązuje do natychmiastowej płatności. Skontaktujemy się z Tobą w celu autoryzacji specyfikacji muzycznej.</p></div>
        </div>
      </form>
      <?php if ( isset( $_GET['mks'] ) && 'success' === $_GET['mks'] ) : ?><p style="color:#10b981;font-size:0.95rem;margin-top:20px;font-weight:600;text-align:center;">✓ Zapytanie wysłane! Skontaktujemy się w ciągu 24h.</p><?php elseif ( isset( $_GET['mks'] ) && 'error' === $_GET['mks'] ) : ?><p style="color:#ef4444;font-size:0.95rem;margin-top:20px;font-weight:600;text-align:center;">✕ Wystąpił błąd. Spróbuj ponownie.</p><?php endif; ?>
    </div>
  </div>
</section>

<section class="faq" id="faq">
  <div class="container">
    <p class="section-eyebrow">Centrum Wiedzy</p>
    <h2 class="section-title">Masz pytania? Mamy odpowiedzi</h2>
    <hr class="divider">
    <div class="faq-list">
      <div class="faq-item"><button class="faq-q" onclick="toggleFaq(this)">Ile czasu trwa realizacja?</button><div class="faq-a">Zależy od wybranego pakietu: dla wersji Mini to zaledwie 2-3 dni robocze, dla Standard 3-5 dni, a przy pełnej produkcji Premium 5-7 dni roboczych. Realizacja ekspresowa jest możliwa na życzenie.</div></div>
      <div class="faq-item"><button class="faq-q" onclick="toggleFaq(this)">Czym różni się Wasze podejście od generatorów AI?</button><div class="faq-a">Generatory AI bazują na losowości i powtarzalnych wzorcach. My opieramy pracę na wnikliwym wywiadzie. Utwory od nas zawierają Twoje unikalne wspomnienia, wewnętrzne żarty, konkretne chwile czy sytuacje rodzinne, o których algorytm nie ma pojęcia.</div></div>
      <div class="faq-item"><button class="faq-q" onclick="toggleFaq(this)">Czy mogę dokonać poprawek do utworu?</button><div class="faq-a">Tak, w zależności od wybranego poziomu cennika oferujemy od 1 do 2 tur korekt tekstu, a w wariancie Premium korekty są całkowicie nieograniczone, aż do pełnej satysfakcji.</div></div>
      <div class="faq-item"><button class="faq-q" onclick="toggleFaq(this)">Jakie gatunki muzyczne robicie?</button><div class="faq-a">Pop, ballada, folk, rock, jazz, bossa nova, hip-hop, muzyka klasyczna. Jeśli masz w głowie konkretną muzykę — podaj nam przykład (link do YouTube lub Spotify) i dostosujemy klimat brzmienia.</div></div>
    </div>
  </div>
</section>

<div class="sticky-cta" id="sticky-cta">
  <p class="sticky-cta-text">Gotowy na <strong>swoją wyjątkową piosenkę?</strong> Bezpłatna konsultacja w ciągu 24h.</p>
  <a href="#formularz" class="btn-primary" style="white-space:nowrap; margin-left:1rem;">Zaprojektuj utwór</a>
</div>

</div><!-- end #mks-page-root -->

<script>
(function() {
  var root = document.getElementById('mks-page-root');
  if (!root) return;

  // Progress bar + sticky CTA
  window.addEventListener('scroll', function() {
    var winScroll = document.documentElement.scrollTop || document.body.scrollTop;
    var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    var scrolled = (winScroll / height) * 100;
    var bar = root.querySelector('#mksProgressBar');
    if (bar) bar.style.width = scrolled + '%';

    var sticky = root.querySelector('#sticky-cta');
    if (sticky) {
      if (window.scrollY > 450) {
        sticky.classList.add('visible');
      } else {
        sticky.classList.remove('visible');
      }
    }
  });
</script>

<?php get_footer(); ?>