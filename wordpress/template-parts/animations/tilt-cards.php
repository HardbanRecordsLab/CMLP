<?php
/**
 * Template Part: 3D Tilt & Cursor Initializer
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$tilt_enabled = get_theme_mod( 'hrl_3d_tilt_toggle', true );
$cursor_enabled = get_theme_mod( 'hrl_3d_cursor_toggle', false );
$tilt_intensity = get_theme_mod( 'hrl_3d_tilt_intensity', 15 );
$perspective = get_theme_mod( 'hrl_3d_perspective', 1000 );
?>
<?php if ( $tilt_enabled || $cursor_enabled ) : ?>
<style>
[data-tilt] { transform-style: preserve-3d; transform: perspective(<?php echo esc_attr( $perspective ); ?>px); transition: transform 0.1s ease-out; will-change: transform; }
.hlr-cursor-dot,.hlr-cursor-ring { display:none; }
@media (hover: hover) and (pointer: fine) {
  .hrl-cursor-dot,.hrl-cursor-ring { display:block; }
}
</style>
<script>
(function(){
  'use strict';

  <?php if ( $tilt_enabled ) : ?>
  var tiltCards = document.querySelectorAll('[data-tilt]');
  tiltCards.forEach(function(card){
    card.addEventListener('mousemove', function(e){
      var r = card.getBoundingClientRect();
      var x = e.clientX - r.left;
      var y = e.clientY - r.top;
      var cx = r.width / 2;
      var cy = r.height / 2;
      var rotateX = ((y - cy) / cy) * -<?php echo esc_js( $tilt_intensity ); ?>;
      var rotateY = ((x - cx) / cx) * <?php echo esc_js( $tilt_intensity ); ?>;
      card.style.transform = 'perspective(<?php echo esc_js( $perspective ); ?>px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) scale3d(1.02,1.02,1.02)';
    });
    card.addEventListener('mouseleave', function(){
      card.style.transform = 'perspective(<?php echo esc_js( $perspective ); ?>px) rotateX(0) rotateY(0) scale3d(1,1,1)';
    });
  });
  <?php endif; ?>

  <?php if ( $cursor_enabled ) : ?>
  var dot = document.createElement('div');
  dot.className = 'hrl-cursor-dot';
  var ring = document.createElement('div');
  ring.className = 'hrl-cursor-ring';
  document.body.appendChild(dot);
  document.body.appendChild(ring);

  var mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
  document.addEventListener('mousemove', function(e){
    mouseX = e.clientX; mouseY = e.clientY;
    dot.style.transform = 'translate(' + (mouseX - 4) + 'px,' + (mouseY - 4) + 'px)';
  });

  function animateRing(){
    ringX += (mouseX - ringX) * 0.15;
    ringY += (mouseY - ringY) * 0.15;
    ring.style.transform = 'translate(' + (ringX - 18) + 'px,' + (ringY - 18) + 'px)';
    requestAnimationFrame(animateRing);
  }
  animateRing();

  var hoverTargets = document.querySelectorAll('a, button, [data-tilt], input, textarea, select');
  hoverTargets.forEach(function(el){
    el.addEventListener('mouseenter', function(){
      dot.classList.add('hover-link');
      ring.classList.add('hover-link');
    });
    el.addEventListener('mouseleave', function(){
      dot.classList.remove('hover-link');
      ring.classList.remove('hover-link');
    });
  });
  <?php endif; ?>

})();
</script>
<?php endif; ?>
