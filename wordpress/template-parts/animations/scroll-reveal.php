<?php
/**
 * Template Part: Scroll Animation Initializer
 * Emits <style> for animation system and inline JS for IntersectionObserver.
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$anim_duration = get_theme_mod( 'hrl_anim_transition_duration', 600 );
$anim_enabled  = get_theme_mod( 'hrl_animations_toggle', true );
$btt_enabled   = get_theme_mod( 'hrl_back_to_top_toggle', true );
$progress_enabled = get_theme_mod( 'hrl_reading_progress_toggle', true );
?>
<?php if ( $anim_enabled ) : ?>
<style id="hrl-animations-inline">
.hrl-reveal,.hrl-reveal-up,.hrl-reveal-left,.hrl-reveal-right,.hrl-reveal-scale {
  opacity:0;
  transition: opacity <?php echo esc_attr( $anim_duration ); ?>ms cubic-bezier(0.16,1,0.3,1), transform <?php echo esc_attr( $anim_duration ); ?>ms cubic-bezier(0.16,1,0.3,1);
  will-change: opacity, transform;
}
.hrl-reveal-up    { transform: translateY(40px); }
.hrl-reveal-left  { transform: translateX(-40px); }
.hrl-reveal-right { transform: translateX(40px); }
.hrl-reveal-scale { transform: scale(0.92); }
.hrl-reveal.is-visible,.hrl-reveal-up.is-visible,.hrl-reveal-left.is-visible,.hrl-reveal-right.is-visible,.hrl-reveal-scale.is-visible {
  opacity:1;
  transform: translateY(0) translateX(0) scale(1);
}
.hrl-stagger > * { opacity:0; transform: translateY(30px); transition: opacity 0.6s cubic-bezier(0.16,1,0.3,1), transform 0.6s cubic-bezier(0.16,1,0.3,1); }
.hrl-stagger.is-visible > *:nth-child(1) { transition-delay:0.00s; opacity:1; transform:none; }
.hrl-stagger.is-visible > *:nth-child(2) { transition-delay:0.08s; opacity:1; transform:none; }
.hrl-stagger.is-visible > *:nth-child(3) { transition-delay:0.16s; opacity:1; transform:none; }
.hrl-stagger.is-visible > *:nth-child(4) { transition-delay:0.24s; opacity:1; transform:none; }
.hrl-stagger.is-visible > *:nth-child(5) { transition-delay:0.32s; opacity:1; transform:none; }
.hrl-stagger.is-visible > *:nth-child(6) { transition-delay:0.40s; opacity:1; transform:none; }
</style>
<?php endif; ?>

<?php if ( $btt_enabled ) : ?>
<button id="hrl-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'hrl-theme' ); ?>" style="
    position:fixed; bottom:28px; right:28px; z-index:999;
    width:44px; height:44px; border-radius:50%;
    background:var(--gradient-gold); color:#000;
    border:none; font-size:1.2rem; cursor:pointer;
    opacity:0; transform:translateY(12px); pointer-events:none;
    transition: opacity 0.4s, transform 0.4s;
    box-shadow: 0 4px 18px rgba(200,169,110,0.3);
    display:flex; align-items:center; justify-content:center;
">
    &#8593;
</button>
<?php endif; ?>

<?php if ( $progress_enabled ) : ?>
<div id="hrl-progress-bar" style="
    position:fixed; top:0; left:0; height:3px;
    background:var(--gradient-accent); width:0; z-index:9999;
    transition: width 0.15s linear;
"></div>
<?php endif; ?>

<?php if ( $anim_enabled || $btt_enabled || $progress_enabled ) : ?>
<script>
(function(){
  'use strict';

  <?php if ( $anim_enabled ) : ?>
  var revealEls = document.querySelectorAll('.hrl-reveal,.hrl-reveal-up,.hrl-reveal-left,.hrl-reveal-right,.hrl-reveal-scale,.hrl-stagger');
  if ('IntersectionObserver' in window) {
    var revealObs = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if (e.isIntersecting) { e.target.classList.add('is-visible'); revealObs.unobserve(e.target); }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(function(el){ revealObs.observe(el); });
  } else {
    revealEls.forEach(function(el){ el.classList.add('is-visible'); });
  }
  <?php endif; ?>

  <?php if ( $btt_enabled ) : ?>
  var bttBtn = document.getElementById('hrl-back-to-top');
  if (bttBtn) {
    window.addEventListener('scroll', function(){
      if (window.scrollY > 600) {
        bttBtn.style.opacity = '1'; bttBtn.style.transform = 'translateY(0)'; bttBtn.style.pointerEvents = 'auto';
      } else {
        bttBtn.style.opacity = '0'; bttBtn.style.transform = 'translateY(12px)'; bttBtn.style.pointerEvents = 'none';
      }
    });
    bttBtn.addEventListener('click', function(){ window.scrollTo({ top: 0, behavior: 'smooth' }); });
  }
  <?php endif; ?>

  <?php if ( $progress_enabled ) : ?>
  var progBar = document.getElementById('hrl-progress-bar');
  if (progBar) {
    window.addEventListener('scroll', function(){
      var st = document.documentElement.scrollTop || document.body.scrollTop;
      var h = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      progBar.style.width = h > 0 ? ((st / h) * 100) + '%' : '0%';
    });
  }
  <?php endif; ?>

})();
</script>
<?php endif; ?>
