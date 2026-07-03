/**
 * HRL BlogCast — Dynamic AJAX Category Filtering
 * HardbanRecords Lab 2.0
 *
 * This script intercepts clicks on category links inside .categories-nav,
 * fetches the filtered post set via WP AJAX (admin-ajax.php), and replaces
 * the post content columns with fade-in animations.
 *
 * Features:
 *   - Reading progress bar (fixed, top of viewport).
 *   - Category click interception with pushState for URL history.
 *   - Fetch API with nonce-based security.
 *   - DOM injection with staggered fade-in animation.
 *   - "Load More" pagination button.
 *   - Active state tracking on category buttons.
 *
 * @license GPL-2.0-or-later
 * @package HRL_Theme
 * @version 3.0.0
 */
(function () {
    'use strict';

    // ═══════════════════════════════════════════════════════
    // CONFIGURATION (injected via wp_localize_script)
    // ═══════════════════════════════════════════════════════
    var config = window.hrlBlogcastConfig || {};
    var AJAX_URL        = config.ajaxUrl || '';
    var NONCE           = config.nonce || '';
    var POSTS_CONTAINER = config.postsContainer || '#blogcastContent';
    var LOADING_TEXT    = config.loadingText || 'Ładowanie artykułów…';
    var NO_RESULTS_TEXT = config.noResultsText || 'Brak wpisów w tej sekcji.';
    var ERROR_TEXT      = config.errorText || 'Wystąpił błąd. Spróbuj ponownie.';

    var currentCategory = 0;
    var currentPage     = 1;
    var hasMore         = false;
    var isLoading       = false;

    // ═══════════════════════════════════════════════════════
    // DOM REFERENCES
    // ═══════════════════════════════════════════════════════
    var container    = null;
    var mainColumn   = null;
    var secondColumn = null;
    var loadMoreBtn  = null;

    /**
     * Initialize on DOMContentLoaded.
     */
    function init() {
        container    = document.querySelector(POSTS_CONTAINER);
        mainColumn   = document.getElementById('mainStoryColumn');
        secondColumn = document.getElementById('secondaryStoryColumn');

        if (!container) {
            return; // Not on a BlogCast page — abort silently.
        }

        bindCategoryClicks();
        createLoadMoreButton();
        initProgressBar();
    }

    // ═══════════════════════════════════════════════════════
    // READING PROGRESS BAR
    // ═══════════════════════════════════════════════════════
    function initProgressBar() {
        window.addEventListener('scroll', function () {
            var winScroll = document.documentElement.scrollTop || document.body.scrollTop;
            var height    = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled  = height > 0 ? (winScroll / height) * 100 : 0;
            var bar       = document.getElementById('progressBar');
            if (bar) {
                bar.style.width = scrolled + '%';
            }
        }, { passive: true });
    }

    // ═══════════════════════════════════════════════════════
    // CATEGORY CLICK INTERCEPTION
    // ═══════════════════════════════════════════════════════
    function bindCategoryClicks() {
        // Delegate from the nav — catches both .cat-link and .sub-link.
        var nav = document.querySelector('.categories-nav');
        if (!nav) return;

        nav.addEventListener('click', function (e) {
            var target = e.target.closest('[data-cat-id]');
            if (!target) return; // Not a category link.

            e.preventDefault();

            var catId = parseInt(target.getAttribute('data-cat-id'), 10) || 0;

            // Prevent double-fetch.
            if (catId === currentCategory && currentPage === 1) return;

            currentCategory = catId;
            currentPage     = 1;
            hasMore         = false;

            // Update active state visually.
            highlightActiveLink(target);

            // Update browser URL without page reload (pushState).
            var href = target.getAttribute('href');
            if (href && window.history && window.history.pushState) {
                window.history.pushState({ catId: catId }, '', href);
            }

            fetchAndRender();
        });
    }

    /**
     * Highlight the clicked link and remove highlight from siblings.
     */
    function highlightActiveLink(active) {
        var nav = document.querySelector('.categories-nav');
        if (!nav) return;

        // Remove previous active markers.
        var allLinks = nav.querySelectorAll('.cat-link, .sub-link');
        [].forEach.call(allLinks, function (link) {
            link.style.color = '';
            link.style.fontWeight = '';
        });

        // Highlight the new active.
        active.style.color      = 'var(--neon-blue)';
        active.style.fontWeight = '700';
    }

    // ═══════════════════════════════════════════════════════
    // LOAD MORE BUTTON
    // ═══════════════════════════════════════════════════════
    function createLoadMoreButton() {
        if (!container) return;

        // Remove existing button if any.
        if (loadMoreBtn) {
            loadMoreBtn.remove();
        }

        loadMoreBtn = document.createElement('button');
        loadMoreBtn.className = 'btn btn-outline blogcast-load-more';
        loadMoreBtn.textContent = 'Załaduj więcej artykułów';
        loadMoreBtn.style.display = 'none';
        loadMoreBtn.style.margin = '30px auto';
        loadMoreBtn.style.width = 'max-content';

        loadMoreBtn.addEventListener('click', function () {
            if (isLoading || !hasMore) return;
            currentPage++;
            fetchAndRender(true); // append = true for pagination.
        });

        container.parentNode.insertBefore(loadMoreBtn, container.nextSibling);
    }

    // ═══════════════════════════════════════════════════════
    // AJAX FETCH & RENDER
    // ═══════════════════════════════════════════════════════

    /**
     * Fetch posts from WP AJAX and inject them into the DOM.
     *
     * @param {boolean} append — if true, append to existing content (pagination);
     *                           if false, replace entire grid.
     */
    function fetchAndRender(append) {
        if (isLoading || !AJAX_URL) return;
        isLoading = true;

        // Show loading indicator.
        showLoading(append);

        var formData = new FormData();
        formData.append('action', 'hrl_filter_posts');
        formData.append('nonce', NONCE);
        formData.append('category', currentCategory);
        formData.append('page', currentPage);

        fetch(AJAX_URL, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.json();
            })
            .then(function (data) {
                if (!data.success) {
                    throw new Error(data.data && data.data.message ? data.data.message : 'Unknown error');
                }

                var html    = data.data.html || '';
                hasMore     = data.data.has_more || false;

                if (append) {
                    appendPosts(html);
                } else {
                    replacePosts(html);
                }

                updateLoadMoreButton();
            })
            .catch(function (error) {
                showError(error.message);
            })
            .finally(function () {
                isLoading = false;
                hideLoading();
            });
    }

    // ═══════════════════════════════════════════════════════
    // DOM INJECTION WITH STAGGERED FADE-IN
    // ═══════════════════════════════════════════════════════

    /**
     * Replace the entire post grid content (new filter selection).
     */
    function replacePosts(html) {
        if (!container) return;

        // Clear existing content.
        container.innerHTML = '';

        // Create a wrapper that will hold the new cards.
        var wrapper = document.createElement('div');
        wrapper.className = 'blogcast-grid-wrapper';
        wrapper.style.display = 'grid';
        wrapper.style.gridTemplateColumns = '3fr 2fr 1.7fr';
        wrapper.style.gap = '30px';
        wrapper.style.alignItems = 'start';
        wrapper.style.width = '100%';

        // Parse HTML into temp container.
        var temp = document.createElement('div');
        temp.innerHTML = html;

        var cards = temp.querySelectorAll('.article-card');
        var articleCards = [].slice.call(cards);

        if (articleCards.length === 0) {
            // No results — show the message full-width.
            wrapper.style.gridTemplateColumns = '1fr';
            wrapper.innerHTML = html;
            container.appendChild(wrapper);
            return;
        }

        // Column 1: First card (featured).
        var col1 = document.createElement('section');
        col1.className = 'main-story';
        col1.style.borderRight = '1px solid var(--border-color)';
        col1.style.paddingRight = '25px';

        // Column 2: Remaining cards (up to 4).
        var col2 = document.createElement('section');
        col2.className = 'secondary-stories';
        col2.style.borderRight = '1px solid var(--border-color)';
        col2.style.paddingRight = '25px';

        // Collapse columns on mobile.
        var styleEl = document.createElement('style');
        styleEl.textContent = '@media(max-width:1024px){.main-story{border-right:none!important;padding-right:0!important}.secondary-stories{border-right:none!important;padding-right:0!important}}';
        col1.appendChild(styleEl.cloneNode(true));

        // Distribute: first card → col1, next 4 → col2, rest → overflow hidden.
        if (articleCards.length > 0) {
            var featuredCard = articleCards[0];
            // Ensure featured title is large.
            var ftTitle = featuredCard.querySelector('.article-title');
            if (ftTitle) {
                ftTitle.style.fontSize = '2.2rem';
            }
            col1.appendChild(featuredCard);

            // Fade in animation.
            featuredCard.style.opacity = '0';
            featuredCard.style.transform = 'translateY(16px)';
            featuredCard.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
        }

        for (var i = 1; i < articleCards.length; i++) {
            var card = articleCards[i];
            var titleEl = card.querySelector('.article-title');
            if (titleEl) {
                titleEl.style.fontSize = '1.4rem';
            }
            col2.appendChild(card);

            card.style.opacity = '0';
            card.style.transform = 'translateY(16px)';
            card.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
        }

        wrapper.appendChild(col1);
        wrapper.appendChild(col2);

        // Column 3: Preserve the sidebar if it exists.
        var existingSidebar = document.getElementById('blogcastSidebar');
        if (existingSidebar) {
            // Do nothing — sidebar stays outside the grid wrapper.
        }

        container.appendChild(wrapper);

        // Trigger staggered fade-in.
        requestAnimationFrame(function () {
            var allCards = wrapper.querySelectorAll('.article-card');
            [].forEach.call(allCards, function (card, idx) {
                setTimeout(function () {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, idx * 80); // 80ms stagger.
            });
        });
    }

    /**
     * Append posts to the secondary stories column (pagination "Load More").
     */
    function appendPosts(html) {
        if (!secondColumn) return;

        var temp = document.createElement('div');
        temp.innerHTML = html;

        var cards = temp.querySelectorAll('.article-card');
        var articleCards = [].slice.call(cards);

        if (articleCards.length === 0) return;

        articleCards.forEach(function (card, idx) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(16px)';
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';

            secondColumn.appendChild(card);

            requestAnimationFrame(function () {
                setTimeout(function () {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, idx * 60);
            });
        });
    }

    // ═══════════════════════════════════════════════════════
    // UI STATE HELPERS
    // ═══════════════════════════════════════════════════════

    function showLoading(append) {
        if (!container) return;

        if (!append) {
            // Replace content with a pulsing skeleton.
            container.innerHTML = '<div class="blogcast-loading" style="display:flex;justify-content:center;align-items:center;padding:80px 0;grid-column:1/-1;">'
                + '<div style="text-align:center;">'
                + '<div class="loading-spinner" style="width:40px;height:40px;border:3px solid var(--border-color);border-top-color:var(--neon-blue);border-radius:50%;animation:hrlSpin 0.8s linear infinite;margin:0 auto 16px;"></div>'
                + '<p style="color:var(--text-secondary);font-size:0.9rem;">' + LOADING_TEXT + '</p>'
                + '</div></div>';
        }
    }

    function hideLoading() {
        // Skeleton is already replaced by content.  No-op.
    }

    function showError(message) {
        if (!container) return;
        container.innerHTML = '<p style="color:var(--market-down);text-align:center;padding:40px 0;grid-column:1/-1;">'
            + ERROR_TEXT + '</p>';
        console.error('[HRL BlogCast]', message);
    }

    function updateLoadMoreButton() {
        if (!loadMoreBtn) return;
        if (hasMore) {
            loadMoreBtn.style.display = 'block';
            loadMoreBtn.disabled = false;
            loadMoreBtn.textContent = 'Załaduj więcej artykułów';
        } else {
            loadMoreBtn.style.display = 'none';
        }
    }

    // ═══════════════════════════════════════════════════════
    // SPINNER KEYFRAMES (injected dynamically)
    // ═══════════════════════════════════════════════════════
    function injectSpinnerKeyframes() {
        if (document.getElementById('hrl-spinner-styles')) return;
        var style = document.createElement('style');
        style.id = 'hrl-spinner-styles';
        style.textContent = '@keyframes hrlSpin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}'
            + '.blogcast-load-more{display:block;margin:30px auto;padding:12px 28px;font-family:var(--font-accents);font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;background:transparent;border:1px solid var(--border-color);color:var(--text-secondary);border-radius:4px;cursor:pointer;transition:all 0.3s;}'
            + '.blogcast-load-more:hover{border-color:var(--neon-blue);color:var(--neon-blue);}'
            + '.blogcast-load-more:disabled{opacity:0.4;cursor:not-allowed;}'
            + '#blogcastContent .blogcast-grid-wrapper{display:grid;grid-template-columns:3fr 2fr 1.7fr;gap:30px;align-items:start;width:100%}'
            + '@media(max-width:1024px){#blogcastContent .blogcast-grid-wrapper{grid-template-columns:1fr!important}}';
        document.head.appendChild(style);
    }

    // ═══════════════════════════════════════════════════════
    // POPSTATE HANDLER — back/forward navigation support
    // ═══════════════════════════════════════════════════════
    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.catId !== undefined) {
            currentCategory = e.state.catId;
            currentPage     = 1;
            hasMore         = false;
            fetchAndRender(false);
        }
    });

    // ═══════════════════════════════════════════════════════
    // BOOT
    // ═══════════════════════════════════════════════════════
    injectSpinnerKeyframes();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();