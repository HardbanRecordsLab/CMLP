/**
 * HRL Amoled Premium — Theme JavaScript
 * Radio player, ticker simulation, nav toggle, accordion, tabs, progress bar
 */
(function () {
  'use strict';

  // ═══════════════════════════════════════════════════════
  // MOBILE NAV TOGGLE
  // ═══════════════════════════════════════════════════════
  var navToggle = document.querySelector('.nav-toggle');
  var navMenu = document.querySelector('.nav-menu');
  if (navToggle && navMenu) {
    navToggle.addEventListener('click', function () {
      var expanded = navToggle.getAttribute('aria-expanded') === 'true';
      navToggle.setAttribute('aria-expanded', !expanded);
      navMenu.classList.toggle('open');
    });
  }

  // ═══════════════════════════════════════════════════════
  // READING PROGRESS BAR
  // ═══════════════════════════════════════════════════════
  var progressBar = document.getElementById('progressBar');
  if (progressBar) {
    window.addEventListener('scroll', function () {
      var winScroll = document.documentElement.scrollTop || document.body.scrollTop;
      var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      var scrolled = (winScroll / height) * 100;
      progressBar.style.width = scrolled + '%';
    });
  }

  // ═══════════════════════════════════════════════════════
  // RADIO PLAYER (Stream: AzuraCast)
  // ═══════════════════════════════════════════════════════
  var radioBtn = document.getElementById('radioPlayBtn');
  var radioStatus = document.getElementById('radioStatus');
  var radioAudio = document.getElementById('radioAudio');
  var radioPlaying = false;

  if (radioBtn && radioAudio && radioStatus) {
    radioBtn.addEventListener('click', function () {
      if (radioPlaying) {
        radioAudio.pause();
        radioPlaying = false;
        radioBtn.textContent = '\u25B6';
        radioBtn.classList.remove('playing');
        radioStatus.textContent = 'Wstrzymano';
      } else {
        radioAudio.src = radioAudio.querySelector('source') ? radioAudio.querySelector('source').src : (typeof hrlRadioConfig !== 'undefined' ? hrlRadioConfig.streamUrl : '');
        radioAudio.load();
        radioAudio.play().then(function () {
          radioPlaying = true;
          radioBtn.textContent = '\u23F8';
          radioBtn.classList.add('playing');
          radioStatus.textContent = 'Odtwarzanie...';
        }).catch(function () {
          radioStatus.textContent = 'Blad strumienia';
        });
      }
    });

    radioAudio.addEventListener('pause', function () {
      radioPlaying = false;
      radioBtn.textContent = '\u25B6';
      radioBtn.classList.remove('playing');
      radioStatus.textContent = 'Wstrzymano';
    });

    radioAudio.addEventListener('playing', function () {
      radioPlaying = true;
      radioBtn.textContent = '\u23F8';
      radioBtn.classList.add('playing');
      radioStatus.textContent = 'Odtwarzanie...';
    });
  }

  // ═══════════════════════════════════════════════════════
  // SONG TABS (Muzyczna Kreacja Slow)
  // ═══════════════════════════════════════════════════════
  var songTabs = document.querySelectorAll('.song-tab');
  var songPanels = document.querySelectorAll('.song-panel');

  songTabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var targetId = 'panel-' + tab.getAttribute('data-tab');
      songTabs.forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');
      songPanels.forEach(function (panel) {
        panel.classList.remove('active');
        if (panel.id === targetId) panel.classList.add('active');
      });
    });
  });

  // ═══════════════════════════════════════════════════════
  // FAQ ACCORDION
  // ═══════════════════════════════════════════════════════
  var faqQuestions = document.querySelectorAll('.faq-question');
  faqQuestions.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var faqItem = btn.parentElement;
      var isOpen = faqItem.classList.contains('open');
      // Close all
      document.querySelectorAll('.faq-item.open').forEach(function (item) {
        item.classList.remove('open');
      });
      if (!isOpen) faqItem.classList.add('open');
    });
  });

  // ═══════════════════════════════════════════════════════
  // PACKAGE CARD SELECTION (Radio button cards for MKS)
  // ═══════════════════════════════════════════════════════
  var pakietCards = document.querySelectorAll('.pakiet-card');
  pakietCards.forEach(function (card) {
    card.addEventListener('click', function () {
      pakietCards.forEach(function (c) { c.classList.remove('selected'); });
      card.classList.add('selected');
      var radio = card.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    });
  });

  // ═══════════════════════════════════════════════════════
  // CURRENT DATE DISPLAY
  // ═══════════════════════════════════════════════════════
  var dateEl = document.getElementById('currentDate');
  if (dateEl) {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    dateEl.innerText = new Date().toLocaleDateString('pl-PL', options);
  }

  // ═══════════════════════════════════════════════════════
  // SIDEBAR AUDIO PLAYER TOGGLE
  // ═══════════════════════════════════════════════════════
  window.toggleSidebarAudio = function () {
    var btn = document.getElementById('sidebarPlayBtn');
    if (!btn) return;
    var isPlaying = btn.textContent === '\u23F8';
    btn.textContent = isPlaying ? '\u25B6' : '\u23F8';
  };

  // ═══════════════════════════════════════════════════════
  // LIVE TICKER AJAX POLLING (60s refresh, no page reload)
  // ═══════════════════════════════════════════════════════
  var tickerNewsContainer = document.querySelector('.ticker-move:not(.reverse)');
  var tickerFinContainer  = document.querySelector('.ticker-move.reverse');

  if (tickerNewsContainer || tickerFinContainer) {
    var tickerVars = window.hrlTickerVars || {};
    var ajaxUrl    = tickerVars.ajaxUrl || '/wp-admin/admin-ajax.php';
    var nonce      = tickerVars.nonce || '';
    var pollMs     = tickerVars.pollIntervalMs || 60000;
    var polling    = false;

    function hrlTickerFetch(action, container) {
      if (!container) return;
      var fd = new FormData();
      fd.append('action', action);
      fd.append('nonce', nonce);
      fetch(ajaxUrl, { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.success && data.data && data.data.html) {
            container.innerHTML = data.data.html;
          }
        })
        .catch(function () {});
    }

    function hrlTickerPoll() {
      if (polling) return;
      polling = true;
      hrlTickerFetch('hrl_ticker_news', tickerNewsContainer);
      hrlTickerFetch('hrl_ticker_financial', tickerFinContainer);
      polling = false;
    }

    // First poll after 1s (after page render), then every 60s
    setTimeout(function () {
      hrlTickerPoll();
      setInterval(hrlTickerPoll, pollMs);
    }, 1000);
  }
})();
