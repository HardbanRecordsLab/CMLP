<?php
/**
 * Template Name: Muzyczna Kreacja Słów
 * Description: Pełna strona docelowa usługi personalizowanych utworów muzycznych.
 * Pricing: Mini 49 zł | Standard 99 zł | Premium 199 zł | Firmowy od 399 zł
 * Version: 4.0.0 — Uses new design system, reduced inline styles
 */
get_header();
?>
<style>
/* ═══════════════════════════════════════════════════════════
   MKS ENCAPSULATED STYLES — Isolated from theme cascade
   Uses design tokens from 00-design-tokens.css
   ═══════════════════════════════════════════════════════════ */
#mks-page-root {
  --mks-bg-main: var(--bg-primary);
  --mks-bg-card: var(--bg-card);
  --mks-border-glow: var(--border-default);
  --mks-gold: var(--accent-gold);
  --mks-gold-light: var(--accent-gold-light);
  --mks-gold-dark: var(--accent-gold-dark);
  --mks-gold-neon: var(--accent-gold-neon);
  --mks-text-primary: var(--text-primary);
  --mks-text-secondary: var(--text-secondary);
  --mks-white: #ffffff;
  --mks-bg-dark: var(--bg-secondary);
  --mks-wine: #6B2737;
  --mks-forest: #1F3D2F;
  --mks-shadow: var(--shadow-xl);
  --mks-font-headings: var(--font-serif);
  --mks-font-sans: var(--font-sans);

  all: unset;
  display: block;
  background: var(--bg-primary) !important;
  color: var(--text-primary);
  font-family: var(--font-sans);
  font-size: var(--text-base);
  line-height: var(--leading-relaxed);
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
#mks-page-root a { color: var(--accent-gold); text-decoration: none; transition: all var(--transition-base); }
#mks-page-root a:hover { color: var(--accent-gold-light); }
#mks-page-root a:focus-visible { outline: 2px solid var(--accent-gold); outline-offset: 2px; border-radius: var(--radius-sm); }
#mks-page-root img { max-width: 100%; height: auto; }
#mks-page-root h1,#mks-page-root h2,#mks-page-root h3,#mks-page-root h4,#mks-page-root h5,#mks-page-root h6 { font-family: var(--font-serif); line-height: var(--leading-tight); }
#mks-page-root section { padding: var(--space-20) var(--space-6); position: relative; display: block; background: transparent; }
#mks-page-root .container { max-width: var(--container-xl); margin: 0 auto; padding-left: var(--space-6); padding-right: var(--space-6); }

/* Progress Bar */
#mks-page-root .progress-container { position: fixed; top: 0; left: 0; width: 100%; height: 3px; z-index: var(--z-max); background: transparent; }
#mks-page-root .progress-bar { height: 100%; background: var(--gradient-gold); width: 0%; }

/* Hero */
#mks-page-root .mks-hero { min-height: 100vh; background: radial-gradient(circle at 50% 35%, rgba(35,28,20,0.7) 0%, var(--bg-primary) 75%) !important; display: flex; align-items: center; justify-content: center; text-align: center; padding: var(--space-24) var(--space-6) var(--space-16); position: relative; overflow: hidden; }
#mks-page-root .mks-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 15% 50%, rgba(107,39,55,0.18) 0%, transparent 55%), radial-gradient(ellipse at 85% 50%, rgba(31,61,47,0.18) 0%, transparent 55%); z-index: 0; }
#mks-page-root .hero-ornament { position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 1px; height: 120px; background: linear-gradient(to bottom, transparent, var(--accent-gold)); opacity: 0.6; z-index: 1; }
#mks-page-root .hero-content { position: relative; max-width: 900px; z-index: 2; background: transparent !important; }
#mks-page-root .hero-eyebrow { color: var(--accent-gold) !important; font-size: var(--text-xs); letter-spacing: var(--tracking-widest); text-transform: uppercase; margin-bottom: var(--space-6); font-weight: var(--font-semibold); font-family: var(--font-sans); }
#mks-page-root .hero-content h1 { font-family: var(--font-serif) !important; font-size: clamp(2.8rem, 6.8vw, 5rem) !important; color: var(--text-primary) !important; line-height: var(--leading-tight) !important; margin-bottom: var(--space-5) !important; font-weight: var(--font-bold) !important; background: transparent !important; }
#mks-page-root .hero-content h1 em { color: var(--accent-gold) !important; font-style: italic; text-shadow: 0 0 20px rgba(200,169,110,0.25); }
#mks-page-root .hero-sub { font-family: var(--font-serif) !important; font-size: clamp(1.15rem, 2.8vw, 1.5rem) !important; color: var(--accent-gold-light) !important; font-style: italic; margin-bottom: var(--space-8); }
#mks-page-root .hero-desc { color: var(--text-secondary) !important; font-size: var(--text-lg); max-width: 720px; margin: 0 auto var(--space-10); line-height: var(--leading-relaxed); }
#mks-page-root .hero-actions { display: flex; gap: var(--space-6); justify-content: center; flex-wrap: wrap; }

#mks-page-root .btn-primary { background: var(--gradient-gold) !important; color: var(--bg-primary) !important; padding: var(--space-4) var(--space-8); font-weight: var(--font-bold); font-size: var(--text-sm); letter-spacing: var(--tracking-wide); text-decoration: none; border-radius: var(--radius-md); transition: all var(--transition-base); display: inline-block; box-shadow: var(--shadow-lg); border: none !important; }
#mks-page-root .btn-primary:hover { background: var(--text-primary) !important; color: var(--bg-primary) !important; transform: translateY(-2px); box-shadow: var(--shadow-xl); }
#mks-page-root .btn-secondary { border: 1px solid var(--border-default) !important; color: var(--accent-gold) !important; padding: var(--space-4) var(--space-8); font-size: var(--text-sm); letter-spacing: var(--tracking-wide); text-decoration: none; border-radius: var(--radius-md); transition: all var(--transition-base); display: inline-block; background: rgba(200,169,110,0.03) !important; }
#mks-page-root .btn-secondary:hover { border-color: var(--accent-gold) !important; background: rgba(200,169,110,0.09) !important; transform: translateY(-2px); color: var(--accent-gold) !important; }

/* Hero Badges */
#mks-page-root .hero-badges { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-6); margin-top: var(--space-16); border-top: 1px solid var(--border-default); padding-top: var(--space-10); }
#mks-page-root .hero-badge { background: var(--bg-card); border: 1px solid var(--border-default); padding: var(--space-6) var(--space-4); border-radius: var(--radius-lg); backdrop-filter: blur(var(--blur-md)); text-align: center; }
#mks-page-root .hero-badge-num { font-family: var(--font-serif) !important; font-size: var(--text-3xl); color: var(--accent-gold) !important; font-weight: var(--font-bold); margin-bottom: var(--space-1); }
#mks-page-root .hero-badge-label { font-size: var(--text-xs); color: var(--text-secondary) !important; letter-spacing: var(--tracking-wide); text-transform: uppercase; }

/* Section shared */
#mks-page-root .section-eyebrow { text-align: center; color: var(--accent-gold) !important; font-size: var(--text-xs); letter-spacing: var(--tracking-widest); text-transform: uppercase; margin-bottom: var(--space-3); font-weight: var(--font-semibold); }
#mks-page-root .section-title { font-family: var(--font-serif) !important; font-size: clamp(2.2rem, 4.5vw, 3.2rem) !important; text-align: center; margin-bottom: var(--space-5); line-height: var(--leading-tight); color: var(--text-primary) !important; background: transparent !important; }
#mks-page-root .section-lead { text-align: center; color: var(--text-secondary) !important; max-width: 750px; margin: 0 auto var(--space-12); font-size: var(--text-lg); line-height: var(--leading-relaxed); }
#mks-page-root .divider { width: 80px; height: 2px; background: linear-gradient(90deg, transparent, var(--accent-gold), transparent); margin: var(--space-8) auto var(--space-10); border: none; display: block; }

/* Manifesto */
#mks-page-root .manifesto { background: var(--bg-secondary) !important; border-top: 1px solid var(--border-default); border-bottom: 1px solid var(--border-default); }
#mks-page-root .manifesto-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-8); margin-top: var(--space-10); }
#mks-page-root .manifesto-card { background: var(--bg-card); border: 1px solid var(--border-default); padding: var(--space-10) var(--space-8); border-radius: var(--radius-lg); backdrop-filter: blur(var(--blur-md)); transition: all var(--transition-base); }
#mks-page-root .manifesto-card:hover { border-color: var(--accent-gold); transform: translateY(-4px); box-shadow: var(--shadow-xl); background: rgba(28,22,17,0.55); }
#mks-page-root .manifesto-icon { font-size: var(--text-3xl); margin-bottom: var(--space-6); display: inline-block; filter: drop-shadow(0 0 12px rgba(200,169,110,0.35)); }
#mks-page-root .manifesto-card h3 { font-family: var(--font-serif) !important; color: var(--accent-gold) !important; font-size: var(--text-xl); margin-bottom: var(--space-5); background: transparent !important; }
#mks-page-root .manifesto-card p { color: var(--text-secondary) !important; font-size: var(--text-base); line-height: var(--leading-relaxed); }

/* Song Tabs */
#mks-page-root .utwory { background: var(--bg-primary) !important; }
#mks-page-root .song-tabs { display: flex; gap: var(--space-2); justify-content: center; border-bottom: 1px solid var(--border-default); margin-bottom: var(--space-12); padding-bottom: var(--space-2); overflow-x: auto; }
#mks-page-root .song-tab { padding: var(--space-4) var(--space-6); font-size: var(--text-sm); letter-spacing: var(--tracking-wide); font-family: var(--font-sans); font-weight: var(--font-medium); border: none !important; background: none !important; cursor: pointer; color: var(--text-secondary) !important; border-bottom: 3px solid transparent !important; transition: all var(--transition-base); white-space: nowrap; border-radius: 0 !important; }
#mks-page-root .song-tab:hover { color: var(--accent-gold) !important; }
#mks-page-root .song-tab.active { color: var(--accent-gold) !important; border-bottom-color: var(--accent-gold) !important; text-shadow: 0 0 12px rgba(200,169,110,0.35); font-weight: var(--font-bold); background: none !important; }
#mks-page-root .song-panel { display: none; grid-template-columns: 1.2fr 1fr; gap: var(--space-12); align-items: start; animation: mksFadeIn 0.6s ease-out; }
#mks-page-root .song-panel.active { display: grid; }
@keyframes mksFadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
#mks-page-root .song-occasion { display: inline-block; background: rgba(200,169,110,0.12); color: var(--accent-gold-light) !important; font-size: var(--text-xs); letter-spacing: var(--tracking-wider); padding: var(--space-1) var(--space-4); border-radius: var(--radius-sm); margin-bottom: var(--space-6); text-transform: uppercase; border: 1px solid rgba(200,169,110,0.2); }
#mks-page-root .song-title-text { font-family: var(--font-serif) !important; font-size: var(--text-4xl); margin-bottom: var(--space-2); line-height: var(--leading-tight); color: var(--text-primary) !important; background: transparent !important; }
#mks-page-root .song-artist { color: var(--accent-gold) !important; font-size: var(--text-base); margin-bottom: var(--space-6); font-style: italic; }
#mks-page-root .song-desc { color: var(--text-secondary) !important; font-size: var(--text-base); line-height: var(--leading-relaxed); margin-bottom: var(--space-8); }
#mks-page-root .song-tags { display: flex; flex-wrap: wrap; gap: var(--space-2); margin-bottom: var(--space-8); }
#mks-page-root .song-tag { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: var(--text-primary) !important; font-size: var(--text-xs); padding: var(--space-1) var(--space-4); border-radius: var(--radius-full); }
#mks-page-root .song-price-box { border: 1px solid var(--border-default); padding: var(--space-6); border-radius: var(--radius-lg); background: var(--bg-card); backdrop-filter: blur(var(--blur-md)); display: inline-block; min-width: 280px; }
#mks-page-root .song-price-box .price { font-family: var(--font-serif) !important; font-size: var(--text-3xl); color: var(--accent-gold-neon) !important; font-weight: var(--font-bold); }
#mks-page-root .song-price-box .price-note { font-size: var(--text-xs); color: var(--text-secondary) !important; margin-top: var(--space-1); }
#mks-page-root .lyrics-card { background: linear-gradient(135deg, rgba(200,169,110,0.04) 0%, rgba(0,0,0,0) 100%); border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: var(--space-10); position: relative; box-shadow: var(--shadow-xl); }
#mks-page-root .lyrics-card::before { content: '\266A'; position: absolute; top: var(--space-6); right: var(--space-8); color: rgba(200,169,110,0.12); font-size: var(--text-5xl); font-family: var(--font-serif); }
#mks-page-root .lyrics-label { font-size: var(--text-xs); letter-spacing: var(--tracking-widest); color: var(--accent-gold) !important; margin-bottom: var(--space-6); text-transform: uppercase; font-weight: var(--font-semibold); border-bottom: 1px solid var(--border-default); padding-bottom: var(--space-2); }
#mks-page-root .lyrics-text { font-family: var(--font-serif) !important; font-style: italic; color: var(--text-primary) !important; font-size: var(--text-lg); line-height: 2.2; white-space: pre-line; }

/* Cennik */
#mks-page-root .cennik { background: var(--bg-secondary) !important; border-top: 1px solid var(--border-default); border-bottom: 1px solid var(--border-default); }
#mks-page-root .pakiety-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-6); margin-top: var(--space-8); }
#mks-page-root .pakiet { background: var(--bg-card) !important; border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: var(--space-10) var(--space-6); position: relative; transition: all var(--transition-base); backdrop-filter: blur(var(--blur-md)); display: flex; flex-direction: column; }
#mks-page-root .pakiet:hover { border-color: var(--accent-gold) !important; transform: translateY(-6px); box-shadow: var(--shadow-xl); }
#mks-page-root .pakiet.featured { border-color: var(--accent-gold) !important; background: linear-gradient(180deg, rgba(35,28,20,0.45) 0%, rgba(18,15,12,0.75) 100%) !important; box-shadow: 0 0 35px rgba(200,169,110,0.12); }
#mks-page-root .pakiet-badge { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--gradient-gold); color: var(--bg-primary) !important; font-size: var(--text-xs); font-weight: var(--font-bold); letter-spacing: var(--tracking-wider); padding: var(--space-1) var(--space-4); border-radius: var(--radius-sm); text-transform: uppercase; white-space: nowrap; }
#mks-page-root .pakiet-name { font-size: var(--text-xs); letter-spacing: var(--tracking-widest); color: var(--text-secondary) !important; text-transform: uppercase; margin-bottom: var(--space-1); font-weight: var(--font-medium); font-family: var(--font-sans); }
#mks-page-root .pakiet-title { font-family: var(--font-serif) !important; font-size: var(--text-2xl); margin-bottom: var(--space-1); color: var(--text-primary) !important; background: transparent !important; }
#mks-page-root .pakiet-price-inline { font-family: var(--font-serif) !important; font-size: var(--text-3xl); color: var(--accent-gold-neon) !important; margin: var(--space-5) 0; font-weight: var(--font-bold); white-space: nowrap; display: flex; align-items: baseline; gap: var(--space-1); }
#mks-page-root .pakiet-price-inline span { font-size: var(--text-base); font-family: var(--font-sans); color: var(--text-secondary) !important; font-weight: var(--font-normal); }
#mks-page-root .pakiet-desc { font-size: var(--text-sm); color: var(--text-secondary) !important; margin-bottom: var(--space-6); line-height: var(--leading-relaxed); min-height: 70px; }
#mks-page-root .pakiet-features { list-style: none !important; margin: 0 0 var(--space-8) 0 !important; padding: 0 !important; flex-grow: 1; }
#mks-page-root .pakiet-features li { padding: var(--space-2) 0; border-bottom: 1px solid rgba(255,255,255,0.03); font-size: var(--text-sm); color: var(--text-primary) !important; display: flex; align-items: center; gap: var(--space-2); line-height: var(--leading-snug); list-style: none !important; margin: 0 !important; }
#mks-page-root .pakiet-features li::before { content: '\2713'; color: var(--accent-gold) !important; font-weight: var(--font-bold); flex-shrink: 0; display: inline-block; }
#mks-page-root .btn-pakiet { display: block; text-align: center; padding: var(--space-3) var(--space-4); border: 1px solid var(--accent-gold) !important; color: var(--accent-gold) !important; font-size: var(--text-sm); font-weight: var(--font-semibold); letter-spacing: var(--tracking-wide); text-decoration: none; border-radius: var(--radius-md); transition: all var(--transition-base); cursor: pointer; background: rgba(0,0,0,0.3) !important; width: 100%; margin-top: auto; }
#mks-page-root .btn-pakiet:hover, #mks-page-root .pakiet.featured .btn-pakiet { background: var(--gradient-gold) !important; color: var(--bg-primary) !important; border-color: transparent !important; box-shadow: var(--shadow-lg); }

/* O mnie */
#mks-page-root .o-mnie { background: var(--bg-primary) !important; }
#mks-page-root .o-mnie-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: var(--space-16); align-items: center; }
#mks-page-root .o-mnie-visual { position: relative; }
#mks-page-root .o-mnie-img-placeholder { width: 100%; aspect-ratio: 4/5; background: linear-gradient(135deg, var(--bg-card) 0%, #201812 100%) !important; border: 1px solid var(--border-default); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; font-size: var(--text-7xl); color: rgba(200,169,110,0.12); box-shadow: var(--shadow-xl); position: relative; }
#mks-page-root .o-mnie-quote-box { position: absolute; bottom: -2rem; right: -2rem; background: #0a0806 !important; border: 1px solid var(--border-default); padding: var(--space-6); border-radius: var(--radius-lg); max-width: 280px; box-shadow: var(--shadow-xl); }
#mks-page-root .o-mnie-quote-text { font-family: var(--font-serif) !important; font-style: italic; font-size: var(--text-base); color: var(--text-primary) !important; line-height: var(--leading-relaxed); }
#mks-page-root .o-mnie-quote-author { font-size: var(--text-xs); color: var(--accent-gold) !important; margin-top: var(--space-3); text-transform: uppercase; letter-spacing: var(--tracking-wide); }
#mks-page-root .o-mnie-eyebrow { color: var(--accent-gold) !important; font-size: var(--text-xs); letter-spacing: var(--tracking-widest); text-transform: uppercase; margin-bottom: var(--space-4); font-weight: var(--font-semibold); }
#mks-page-root .o-mnie h2 { font-family: var(--font-serif) !important; font-size: var(--text-4xl); margin-bottom: var(--space-8); line-height: var(--leading-tight); color: var(--text-primary) !important; background: transparent !important; }
#mks-page-root .o-mnie p { color: var(--text-secondary) !important; line-height: 1.9; margin-bottom: var(--space-6); font-size: var(--text-base); }
#mks-page-root .o-mnie p strong { color: var(--accent-gold-light) !important; }
#mks-page-root .ai-manifesto-box { background: rgba(107,39,55,0.12) !important; border-left: 3px solid var(--accent-gold); padding: var(--space-6) var(--space-8); border-radius: 0 var(--radius-lg) var(--radius-lg) 0; margin: var(--space-10) 0; border-top: 1px solid rgba(200,169,110,0.06); border-bottom: 1px solid rgba(200,169,110,0.06); }
#mks-page-root .ai-manifesto-box p { font-style: italic; color: var(--text-primary) !important; margin: 0; font-size: var(--text-lg); font-family: var(--font-serif) !important; line-height: var(--leading-relaxed); }
#mks-page-root .credentials { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-4); margin-top: var(--space-10); border-top: 1px solid var(--border-default); padding-top: var(--space-8); }
#mks-page-root .credential { text-align: center; background: rgba(255,255,255,0.02) !important; padding: var(--space-5) var(--space-3); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.03); }
#mks-page-root .credential-num { font-family: var(--font-serif) !important; font-size: var(--text-2xl); color: var(--accent-gold-neon) !important; font-weight: var(--font-bold); }
#mks-page-root .credential-label { font-size: var(--text-xs); color: var(--text-secondary) !important; text-transform: uppercase; letter-spacing: var(--tracking-wide); margin-top: var(--space-1); }

/* Opinie */
#mks-page-root .opinie { background: var(--bg-secondary) !important; border-top: 1px solid var(--border-default); border-bottom: 1px solid var(--border-default); }
#mks-page-root .opinie-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-8); }
#mks-page-root .opinia { background: var(--bg-card) !important; border: 1px solid var(--border-default); padding: var(--space-10); border-radius: var(--radius-lg); backdrop-filter: blur(var(--blur-md)); position: relative; }
#mks-page-root .opinia-stars { color: var(--accent-gold) !important; font-size: var(--text-base); margin-bottom: var(--space-6); letter-spacing: 3px; }
#mks-page-root .opinia-text { font-family: var(--font-serif) !important; font-style: italic; color: var(--text-primary) !important; font-size: var(--text-lg); line-height: var(--leading-relaxed); margin-bottom: var(--space-8); }
#mks-page-root .opinia-author { font-size: var(--text-sm); color: var(--text-secondary) !important; border-top: 1px solid rgba(255,255,255,0.06); padding-top: var(--space-5); }
#mks-page-root .opinia-author strong { color: var(--accent-gold) !important; display: block; font-size: var(--text-base); font-family: var(--font-sans); font-weight: var(--font-semibold); margin-bottom: var(--space-1); }

/* Formularz */
#mks-page-root .formularz { background: var(--bg-primary) !important; }
#mks-page-root .form-container { max-width: 950px; margin: 0 auto; background: var(--bg-card) !important; border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: var(--space-12); box-shadow: var(--shadow-xl); backdrop-filter: blur(var(--blur-lg)); }
#mks-page-root .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-6); }
#mks-page-root .form-group { margin-bottom: 0; }
#mks-page-root .form-group.full { grid-column: 1 / -1; }
#mks-page-root .form-section-title { font-family: var(--font-serif) !important; font-size: var(--text-xl); color: var(--accent-gold) !important; margin: var(--space-10) 0 var(--space-6); padding-bottom: var(--space-2); border-bottom: 1px solid var(--border-default); grid-column: 1 / -1; font-weight: var(--font-bold); background: transparent !important; }
#mks-page-root .form-radio-group { display: flex; flex-wrap: wrap; gap: var(--space-3); grid-column: 1 / -1; }
#mks-page-root .form-radio-label { border: 1px solid var(--border-default); padding: var(--space-3) var(--space-6); border-radius: var(--radius-md); font-size: var(--text-sm); cursor: pointer; color: var(--text-secondary) !important; transition: all var(--transition-base); background: rgba(0,0,0,0.4) !important; display: inline-block; }
#mks-page-root .form-radio-label:hover { border-color: var(--accent-gold) !important; color: var(--text-primary) !important; }
#mks-page-root .form-radio-label input { display: none; }
#mks-page-root .form-radio-label.selected { background: var(--gradient-gold) !important; border-color: transparent !important; color: var(--bg-primary) !important; font-weight: var(--font-bold); box-shadow: 0 0 15px rgba(200,169,110,0.25); }
#mks-page-root .form-slider-wrapper { grid-column: 1 / -1; background: rgba(255,255,255,0.01) !important; padding: var(--space-8); border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.04); }
#mks-page-root .form-slider { width: 100%; appearance: none; -webkit-appearance: none; height: 6px; background: rgba(255,255,255,0.12); border-radius: 3px; outline: none; margin: var(--space-6) 0; }
#mks-page-root .form-slider::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 22px; height: 22px; background: var(--accent-gold); border-radius: var(--radius-full); cursor: pointer; box-shadow: 0 0 12px rgba(200,169,110,0.6); transition: transform var(--transition-fast); }
#mks-page-root .form-slider::-webkit-slider-thumb:hover { transform: scale(1.25); }
#mks-page-root .slider-labels { display: flex; justify-content: space-between; font-size: var(--text-sm); color: var(--text-secondary) !important; font-weight: var(--font-medium); }
#mks-page-root .form-submit { grid-column: 1 / -1; text-align: center; margin-top: var(--space-8); }
#mks-page-root .btn-submit { background: var(--gradient-gold) !important; color: var(--bg-primary) !important; padding: var(--space-5) var(--space-12); font-size: var(--text-base); font-weight: var(--font-bold); letter-spacing: var(--tracking-wide); border: none !important; border-radius: var(--radius-md); cursor: pointer; transition: all var(--transition-base); box-shadow: var(--shadow-lg); width: 100%; text-transform: uppercase; font-family: var(--font-sans); }
#mks-page-root .btn-submit:hover { background: var(--text-primary) !important; transform: translateY(-2px); box-shadow: var(--shadow-xl); color: var(--bg-primary) !important; }
#mks-page-root .form-note { font-size: var(--text-sm); color: var(--text-secondary) !important; margin-top: var(--space-5); }
#mks-page-root .form-gwarancja { display: flex; align-items: center; gap: var(--space-6); background: rgba(31,61,47,0.18) !important; border: 1px solid rgba(31,61,47,0.35); padding: var(--space-6) var(--space-8); border-radius: var(--radius-lg); margin-top: var(--space-10); grid-column: 1 / -1; }
#mks-page-root .gwarancja-icon { font-size: var(--text-5xl); filter: drop-shadow(0 0 12px rgba(31,61,47,0.5)); }
#mks-page-root .gwarancja-text { font-size: var(--text-base); color: var(--text-primary) !important; line-height: var(--leading-relaxed); }
#mks-page-root .gwarancja-text strong { color: var(--accent-gold-light) !important; }
#mks-page-root label { display: block; font-size: var(--text-sm); font-weight: var(--font-semibold); letter-spacing: var(--tracking-wide); color: var(--accent-gold-light) !important; margin-bottom: var(--space-2); text-transform: uppercase; }
#mks-page-root label .req { color: var(--accent-gold-neon) !important; }
#mks-page-root input[type="text"], #mks-page-root input[type="email"], #mks-page-root input[type="tel"], #mks-page-root input[type="date"], #mks-page-root select, #mks-page-root textarea { width: 100%; padding: var(--space-4) var(--space-5); border: 1px solid var(--border-default) !important; border-radius: var(--radius-md); font-family: var(--font-sans); font-size: var(--text-sm); color: var(--text-primary) !important; background: rgba(0,0,0,0.6) !important; transition: all var(--transition-base); outline: none; }
#mks-page-root input:focus, #mks-page-root select:focus, #mks-page-root textarea:focus { border-color: var(--accent-gold) !important; background: #080705 !important; box-shadow: 0 0 12px rgba(200,169,110,0.2); }
#mks-page-root textarea { resize: vertical; min-height: 140px; }

/* FAQ */
#mks-page-root .faq { background: var(--bg-secondary) !important; border-top: 1px solid var(--border-default); border-bottom: 1px solid var(--border-default); }
#mks-page-root .faq-list { max-width: 900px; margin: 0 auto; }
#mks-page-root .faq-list .faq-item { border: none !important; border-bottom: 1px solid var(--border-default) !important; padding: var(--space-2) 0; margin-bottom: 0; border-radius: 0 !important; overflow: visible; background: transparent !important; backdrop-filter: none; }
#mks-page-root .faq-q { width: 100%; text-align: left; background: none !important; border: none !important; padding: var(--space-6) var(--space-5); font-size: var(--text-lg); font-weight: var(--font-semibold); cursor: pointer; color: var(--text-primary) !important; display: flex; justify-content: space-between; align-items: center; transition: background var(--transition-fast), color var(--transition-fast); border-radius: var(--radius-sm); }
#mks-page-root .faq-q:hover { background: rgba(255,255,255,0.02) !important; color: var(--accent-gold) !important; }
#mks-page-root .faq-q::after { content: '+'; color: var(--accent-gold) !important; font-size: var(--text-2xl); font-weight: var(--font-light); transition: transform var(--transition-base); }
#mks-page-root .faq-q.open { color: var(--accent-gold) !important; }
#mks-page-root .faq-q.open::after { content: '+'; transform: rotate(45deg); color: var(--text-primary) !important; }
#mks-page-root .faq-a { display: none; padding: var(--space-2) var(--space-5) var(--space-6); color: var(--text-secondary) !important; font-size: var(--text-base); line-height: var(--leading-relaxed); animation: mksSlideDown 0.35s ease-out; }
#mks-page-root .faq-a.open { display: block; }
@keyframes mksSlideDown { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }

/* Sticky CTA */
#mks-page-root .sticky-cta { position: fixed; bottom: 0; left: 0; right: 0; z-index: var(--z-sticky); background: rgba(0,0,0,0.95) !important; border-top: 1px solid var(--border-default); padding: var(--space-4) var(--space-10); display: flex; align-items: center; justify-content: space-between; transform: translateY(100%); transition: transform var(--transition-slow); backdrop-filter: blur(var(--blur-lg)); -webkit-backdrop-filter: blur(var(--blur-lg)); box-shadow: 0 -12px 35px rgba(0,0,0,0.6); }
#mks-page-root .sticky-cta.visible { transform: translateY(0); }
#mks-page-root .sticky-cta-text { color: var(--text-primary) !important; font-size: var(--text-base); }
#mks-page-root .sticky-cta-text strong { color: var(--accent-gold-neon) !important; font-weight: var(--font-bold); }

/* Responsive */
@media (max-width: 1024px) {
  #mks-page-root .pakiety-grid { grid-template-columns: repeat(2, 1fr) !important; gap: var(--space-8); }
}
@media (max-width: 768px) {
  #mks-page-root .hero-badges { grid-template-columns: repeat(2, 1fr); gap: var(--space-5); }
  #mks-page-root .manifesto-grid { grid-template-columns: 1fr; }
  #mks-page-root .song-panel { grid-template-columns: 1fr !important; gap: var(--space-10); }
  #mks-page-root .o-mnie-grid { grid-template-columns: 1fr !important; gap: var(--space-12); }
  #mks-page-root .o-mnie-quote-box { position: static; margin: var(--space-8) auto 0; max-width: 100%; }
  #mks-page-root .opinie-grid { grid-template-columns: 1fr; }
  #mks-page-root .form-container { padding: var(--space-10) var(--space-6); }
  #mks-page-root .form-grid { grid-template-columns: 1fr; }
  #mks-page-root .credentials { grid-template-columns: repeat(2, 1fr); }
  #mks-page-root .sticky-cta { padding: var(--space-5); flex-direction: column; gap: var(--space-5); text-align: center; }
}
@media (max-width: 680px) {
  #mks-page-root .pakiety-grid { grid-template-columns: 1fr !important; gap: var(--space-8); }
}
@media (max-width: 600px) {
  #mks-page-root section { padding: var(--space-16) var(--space-5); }
  #mks-page-root .hero-badges { grid-template-columns: 1fr; }
  #mks-page-root .credentials { grid-template-columns: repeat(2, 1fr); }
  #mks-page-root .form-grid { grid-template-columns: 1fr; }
  #mks-page-root .sticky-cta { padding: var(--space-5); flex-direction: column; gap: var(--space-5); text-align: center; }
  #mks-page-root .song-tabs { gap: var(--space-1); }
  #mks-page-root .song-tab { padding: var(--space-2) var(--space-4); font-size: var(--text-xs); }
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
      <div class="hero-badge"><div class="hero-badge-num">8+</div><div class="hero-badge-label">Zrealizowanych zamówień</div></div>
      <div class="hero-badge"><div class="hero-badge-num">od 2020</div><div class="hero-badge-label">Lat doświadczenia</div></div>
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
          <div class="credential"><div class="credential-num">6+</div><div class="credential-label">lat w muzyce</div></div>
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

<!-- ════════════════════════ SEKCJA CASE STUDIES: ZREALIZOWANE UTWORY ════════════════════════ -->
<section style="padding:8rem 2rem;position:relative;display:block;background:var(--mks-bg-main);border-top:1px solid var(--mks-border-glow);" id="realizacje">
  <div class="container" style="max-width:1200px;margin:0 auto;">
    <p class="section-eyebrow">Portfolio</p>
    <h2 class="section-title">Zrealizowane Utwory — Prawdziwe Historie</h2>
    <hr class="divider">
    <p class="section-lead">Każdy utwór to osobna historia. Poniżej lista zrealizowanych projektów z cytatami klientów i możliwością odsłuchania. Aby dodać kolejny, skopiuj blok .mks-track w tablicy tracks[] w JavaScript na dole strony.</p>

    <div id="mksTracksContainer"></div>

    <div style="text-align:center;margin-top:2rem;">
      <p style="color:var(--mks-text-secondary);font-size:0.9rem;">✏️ <strong style="color:var(--mks-gold);">Jak dodać utwór?</strong> Otwórz plik, znajdź sekcję <code style="color:var(--mks-gold);">const tracks = [...]</code> w JavaScript i dodaj nowy obiekt. Każdy wpis automatycznie tworzy kartę z odtwarzaczem audio.</p>
    </div>
  </div>
</section>

<script>
(function() {
  var root = document.getElementById('mks-page-root');
  if (!root) return;

  // ═══════════════════════════════════════════════════════
  // ZAMÓWIENIA MKS — ładowane dynamicznie z panelu WP
  // Dodawaj przez WP Admin → MKS Orders → Dodaj zamówienie
  // ═══════════════════════════════════════════════════════
  const tracks = <?php
$orders = HRL_MKS_Orders::get_orders();
if ( ! empty( $orders ) ) {
    echo json_encode( $orders, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
} else {
    // Fallback — przykładowe dane gdy brak wpisów w CPT
    echo json_encode( array(
        array(
            'id' => 1, 'label' => '✔️ ZREALIZOWANY',
            'title' => '„66 Maryysia nasza Mama”',
            'for' => 'dla mamy Marysi — od rodziny',
            'date' => 'Data: lipiec 2026 · Czas realizacji: 3 dni',
            'opis' => 'Radosna i pełna humoru piosenka urodzinowa z okazji 66. urodzin. Utwór miał za zadanie podkreślić niespożytą energię, witalność i gotowość do zabawy solenizantki.',
            'efekt' => 'Piosenka rozkręciła urodzinową imprezę, wywołując mnóstwo śmiechu.',
            'pakiet' => 'Standard (99 PLN) + wokal rozrywkowy',
            'cytat' => '„Super pamiątka, mama do dziś nuci to pod nosem!”',
            'cytat_od' => 'Córka',
            'fragment' => "Marysiu, Marysiu, mamo nasza złota, masz już 66, i dalej cnota,\ntwoja energia, power i zabawa, z tobą życie to jedna wielka wyprawa!",
            'audioSrc' => get_template_directory_uri() . '/audio/66 Maryysia nasza Mama.wav',
        ),
        array(
            'id' => 2, 'label' => '✔️ ZREALIZOWANY',
            'title' => '„Ewelina, Ewelina (Pulsing Bass Next Summer Hit)”',
            'for' => 'dla Eweliny — od męża Adriana i dzieci',
            'date' => 'Data: czerwiec 2026 · Czas realizacji: 5 dni',
            'opis' => 'Taneczny, klubowy hit na 40. urodziny z pulsującym basem. Tekst z przymrużeniem oka opisuje wszechstronność Eweliny jako żony i matki oraz jej szaloną naturę, a także żartobliwie nawiązuje do domowych obowiązków.',
            'efekt' => 'Utwór stał się niekwestionowanym hitem domowych imprez, a goście tańczyli do niego wielokrotnie w ciągu nocy.',
            'pakiet' => 'Club Banger (199 PLN)',
            'cytat' => '„Bas rewelacyjny, a tekst w punkt. Żona była w szoku i od razu ruszyła na parkiet!”',
            'cytat_od' => 'Mąż',
            'fragment' => "Ewelina, Ewelina, pysznie, a nie lekko,\nżona, matka, córka, siostra, roztańczona jak ta miotła,\n40 już minęło, Szekspirowskie piszesz dzieło...",
            'audioSrc' => get_template_directory_uri() . '/audio/Ewelina, Ewelina (Pulsing Bass Next Summer Hit).wav',
        ),
        array(
            'id' => 3, 'label' => '✔️ ZREALIZOWANY',
            'title' => '„Hej, rodzinko, czas zabawy!”',
            'for' => 'dla mamy Stefanii — od licznie zgromadzonej rodziny',
            'date' => 'Data: sierpień 2026 · Czas realizacji: 4 dni',
            'opis' => 'Skoczna, biesiadna piosenka z okazji 65. urodzin mamy Stefanii. Zamysłem było stworzenie utworu idealnego do wspólnego, głośnego śpiewania podczas wielkiego zjazdu rodzinnego dla braci, szwagrów i sióstr.',
            'efekt' => 'Piosenka stała się hymnem całej uroczystości, integrując przy stole kilka pokoleń.',
            'pakiet' => 'Biesiada (129 PLN) + chórki',
            'cytat' => '„Cała rodzina śpiewała refren, doskonały klimat i cudowna pamiątka.”',
            'cytat_od' => 'Syn',
            'fragment' => "Dzisiaj dzionek mamy wielki,\nimpreska się szykuje, bo nić się czujni wierci,\nianie bo Stefusia ma rozstanie, dziś skowrowej pijmy zdrowie...",
            'audioSrc' => get_template_directory_uri() . '/audio/Hej, rodzinko, czas zabawy!.wav',
        ),
        array(
            'id' => 4, 'label' => '✔️ ZREALIZOWANY',
            'title' => '„Jeszcze nie koniec”',
            'for' => 'dla ukochanej — na 16. rocznicę',
            'date' => 'Data: marzec 2026 · Czas realizacji: 6 dni',
            'opis' => 'Bardzo emocjonalny, szczery utwór podsumowujący 16 lat wspólnego życia. Piosenka opowiada o wspólnym pokonywaniu życiowych burz, budowaniu poczucia bezpieczeństwa i miłości, która jest cenniejsza niż złoto.',
            'efekt' => 'Ogromne wzruszenie i łzy szczęścia podczas romantycznej, rocznicowej kolacji.',
            'pakiet' => 'Premium (149 PLN) + nastrojowy wokal',
            'cytat' => '„Oddaliście w słowach to, czego ja nie potrafiłem powiedzieć przez te wszystkie lata.”',
            'cytat_od' => 'Zleceniodawca',
            'fragment' => "16 lat, a czuję jakby wczoraj, wzięliśmy świat na własne barki,\nburze szalały, los rzucał kłody, ale zawsze trzymałem twoją dłoń.",
            'audioSrc' => get_template_directory_uri() . '/audio/Jeszcze nie koniec .wav',
        ),
        array(
            'id' => 5, 'label' => '✔️ ZREALIZOWANY',
            'title' => '„Matka z Żelaza i Miłości”',
            'for' => 'dla matki Stefanii — od pięciorga dzieci',
            'date' => 'Data: styczeń 2026 · Czas realizacji: 7 dni',
            'opis' => 'Niezwykle osobista i głęboko wzruszająca ballada dla matki, która w trudnych warunkach samotnie obroniła i wychowała pięcioro dzieci. Utwór jest hołdem dla jej determinacji i ogromnego serca.',
            'efekt' => 'Bardzo intymny moment wręczenia prezentu; piosenka wywołała łzy wzruszenia u wszystkich obecnych.',
            'pakiet' => 'VIP (249 PLN) + aranżacja filmowa',
            'cytat' => '„To muzyczny pomnik, który postawiliśmy mamie za jej siłę.”',
            'cytat_od' => 'Rodzeństwo',
            'fragment' => "Stefaniu, mamo nasza z żelaza i z miłości,\nwybacz dzieciom łzy, które spływały po twojej twarzy,\nw twoim śmiechu była siła, mimo burz i ciemności...",
            'audioSrc' => get_template_directory_uri() . '/audio/Matka z Żelaza i Miłości.wav',
        ),
        array(
            'id' => 6, 'label' => '✔️ ZREALIZOWANY',
            'title' => '„Skarby”',
            'for' => 'dla żony Ilony i córki Oliwii — od męża i ojca',
            'date' => 'Data: kwiecień 2026 · Czas realizacji: 5 dni',
            'opis' => 'Pogodna i pełna ciepła dedykacja, w której autor wyraża głęboką wdzięczność za obecność ukochanej żony i wspaniałej córeczki w jego życiu. Utwór zrealizowany z okazji 16-lecia związku, podkreślający, że rodzina to najcenniejszy dar.',
            'efekt' => 'Uśmiech, radość i mnóstwo przytuleń podczas wspólnego słuchania w domowym zaciszu.',
            'pakiet' => 'Standard (99 PLN)',
            'cytat' => '„Moje dziewczyny były zachwycone, piosenka super trafiona i bardzo ciepła!”',
            'cytat_od' => 'Zleceniodawca',
            'fragment' => "Ilona, moja żona kochana, i Oliwia, córeczka wspaniała,\nczasami robicie mi w głowie zamęt, ale bez was życie nie miałoby sensu.",
            'audioSrc' => get_template_directory_uri() . '/audio/Skarby.wav',
        ),
    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
}
?>;

  // Renderowanie kart
  var container = root.querySelector('#mksTracksContainer');
  if (!container) return;

  tracks.forEach(function(t) {
    var html = '<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:3rem;background:var(--mks-bg-card);border:1px solid var(--mks-border-glow);border-radius:8px;padding:3rem;margin-bottom:2.5rem;backdrop-filter:blur(10px);box-shadow:var(--mks-shadow);">';
    html += '<div>';
    html += '<p style="color:var(--mks-gold);font-family:var(--mks-font-headings);font-size:0.8rem;letter-spacing:0.25em;text-transform:uppercase;margin-bottom:0.8rem;font-weight:600;">' + t.label + '</p>';
    html += '<h3 style="font-family:var(--mks-font-headings);font-size:2rem;color:var(--mks-white);margin-bottom:0.5rem;">' + t.title + '</h3>';
    html += '<p style="color:var(--mks-gold-light);font-style:italic;margin-bottom:1.5rem;">' + t.for + '<br><small style="color:var(--mks-text-secondary);font-style:normal;">' + t.date + '</small></p>';
    html += '<div style="color:var(--mks-text-secondary);line-height:1.8;">';
    html += '<p style="margin-top:0.5rem;"><strong style="color:var(--mks-white);">Opis:</strong> ' + t.opis + '</p>';
    html += '<p style="margin-top:0.8rem;"><strong style="color:var(--mks-white);">Efekt:</strong> ' + t.efekt + '</p>';
    html += '<p style="margin-top:0.8rem;"><strong style="color:var(--mks-white);">Pakiet:</strong> ' + t.pakiet + '</p>';
    html += '</div>';
    html += '<div style="margin-top:1.5rem;padding:1.5rem;background:rgba(200,169,110,0.06);border-left:3px solid var(--mks-gold);border-radius:0 6px 6px 0;">';
    html += '<p style="color:var(--mks-text-secondary);font-size:0.9rem;font-style:italic;">' + t.cytat + '</p>';
    html += '<p style="color:var(--mks-gold);font-size:0.8rem;margin-top:0.5rem;text-align:right;">— ' + t.cytat_od + '</p>';
    html += '</div></div>';

    html += '<div style="background:linear-gradient(135deg,rgba(200,169,110,0.03) 0%,rgba(0,0,0,0) 100%);border:1px solid var(--mks-border-glow);border-radius:6px;padding:2.5rem;position:relative;">';
    html += '<p style="color:var(--mks-gold);font-size:0.75rem;letter-spacing:0.2em;text-transform:uppercase;margin-bottom:1rem;">Fragment tekstu</p>';
    html += '<p style="font-family:var(--mks-font-headings);font-style:italic;color:var(--mks-text-primary);font-size:1.05rem;line-height:2.2;white-space:pre-line;">' + t.fragment + '</p>';
    if (t.audioSrc && t.audioSrc !== '#') {
      html += '<p style="color:var(--mks-gold);font-size:0.75rem;letter-spacing:0.2em;text-transform:uppercase;margin-top:2.5rem;margin-bottom:0.8rem;">🔊 Posłuchaj</p>';
      html += '<div style="background:rgba(0,0,0,0.5);border-radius:4px;padding:0.8rem;border:1px solid var(--mks-border-glow);">';
      html += '<audio controls style="width:100%;height:40px;border-radius:4px;" preload="none"><source src="' + t.audioSrc + '" type="audio/mpeg"></audio>';
      html += '</div>';
    } else {
      html += '<p style="color:var(--mks-gold);font-size:0.75rem;letter-spacing:0.2em;text-transform:uppercase;margin-top:2.5rem;margin-bottom:0.8rem;">🔊 Posłuchaj</p>';
      html += '<div style="background:rgba(0,0,0,0.5);border-radius:4px;padding:0.8rem;border:1px solid var(--mks-border-glow);">';
      html += '<p style="color:var(--mks-text-secondary);font-size:0.8rem;margin:0.5rem 0;">📌 Wstaw URL do MP3 w polu <code style="color:var(--mks-gold);">audioSrc</code> w tablicy <code style="color:var(--mks-gold);">tracks[]</code> powyżej</p>';
      html += '</div>';
    }
    html += '</div></div>';

    container.insertAdjacentHTML('beforeend', html);
  });
})();
</script>

<!-- ════════════════════════ SEKCJA 1: WPROWADZENIE ════════════════════════ -->
<section class="section" id="wprowadzenie" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wprowadzenie do Usługi MKS', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Muzyczna Kreacja Słów (MKS) to autorska usługa HardbanRecords Lab polegająca na tworzeniu spersonalizowanych utworów muzycznych na zamówienie. W przeciwieństwie do generatorów AI, które produkują szablony na podstawie algorytmów, każdy utwór MKS powstaje od podstaw — od wywiadu z klientem, przez ręczne napisanie tekstu, aż po profesjonalne nagranie w studiu.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc" style="margin-top:-32px;">
            <?php esc_html_e( 'Usługa łączy w sobie kompetencje z trzech obszarów: poezji użytkowej (pisanie tekstów piosenek na konkretną okazję), kompozycji muzycznej (dobór gatunku, tempa, instrumentarium do zamówionego nastroju) oraz produkcji audio (nagranie, miks, mastering w standardzie -14 LUFS). Rezultatem jest unikalny utwór, który nie istnieje nigdzie indziej na świecie — stworzony specjalnie dla jednej osoby, pary, rodziny lub firmy.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 2: CZYM JEST ════════════════════════ -->
<section class="section" id="czym-jest">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Definicja i Zakres', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Czym Jest Muzyczna Kreacja Słów?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'MKS to usługa typu "utwór na zamówienie" (custom songwriting), realizowana w pełni przez zespół HardbanRecords Lab. Obejmuje wszystkie etapy powstawania utworu: od briefu i wywiadu z klientem, przez autorski tekst i kompozycję, po nagranie, produkcję i mastering w standardzie studyjnym.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc" style="margin-top:-32px;">
            <?php esc_html_e( 'Zakres usługi obejmuje: piosenki na wesele i pierwszy taniec, urodziny i rocznice, powitanie dziecka, pożegnania i pamiątki, hymny firmowe, dżingle reklamowe, audio logo, muzykę do kampanii marketingowych oraz utwory okolicznościowe na każdą inną wyjątkową okazję. W pakietach Premium i Firmowy klient otrzymuje pełne prawa majątkowe do utworu.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 3: DLACZEGO ════════════════════════ -->
<section class="section" id="dlaczego" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Problem Rynku', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego Warto Wybrać MKS zamiast AI?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Rynek personalizowanych piosenek został zalany przez generatory AI, które obiecują utwór w 5 minut za kilka złotych. Niestety, jakość tych utworów jest niska: teksty są bezosobowe, melodie powtarzalne, wykonanie brzmi sztucznie. Klient dostaje produkt identyczny jak tysiące innych.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc" style="margin-top:-32px;">
            <?php esc_html_e( 'MKS rozwiązuje ten problem przez: indywidualny wywiad (pytamy o konkretne wspomnienia, wewnętrzne żarty, miejsca i daty), ręczne pisanie tekstu (każde słowo jest celowe), profesjonalną aranżację (dobór instrumentów i gatunku do historii) oraz studyjne nagranie z realnymi wokalami. Rezultat: utwór, który brzmi jak prawdziwa piosenka.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 4: JAK DZIAŁA ════════════════════════ -->
<section class="section" id="jak-dziala">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Mechanizm Działania', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak Działa MKS?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Proces tworzenia utworu w MKS składa się z czterech faz: Brief (klient wypełnia formularz lub odbywa rozmowę, zbieramy informacje o okazji, osobie, wspomnieniach i preferencjach), Kompozycja (piszemy tekst i komponujemy melodię, klient otrzymuje demo do akceptacji), Produkcja (po akceptacji tekstu nagrywamy utwór w studiu — wokal, instrumenty, miks i mastering), Dostawa (klient otrzymuje gotowy utwór w MP3 i WAV z pełnymi prawami majątkowymi).', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 5: KLUCZOWE FUNKCJE ════════════════════════ -->
<section class="section" id="funkcje" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Oferta', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Kluczowe Funkcje MKS', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'MKS oferuje funkcjonalności niedostępne w masowych generatorach online.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card"><div class="product-card-icon">✍️</div><h3><?php esc_html_e( 'Ręcznie Pisany Tekst', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Każde słowo pisane przez człowieka. Twoje wspomnienia, wewnętrzne żarty, konkretne daty i miejsca. Zero losowych fraz z bazy AI.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">🎸</div><h3><?php esc_html_e( 'Instrumenty na Żywo', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Premium i Firmowy: nagrania z realnymi muzykami — gitarą, pianinem, skrzypcami. Standard: wysokiej jakości instrumenty wirtualne z ręczną aranżacją.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">🎤</div><h3><?php esc_html_e( 'Profesjonalny Wokal', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Nagranie wokalne w studiu z profesjonalnym wokalistą. Możliwość nagrania przez samego klienta. Korekcja wysokości Melodyne.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">🎛️</div><h3><?php esc_html_e( 'Studio Mastering', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Miks i mastering w standardzie -14 LUFS. Utwór brzmi profesjonalnie na Spotify, Apple Music, Tidal. Gotowy do publikacji.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">📜</div><h3><?php esc_html_e( 'Pełne Prawa Autorskie', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'W pakietach Premium i Firmowy: przeniesienie majątkowych praw autorskich. Utwór staje się Twoją własnością — możesz go dowolnie wykorzystywać.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">💬</div><h3><?php esc_html_e( 'Nieograniczone Korekty', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Premium: nieograniczone poprawki tekstu i aranżacji. Pracujemy tak długo, aż utwór będzie dokładnie taki, jak sobie wyobrażałeś.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 6: TECHNOLOGIE ════════════════════════ -->
<section class="section" id="technologie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Zaplecze Techniczne', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Technologie w MKS', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Mimo że MKS stawia na ludzką kreatywność, wspieramy się nowoczesnymi technologiami studyjnymi, aby zapewnić najwyższą jakość produkcji.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card"><div class="product-card-icon">🎚️</div><h3><?php esc_html_e( 'DAW: Pro Tools + Logic Pro', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Nagrania w Pro Tools Ultimate i Logic Pro. Edycja, korekcja wysokości (Melodyne), timing, komp editing.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">🎛️</div><h3><?php esc_html_e( 'Instrumenty Wirtualne', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Native Instruments Kontakt, Spectrasonics Omnisphere, Keyscape, EastWest Hollywood Orchestra. Dla Premium: live sessions.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">🎤</div><h3><?php esc_html_e( 'Sprzęt Studyjny', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Mikrofony Neumann U87 Ai, AKG C414, Sennheiser MD 421. Przedwzmacniacze Universal Audio. Monitoring Adam Audio i Neumann.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">📦</div><h3><?php esc_html_e( 'Formaty Dostawy', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Standard: MP3 320kbps + WAV 24-bit/48kHz. Na życzenie: FLAC, AIFF. Pliki przez link do pobrania (Wetransfer).', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 7: KORZYŚCI ════════════════════════ -->
<section class="section" id="korzysci" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego MKS', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Korzyści z Zakupu Utworu MKS', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Zamawiając utwór w Muzycznej Kreacji Słów, otrzymujesz coś więcej niż plik audio.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card"><h3><?php esc_html_e( 'Unikalność', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Twój utwór istnieje tylko w jednej kopii. Żaden generator nie wygeneruje identycznego tekstu, melodii ani aranżacji. Niepowtarzalna pamiątka.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3><?php esc_html_e( 'Emocjonalna Głębia', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Profesjonalnie wykonany utwór wywołuje autentyczne emocje. To nie tło — to punkt kulminacyjny każdej uroczystości.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3><?php esc_html_e( 'Profesjonalna Jakość', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Utwór brzmi jak radiowy hit. Możesz go odtwarzać publicznie, opublikować na streamingach, wykorzystać w reklamie.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3><?php esc_html_e( 'Pełna Kontrola', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Ty decydujesz o tekście, gatunku, nastroju i tempie. Pracujemy do momentu, gdy utwór w 100% odpowiada Twojej wizji.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 8: ZASTOSOWANIA ════════════════════════ -->
<section class="section" id="zastosowania">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Zastosowania', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Zastosowania MKS', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'MKS znajduje zastosowanie w wielu sytuacjach — od prywatnych po korporacyjne.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card"><div class="product-card-icon">💍</div><h3><?php esc_html_e( 'Śluby i Rocznice', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Piosenka na pierwszy taniec, prezent dla małżonka. Utwór opowiadający historię związku. Najpopularniejsza kategoria MKS.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">🎂</div><h3><?php esc_html_e( 'Urodziny i Imieniny', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Prezent na 18, 30, 50 urodziny. Żartobliwy lub wzruszający utwór. Możliwość zaskoczenia jubilata.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">👶</div><h3><?php esc_html_e( 'Narodziny i Chrzciny', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Kołysanka dla dziecka z imieniem w tekście. Pamiątka na całe życie.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">💫</div><h3><?php esc_html_e( 'Pożegnania i Pamiątki', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Utrwalenie wspomnień o bliskiej osobie w formie piosenki. Pełna ciepła, bez nadmiernego patosu.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">🏢</div><h3><?php esc_html_e( 'Firmowe i Biznesowe', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Hymn firmowy, piosenka integracyjna, dżingiel reklamowy, audio logo marki.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">❤️</div><h3><?php esc_html_e( 'Wyznania i Przeprosiny', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Utwór jako forma wyznania miłości, przeprosin lub podziękowania.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 9: PROCES ════════════════════════ -->
<section class="section" id="proces" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Proces Krok po Kroku', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak Zamówić Utwór?', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'Proces zamówienia jest prosty i transparentny. Od formularza do gotowego utworu.', 'hrl-theme' ); ?></p>
        <div class="product-grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
            <div class="product-card" style="text-align:center;"><div style="font-family:var(--font-headings);font-size:2.5rem;color:var(--gold);font-weight:700;margin-bottom:8px;">01</div><h3><?php esc_html_e( 'Formularz', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Wypełniasz formularz online. Opisz okazję, osobę, wspomnienia. Im więcej szczegółów, tym lepszy efekt.', 'hrl-theme' ); ?></p></div>
            <div class="product-card" style="text-align:center;"><div style="font-family:var(--font-headings);font-size:2.5rem;color:var(--gold);font-weight:700;margin-bottom:8px;">02</div><h3><?php esc_html_e( 'Wycena i Brief', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Otrzymujesz wycenę w 24h. Po akceptacji odbywamy rozmowę briefową (30-60 min). Zbieramy wspomnienia i preferencje.', 'hrl-theme' ); ?></p></div>
            <div class="product-card" style="text-align:center;"><div style="font-family:var(--font-headings);font-size:2.5rem;color:var(--gold);font-weight:700;margin-bottom:8px;">03</div><h3><?php esc_html_e( 'Produkcja', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Piszemy tekst, komponujemy muzykę, nagrywamy w studio. Przesyłamy demo do akceptacji. Wprowadzamy poprawki.', 'hrl-theme' ); ?></p></div>
            <div class="product-card" style="text-align:center;"><div style="font-family:var(--font-headings);font-size:2.5rem;color:var(--gold);font-weight:700;margin-bottom:8px;">04</div><h3><?php esc_html_e( 'Gotowy Utwór', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Otrzymujesz gotowy utwór w profesjonalnej jakości. Prawa autorskie przechodzą na Ciebie. Możesz publikować.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 10: BEZPIECZEŃSTWO ════════════════════════ -->
<section class="section" id="bezpieczenstwo">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Ochrona i Prawa', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Bezpieczeństwo i Prawa Autorskie', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'MKS przykłada ogromną wagę do kwestii prawnych związanych z prawami autorskimi i poufnością.', 'hrl-theme' ); ?></p>
        <div class="product-grid">
            <div class="product-card"><div class="product-card-icon">📜</div><h3><?php esc_html_e( 'Przeniesienie Praw', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'W pakietach Premium i Firmowy prawa majątkowe przechodzą na klienta. Umowa pisemna w formie elektronicznej.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">🔒</div><h3><?php esc_html_e( 'Poufność', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Wszystkie informacje z briefu są poufne. Nie publikujemy utworów bez zgody klienta.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">✍️</div><h3><?php esc_html_e( 'Rejestr Utworów', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Każdy utwór rejestrowany w wewnętrznym rejestrze HRL. W razie potrzeby wystawiamy oświadczenie o autorstwie.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">📋</div><h3><?php esc_html_e( 'Licencjonowanie', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Utwory MKS są w 100% autorskie, nie podlegają OZZ. Możesz je odtwarzać publicznie bez dodatkowych opłat.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 11: INTEGRACJE ════════════════════════ -->
<section class="section" id="integracje" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Platformy i Media', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Integracje i Platformy', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'MKS współpracuje z platformami dystrybucyjnymi i streamingowymi.', 'hrl-theme' ); ?></p>
        <div class="product-grid">
            <div class="product-card"><div class="product-card-icon">🎵</div><h3><?php esc_html_e( 'Dystrybucja Streamingowa', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Publikujemy utwór na Spotify, Apple Music, Tidal przez dystrybutora. Klient zachowuje 100% tantiem.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">📺</div><h3><?php esc_html_e( 'Reklama i Synchronizacja', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Utwory Firmowe mogą być użyte w reklamie TV, radio, social media. Wystawiamy licencję synch.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">📱</div><h3><?php esc_html_e( 'Social Media', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Utwory MKS sprawdzają się jako muzyka do rolek, stories, filmów na Instagramie, TikToku i YouTube bez blokad.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">📻</div><h3><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Wybrane utwory (za zgodą klienta) emitowane w Radiu HRL Live jako przykład możliwości twórczych.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 12: AUTOMATYZACJA ════════════════════════ -->
<section class="section" id="automatyzacja">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Wsparcie Procesu', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Automatyzacja Procesów', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'Mimo że tworzenie utworu jest ręczne, wspieramy się automatyzacją w obszarach administracyjnych.', 'hrl-theme' ); ?></p>
        <div class="product-grid">
            <div class="product-card"><div class="product-card-icon">📝</div><h3><?php esc_html_e( 'Auto-Formularz', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Formularz automatycznie kategoryzuje zgłoszenie, wysyła potwierdzenie i przekazuje dane do CRM. Wycena w 24h.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">💳</div><h3><?php esc_html_e( 'Auto-Płatności', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Po akceptacji wyceny klient otrzymuje link do płatności online (Stripe/PayPal). Po zaksięgowaniu projekt rusza.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">📧</div><h3><?php esc_html_e( 'Auto-Powiadomienia', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Automatyczne e-maile o statusie realizacji: brief zaakceptowany, produkcja, demo gotowe, utwór gotowy.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><div class="product-card-icon">📦</div><h3><?php esc_html_e( 'Auto-Dostawa', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Po zakończeniu produkcji system automatycznie wysyła link do pobrania plików. Pliki dostępne przez 30 dni.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 13: SŁOWNIK ════════════════════════ -->
<section class="section" id="slownik" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Definicje', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Słownik Pojęć MKS', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'Kluczowe terminy używane w kontekście usługi Muzyczna Kreacja Słów.', 'hrl-theme' ); ?></p>
        <div class="product-grid" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;">
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'Songwriting (pisanie piosenek)', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Proces twórczy polegający na napisaniu tekstu i melodii utworu muzycznego. Obejmuje dobór słów, rymów, struktury zwrotek i refrenu.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'Aranżacja', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Dobór instrumentów, harmonia, dynamika i struktura utworu. Określa, jak utwór brzmi — od solowej gitary po pełną orkiestrę.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'Mastering', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Ostatni etap produkcji muzycznej. Utwór jest dopracowywany pod kątem głośności, dynamiki i barwy. W HRL: standard -14 LUFS.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'Przeniesienie Praw Autorskich', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Umowa, w której twórca przekazuje nabywcy całość majątkowych praw autorskich do utworu. Nabywca może dowolnie korzystać z utworu.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 14: PODSUMOWANIE ════════════════════════ -->
<section class="section" id="podsumowanie" style="text-align:center;background:var(--bg-main);border-top:1px solid var(--border-color);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Zamów Utwór', 'hrl-theme' ); ?></p>
        <h2 class="section-title" style="margin-bottom:24px;"><?php esc_html_e( 'Muzyczna Kreacja Słów — Twoja Historia w Nutach', 'hrl-theme' ); ?></h2>
        <div style="max-width:800px;margin:0 auto 40px;text-align:left;">
            <p style="color:var(--text-secondary);font-size:1.05rem;line-height:1.8;margin-bottom:20px;"><?php esc_html_e( 'Muzyczna Kreacja Słów to więcej niż usługa — to sposób na zachowanie najważniejszych chwil w formie, która trwa. Każdy utwór, który tworzymy, jest unikalny, stworzony od podstaw z myślą o jednej konkretnej osobie, parze, rodzinie lub firmie.', 'hrl-theme' ); ?></p>
            <p style="color:var(--text-secondary);font-size:1.05rem;line-height:1.8;margin-bottom:20px;"><?php esc_html_e( 'W świecie zdominowanym przez generatywną AI i masową produkcję, ręcznie pisany, profesjonalnie nagrany utwór jest aktem odwagi i autentyczności. To dowód, że komuś zależy na tyle, by poświęcić czas, uwagę i serce na stworzenie czegoś prawdziwego.', 'hrl-theme' ); ?></p>
            <p style="color:var(--text-secondary);font-size:1.05rem;line-height:1.8;"><?php esc_html_e( 'Wypełnij formularz, opowiedz nam swoją historię, a my zamienimy ją w muzykę. Bezpłatna konsultacja w ciągu 24 godzin.', 'hrl-theme' ); ?></p>
        </div>
        <div style="display:flex;gap:20px;justify-content:center;flex-wrap:wrap;">
            <a href="#formularz" class="btn btn-primary"><?php esc_html_e( 'Zamów Utwór →', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Masz Pytania?', 'hrl-theme' ); ?></a>
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