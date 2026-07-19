/**
 * HRL Theme — Main JavaScript Module
 * Particles, 3D tilt, scroll reveal, counters, radio player, mobile nav, back-to-top.
 *
 * @package HRL_Theme
 * @version 3.1.0
 */

(function () {
  'use strict';

  // ═══════════════════════════════════════════════════════
  // CONFIG
  // ═══════════════════════════════════════════════════════
  const CONFIG = {
    particles: { count: 100, maxDist: 120, colors: ['#C8A96E', '#38bdf8'] },
    tilt: { max: 8, perspective: 1000 },
    reveal: { threshold: 0.15, rootMargin: '0px 0px -50px 0px' },
    counters: { duration: 2000, easing: 'easeOutCubic' },
  };

  const isMobile = window.matchMedia('(max-width: 768px)').matches;
  const isTouch = window.matchMedia('(hover: none)').matches;
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ═══════════════════════════════════════════════════════
  // UTILS
  // ═══════════════════════════════════════════════════════
  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
  const lerp = (start, end, t) => start + (end - start) * t;

  // ═══════════════════════════════════════════════════════
  // 1. STICKY HEADER
  // ═══════════════════════════════════════════════════════
  function initStickyHeader() {
    const header = $('#siteHeader');
    if (!header) return;

    const onScroll = () => {
      if (window.scrollY > 80) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // ═══════════════════════════════════════════════════════
  // 2. MOBILE NAV
  // ═══════════════════════════════════════════════════════
  function initMobileNav() {
    const toggle = $('.nav-toggle');
    const menu = $('.nav-menu');
    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
      const isOpen = menu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Close on link click
    $$('.nav-menu a', menu).forEach(link => {
      link.addEventListener('click', () => {
        menu.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });

    // Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menu.classList.contains('open')) {
        menu.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });
  }

  // ═══════════════════════════════════════════════════════
  // 3. BACK TO TOP
  // ═══════════════════════════════════════════════════════
  function initBackToTop() {
    const btn = $('#backToTop');
    if (!btn) return;

    const onScroll = () => {
      if (window.scrollY > 400) {
        btn.classList.add('visible');
      } else {
        btn.classList.remove('visible');
      }
    };

    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // ═══════════════════════════════════════════════════════
  // 4. PARTICLE SYSTEM (REMOVED - Performance optimization)
  // Particle animation removed to improve Core Web Vitals
  // ═══════════════════════════════════════════════════════
  function initParticles() {
    // Removed for performance optimization
    // Particles caused significant CPU/GPU load and poor LCP scores
    return;
  }

  // ═══════════════════════════════════════════════════════
  // 5. 3D TILT CARDS (REMOVED - Performance optimization)
  // 3D tilt effects removed to improve Core Web Vitals
  // ═══════════════════════════════════════════════════════
  function initTiltCards() {
    // Removed for performance optimization
    // 3D tilt effects caused layout shifts and poor performance
    return;
  }

  // ═══════════════════════════════════════════════════════
  // 6. SCROLL REVEAL
  // ═══════════════════════════════════════════════════════
  function initScrollReveal() {
    if (prefersReducedMotion) {
      $$('.reveal-up, .reveal-fade').forEach(el => el.classList.add('revealed'));
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: CONFIG.reveal.threshold,
        rootMargin: CONFIG.reveal.rootMargin,
      }
    );

    $$('.reveal-up, .reveal-fade').forEach(el => observer.observe(el));
  }

  // ═══════════════════════════════════════════════════════
  // 7. NUMBER COUNTERS
  // ═══════════════════════════════════════════════════════
  function initCounters() {
    if (prefersReducedMotion) return;

    const counters = $$('[data-count]');
    if (!counters.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const el = entry.target;
            const target = parseInt(el.getAttribute('data-count'), 10);
            const duration = CONFIG.counters.duration;
            const start = performance.now();

            const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

            const update = (now) => {
              const elapsed = now - start;
              const progress = Math.min(elapsed / duration, 1);
              const value = Math.floor(easeOutCubic(progress) * target);
              el.textContent = value;

              if (progress < 1) {
                requestAnimationFrame(update);
              } else {
                el.textContent = target;
              }
            };

            requestAnimationFrame(update);
            observer.unobserve(el);
          }
        });
      },
      { threshold: 0.2, rootMargin: '0px 0px -20px 0px' }
    );

    counters.forEach(el => observer.observe(el));

    // Fallback: trigger counters already in viewport on load
    setTimeout(() => {
      counters.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
          const event = new Event('intersect');
          observer.observe(el);
        }
      });
    }, 300);
  }

  // ═══════════════════════════════════════════════════════
  // 8. RADIO PLAYER
  // ═══════════════════════════════════════════════════════
  function initRadioPlayer() {
    const playBtn = $('#radioPlayBtn');
    const audio = $('#radioAudio');
    const status = $('#radioStatus');
    const viz = $('#radioVisualizer');
    const volume = $('#radioVolume');
    if (!playBtn || !audio) return;

    audio.volume = 0.8;
    if (volume) {
      volume.addEventListener('input', () => { audio.volume = volume.value / 100; });
    }

    // Fetch now playing track from AzuraCast API
    const fetchNowPlaying = async () => {
      try {
        const res = await fetch('https://radio.hardbanrecordslab.online/api/nowplaying/1');
        const data = await res.json();
        if (data && data.now_playing && data.now_playing.song) {
          const song = data.now_playing.song;
          return song.artist ? song.artist + ' — ' + song.title : song.title;
        }
      } catch(e) { /* silently fail */ }
      return null;
    };

    const updateNowPlaying = async () => {
      const title = await fetchNowPlaying();
      if (title && !audio.paused) {
        status.textContent = title;
        const titleEl = document.querySelector('.radio-title');
        if (titleEl) titleEl.textContent = title;
      }
    };

    // Poll every 10s while playing
    let npInterval = null;
    const startNPPoll = () => {
      updateNowPlaying();
      npInterval = setInterval(updateNowPlaying, 15000);
    };
    const stopNPPoll = () => {
      if (npInterval) { clearInterval(npInterval); npInterval = null; }
    };

    let retries = 0;
    const maxRetries = 3;

    playBtn.addEventListener('click', () => {
      if (audio.paused) {
        audio.play()
          .then(() => {
            playBtn.textContent = '⏸';
            status.textContent = 'Łączenie...';
            if (viz) viz.classList.add('playing');
            startNPPoll();
            retries = 0;
          })
          .catch(() => {
            status.textContent = 'Błąd połączenia. Ponawiam...';
            retries++;
            if (retries <= maxRetries) {
              setTimeout(() => playBtn.click(), 3000);
            } else {
              status.textContent = 'Nie udało się połączyć.';
            }
          });
      } else {
        audio.pause();
        playBtn.textContent = '▶';
        status.textContent = 'Zatrzymano';
        if (viz) viz.classList.remove('playing');
        stopNPPoll();
      }
    });

    audio.addEventListener('ended', () => {
      playBtn.textContent = '▶';
      status.textContent = 'Gotowy do odtwarzania';
      if (viz) viz.classList.remove('playing');
    });

    audio.addEventListener('error', () => {
      status.textContent = 'Błąd streamu. Spróbuj później.';
      if (viz) viz.classList.remove('playing');
    });
  }

  // ═══════════════════════════════════════════════════════
  // 9. FLIP CARDS (pakiety cenowe)
  // ═══════════════════════════════════════════════════════
  function initFlipCards() {
    const cards = $$('.pakiet-card');
    if (!cards.length) return;

    cards.forEach(card => {
      const toggle = () => {
        if (isTouch) {
          card.classList.toggle('is-flipped');
        } else {
          // Hover handled by CSS :hover
        }
      };

      card.addEventListener('click', toggle);
      card.addEventListener('touchend', (e) => {
        e.preventDefault();
        toggle();
      });
    });
  }

  // ═══════════════════════════════════════════════════════
  // 10. SMOOTH SCROLL FOR ANCHOR LINKS
  // ═══════════════════════════════════════════════════════
  function initSmoothScroll() {
    $$('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', (e) => {
        const href = anchor.getAttribute('href');
        if (href === '#') return;

        const target = $(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  }

  // ═══════════════════════════════════════════════════════
  // INIT
  // ═══════════════════════════════════════════════════════
  function init() {
    initStickyHeader();
    initMobileNav();
    initBackToTop();
    initParticles();
    initTiltCards();
    initScrollReveal();
    initCounters();
    initRadioPlayer();
    initFlipCards();
    initSmoothScroll();
    initFAQ();
  }

  // ═══════════════════════════════════════════════════════
  // 9. FAQ ACCORDION
  // ═══════════════════════════════════════════════════════
  function initFAQ() {
    const faqItems = document.querySelectorAll('.faq-item');
    if (!faqItems.length) return;

    faqItems.forEach(item => {
      const q = item.querySelector('.faq-q');
      const a = item.querySelector('.faq-a');
      if (!q || !a) return;

      q.addEventListener('click', () => {
        const isOpen = a.classList.contains('open');
        // Close all
        faqItems.forEach(i => {
          const aq = i.querySelector('.faq-q');
          const aa = i.querySelector('.faq-a');
          if (aq) aq.classList.remove('active');
          if (aa) aa.classList.remove('open');
        });
        if (!isOpen) {
          q.classList.add('active');
          a.classList.add('open');
        }
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
